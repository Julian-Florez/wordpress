# Instructivo: Configuracion del chatbot entre WordPress y n8n

Este documento explica como configurar el chatbot en ambos lados:

- Lado WordPress (frontend del tema)
- Lado n8n (webhook + respuesta)

Objetivo: que el chat muestre la respuesta real de n8n, no un mensaje generico, y que pueda manejar conversaciones por pasos con estado persistente.

---

## 1) Arquitectura resumida

1. El usuario escribe en el chat de la home.
2. El frontend envia `FormData` al webhook de n8n.
3. n8n procesa el mensaje y recupera el estado de la sesion.
4. n8n responde JSON (o texto).
5. El frontend renderiza esa respuesta en el panel del chat.

---

## 2) Configuracion en WordPress

### 2.1 Archivo y variable de configuracion

Se usa una constante para definir el webhook:

- Archivo: `wp-config.php`
- Constante: `WEIRDLINGS_CHATBOT_WEBHOOK`

Ejemplo:

```php
define( 'WEIRDLINGS_CHATBOT_WEBHOOK', 'https://tu-n8n.com/webhook/tu-id' );
```

Importante:

- En produccion usa `/webhook/...`
- `webhook-test` solo funciona en ejecucion de prueba del workflow en n8n

### 2.2 Donde se inyecta en el frontend

El boton del chatbot toma la URL desde esa constante:

- Archivo: `wp-content/themes/weirdlings-modern/front-page.php`
- Atributo: `data-chatbot-webhook="..."`

### 2.3 Flujo JS del chatbot

El envio/recepcion se hace aqui:

- Archivo: `wp-content/themes/weirdlings-modern/assets/js/theme.js`
- Funcion principal: `sendMessage(messageText, files)`

Comportamiento esperado:

- Envia `FormData` con `payload` y adjuntos `media[]`
- Incluye un `session_id` persistente para que n8n pueda recordar el paso de la conversacion
- Espera respuesta HTTP legible
- Si llega JSON, intenta leer: `reply`, `message`, `response`, `output`, `answer`, `text`
- Si falla lectura en tiempo real, muestra mensaje de fallback

### 2.4 Payload que manda WordPress

Dentro de `payload` se envia algo como:

```json
{
  "event": "chatbot_message",
  "session_id": "wl-123456",
  "source": "front_page",
  "message": "Texto del usuario",
  "page_url": "https://tu-sitio.com/",
  "page_title": "Inicio - Weirdlings",
  "timestamp": "2026-05-25T00:00:00.000Z",
  "user_agent": "..."
}
```

---

## 3) Configuracion en n8n

### 3.1 Crear workflow base

Nodos minimos recomendados:

1. `Webhook` (POST)
2. `Function` o `Code` (parsear payload)
3. `Data Store` (`Get`)
4. `Switch` de estado
5. (Opcional) nodos de IA/DB/CRM
6. `Respond to Webhook`

Si el flujo de recomendacion necesita recordar el paso actual, usa un Data Store para guardar el estado por `session_id`.

Nombre sugerido del Data Store:

- `chatbot_estado`

Campos sugeridos:

- `session_id`
- `estado`

### 3.2 Configurar Webhook node

- Method: `POST`
- Path: por ejemplo `weirdlings-chatbot`
- Response mode: usando `Respond to Webhook`

URL resultante:

- Test: `https://tu-n8n.com/webhook-test/weirdlings-chatbot`
- Prod: `https://tu-n8n.com/webhook/weirdlings-chatbot`

### 3.3 Leer datos entrantes

Como WordPress envia `multipart/form-data`, en n8n podras leer:

- `payload` (string JSON)
- archivos en binarios (si hubo adjuntos)

Ejemplo en `Code` node:

```javascript
const rawPayload = $input.first().json.body.payload;
let payload = {};

try {
  payload = typeof rawPayload === 'string' ? JSON.parse(rawPayload) : rawPayload;
} catch (e) {
  payload = {};
}

const userMessage = payload.message || 'Sin mensaje';

return [
  {
    json: {
      session_id: payload.session_id || '',
      reply: `Recibido: ${userMessage}`
    }
  }
];
```

