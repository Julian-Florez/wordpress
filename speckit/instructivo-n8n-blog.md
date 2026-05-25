# Instructivo: Publicar entradas de blog desde n8n en WordPress

Este instructivo permite crear entradas en tu blog de WordPress desde automatizaciones de n8n, sin perder el metodo normal del panel (`Entradas > Anadir nueva`).

## 1) Requisitos

- WordPress accesible desde n8n (URL publica o red local alcanzable).
- Usuario de WordPress con rol `Editor` o `Administrador`.
- Contrasena de aplicacion de WordPress (recomendado).
- n8n funcionando.

## 2) Preparar WordPress (una sola vez)

1. Entra a tu perfil de usuario en WordPress: `Usuarios > Perfil`.
2. En la seccion **Contrasenas de aplicacion**, crea una nueva (ejemplo: `n8n-blog`).
3. Guarda el usuario y la contrasena generada.

Notas:
- WordPress expone la API en `https://tu-dominio.com/wp-json/wp/v2/`.
- Para local, reemplaza por tu URL (por ejemplo `http://localhost/wordpress/wp-json/wp/v2/`).

## 3) Credencial en n8n

En n8n crea una credencial para HTTP Basic Auth:

- `User`: usuario de WordPress
- `Password`: contrasena de aplicacion

## 4) Flujo minimo para crear un post

## Nodo 1: Trigger
Puede ser cualquiera:
- `Webhook`
- `Cron`
- `Manual Trigger`
- `RSS Read` (si replicas contenido)

## Nodo 2: Set (armar payload)
Define campos como:

- `title`
- `content` (HTML permitido)
- `status` (`draft` o `publish`)
- `slug` (opcional)
- `excerpt` (opcional)
- `categories` (array de IDs)
- `tags` (array de IDs)

Ejemplo de JSON:

```json
{
  "title": "Nueva criatura: Nyx",
  "content": "<p>Hoy presentamos a Nyx...</p>",
  "status": "publish",
  "slug": "nueva-criatura-nyx",
  "excerpt": "Conoce a Nyx, la nueva criatura de Weirdlings"
}
```

## Nodo 3: HTTP Request (crear entrada)
Configuracion sugerida:

- `Method`: `POST`
- `URL`: `https://tu-dominio.com/wp-json/wp/v2/posts`
- `Authentication`: Basic Auth (la credencial creada)
- `Send Body`: JSON
- `Body Content Type`: JSON

Respuesta esperada:
- Codigo `201`
- JSON con `id`, `link`, `status`, etc.

## 5) Flujo con imagen destacada (opcional)

Si quieres publicar con imagen destacada:

1. Sube el archivo con un nodo `HTTP Request` a:
   - `POST /wp-json/wp/v2/media`
2. En headers agrega:
   - `Content-Disposition: attachment; filename=mi-imagen.jpg`
   - `Content-Type: image/jpeg` (o el mime correcto)
3. Guarda el `id` del media retornado.
4. En el POST de entradas, envia `featured_media: <id>`.

Ejemplo de body del post con imagen:

```json
{
  "title": "Tutorial de amigurumi",
  "content": "<p>Contenido del tutorial...</p>",
  "status": "publish",
  "featured_media": 123
}
```

## 6) Categorias y etiquetas automaticas (opcional)

Si recibes nombres y no IDs:

1. Buscar categoria por nombre:
   - `GET /wp-json/wp/v2/categories?search=nombre`
2. Si no existe, crear:
   - `POST /wp-json/wp/v2/categories`
3. Repite igual para tags:
   - `GET/POST /wp-json/wp/v2/tags`

Luego envia los IDs en `categories` y `tags` al crear el post.

## 7) Metodo normal y metodo automatizado conviven

Puedes seguir publicando desde el panel normal sin ningun cambio:

- `WordPress > Entradas > Anadir nueva`

Las entradas creadas por n8n aparecen igual que cualquier otra en el blog del tema.

## 8) Recomendaciones de seguridad

- Usa siempre contrasenas de aplicacion, no la contrasena real del usuario.
- Limita permisos del usuario usado por n8n (idealmente `Editor`).
- Si usas `Webhook` publico en n8n, protege con firma/token.
- Publica primero como `draft` y agrega una aprobacion humana antes de `publish`.

## 9) Prueba rapida

1. Ejecuta el workflow en n8n con `status = draft`.
2. Verifica en `Entradas` de WordPress que se creo correctamente.
3. Cambia a `publish` cuando valides formato y SEO.
