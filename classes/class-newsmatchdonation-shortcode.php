<?php
/**
 * The class for the News Match Donation Shortcode, and associated functions
 *
 * @package NewsMatchDonation\Shortcode
 */

/**
 * Register a shortcode
 */
class NewsMatchDonation_Shortcode {
	/**
	 * The constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

		add_shortcode( 'newsmatch_donation_form', array( $this, 'donation_form_shortcode' ) );
	}

	/**
	 * Define the donation form shortcode.
	 * <p>
	 *     This shortcode is intended for use somewhere on a page.
	 * </p>
	 * <p>
	 *     Example usage:
	 *     Add donation form with no Salesforce campaign id and no default donation amount specified:
	 *     [newsmatch_donation_form]
	 *
	 *      Add donation form with no Salesforce campaign id and $50.00 as the default donation amount:
	 *      [newsmatch_donation_form amount="50"]
	 *
	 *      Add a donation form with a Salesforce campaign id of "foo" and $25.00 as the default donation amount:
	 *      [newsmatch_donation_form sf_campaign_id="foo" amount="25"]
	 *
	 *      Add a donation form with a Salesforce campaign id of "foo" and do not specify a default donation amount:
	 *      [newsmatch_donation_form sf_campaign_id="foo"]
	 * </p>
	 *
	 * @param  array $atts The attribute values passed in through the shortcode.
	 * @return string The HTML markup for the donation form.
	 */
	public function donation_form_shortcode( $atts ) {
		if ( isset( $atts['type'] ) && 'select' === $atts['type'] ) {
			return $this->render_view( '/views/rr-donation-form-select.view.php', $atts );
		} else {
			return $this->render_view( '/views/rr-donation-form-buttons.view.php', $atts );
		}
	}

	/**
	 * Register the donation plugin's shortcode's CSS and Javascript files.
	 */
	public function register_assets() {
		wp_register_style(
			'newsmatch-donation',
			plugins_url( 'assets/css/donation.css', NMD_PLUGIN_FILE )
		);

		wp_enqueue_style( 'newsmatch-donation' );

		wp_register_script(
			'newsmatch-donation',
			plugins_url( 'assets/js/donation.js', NMD_PLUGIN_FILE ),
			array( 'jquery' ),
			null,
			true
		);

		wp_enqueue_script( 'newsmatch-donation' );
	}

	/**
	 * Get the view for the specified file path.
	 *
	 * @param  string $view_path The path to the desired view file.
	 * @param  array  $atts The attributes passed in via the shortcode.
	 * @return string the HTML for the specified view.
	 */
	private function render_view( $view_path, $atts ) {
		$path_to_view = dirname( NMD_PLUGIN_FILE ) . $view_path;
		$view_data = shortcode_atts(
			array(
				'url' => 'https://checkout.fundjournalism.org',
				'sf_campaign_id' => '',
				'amount' => '15',
				'level' => 'individual',
			),
		$atts);

		// Make sure that this is a valid value.
		if ( ! in_array( $view_data['level'], array( 'individual', 'nonprofit', 'business' ), true ) ) {
			$view_data['level'] = 'individual';
		}

		return $this->render( $path_to_view, $view_data );
	}

	/**
	 * Build the HTML to display the output of the shortcode.
	 *
	 * @param string $file_path The path to the template containing the HTML to display the donation shortcode.
	 * @param array  $data      The data necessary to populate the template.
	 */
	private function render( $file_path, $data = null ) {
		( $data ) ? extract( $data ) : null;

		ob_start();

		include( $file_path );
		$template = ob_get_contents();

		ob_end_clean();

		return $template;
	}
}