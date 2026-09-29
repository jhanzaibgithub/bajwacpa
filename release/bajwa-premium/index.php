<?php
if ( ! defined( 'ABSPATH' ) ) {
	require __DIR__ . '/standalone.php';
	exit;
}
get_header();
?><main id="main"><header class="page-hero"><div class="container"><p class="eyebrow">Insights</p><h1><?php is_home() ? single_post_title() : wp_title(''); ?></h1></div></header><section class="section"><div class="container card-grid"><?php while(have_posts()): the_post(); ?><article class="post-card"><p class="eyebrow"><?php echo esc_html(get_the_date()); ?></p><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html(wp_trim_words(get_the_excerpt(),24)); ?></p><a class="text-link" href="<?php the_permalink(); ?>">Read article <?php echo bajwa_icon('arrow'); ?></a></article><?php endwhile; ?></div></section></main><?php get_footer(); ?>
