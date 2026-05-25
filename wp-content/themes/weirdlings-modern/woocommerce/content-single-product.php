<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * @package WooCommerce\Templates
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'wl-single-product', $product ); ?>>
	<div class="wl-single-product__top">
		<div class="wl-single-product__gallery">
			<?php
			/**
			 * Hook: woocommerce_before_single_product_summary.
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10
			 * @hooked woocommerce_show_product_images - 20
			 */
			do_action( 'woocommerce_before_single_product_summary' );
			?>
		</div>

		<div class="wl-single-product__sidebar">
			<div class="summary entry-summary">
				<?php
				/**
				 * Hook: woocommerce_single_product_summary.
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 * @hooked WC_Structured_Data::generate_product_data() - 60
				 */
				do_action( 'woocommerce_single_product_summary' );
				?>
			</div>

			<div class="wl-single-product__details">
				<section class="wl-single-product__section wl-single-product__section--description">
					<?php wc_get_template( 'single-product/tabs/description.php' ); ?>
				</section>
			</div>
		</div>
	</div>

	<div class="wl-single-product__related">
		<?php woocommerce_output_related_products(); ?>
	</div>

	<section class="wl-single-product__section wl-single-product__section--reviews wl-single-product__reviews">
		<?php comments_template(); ?>
	</section>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>