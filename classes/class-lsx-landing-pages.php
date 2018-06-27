<?php
/**
 * LSX Landing Pages Admin Class
 *
 * @package   uix_example
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2018 LightSpeed
 */

class LSX_Landing_pages {

	public $options;

	public function __construct() {
		if ( function_exists( 'tour_operator' ) ) {
			$this->options = get_option( '_lsx-to_settings', false );
		} else {
			$this->options = get_option( '_lsx_settings', false );

			if ( false === $this->options ) {
				$this->options = get_option( '_lsx_lsx-settings', false );
			}
		}
	}


}

global $lsx_landing_pages;
$lsx_landing_pages = new LSX_Landing_pages();