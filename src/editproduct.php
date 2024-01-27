<?php
namespace Wlc\PromotedProduct;

class EditProduct {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_id_field' ), 11 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_id_field' ), 11 );

		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_custom_title_field' ), 13 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_custom_title_field' ), 13 );

		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_expiry_field' ), 14 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_expiry_field' ), 14 );

		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_expiry_date_field' ), 15 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_expiry_date_field' ), 15 );
	}

	/**
	 * Enqueue styles.
	 *
	 * @return void
	 */
	public function admin_styles() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		wp_register_style( 'jquery-ui-timepicker-style', WLC_PP_PLUGIN_URL . '/assets/lib/jquery-ui-timepicker-addon.min.css', array(), '1.6.3' );

		if ( in_array( $screen_id, array( 'product', 'edit-product' ) ) ) {
			wp_enqueue_style( 'jquery-ui-timepicker-style' );
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function admin_scripts() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		wp_register_script( 'jquery-ui-timepicker', WLC_PP_PLUGIN_URL . '/assets/lib/jquery-ui-timepicker-addon.min.js', array( 'jquery', 'jquery-ui-datepicker' ), '1.6.3', true );
		wp_register_script( 'wlc-admin-product', WLC_PP_PLUGIN_URL . '/assets/admin-product.min.js', array( 'jquery-ui-timepicker' ), '1.0', true );
		wp_localize_script(
			'wlc-admin-product',
			'wlc_admin_product_params',
			array(
				'i18n_time_only_title' => _x( 'Choose Time', 'timepicker', WLC_PP_TD ),
				'i18n_time_text'       => _x( 'Time', 'timepicker', WLC_PP_TD ),
				'i18n_hour_text'       => _x( 'Hour', 'timepicker', WLC_PP_TD ),
				'i18n_minute_text'     => _x( 'Minute', 'timepicker', WLC_PP_TD ),
				'i18n_second_text'     => _x( 'Second', 'timepicker', WLC_PP_TD ),
				'i18n_current_text'    => _x( 'Now', 'timepicker', WLC_PP_TD ),
				'i18n_close_text'      => _x( 'Done', 'timepicker', WLC_PP_TD ),
			)
		);

		if ( in_array( $screen_id, array( 'product', 'edit-product' ) ) ) {
			wp_enqueue_script( 'wlc-admin-product' );
		}
	}

	/**
	 * Add Promoted Product ID field to product edit page.
	 *
	 * @return void
	 */
	public function add_id_field() {
		global $post;

		woocommerce_wp_checkbox(
			array(
				'id'          => 'wlc_promoted_product_id',
				'label'       => __( 'Promote this product', WLC_PP_TD ),
				'description' => __( 'Check this box if you want to promote this product.', WLC_PP_TD ),
				'value'       => wc_bool_to_string( $post->ID === absint( get_option( 'wlc_promoted_product_id' ) ) ),
			)
		);

		echo '<div class="promoted_fields">';
	}

	/**
	 * Save Promoted Product ID field.
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	public function save_id_field( $post_id ) {
		$promoted_product_id = absint( get_option( 'wlc_promoted_product_id' ) );

		if ( $promoted_product_id && $promoted_product_id === $post_id ) {
			// unset promoted product if it's unchecked
			if ( ! isset( $_POST['wlc_promoted_product_id'] ) ) {
				update_option( 'wlc_promoted_product_id', '' );
				return;
			}
		}

		// set promoted product if it's checked
		if ( ! empty( $_POST['wlc_promoted_product_id'] ) ) {
			update_option( 'wlc_promoted_product_id', $post_id );
			return;
		}
	}

	/**
	 * Add Promoted Product custom title field to product edit page.
	 *
	 * @return void
	 */
	public function add_custom_title_field() {
		woocommerce_wp_text_input(
			array(
				'id'          => 'wlc_promoted_product_custom_title',
				'label'       => __( 'Set custom title', WLC_PP_TD ),
				'type'        => 'text',
				'description' => __( 'Enter the custom title for promoted product.', WLC_PP_TD ),
				'value'       => esc_attr( get_option( 'wlc_promoted_product_custom_title' ) ),
			)
		);
	}

	/**
	 * Save Promoted Product custom title field.
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	public function save_custom_title_field( $post_id ) {
		$custom_title = wp_kses_post( $_POST['wlc_promoted_product_custom_title'] );

		update_option( 'wlc_promoted_product_custom_title', $custom_title );
	}

	/**
	 * Add Promoted Product Expiry field to product edit page.
	 *
	 * @return void
	 */
	public function add_expiry_field() {
		woocommerce_wp_checkbox(
			array(
				'id'          => 'wlc_promoted_product_expiry',
				'label'       => __( 'Expiry this product', WLC_PP_TD ),
				'description' => __( 'Check this box if you want to expiry this product.', WLC_PP_TD ),
				'value'       => wc_bool_to_string( absint( get_option( 'wlc_promoted_product_expiry' ) ) ),
			)
		);
	}

	/**
	 * Save Promoted Product Expiry field.
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	public function save_expiry_field( $post_id ) {
		$promoted_product_id = absint( get_option( 'wlc_promoted_product_id' ) );

		if ( $promoted_product_id && $promoted_product_id === $post_id ) {
			// unset promoted product expiry if it's unchecked
			if ( ! isset( $_POST['wlc_promoted_product_expiry'] ) ) {
				update_option( 'wlc_promoted_product_expiry', '' );
				return;
			}

			// set promoted product expiry if it's checked
			if ( ! empty( $_POST['wlc_promoted_product_expiry'] ) ) {
				update_option( 'wlc_promoted_product_expiry', 1 );
				return;
			}
		}
	}

	/**
	 * Add Promoted Product expiry date field to product edit page.
	 *
	 * @return void
	 */
	public function add_expiry_date_field() {
		echo '<div class="expiry_fields">';

		// Expiry date.
		$expiry_date = get_option( 'wlc_promoted_product_expiry_date' );
		woocommerce_wp_text_input(
			array(
				'id'                => 'wlc_promoted_product_expiry_date',
				'value'             => esc_attr( $expiry_date ),
				'label'             => __( 'Expiry date', WLC_PP_TD ),
				'placeholder'       => 'YYYY-MM-DD HH:MM',
				'description'       => __( 'The promoted product will expire at this date.', WLC_PP_TD ),
				'desc_tip'          => true,
				'class'             => 'datetime-picker',
			)
		);

		echo '</div></div>';
	}

	/**
	 * Save Promoted Product expiry date field.
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	public function save_expiry_date_field( $post_id ) {
		$expiry_date = isset( $_POST['wlc_promoted_product_expiry_date'] ) ? wc_clean( $_POST['wlc_promoted_product_expiry_date'] ) : '';

		update_option( 'wlc_promoted_product_expiry_date', $expiry_date );
	}
}
