<?php
use Wlc\PromotedProduct\Settings;
use WP_Mock\Matcher\AnyInstance;
use WP_Mock\Tools\TestCase;

final class SettingsTestCase extends TestCase {

	public function test_hook_expectations() : void {
		$anySettings = new AnyInstance( Settings::class );

		WP_Mock::expectFilterAdded( 'woocommerce_products_general_settings', array( $anySettings, 'add_products_general_settings' ) );

		new Settings();

		$this->assertConditionsMet();
	}

}
