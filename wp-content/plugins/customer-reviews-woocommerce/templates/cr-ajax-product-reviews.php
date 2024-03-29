<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

$no_comments_yet = true;

?>
<div id="reviews" class="woocommerce-Reviews cr-reviews-ajax-reviews">
	<div id="comments" class="cr-reviews-ajax-comments">
		<h2 class="woocommerce-Reviews-title">
			<?php
			$count = $product->get_review_count();
			if ( $count && wc_review_ratings_enabled() ) {
				/* translators: 1: reviews count 2: product name */
				$reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
				echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // WPCS: XSS ok.
			} else {
				esc_html_e( 'Reviews', 'woocommerce' );
			}
			?>
		</h2>

		<?php if ( have_comments() ) : ?>

			<?php
			$no_comments_yet = false;
			//check for old WooCommerce versions
			if( method_exists( $product, 'get_id' ) ) {
				$cr_product_id  = $product->get_id();
			} else {
				$cr_product_id  = $product->id;
			}
			$cr_get_reviews = CR_Ajax_Reviews::get_reviews( $cr_product_id );
			do_action( 'ivole_reviews_summary', $cr_product_id, true );
			do_action( 'cr_reviews_customer_images', $cr_get_reviews['reviews'] );
			do_action( 'cr_reviews_sorting', $cr_product_id );
			?>
			<ol class="commentlist cr-ajax-reviews-list" data-product="<?php echo $cr_product_id; ?>">
				<?php
					wp_list_comments( apply_filters(
						'woocommerce_product_review_list_args',
						array( 'callback' => 'woocommerce_comments', 'reverse_top_level' => false, 'per_page' => CR_Ajax_Reviews::get_per_page(), 'page' => 1 ) ),
						$cr_get_reviews['reviews'][0]
					);
				?>
			</ol>

			<?php
				$nonce = wp_create_nonce( "cr_product_reviews_" . $cr_product_id );
				if ( $cr_get_reviews['reviews_count'] > CR_Ajax_Reviews::get_per_page() ) :
			?>
				<button id="cr-show-more-reviews-id" type="button" data-nonce="<?php echo $nonce; ?>" data-page="1"><?php echo __( 'Show more', IVOLE_TEXT_DOMAIN ); ?></button>
				<span id="cr-show-more-review-spinner" style="display:none;"></span>
			<?php
				endif;
			?>

		<?php else : ?>
			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
		<?php endif; ?>
	</div>

	<div id="cr-ajax-reviews-review-form" <?php if( $no_comments_yet ) { echo 'class="cr-ajax-reviews-review-form-nc"'; } ?>>
		<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
			<div id="review_form_wrapper">
				<div id="review_form">
					<?php
					$commenter    = wp_get_current_commenter();
					$comment_form = array(
						/* translators: %s is product title */
						'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'woocommerce' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
						/* translators: %s is product title */
						'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
						'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
						'title_reply_after'   => '</span>',
						'comment_notes_after' => '',
						'label_submit'        => esc_html__( 'Submit', 'woocommerce' ),
						'logged_in_as'        => '',
						'comment_field'       => '',
						'submit_button'				=> '<a href="' . esc_url( get_permalink( $cr_product_id ) ) . '#tab-reviews" id="cr-ajax-reviews-cancel">' . __( 'Cancel', IVOLE_TEXT_DOMAIN ) . '</a><input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />'
					);

					$name_email_required = (bool) get_option( 'require_name_email', 1 );
					$fields              = array(
						'author' => array(
							'label'    => __( 'Name', 'woocommerce' ),
							'type'     => 'text',
							'value'    => $commenter['comment_author'],
							'required' => $name_email_required,
						),
						'email' => array(
							'label'    => __( 'Email', 'woocommerce' ),
							'type'     => 'email',
							'value'    => $commenter['comment_author_email'],
							'required' => $name_email_required,
						),
					);

					$comment_form['fields'] = array();

					foreach ( $fields as $key => $field ) {
						$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
						$field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

						if ( $field['required'] ) {
							$field_html .= '&nbsp;<span class="required">*</span>';
						}

						$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

						$comment_form['fields'][ $key ] = $field_html;
					}

					$account_page_url = wc_get_page_permalink( 'myaccount' );
					if ( $account_page_url ) {
						/* translators: %s opening and closing link tags respectively */
						$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>' .
						'<p><a href="' . esc_url( get_permalink( $cr_product_id ) ) . '#tab-reviews" id="cr-ajax-reviews-cancel">' . __( 'Cancel', IVOLE_TEXT_DOMAIN ) . '</a></p>';
					}

					if ( wc_review_ratings_enabled() ) {
						$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . '</label><select name="rating" id="rating" required>
							<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
							<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
							<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
							<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
							<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
							<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
						</select></div>';
					}

					$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';

					comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
					?>
				</div>
			</div>
		<?php else : ?>
			<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>
			<p><a href="<?php echo esc_url( get_permalink( $cr_product_id ) ) . '#tab-reviews'; ?>" id="cr-ajax-reviews-cancel"><?php echo __( 'Cancel', IVOLE_TEXT_DOMAIN ); ?></a></p>
		<?php endif; ?>
	</div>

	<div class="clear"></div>
</div>
