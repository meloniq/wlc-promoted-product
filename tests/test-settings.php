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

	public function test_add_products_general_settings() : void {
		$settingsInstance = new Settings();

		WP_Mock::userFunction('get_option')->once()->with('wlc_promoted_product_id')->andReturn(123);
		WP_Mock::userFunction('get_option')->once()->with('wlc_promoted_product_expiry')->andReturn(1);
		WP_Mock::userFunction('get_option')->once()->with('wlc_promoted_product_expiry_date')->andReturn(date('Y-m-d H:i', time() + 1000));
		WP_Mock::userFunction('current_time')->once()->with('timestamp')->andReturn(time());
		WP_Mock::userFunction('get_edit_post_link')->once()->with(123)->andReturn('http://example.com');
		WP_Mock::userFunction('get_the_title')->once()->with(123)->andReturn('Hello World');

		$settings = $settingsInstance->add_products_general_settings( array() );

		$this->assertIsArray( $settings );
		$this->assertCount( 6, $settings );

		$this->assertArrayHasKey( 'id', $settings[0] );
		$this->assertEquals( 'products_general_settings', $settings[0]['id'] );

		$this->assertArrayHasKey( 'id', $settings[1] );
		$this->assertEquals( 'wlc_promoted_product_title_prefix', $settings[1]['id'] );

		$this->assertArrayHasKey( 'id', $settings[2] );
		$this->assertEquals( 'wlc_promoted_product_bg_color', $settings[2]['id'] );

		$this->assertArrayHasKey( 'id', $settings[3] );
		$this->assertEquals( 'wlc_promoted_product_text_color', $settings[3]['id'] );

		$this->assertArrayHasKey( 'type', $settings[4] );
		$this->assertEquals( 'info', $settings[4]['type'] );

		$link = $settings[4]['text'];

		$this->assertIsString( $link );
		$this->assertEquals( '<a href="http://example.com">Hello World</a>', $link );

		$this->assertArrayHasKey( 'type', $settings[5] );
		$this->assertEquals( 'sectionend', $settings[5]['type'] );
	}

}
