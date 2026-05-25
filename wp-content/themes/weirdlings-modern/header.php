<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
$logo = get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'wl-brand__image', 'alt' => get_bloginfo( 'name' ) ) ) : '';
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
  <div class="wl-topbar">
    <div class="wl-container wl-topbar__inner">
      <div class="wl-topbar__left">Envíos a todo Colombia</div>
      <div class="wl-topbar__center">Hechos a mano con amor y un poquito de magia oscura</div>
      <div class="wl-topbar__right wl-topbar__social" aria-label="Redes sociales">
        <a href="#" aria-label="Instagram" title="Instagram">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.6"/><circle cx="17.5" cy="6.5" r="1.1" fill="currentColor"/></svg>
        </a>
        <a href="#" aria-label="TikTok" title="TikTok">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 3c.2 1.6 1.2 4.4 4 5v3.2c-1.4 0-2.8-.4-4-1.2V16.2c0 2.8-2.3 5.1-5.1 5.1S3.8 19 3.8 16.2s2.3-5.1 5.1-5.1c.3 0 .7 0 1 .1V10c-.3-.1-.7-.1-1-.1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 3v11.6c0 2.8-2.3 5.1-5.1 5.1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="#" aria-label="Pinterest" title="Pinterest">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21a9 9 0 1 0-2.2-17.7c-1.8.6-3.6 2.2-3.6 4.9 0 1.7.7 3 1.8 3.8.2.1.4 0 .5-.2.1-.1.2-.5.2-.7 0-.2-.5-1.7-.5-1.7-.3-.8-.1-1.6.1-2.2.4-1 1.4-2.1 2.8-2.1 1.5 0 2.7 1 2.7 2.8 0 1.9-1 4-2.4 4-.7 0-1.2-.6-1.1-1.4.2-1 .7-2.1.7-2.8 0-.6-.3-1.2-1-1.2-1 0-1.7 1.1-1.7 2.6 0 1 .3 1.7.3 1.7l-1.2 5c-.3 1-.4 2.3-.3 3.3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
      </div>
    </div>
  </div>

  <header class="wl-site-header">
    <div class="wl-container wl-site-header__inner">
      <a class="wl-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
        <?php if ( $logo ) : ?>
          <?php echo $logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php else : ?>
          <span class="wl-brand__mark"><?php echo esc_html( get_bloginfo( 'name', 'display' ) ?: 'Weirdlings' ); ?></span>
          <span class="wl-brand__tag"><?php echo esc_html( get_bloginfo( 'description', 'display' ) ?: 'crochet creatures' ); ?></span>
        <?php endif; ?>
      </a>

      <nav class="wl-nav" aria-label="<?php esc_attr_e( 'Menú principal', 'weirdlings-modern' ); ?>">
        <?php echo weirdlings_render_menu_items( 'primary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
      </nav>

      <div class="wl-header-actions">
        <a class="wl-action-icon" href="<?php echo esc_url( $shop_url ); ?>" aria-label="<?php esc_attr_e( 'Tienda', 'weirdlings-modern' ); ?>">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 6h18l-1.7 8.5a3 3 0 0 1-2.9 2.3H7.2a3 3 0 0 1-2.9-2.3L2.8 2.8H1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="20" r="1.4" fill="currentColor"/><circle cx="17" cy="20" r="1.4" fill="currentColor"/></svg>
        </a>
        <a class="wl-action-icon" href="<?php echo esc_url( $account_url ); ?>" aria-label="<?php esc_attr_e( 'Mi cuenta', 'weirdlings-modern' ); ?>">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.6"/><path d="M4 21c1.7-4.2 5.1-6 8-6s6.3 1.8 8 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
        </a>
        <a class="wl-action-icon" href="<?php echo esc_url( $cart_url ); ?>" aria-label="<?php esc_attr_e( 'Carrito', 'weirdlings-modern' ); ?>">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 4h2.2l2 10.3a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.5L20.8 8H7.1" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="20" r="1.4" fill="currentColor"/><circle cx="17" cy="20" r="1.4" fill="currentColor"/></svg>
        </a>
        <button class="wl-menu-toggle" type="button" aria-controls="wl-mobile-menu" aria-expanded="false" data-menu-toggle>
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
      </div>
    </div>

    <div class="wl-mobile-panel">
      <div id="wl-mobile-menu" class="wl-mobile-panel__menu" data-mobile-menu>
        <?php echo weirdlings_render_menu_items( 'primary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
      </div>
    </div>
  </header>
  <main class="wl-main">
