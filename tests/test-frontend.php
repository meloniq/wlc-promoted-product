<?php
/**
 * Frontend class tests.
 *
 * @package Wlc\PromotedProduct\Tests
 */

use Wlc\PromotedProduct\Frontend;
use WP_Mock\Matcher\AnyInstance;
use WP_Mock\Tools\TestCase;

final class FrontendTestCase extends TestCase {

	public function test_hook_expectations(): void {
		$anyFrontend = new AnyInstance( Frontend::class );

		WP_Mock::expectActionAdded( 'woocommerce_before_main_content', array( $anyFrontend, 'display_promoted_product_info' ), 13 );

		new Frontend();

		$this->assertConditionsMet();
	}

	public function test_is_product_promoted(): void {
		$frontendInstance = new Frontend();

		// not promoted.
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_id' )->andReturn( 0 );
		WP_Mock::userFunction( 'absint' )->once()->with( 0 )->andReturn( 0 );

		$promoted = $frontendInstance->is_product_promoted( 123 );
		$this->assertFalse( $promoted );

		// promoted.
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_id' )->andReturn( 123 );
		WP_Mock::userFunction( 'absint' )->once()->with( 123 )->andReturn( 123 );
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_expiry' )->andReturn( 0 );

		$promoted = $frontendInstance->is_product_promoted( 123 );
		$this->assertTrue( $promoted );
	}

	public function test_is_promoted_product_expired(): void {
		$frontendInstance = new Frontend();

		// not expired.
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_expiry' )->andReturn( 1 );
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_expiry_date' )->andReturn( date( 'Y-m-d H:i', time() + 1000 ) );
		WP_Mock::userFunction( 'current_time' )->once()->with( 'timestamp' )->andReturn( time() );

		$expired = $frontendInstance->is_promoted_product_expired( 123 );
		$this->assertFalse( $expired );

		// expired.
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_expiry' )->andReturn( 1 );
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_expiry_date' )->andReturn( date( 'Y-m-d H:i', time() - 1000 ) );
		WP_Mock::userFunction( 'current_time' )->once()->with( 'timestamp' )->andReturn( time() );

		$expired = $frontendInstance->is_promoted_product_expired( 123 );
		$this->assertTrue( $expired );

		// no expiry.
		WP_Mock::userFunction( 'get_option' )->once()->with( 'wlc_promoted_product_expiry' )->andReturn( 0 );

		$expired = $frontendInstance->is_promoted_product_expired( 123 );
		$this->assertFalse( $expired );
	}
}
