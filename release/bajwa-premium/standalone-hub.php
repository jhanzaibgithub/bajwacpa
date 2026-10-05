<?php
$theme_uri = rtrim( $base_path, '/' );
$contact = html_entity_decode( $url( 'contact' ) );
$resource_urls = array( 'tips' => html_entity_decode( $url( 'tips' ) ), 'deadlines' => html_entity_decode( $url( 'deadlines' ) ), 'checklists' => html_entity_decode( $url( 'checklists' ) ), 'blog' => html_entity_decode( $url( 'blog' ) ) );
$hub_cards = array();
foreach ( array_slice( $blog_posts, 0, 3 ) as $hub_post ) {
	$hub_img = $blog_image( $hub_post['slug'] );
	$hub_small = file_exists( __DIR__ . '/assets/images/' . $hub_img . '-600.jpg' ) ? '-600' : '-960';
	$hub_cards[] = array( 'title' => html_entity_decode( strip_tags( $hub_post['title']['rendered'] ?? '' ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ), 'url' => html_entity_decode( $article_url( $hub_post['slug'] ) ), 'image' => '<img class="related-post__image" src="assets/images/' . $hub_img . $hub_small . '.jpg" loading="lazy" decoding="async" alt="">', 'date' => date( 'M j, Y', strtotime( $hub_post['date'] ?? 'now' ) ), 'text' => bajwa_sections_trim_words( $hub_post['excerpt']['rendered'] ?? '', 18 ) );
}
ob_start();
require __DIR__ . '/inc/content/tax-resources.php';
$hub_html = str_replace( '[bajwa_latest_posts count="3"]', bajwa_render_post_cards( $hub_cards ), ob_get_clean() );
?>
<main id="main"><header class="page-hero page-hero--image"><div class="page-hero__media"><img class="page-hero__image" src="assets/images/service-books.jpg" srcset="assets/images/service-books-960.jpg 960w, assets/images/service-books-1280.jpg 1280w" width="1672" height="941" alt=""><span class="page-hero__overlay"></span></div><div class="container page-hero__content"><p class="eyebrow">Tax resources</p><h1>Tax Resources</h1><p class="page-hero__intro">Tips, key dates, checklists and articles to help you prepare for tax season with confidence.</p></div></header>
<?php echo $hub_html; ?>
</main>
