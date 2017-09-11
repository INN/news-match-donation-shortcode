<?php
/*
Plugin Name: News Match Donation Shortcode
Plugin URI: http://fairwaytech.com
Description:  Provides methods to integrate with the donation application hosted at checkout.voiceofsandiego.org
Version: 0.1
Author:  inn_nerds, Fairway Technologies
Author URI: http://fairwaytech.com
*/

// Plugin directory normalization
define( 'NMD_PLUGIN_FILE', __FILE__ );

/**
 * Set up the plugin
 */
class NewsMatchDonation {
	public function __construct() {
		require_once( __DIR__ . '/classes/NewsMatchDonation_Shortcode.php' );
		new NewsMatchDonation_Shortcode();
		require_once( __DIR__ . '/classes/NewsMatchDonation_Settings.php' );
		new NewsMatchDonation_Settings();
	}
}

new NewsMatchDonation();
