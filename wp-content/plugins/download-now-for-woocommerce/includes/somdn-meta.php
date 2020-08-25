<?php
/**
 * Free Downloads - Post Meta
 * 
 * Post meta boxes for products.
 * 
 * @version	2.3.5
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Generated by the WordPress Meta Box generator
 * at http://jeremyhixon.com/tool/wordpress-meta-box-generator/
 */

function somdn_product_meta_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function somdn_product_meta_box_html( $post ) {

	wp_nonce_field( '_download_now_nonce', 'download_now_nonce' );

	$product_id = $post->ID;
	$product = somdn_get_product( $product_id );

	$genoptions = get_option( 'somdn_gen_settings' );
	$somdn_indy = isset( $genoptions['somdn_indy_items'] ) ? $genoptions['somdn_indy_items'] : false ;
	$somdn_indy_excl = isset( $genoptions['somdn_indy_exclude_items'] ) ? $genoptions['somdn_indy_exclude_items'] : false ;
	$indy_text = __( 'Allow free download', 'download-now-for-woocommerce' );

	if ( $somdn_indy_excl ) {
		$indy_text = __( 'Disallow free download', 'download-now-for-woocommerce' );
	}
	
	?>

	<p class="font-15">

		<?php $download_count = get_post_meta( $post->ID, 'somdn_dlcount', true ) ? get_post_meta( $post->ID, 'somdn_dlcount', true ) : 0 ; ?>

		<span class="dashicons dashicons-download"></span> <strong>Total Downloads: <?php echo esc_html( $download_count ); ?></strong>
		
	</p>
	
	<?php if ( $somdn_indy || $somdn_indy_excl ) { ?>

		<p>

			<input type="checkbox" name="somdn_included" id="somdn_included" value="somdn_included" <?php echo ( somdn_product_meta_get_meta( 'somdn_included' ) === 'somdn_included' ) ? 'checked' : ''; ?>>
			<label for="somdn_included"><?php echo $indy_text; ?></label>
		</p>
		
		<p class="description">Only applies to free digital products</p>

	<?php } ?>

	<?php if ( $download_count ) { ?>
		<p>

		<input type="checkbox" id="somdn-show-reset" name="somdn-show-reset" role="button">
		<label for="somdn-show-reset" id="somdn-show-reset-button" class="button">Reset Count</label>

		<label for"somdn-reset-dl-count-checkbox" id="somdn-reset-dl-count-checkbox-label">
			<input type="checkbox" name="somdn-reset-dl-count-checkbox" id="somdn-reset-dl-count-checkbox">
			Are you sure?
		</label>
		<label for="somdn-show-reset" id="somdn-show-reset-button-cancel" class="button">Cancel</label>

		</p>
	<?php } ?>

	<?php do_action( 'somdn_product_meta_box_html', $post, $product_id ); ?>

	<?php

}

add_action( 'save_post', 'somdn_save_meta_product_meta' );
function somdn_save_meta_product_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['download_now_nonce'] ) || ! wp_verify_nonce( $_POST['download_now_nonce'], '_download_now_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['somdn_included'] ) ) {
		update_post_meta( $post_id, 'somdn_included', sanitize_key( $_POST['somdn_included'] ) );
	} else {
		update_post_meta( $post_id, 'somdn_included', NULL );
	}

	if ( isset( $_POST['somdn-show-reset'] ) && isset( $_POST['somdn-reset-dl-count-checkbox'] ) ) {
		update_post_meta( $post_id, 'somdn_dlcount', 0 );
	}
	do_action( 'somdn_save_meta_product_meta', $post_id );
}