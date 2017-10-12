<?php
/*
 * The class for the News Match Donation plugin's setting
 *
 * @package NewsMatchDonation\Settings
 */

/**
 * Register and populate a settings page for the News Match Donation Plugin:
 */
class NewsMatchDonation_Settings {
	/**
	 * The slug of the settings page
	 *
	 * @var string $settings_page The settings page slug
	 */
	private $settings_page = 'newsmatchdonation';

	/**
	 * The slug of the settings group
	 *
	 * @var string $settings_group The settings group slug
	 */
	private $settings_group = 'newsmatchdonation_group';

	/**
	 * The slug of the settings section
	 *
	 * @var string $settings_section The slug of the settings section
	 */
	private $settings_section = 'newsmatchdonation_section';

	/**
	 * The prefix used for this plugin's options saved in the options table
	 *
	 * @var string $options_prefix The prefix for this plugin's options saved in the options table
	 */
	public static $options_prefix = 'nmds_';

	/**
	 * Constructor for the settings class
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_submenu_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register the settings page
	 */
	public function register_submenu_page() {
		add_submenu_page(
			'plugins.php',
			esc_html__( 'News Match Donation Shortcode', 'newsmatch' ),
			esc_html__( 'News Match Shortcode', 'newsmatch' ),
			'manage_options', // permissions level is this because that seems right for site-wide config options.
			$this->settings_page,
			array( $this, 'settings_page_output' )
		);
	}

