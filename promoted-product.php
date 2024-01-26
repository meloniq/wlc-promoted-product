<?php
/**
 * Plugin Name:       WLC Promoted Product for WooCommerce
 * Plugin URI:        https://blog.meloniq.net/
 * Description:       Set Promoted Product for WooCommerce.
 *
 * Requires at least: 6.4
 * Requires PHP:      8.0
 * Version:           1.0
 *
 * Author:            MELONIQ.NET
 * Author URI:        https://blog.meloniq.net
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wlc-promoted-product
 *
 * @package           meloniq
 */

namespace Wlc\PromotedProduct;

// If this file is accessed directly, then abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WLC_PP_TD', 'wlc-promoted-product' );
define( 'WLC_PP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include the autoloader so we can dynamically include the rest of the classes.
require_once trailingslashit( dirname( __FILE__ ) ) . 'vendor/autoload.php';

/**
 * Setup Plugin data.
 *
 * @return void
 */
function setup() {
	global $wlc_promoted_product;

	// If WooCommerce is not active, then abort.
	if ( ! function_exists( 'WC' ) ) {
		return;
	}

	$wlc_promoted_product['settings']     = new Settings();
	$wlc_promoted_product['edit_product'] = new EditProduct();
	$wlc_promoted_product['frontend']     = new Frontend();
}
add_action( 'after_setup_theme', 'Wlc\PromotedProduct\setup' );
