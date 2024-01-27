<?php
use Wlc\PromotedProduct\EditProduct;
use WP_Mock\Matcher\AnyInstance;
use WP_Mock\Tools\TestCase;

final class EditProductTestCase extends TestCase {

	public function test_hook_expectations() : void {
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

}
