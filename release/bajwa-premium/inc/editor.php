<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'bajwa-page-design', 'Bajwa Page Hero & Design', 'bajwa_page_design_box', array( 'page', 'service', 'post' ), 'normal', 'high' );
	add_meta_box( 'bajwa-editor-guide', 'Where to edit this page', 'bajwa_editor_guide_box', array( 'page', 'service', 'post' ), 'side', 'high' );
} );
function bajwa_page_design_box( $post ) {
	wp_nonce_field( 'bajwa_page_design', 'bajwa_page_design_nonce' );
	$fields = array( '_bajwa_hero_eyebrow' => array( 'Hero eyebrow', 'Small uppercase text above the page title.' ), '_bajwa_hero_title' => array( 'Hero heading', 'Leave empty to use the WordPress page title.' ), '_bajwa_hero_intro' => array( 'Hero introduction', 'Short supporting text displayed in the designed page header.' ) );
	foreach ( $fields as $key => $config ) { $value = get_post_meta( $post->ID, $key, true ); echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $config[0] ) . '</strong></label><br>'; if ( '_bajwa_hero_intro' === $key ) { echo '<textarea class="widefat" rows="3" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">' . esc_textarea( $value ) . '</textarea>'; } else { echo '<input class="widefat" type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">'; } echo '<span class="description">' . esc_html( $config[1] ) . '</span></p>'; }
	$html = get_post_meta( $post->ID, '_bajwa_section_html', true );
	echo '<hr><p><label for="_bajwa_section_html"><strong>Additional section HTML</strong></label></p><p class="description">Optional section-by-section HTML or shortcodes, rendered before the consultation section.</p><textarea class="widefat code" rows="12" id="_bajwa_section_html" name="_bajwa_section_html">' . esc_textarea( $html ) . '</textarea><p><strong>Page image:</strong> replace it through the Featured Image panel. Imported theme images are available in Media Library.</p>';
}
function bajwa_editor_guide_box( $post ) {
	echo '<p><strong>Main content:</strong> WordPress block editor.</p><p><strong>Top image:</strong> Featured Image.</p><p><strong>Top text:</strong> Bajwa Page Hero & Design.</p>';
	if ( 'page' === $post->post_type && 'home' === $post->post_name ) { echo '<p><strong>Homepage carousel:</strong> <a href="' . esc_url( admin_url( 'edit.php?post_type=hero_slide' ) ) . '">Hero Slides</a>.</p><p><strong>Service cards:</strong> <a href="' . esc_url( admin_url( 'edit.php?post_type=service' ) ) . '">Services</a>.</p>'; }
	if ( 'post' === $post->post_type ) { echo '<p>The Featured Image is used on both the blog card and article hero.</p>'; }
}
add_action( 'save_post', function ( $post_id ) {
	if ( ! isset( $_POST['bajwa_page_design_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bajwa_page_design_nonce'] ) ), 'bajwa_page_design' ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) { return; }
	foreach ( array( '_bajwa_hero_eyebrow', '_bajwa_hero_title', '_bajwa_hero_intro' ) as $key ) { $value = sanitize_textarea_field( wp_unslash( $_POST[ $key ] ?? '' ) ); if ( '' === $value ) { delete_post_meta( $post_id, $key ); } else { update_post_meta( $post_id, $key, $value ); } }
	$html = wp_kses_post( wp_unslash( $_POST['_bajwa_section_html'] ?? '' ) ); if ( '' === trim( $html ) ) { delete_post_meta( $post_id, '_bajwa_section_html' ); } else { update_post_meta( $post_id, '_bajwa_section_html', $html ); }
} );
function bajwa_render_managed_sections() { if ( ! is_singular() ) { return; } $html = get_post_meta( get_queried_object_id(), '_bajwa_section_html', true ); if ( $html ) { echo '<section class="section page-managed-sections"><div class="container prose">' . do_shortcode( wp_kses_post( $html ) ) . '</div></section>'; } }
add_action( 'admin_notices', function () { if ( ! current_user_can( 'manage_options' ) || ! get_option( 'bajwa_theme_setup_complete' ) || get_user_meta( get_current_user_id(), '_bajwa_setup_notice_seen', true ) ) { return; } echo '<div class="notice notice-success is-dismissible"><p><strong>Bajwa CPA website is ready.</strong> Pages, services, navigation, articles and Media Library images were created automatically. Edit content under Pages, Services, Posts and Hero Slides.</p></div>'; update_user_meta( get_current_user_id(), '_bajwa_setup_notice_seen', 1 ); } );
