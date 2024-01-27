<?php
use Wlc\PromotedProduct\Frontend;
use WP_Mock\Matcher\AnyInstance;
use WP_Mock\Tools\TestCase;

final class FrontendTestCase extends TestCase {

	public function test_hook_expectations() : void {
		$anyFrontend = new AnyInstance( Frontend::class );

		WP_Mock::expectActionAdded( 'woocommerce_before_main_content', array( $anyFrontend, 'display_promoted_product_info' ), 13 );

		new Frontend();

		$this->assertConditionsMet();
	}

}
