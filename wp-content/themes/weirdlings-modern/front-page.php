<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$items = weirdlings_home_featured_items();
?>

<section class="wl-hero">
  <div class="wl-container">
    <div class="wl-hero__shell">
      <div class="wl-hero__inner">
        <div>
          <span class="wl-kicker">Weirdlings crochet creatures</span>
          <h1>Adopta tu <span class="accent">criatura favorita</span></h1>
          <p>Amigurumis y llaveros tejidos a mano con diseños originales, inspirados en lo extraño, lo tierno y lo mágico. Todo el universo visual de la tienda sigue este mismo lenguaje oscuro y coleccionable.</p>
          <div class="wl-hero__actions">
            <a class="wl-button wl-button--primary" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/tienda/' ) ); ?>">Ir a la tienda</a>
            <a class="wl-button wl-button--ghost" href="#criaturas">Ver criaturas</a>
          </div>
        </div>

        <div class="wl-hero__art">
          <div class="wl-hero__canvas"></div>
          <figure class="wl-hero__media" aria-label="Hero image">
            <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/hero-home.png' ) ); ?>" alt="Weirdlings hero image">
          </figure>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="wl-feature-strip">
  <div class="wl-container wl-feature-strip__inner">
    <article class="wl-feature">
      <div class="wl-feature__icon" aria-hidden="true"><?php echo weirdlings_svg_icon_placeholder( 'Hechos a mano' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
      <div>
        <h3>Hechos a mano</h3>
        <p>Cada criatura nace punto a punto y con carácter propio.</p>
      </div>
    </article>
    <article class="wl-feature">
      <div class="wl-feature__icon" aria-hidden="true"><?php echo weirdlings_svg_icon_placeholder( 'Diseños originales' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
      <div>
        <h3>Diseños originales</h3>
        <p>No verás estas criaturas en otro lugar.</p>
      </div>
    </article>
    <article class="wl-feature">
      <div class="wl-feature__icon" aria-hidden="true"><?php echo weirdlings_svg_icon_placeholder( 'Personalizados' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
      <div>
        <h3>Personalizados</h3>
        <p>Convertimos ideas extrañas en crochet real.</p>
      </div>
    </article>
    <article class="wl-feature">
      <div class="wl-feature__icon" aria-hidden="true"><?php echo weirdlings_svg_icon_placeholder( 'Envíos seguros' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
      <div>
        <h3>Envíos seguros</h3>
        <p>Empaque cuidado y protección para cada pedido.</p>
      </div>
    </article>
  </div>
</section>

<section id="criaturas" class="wl-section">
  <div class="wl-container">
    <h2 class="wl-section__title">Nuestras criaturas más populares</h2>
    <p class="wl-section__subtitle">La misma estética de la portada se mantiene en cada tarjeta de producto: oscura, tierna y coleccionable.</p>

    <div class="wl-product-grid">
      <?php foreach ( $items as $item ) : ?>
        <article class="wl-product-card">
          <span class="wl-product-card__badge <?php echo ! empty( $item['on_sale'] ) ? 'wl-product-card__badge--sale' : ''; ?>"><?php echo esc_html( $item['on_sale'] ? 'SALE' : $item['badge'] ); ?></span>
          <a href="<?php echo esc_url( $item['link'] ); ?>" class="wl-product-card__media" aria-label="<?php echo esc_attr( $item['title'] ); ?>">
            <?php echo $item['image']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
          </a>
          <div class="wl-product-card__content">
            <div class="wl-product-card__type"><?php echo esc_html( $item['type'] ); ?></div>
            <h3 class="wl-product-card__title"><?php echo esc_html( $item['title'] ); ?></h3>
            <div class="wl-product-card__price <?php echo ! empty( $item['on_sale'] ) ? 'wl-product-card__price--sale' : ''; ?>">
              <?php if ( ! empty( $item['on_sale'] ) && ! empty( $item['price']['regular'] ) && ! empty( $item['price']['sale'] ) ) : ?>
                <span class="wl-product-card__price-regular"><del><?php echo esc_html( $item['price']['regular'] ); ?></del></span>
                <span class="wl-product-card__price-sale"><?php echo esc_html( $item['price']['sale'] ); ?></span>
              <?php else : ?>
                <span class="wl-product-card__price-current"><?php echo esc_html( $item['price']['current'] ?? '' ); ?></span>
              <?php endif; ?>
            </div>
          </div>
          <a class="wl-product-card__action" href="<?php echo esc_url( $item['link'] ); ?>" aria-label="Ver <?php echo esc_attr( $item['title'] ); ?>">+</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="wl-section">
  <div class="wl-container">
    <div class="wl-banner">
      <div class="wl-banner__copy">
        <div class="wl-banner__eyebrow">¿Tienes una idea en mente?</div>
        <h2 class="wl-banner__title">La hacemos realidad.</h2>
        <p class="wl-banner__text">Encargos personalizados, criaturas temáticas y piezas pensadas como pequeños objetos de colección.</p>
        <div>
          <a class="wl-button wl-button--primary" href="<?php echo esc_url( function_exists( 'weirdlings_customization_page_url' ) ? weirdlings_customization_page_url() : home_url( '/personalizados/' ) ); ?>">Pedir personalizado</a>
        </div>
      </div>
      <div class="wl-banner__visuals">
        <?php echo weirdlings_render_placeholder( 'Personalizado', 'portrait', 860, 1100 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <div class="wl-polaroid">
          <?php echo weirdlings_render_placeholder( 'Polaroid', 'square', 700, 900 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
          <div class="wl-polaroid__caption">Tu criatura, tu historia ♥</div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="wl-section">
  <div class="wl-container">
    <div class="wl-promise-grid">
      <article class="wl-promise">
        <div class="wl-promise__icon"><?php echo weirdlings_svg_icon_placeholder( 'Coleccionables' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
        <h3>Coleccionables</h3>
        <p>Ediciones limitadas y criaturas especiales.</p>
      </article>
      <article class="wl-promise">
        <div class="wl-promise__icon"><?php echo weirdlings_svg_icon_placeholder( 'Hecho con amor' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
        <h3>Hecho con amor</h3>
        <p>Cada pieza lleva tiempo, paciencia y mucha magia.</p>
      </article>
      <article class="wl-promise">
        <div class="wl-promise__icon"><?php echo weirdlings_svg_icon_placeholder( 'Presentación especial' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
        <h3>Presentación especial</h3>
        <p>Empaque cuidado con tarjeta incluida.</p>
      </article>
      <article class="wl-promise">
        <div class="wl-promise__icon"><?php echo weirdlings_svg_icon_placeholder( 'Comunidad' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
        <h3>Comunidad Weirdlings</h3>
        <p>Invita a tu manada a seguir la marca en redes.</p>
      </article>
    </div>
  </div>
</section>

<div class="wl-chatbot-sticky" aria-live="polite" data-chatbot-widget>
  <button
    type="button"
    class="wl-chatbot-sticky__button"
    data-chatbot-toggle
    data-chatbot-webhook="https://sistemas-cjp8.onrender.com/webhook-test/72f50ccc-feb1-42a6-8f24-7815314faef9"
    data-chatbot-state="idle"
    aria-expanded="false"
    aria-controls="wl-chatbot-panel"
    aria-label="Abrir chatbot Weirdlings"
  >
    <span class="wl-chatbot-sticky__icon" aria-hidden="true">✦</span>
    <span class="wl-chatbot-sticky__label">Chat Weirdlings</span>
  </button>

  <aside class="wl-chatbot-panel" id="wl-chatbot-panel" data-chatbot-panel hidden>
    <header class="wl-chatbot-panel__head">
      <div>
        <div class="wl-chatbot-panel__kicker">Manada Weirdlings</div>
        <h3>Chat de ayuda</h3>
      </div>
      <button type="button" class="wl-chatbot-panel__close" data-chatbot-close aria-label="Cerrar chat">×</button>
    </header>

    <div class="wl-chatbot-panel__body">
      <div class="wl-chatbot-messages" data-chatbot-messages>
        <article class="wl-chatbot-message wl-chatbot-message--bot">
          <p>Hola, criatura. ¿En qué podemos ayudarte hoy?</p>
        </article>
      </div>

      <div class="wl-chatbot-options" data-chatbot-options>
        <button type="button" class="wl-chatbot-option" data-chatbot-option="Quiero cotizar un personalizado">Cotizar personalizado</button>
        <button type="button" class="wl-chatbot-option" data-chatbot-option="Necesito ayuda con un pedido">Estado de pedido</button>
        <button type="button" class="wl-chatbot-option" data-chatbot-option="Quiero recomendaciones de criaturas">Recomendaciones</button>
      </div>

      <form class="wl-chatbot-form" data-chatbot-form>
        <label class="screen-reader-text" for="wl-chatbot-input">Escribe tu mensaje</label>
        <textarea id="wl-chatbot-input" class="wl-chatbot-form__input" data-chatbot-input rows="2" placeholder="Cuéntanos qué necesitas..."></textarea>

        <div class="wl-chatbot-form__bar">
          <label class="wl-chatbot-file" for="wl-chatbot-file">
            <span>Adjuntar</span>
            <input id="wl-chatbot-file" type="file" data-chatbot-file multiple accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.txt">
          </label>

          <button type="submit" class="wl-button wl-button--primary wl-chatbot-form__send" data-chatbot-send>Enviar</button>
        </div>

        <ul class="wl-chatbot-attachments" data-chatbot-attachments hidden></ul>
      </form>

      <span class="wl-chatbot-sticky__status" data-chatbot-status hidden></span>
    </div>
  </aside>
</div>

<?php
get_footer();
