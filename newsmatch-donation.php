<?php
/*
Plugin Name: News Match Donation Shortcode
Plugin URI: http://fairwaytech.com
Description:  Provides methods to integrate with the donation application hosted at checkout.voiceofsandiego.org
Version: 0.1
Author:  inn_nerds, Fairway Technologies
Author URI: http://fairwaytech.com
*/
class NewsMatchDonation {
	public function __construct() {
		require_once( __DIR__ . '/classes/NewsMatchDonation_Shortcode.php' );
		new NewsMatchDonation_Shortcode();
	}
}

new NewsMatchDonation();
