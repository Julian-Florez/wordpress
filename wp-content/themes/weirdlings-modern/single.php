<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="wl-page-wrap wl-blog-single-wrap">
	<div class="wl-container wl-blog-single-container">
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'wl-blog-single' ); ?>>
				<header class="wl-blog-single__header">
					<div class="wl-blog-single__meta">
						<span><?php echo esc_html( get_the_date() ); ?></span>
						<span><?php echo esc_html( get_the_author() ); ?></span>
					</div>
					<h1><?php the_title(); ?></h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="wl-blog-single__thumb">
						<?php the_post_thumbnail( 'large' ); ?>
					</figure>
				<?php endif; ?>

				<div class="wl-blog-single__content">
					<?php the_content(); ?>
				</div>

				<footer class="wl-blog-single__footer">
					<div class="wl-blog-single__tags">
						<?php the_tags( '<span>', '</span><span>', '</span>' ); ?>
					</div>
					<div class="wl-blog-single__nav">
						<div><?php previous_post_link( '%link', __( 'Entrada anterior', 'weirdlings-modern' ) ); ?></div>
						<div><?php next_post_link( '%link', __( 'Entrada siguiente', 'weirdlings-modern' ) ); ?></div>
					</div>
				</footer>
			</article>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		<?php endwhile; ?>
	</div>
</section>

<?php
get_footer();
