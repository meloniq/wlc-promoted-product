<?php
/**
 * Test EditProduct class.
 *
 * @package Wlc\PromotedProduct\Tests
 */

use Wlc\PromotedProduct\EditProduct;
use WP_Mock\Matcher\AnyInstance;
use WP_Mock\Tools\TestCase;

final class EditProductTestCase extends TestCase {

	public function test_hook_expectations(): void {
		$anyEditProduct = new AnyInstance( EditProduct::class );

		WP_Mock::expectActionAdded( 'admin_enqueue_scripts', array( $anyEditProduct, 'admin_styles' ) );
		WP_Mock::expectActionAdded( 'admin_enqueue_scripts', array( $anyEditProduct, 'admin_scripts' ) );

		WP_Mock::expectActionAdded( 'woocommerce_product_options_general_product_data', array( $anyEditProduct, 'add_id_field' ), 11 );
		WP_Mock::expectActionAdded( 'woocommerce_process_product_meta', array( $anyEditProduct, 'save_id_field' ), 11 );

		WP_Mock::expectActionAdded( 'woocommerce_product_options_general_product_data', array( $anyEditProduct, 'add_custom_title_field' ), 13 );
		WP_Mock::expectActionAdded( 'woocommerce_process_product_meta', array( $anyEditProduct, 'save_custom_title_field' ), 13 );

		WP_Mock::expectActionAdded( 'woocommerce_product_options_general_product_data', array( $anyEditProduct, 'add_expiry_field' ), 14 );
		WP_Mock::expectActionAdded( 'woocommerce_process_product_meta', array( $anyEditProduct, 'save_expiry_field' ), 14 );

		WP_Mock::expectActionAdded( 'woocommerce_product_options_general_product_data', array( $anyEditProduct, 'add_expiry_date_field' ), 15 );
		WP_Mock::expectActionAdded( 'woocommerce_process_product_meta', array( $anyEditProduct, 'save_expiry_date_field' ), 15 );

		new EditProduct();

		$this->assertConditionsMet();
	}

	public function test_save_id_field(): void {
		$editProductInstance = new EditProduct();

		// unset promoted product if it's unchecked.
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_id' )->andReturn( 123 );
		WP_Mock::userFunction( 'absint' )->once()->with( 123 )->andReturn( 123 );
		WP_Mock::userFunction( 'update_option' )->once()->with( 'wlc_promoted_product_id', '' );

		$editProductInstance->save_id_field( 123 );

		// set promoted product if it's checked.
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_id' )->andReturn( '' );
		WP_Mock::userFunction( 'absint' )->once()->with( '' )->andReturn( 0 );
		WP_Mock::userFunction( 'update_option' )->once()->with( 'wlc_promoted_product_id', 123 );
		$_POST['wlc_promoted_product_id'] = 1;

		$editProductInstance->save_id_field( 123 );

		$this->assertConditionsMet();
	}

	public function test_save_custom_title_field(): void {
		$editProductInstance = new EditProduct();

		// set custom title.
		$_POST['wlc_promoted_product_custom_title'] = 'Hello World';
		WP_Mock::userFunction( 'wp_kses_post' )->once()->with( 'Hello World' )->andReturn( 'Hello World' );
		WP_Mock::userFunction( 'update_option' )->once()->with( 'wlc_promoted_product_custom_title', 'Hello World' );

		$editProductInstance->save_custom_title_field( 123 );

		$this->assertConditionsMet();
	}

	public function test_save_expiry_field(): void {
		$editProductInstance = new EditProduct();

		// unset promoted product expiry if it's unchecked.
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_id' )->andReturn( 123 );
		WP_Mock::userFunction( 'absint' )->once()->with( 123 )->andReturn( 123 );
		WP_Mock::userFunction( 'update_option' )->once()->with( 'wlc_promoted_product_expiry', '' );

		$editProductInstance->save_expiry_field( 123 );

		// set promoted product expiry if it's checked.
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_id' )->andReturn( 123 );
		WP_Mock::userFunction( 'absint' )->once()->with( 123 )->andReturn( 123 );
		WP_Mock::userFunction( 'update_option' )->once()->with( 'wlc_promoted_product_expiry', 1 );
		$_POST['wlc_promoted_product_expiry'] = 1;

		$editProductInstance->save_expiry_field( 123 );

		$this->assertConditionsMet();
	}

	public function test_save_expiry_date_field(): void {
		$editProductInstance = new EditProduct();

		// set expiry date.
		$_POST['wlc_promoted_product_expiry_date'] = '2023-01-01 00:00';
		WP_Mock::userFunction( 'wc_clean' )->once()->with( '2023-01-01 00:00' )->andReturn( '2023-01-01 00:00' );
		WP_Mock::userFunction( 'update_option' )->once()->with( 'wlc_promoted_product_expiry_date', '2023-01-01 00:00' );

		$editProductInstance->save_expiry_date_field( 123 );

		// missing expiry date.
		unset( $_POST['wlc_promoted_product_expiry_date'] );
		WP_Mock::userFunction( 'update_option' )->once()->with( 'wlc_promoted_product_expiry_date', '' );

		$editProductInstance->save_expiry_date_field( 123 );

		$this->assertConditionsMet();
	}
}
