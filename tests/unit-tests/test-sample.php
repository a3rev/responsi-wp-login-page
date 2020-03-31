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

	function test_sample() {
		$output = 1;

		$this->assertEquals( 1 , $output );
	}

	function test_sample2() {
		$output = 2;

		$this->assertEquals( 2 , $output );
	}

}