### 3.4 Flujo de estado recomendado

#### Caso principal: estado vacio

1. El usuario pulsa `Recomendar criatura`.
2. Guardas en Data Store:

```json
{
  "session_id": "...",
  "estado": "esperando_tipo"
}
```

3. Respondes:

```json
{
  "reply": "🔮 ¿Qué tipo de criatura buscas?",
  "options": ["Raro", "Espeluznante", "Criatura del bosque", "Alienígenas"]
}
```

#### Caso 2: estado `esperando_tipo`

1. Buscas el registro en Data Store por `session_id`.
2. Si `estado = esperando_tipo`, enrutas por categoría.
3. Respondes con la recomendacion correspondiente.
4. Actualizas el estado a `normal` o eliminas el estado si ya no quieres seguir en modo guia.

Ejemplo de respuesta:

```json
{
  "reply": "🌿 Te recomendamos nuestras criaturas del bosque.",
  "options": ["Recomendar criatura", "Estado de pedido", "Tengo un problema"]
}
```

### 3.5 Responder al frontend

En `Respond to Webhook`:

- Status code: `200`
- Response format: JSON
- Body:

```json
{
  "reply": "Hola, te ayudo con eso. Cuentame mas detalles."
}
```

Campos compatibles con el frontend:

- `reply` (preferido)
- `message`
- `response`
- `output`
- `answer`
- `text`

---

## 4) CORS (clave para frontend directo)

Si WordPress llama directo al webhook desde navegador, n8n debe permitir CORS.

Debes habilitar al menos:

- Origin: dominio de WordPress (ej: `http://192.168.80.26` o tu dominio final)
- Methods: `POST, OPTIONS`
- Headers: por defecto para `multipart/form-data` y `Accept`

Si CORS no esta bien:

- n8n puede recibir el mensaje
- pero el navegador no podra leer la respuesta

---

## 5) Prueba end-to-end

1. Publica el workflow en n8n (`Active`).
2. En WordPress, define `WEIRDLINGS_CHATBOT_WEBHOOK` apuntando a `/webhook/...`.
3. Limpia cache del sitio/navegador.
4. Envia un mensaje desde la home.
5. Verifica en n8n que llega la ejecucion.
6. Verifica en el chat que aparece la respuesta exacta de n8n.
7. Pulsa `Recomendar criatura` y confirma que el estado se guarda como `esperando_tipo`.
8. Pulsa una de las opciones de criatura y confirma que la respuesta sale del caso de estado, no del flujo principal.

---

## 6) Troubleshooting rapido

### Caso A: "Mensaje enviado" pero no sale respuesta de n8n

Posibles causas:

- URL en `webhook-test` en vez de `webhook`
- `Respond to Webhook` no configurado
- CORS bloqueando lectura de respuesta

### Caso B: Respuesta vacia

Posibles causas:

- n8n responde sin `reply/message/...`
- body no JSON valido

Solucion:

- Responder siempre `{"reply":"..."}`

### Caso C: Error HTTP 4xx/5xx

Posibles causas:

- Path incorrecto
- workflow inactivo
- error interno en nodos

Solucion:

- Revisar Execution log de n8n
- Probar webhook con Postman/curl

---

## 7) Recomendacion para produccion

Si no quieres depender de CORS en navegador, crea un proxy en WordPress (REST o admin-ajax):

- Frontend -> WordPress endpoint interno
- WordPress (server-side) -> n8n webhook
- WordPress devuelve respuesta al frontend

Ventajas:

- Menos problemas de CORS
- Mejor control de seguridad y logs

---

## 8) Checklist final

- [ ] `WEIRDLINGS_CHATBOT_WEBHOOK` definido con URL de produccion
- [ ] workflow n8n activo
- [ ] nodo `Respond to Webhook` devolviendo JSON
- [ ] campo `reply` presente en respuesta
- [ ] CORS permitido para dominio WordPress
- [ ] prueba desde chat mostrando respuesta real
