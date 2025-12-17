<?php
/**
 * Class Frontend
 *
 * @package Wlc\PromotedProduct
 */

namespace Wlc\PromotedProduct;

/**
 * Class Frontend
 */
class Frontend {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'woocommerce_before_main_content', array( $this, 'display_promoted_product_info' ), 13 );
	}

	/**
	 * Display promoted product info.
	 *
	 * @return void
	 */
	public function display_promoted_product_info() {
		$promoted_product_id = absint( get_option( 'wlc_promoted_product_id' ) );
		if ( ! $promoted_product_id ) {
			return;
		}

		if ( $this->is_promoted_product_expired( $promoted_product_id ) ) {
			return;
		}

		$product = wc_get_product( $promoted_product_id );
		if ( ! $product ) {
			return;
		}

		$custom_title = get_option( 'wlc_promoted_product_custom_title' );
		$custom_title = $custom_title ? $custom_title : $product->get_name();

		$link = get_permalink( $promoted_product_id );

		$bg_color   = get_option( 'wlc_promoted_product_bg_color' );
		$text_color = get_option( 'wlc_promoted_product_text_color' );

		$style = '';
		if ( $bg_color ) {
			$style .= 'background-color: ' . $bg_color . ';';
		}
		if ( $text_color ) {
			$style .= 'color: ' . $text_color . ';';
		}

		$title_prefix = get_option( 'wlc_promoted_product_title_prefix' );
		$title_prefix = $title_prefix ? $title_prefix : '';

		?>
		<div class="wlc-promoted-product-info" style="<?php echo esc_attr( $style ); ?>">
			<div class="wlc-promoted-product-info__inner">
				<?php echo esc_html( $title_prefix ); ?>
				<?php printf( '<a href="%s">%s</a>', $link, esc_html( $custom_title ) ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Is product promoted.
	 *
	 * @param int $product_id Product ID.
	 *
	 * @return bool
	 */
	public function is_product_promoted( $product_id ) {
		$promoted_product_id = absint( get_option( 'wlc_promoted_product_id' ) );

		if ( $promoted_product_id !== $product_id ) {
			return false;
		}

		if ( $this->is_promoted_product_expired( $product_id ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Is promoted product expired.
	 *
	 * @param int $product_id Product ID.
	 *
	 * @return bool
	 */
	public function is_promoted_product_expired( $product_id ) {
		$expiry = get_option( 'wlc_promoted_product_expiry' );

		if ( ! $expiry ) {
			return false;
		}

		$expiry_date = get_option( 'wlc_promoted_product_expiry_date' );
		if ( $expiry_date ) {
			$expiry_date = strtotime( $expiry_date );

			if ( $expiry_date < current_time( 'timestamp' ) ) {
				return true;
			}
		}

		return false;
	}
}
