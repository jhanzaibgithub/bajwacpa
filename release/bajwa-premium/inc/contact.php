<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'admin_post_nopriv_bajwa_contact', 'bajwa_process_contact' );
add_action( 'admin_post_bajwa_contact', 'bajwa_process_contact' );
function bajwa_process_contact() {
	if ( ! isset( $_POST['bajwa_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bajwa_contact_nonce'] ) ), 'bajwa_contact' ) ) { wp_die( 'Security check failed.' ); }
	if ( ! empty( $_POST['website'] ) ) { wp_safe_redirect( home_url( '/contact/?sent=1' ) ); exit; }
	$name = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$service = sanitize_text_field( wp_unslash( $_POST['service_interest'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
	if ( ! $name || ! is_email( $email ) || ! $message ) { wp_safe_redirect( home_url( '/contact/?sent=0' ) ); exit; }
	$body = "Name: $name\nEmail: $email\nPhone: $phone\nService: $service\n\n$message";
	$sent = wp_mail( get_option( 'admin_email' ), 'Website enquiry from ' . $name, $body, array( 'Reply-To: ' . $name . ' <' . $email . '>' ) );
	wp_safe_redirect( home_url( '/contact/?sent=' . ( $sent ? '1' : '0' ) ) ); exit;
}
