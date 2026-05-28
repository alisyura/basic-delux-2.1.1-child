<?php get_header(); ?>
	<main id="content" class="content">
	                                    
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'content',  get_post_format() );
		?>

		<!-- Блок похожих записей -->
		<?php echo do_shortcode('[related_posts limit="6" title="Вам также может понравиться"]'); ?>

		<?php
		if ( comments_open() || get_comments_number() ) :
			do_action( 'basic_before_post_comments_area' );
			comments_template();
			do_action( 'basic_after_post_comments_area' );
		endif;
	endwhile; ?>

	</main><!-- #content -->
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
