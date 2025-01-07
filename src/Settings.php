<?php
namespace Wlc\PromotedProduct;

class Settings {

	/**
	 * Settings constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'woocommerce_products_general_settings', array( $this, 'add_products_general_settings' ), 10, 1 );
	}

	/**
	 * Add settings to WooCommerce > Settings > Products > General.
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public function add_products_general_settings( $settings ) {
		$settings[] = array(
			'title' => __( 'WLC Promoted Product', WLC_PP_TD ),
			'type'  => 'title',
			'desc'  => '',
			'id'    => 'products_general_settings',
		);

		$settings[] = array(
			'title'       => __( 'Title prefix', WLC_PP_TD ),
			'id'          => 'wlc_promoted_product_title_prefix',
			'type'        => 'text',
			'default'     => __( 'FLASH SALE: ', WLC_PP_TD ),
			'class'       => '',
			'css'         => '',
			'placeholder' => '',
			'desc_tip'    => __( 'This text will be added before the promoted product title.', WLC_PP_TD ),
		);

		$settings[] = array(
			'title'       => __( 'Background color', WLC_PP_TD ),
			'id'          => 'wlc_promoted_product_bg_color',
			'type'        => 'color',
			'default'     => '',
			'class'       => '',
			'css'         => '',
			'placeholder' => '',
			'desc_tip'    => __( 'This is the promoted product background color.', WLC_PP_TD ),
		);

		$settings[] = array(
			'title'       => __( 'Text color', WLC_PP_TD ),
			'id'          => 'wlc_promoted_product_text_color',
			'type'        => 'color',
			'default'     => '',
			'class'       => '',
			'css'         => '',
			'placeholder' => '',
			'desc_tip'    => __( 'This is the promoted product text color.', WLC_PP_TD ),
		);

		$settings[] = array(
			'type'        => 'info',
			'row_class'   => '',
			'css'         => '',
			'text'        => $this->get_promoted_product_link(),
		);

		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'products_general_settings',
		);

		return $settings;
	}

	/**
	 * Get promoted product link.
	 *
	 * @return string
	 */
	protected function get_promoted_product_link() {
		$promoted_product_id = get_option( 'wlc_promoted_product_id' );

		// check if promoted product is selected
		if ( ! $promoted_product_id ) {
			return __( 'No promoted product selected.', WLC_PP_TD );
		}

		// check expiry
		$expiry = get_option( 'wlc_promoted_product_expiry' );
		if ( $expiry ) {
			$expiry_date = get_option( 'wlc_promoted_product_expiry_date' );
			if ( strtotime( $expiry_date ) < current_time( 'timestamp' ) ) {
				return __( 'Promoted product expired.', WLC_PP_TD );
			}
		}

		$link  = get_edit_post_link( $promoted_product_id );
		$title = get_the_title( $promoted_product_id );

		return sprintf( '<a href="%s">%s</a>', $link, $title );
	}
}