	/**
	 * The settings page output
	 *
	 * Should output a bunch of HTML
	 */
	public function settings_page_output() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'newsmatch' ) );
		}
		?>
		<div class="wrap newsmatch-admin">
			<h1><?php esc_html_e( 'News Match Donation Shortcode Options', 'newsmatch' ); ?></h1>
			<form method="post" action="options.php">
				<?php
					settings_fields( $this->settings_group );
					do_settings_sections( $this->settings_page );
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
			esc_html__( 'News Match Shortcode Settings', 'newsmatch' ),
			array( $this, 'settings_section_callback' ),
			$this->settings_page
		);

		register_setting( $this->settings_group, self::$options_prefix . 'org_name', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'org_name',
			__( 'Organization Name', 'newsmatch' ),
			array( $this, 'field_org_name' ),
			$this->settings_page,
			$this->settings_section
		);

		register_setting( $this->settings_group, self::$options_prefix . 'org_id', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'org_id',
			__( 'Organization ID', 'newsmatch' ),
			array( $this, 'field_org_id' ),
			$this->settings_page,
			$this->settings_section
		);

		register_setting( $this->settings_group, self::$options_prefix . 'url_live', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'url_live',
			__( 'Live donation form URL', 'newsmatch' ),
			array( $this, 'field_url_live' ),
			$this->settings_page,
			$this->settings_section
		);

		register_setting( $this->settings_group, self::$options_prefix . 'url_staging', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'url_staging',
			__( 'Testing donation form URL', 'newsmatch' ),
			array( $this, 'field_url_staging' ),
			$this->settings_page,
			$this->settings_section
		);

		register_setting( $this->settings_group, self::$options_prefix . 'url_toggle', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'url_toggle',
			__( 'Use the live or testing donation form?', 'newsmatch' ),
			array( $this, 'field_url_toggle' ),
			$this->settings_page,
			$this->settings_section
		);

		register_setting( $this->settings_group, self::$options_prefix . 'default_sf_id', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'default_sf_id',
			__( 'Default Salesforce campaign id', 'newsmatch' ),
			array( $this, 'field_default_sf_id' ),
			$this->settings_page,
			$this->settings_section
		);

		// Donor Levels Section
		add_settings_section(
			$this->donor_levels_section,
			esc_html__( 'Donor Levels Settings', 'newsmatch' ),
			array( $this, 'donor_levels_section_callback' ),
			$this->settings_page
		);

		// Generic Donor Article (a/an/the)
		register_setting( $this->settings_group, self::$options_prefix . 'gd_a', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'gd_a',
			__( 'Generic donor article', 'newsmatch' ),
			array( $this, 'field_gd_a' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Generic Donor Label (supporter/donor/sustainer/etc)
		register_setting( $this->settings_group, self::$options_prefix . 'gd_name', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'gd_name',
			__( 'Generic donor label', 'newsmatch' ),
			array( $this, 'field_gd_name' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 1 Article (a/an/the)
		register_setting( $this->settings_group, self::$options_prefix . 'l1_a', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l1_a',
			__( 'Level 1 article', 'newsmatch' ),
			array( $this, 'field_l1_a' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 1 Name (supporter/donor/sustainer/etc)
		register_setting( $this->settings_group, self::$options_prefix . 'l1_name', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l1_name',
			__( 'Level 1 name', 'newsmatch' ),
			array( $this, 'field_l1_name' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 1 Min (minimum dollar emount to qualify)
		register_setting( $this->settings_group, self::$options_prefix . 'l1_min', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l1_min',
			__( 'Level 1 min', 'newsmatch' ),
			array( $this, 'field_l1_min' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 1 Max (maximum dollar amount)
		register_setting( $this->settings_group, self::$options_prefix . 'l1_max', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l1_max',
			__( 'Level 1 max', 'newsmatch' ),
			array( $this, 'field_l1_max' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 2 Article (a/an/the)
		register_setting( $this->settings_group, self::$options_prefix . 'l2_a', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l2_a',
			__( 'Level 2 article', 'newsmatch' ),
			array( $this, 'field_l2_a' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 2 Name (supporter/donor/sustainer/etc)
		register_setting( $this->settings_group, self::$options_prefix . 'l2_name', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l2_name',
			__( 'Level 2 name', 'newsmatch' ),
			array( $this, 'field_l2_name' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 2 Min (minimum dollar emount to qualify)
		register_setting( $this->settings_group, self::$options_prefix . 'l2_min', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l2_min',
			__( 'Level 2 min', 'newsmatch' ),
			array( $this, 'field_l2_min' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 2 Max (maximum dollar amount)
		register_setting( $this->settings_group, self::$options_prefix . 'l2_max', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l2_max',
			__( 'Level 2 max', 'newsmatch' ),
			array( $this, 'field_l2_max' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 3 Article (a/an/the)
		register_setting( $this->settings_group, self::$options_prefix . 'l3_a', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l3_a',
			__( 'Level 3 article', 'newsmatch' ),
			array( $this, 'field_l3_a' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 3 Name (supporter/donor/sustainer/etc)
		register_setting( $this->settings_group, self::$options_prefix . 'l3_name', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l3_name',
			__( 'Level 3 name', 'newsmatch' ),
			array( $this, 'field_l3_name' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 3 Min (minimum dollar emount to qualify)
		register_setting( $this->settings_group, self::$options_prefix . 'l3_min', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l3_min',
			__( 'Level 3 min', 'newsmatch' ),
			array( $this, 'field_l3_min' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 3 Max (maximum dollar amount)
		register_setting( $this->settings_group, self::$options_prefix . 'l3_max', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l3_max',
			__( 'Level 3 max', 'newsmatch' ),
			array( $this, 'field_l3_max' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 4 Article (a/an/the)
		register_setting( $this->settings_group, self::$options_prefix . 'l4_a', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l4_a',
			__( 'Level 4 article', 'newsmatch' ),
			array( $this, 'field_l4_a' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 4 Name (supporter/donor/sustainer/etc)
		register_setting( $this->settings_group, self::$options_prefix . 'l4_name', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l4_name',
			__( 'Level 4 name', 'newsmatch' ),
			array( $this, 'field_l4_name' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 4 Min (minimum dollar emount to qualify)
		register_setting( $this->settings_group, self::$options_prefix . 'l4_min', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l4_min',
			__( 'Level 4 min', 'newsmatch' ),
			array( $this, 'field_l4_min' ),
			$this->settings_page,
			$this->donor_levels_section
		);

		// Level 4 Max (minimum dollar emount to qualify)
		register_setting( $this->settings_group, self::$options_prefix . 'l4_max', 'sanitize_text_field' );
		add_settings_field(
			self::$options_prefix . 'l4_max',
			__( 'Level 4 max', 'newsmatch' ),
			array( $this, 'field_l4_max' ),
			$this->settings_page,
			$this->donor_levels_section
		);
	}

	/**
	 * Settings section description
	 */
	public function settings_section_callback() {
		echo '';
	}

	/**
	 * Settings section description for donor levels
	 */
	public function donor_levels_section_callback() {
		echo esc_html__( 'In this section, set the names and donation amounts for the various donation levels. You can configure up to four donation levels, or use fewer.' , 'newsmatch' );
	}

	/**
	 * Output the input field for the organization's name
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_org_name( $args ) {
		$option = self::$options_prefix . 'org_name';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
	}

	/**
	 * Output the input field for the organization's org_id
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_org_id( $args ) {
		$option = self::$options_prefix . 'org_id';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
	}

	/**
	 * Output the input field for the live donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_url_live( $args ) {
		$option = self::$options_prefix . 'url_live';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
	}

	/**
	 * Output the input field for the staging/testing donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_url_staging( $args ) {
		$option = self::$options_prefix . 'url_staging';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
	}

	/**
	 * Ouput the radio button for toggling between 'live' and 'testing' urls
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_url_toggle( $args ) {
		$option = self::$options_prefix . 'url_toggle';
		$value = get_option( $option, 'staging' );
		if ( ! in_array( $value, array( 'staging', 'live' ), true ) ) {
			$value = 'staging';
		}

		echo sprintf(
			'<p><label>
				<input name="%1$s" id="%1$s-staging" type="radio" value="staging" %2$s>
				%4$s
			</label></p>
			<p><label>
				<input name="%1$s" id="%1$s-live" type="radio" value="live" %3$s>
				%5$s
			</label></p>',
			esc_attr( $option ),
			checked( $value, 'staging', false ), // Checked for testing.
			checked( $value, 'live', false ), // Checked for live.
			esc_html__( 'Staging', 'newsmatch' ),
			esc_html__( 'Live', 'newsmatch' )
		);
	}

	/**
	 * Render the settings input for the default SalesForce ID
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_default_sf_id( $args ) {
		$option = self::$options_prefix . 'default_sf_id';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
	}

	/**
	 * Output the input field for the generic donor article
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_gd_a( $args ) {
		$option = self::$options_prefix . 'gd_a';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'a, an, the: This is an article that refers to a donor.', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the generic donor label
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_gd_name( $args ) {
		$option = self::$options_prefix . 'gd_name';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'Supporter, Donor, Sustainer, etc.', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 1 article
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l1_a( $args ) {
		$option = self::$options_prefix . 'l1_a';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'a, an, the: This is an article that refers to a donor of this level.', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 1 name
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l1_name( $args ) {
		$option = self::$options_prefix . 'l1_name';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'i.e. Friend', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 1 min
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l1_min( $args ) {
		$option = self::$options_prefix . 'l1_min';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'minimum dollar amount to qualify for this level', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 1 max
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l1_max( $args ) {
		$option = self::$options_prefix . 'l1_max';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'maximum dollar amount for this level', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 2 article
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l2_a( $args ) {
		$option = self::$options_prefix . 'l2_a';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'a, an, the: This is an article that refers to a donor of this level.', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 2 name
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l2_name( $args ) {
		$option = self::$options_prefix . 'l2_name';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'i.e. Ally', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 2 min
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l2_min( $args ) {
		$option = self::$options_prefix . 'l2_min';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'minimum dollar amount to qualify for this level', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 2 max
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l2_max( $args ) {
		$option = self::$options_prefix . 'l2_max';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'maximum dollar amount for this level', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 3 article
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l3_a( $args ) {
		$option = self::$options_prefix . 'l3_a';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'a, an, the: This is an article that refers to a donor of this level.', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 3 name
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l3_name( $args ) {
		$option = self::$options_prefix . 'l3_name';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'i.e. Champion', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 3 min
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l3_min( $args ) {
		$option = self::$options_prefix . 'l3_min';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'minimum dollar amount to qualify for this level', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 3 max
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l3_max( $args ) {
		$option = self::$options_prefix . 'l3_max';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'maximum dollar amount for this level', 'newsmatch' ),
			esc_attr( $option )
		);
	}


	/**
	 * Output the input field for the level 4 article
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l4_a( $args ) {
		$option = self::$options_prefix . 'l4_a';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'a, an, the: This is an article that refers to a donor of this level.', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 4 name
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l4_name( $args ) {
		$option = self::$options_prefix . 'l4_name';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'i.e. Ambassador', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 4 min
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l4_min( $args ) {
		$option = self::$options_prefix . 'l4_min';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'minimum dollar amount to qualify for this level', 'newsmatch' ),
			esc_attr( $option )
		);
	}

	/**
	 * Output the input field for the level 4 max
	 *
	 * This is part of the donation form URL
	 *
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function field_l4_max( $args ) {
		$option = self::$options_prefix . 'l4_max';
		$value = get_option( $option, '' );
		echo sprintf(
			'<input name="%1$s" id="%1$s" type="text" value="%2$s">',
			esc_attr( $option ),
			esc_attr( $value )
		);
		echo sprintf(
			'<label for="%2$s"><i>%1$s</i></label>',
			esc_html__( 'minimum dollar amount to qualify for this level', 'newsmatch' ),
			esc_attr( $option )
		);
	}
}
