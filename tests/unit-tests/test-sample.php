<?php
/**
 * Class SampleTest
 *
 * @package Sample_Plugin
 */

/**
 * Sample test case.
 */
class a3Rev_Tests_Sample extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */

	function test_responsi_theme() {
		$output = 1;

		$this->assertTrue( defined( 'RESPONSI_FRAMEWORK_VERSION' ) );
	}

	function test_sample() {
		$output = 1;

		$this->assertEquals( 1 , $output );
	}

	function test_responsi_css() {

		global $responsi_wp_login_page;
		$output = (string)$responsi_wp_login_page->responsi_build_dynamic_css();
		$this->assertStringContainsString( 'html body.login form, html body.login form.loginform, html body.login #login form.loginform' , $output );

	}

}
