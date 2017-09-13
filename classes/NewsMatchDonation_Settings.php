<?php
/**
 * The class for the News Match Donation plugin's settings
 */

/**
 * Register and populate a settings page for the News Match Donation Plugin:
 *
 * - org name
 * - org_id
 * - live url for donation form
 * - staging url for donation form
 * - staging/live toggle
 * - default salesforce compaign ID
 * - ???
 */
class NewsMatchDonation_Settings {
	public $settings_page = 'newsmatchdonation';
	public $settings_group = 'newsmatchdonation_group';
	public $settings_section = 'newsmatchdonation_section';
	public $options_prefix = 'nmds_';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_submenu_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * register the settings page
	 */
	public function register_submenu_page() {
		add_submenu_page(
			'plugins.php',
			__( 'News Match Donation Shortcode', 'newsmatch' ),
			__( 'News Match Shortcode', 'newsmatch' ),
			'manage_options', //permissions level is this because that seems right for site-wide config options
			$this->settings_page,
			array( $this, 'settings_page_output' )
		);
	}

	/**
	 * The settings page output
	 *
	 * should output a bunch of HTML
	 */
	public function settings_page_output() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'scaip' ) );
		}
		?>
		<div class="wrap newsmatch-admin">
			<h1><?php esc_html_e( 'News Match Donation Shortcode Options', 'newsmatch' ); ?></h1>
			<form method="post" action="options.php">
				<?php
					settings_fields( $this->settings_group );
					do_settings_sections( $this->settings_section );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * All the settings
	 */
	public function register_settings() {
		add_settings_section(
			$this->settings_section,
			__( 'News Match Shortcode Settings', 'newsmatch' ),
			array( $this, 'settings_section_callback' ),
			$this->settings_page
		);

		register_setting( $this->settings_group, $this->options_prefix . 'field_org_name', 'sanitize_text_field' );
		add_settings_field(
			$this->options_prefix . 'org_name',
			__('Organization Name', 'newsmatch'),
			array( $this, 'field_org_name' ),
			$this->settings_page,
			$this->settings_section
		);

		register_setting( $this->settings_group, $this->options_prefix . 'field_org_id', 'sanitize_text_field' );
		add_settings_field(
			$this->options_prefix . 'org_id',
			__('Organization ID', 'newsmatch'),
			array( $this, 'field_org_id' ),
			$this->settings_page,
			$this->settings_section
		);

		register_setting( $this->settings_group, $this->options_prefix . 'url_live', 'sanitize_text_field' );
		add_settings_field(
			$this->options_prefix . 'url_live',
			__('Live donation form URL', 'newsmatch'),
			array( $this, 'field_url_live' ),
			$this->settings_page,
			$this->settings_section
		);

		register_setting( $this->settings_group, $this->options_prefix . 'url_staging', 'sanitize_text_field' );
		add_settings_field(
			$this->options_prefix . 'url_staging',
			__('Testing donation form URL', 'newsmatch'),
			array( $this, 'field_url_staging' ),
			$this->settings_page,
			$this->settings_section
		);

		register_setting( $this->settings_group, $this->options_prefix . 'url_toggle', 'sanitize_text_field' );
		add_settings_field(
			$this->options_prefix . 'url_toggle',
			__('Use the live or testing donation form?', 'newsmatch'),
			array( $this, 'field_url_toggle' ),
			$this->settings_page,
			$this->settings_section
		);

		register_setting( $this->settings_group, $this->options_prefix . 'default_sf_id', 'sanitize_text_field' );
		add_settings_field(
			$this->options_prefix . 'default_sf_id',
			__('Default Salesforce campaign id', 'newsmatch'),
			array( $this, 'field_default_sf_id' ),
			$this->settings_page,
			$this->settings_section
		);
	}

	/**
	 * Settings section description
	 */
	public function settings_section_callback() {
		echo 'aaa';
	}

	/**
	 * output the input field for the organization's name
	 */
	public function field_org_name( $args ) {
		$option = $this->options_prefix . 'org_name';
		$org_name = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			$org_name,
			$option
		);
	}

	/**
	 * output the input field for the organization's org_id
	 *
	 * this is part of the donation form URL
	 */
	public function field_org_id( $args ) {
		$option = $this->options_prefix . 'org_id';
		$org_name = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			$org_name,
			$option
		);
	}

	/**
	 * output the input field for the live donation form URL
	 */
	public function field_url_live( $args ) {
		$option = $this->options_prefix . 'url_live';
		$org_name = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			$org_name,
			$option
		);
	}

	/**
	 * output the input field for the staging/testing donation form URL
	 */
	public function field_url_staging( $args ) {
		$option = $this->options_prefix . 'url_staging';
		$org_name = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			$org_name,
			$option
		);
	}

	/**
	 * ouput the radio button for toggling between 'live' and 'testing' urls
	 */
	public function field_url_toggle( $args ) {
		$option = $this->options_prefix . 'url_toggle';
		$org_name = get_option( $option, 'staging' );
		echo sprintf(
			'<input name="%1$s" id="%1$s-staging" type="radio" value="staging" %2$s><label for="%1$s-staging">%4$s</label>
			<input name="%1$s" id="%1$s-live" type="radio" value="live" %3$s><label for="%1$s-live">%5$s</label>',
			$org_name,
			checked( $option, 'staging' ), // checked for testing
			checked( $option, 'live' ), // checked for live
			__( 'Staging', 'newsmatch' ),
			__( 'Live', 'newsmatch' )
		);
	}

	/**
	 *
	 */
	public function field_default_sf_id( $args ) {
		$option = $this->options_prefix . 'org_name';
		$org_name = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			$org_name,
			$option
		);
	}
}
