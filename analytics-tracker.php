<?php
/*
 * Plugin Name: Analytics Tracker
 * Plugin URI: https://stylishwp.com/product/wordpress-plugins/google-analytics-tracker/
 * Description: Analytics Tracker makes it super easy to add Google Analytics tracking code on your site
 * Text Domain: analytics-tracker
 * Domain Path: /languages
 * Version: 1.1.1
 * Author: Valeriu Tihai
 * Author URI: https://valeriu.tihai.ca
 * Contributors: valeriutihai
 * Donate link: https://paypal.me/valeriu/5
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Main Class - Analytics Tracker
 *
 * @since 1.0.0
 */
class AnalyticsTracker {


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Load language file
		add_action( 'plugins_loaded', array( &$this, 'analyticstracker_load_textdomain' ), 2 );

		// Add Support Link
		add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), array( &$this, 'analyticstracker_links' ), 1 );

		// Add GA code
		add_action('wp_head', array( &$this, 'analyticstracker_ga_script' ), 3 );

		//Add AMP analytics JavaScript
		add_action( 'amp_post_template_head', array(&$this, 'analyticstracker_amp_analytics_scripts' ), 1 );

		//Add AMP analytics tracking code
		add_action( 'amp_post_template_footer', array(&$this, 'analyticstracker_amp_analytics_code' ), 1 );

		// Add Admin menu
		add_action( 'admin_menu', array( $this, 'analyticstracker_admin_menu' ), 4 );

		// Load settings
		add_action( 'plugins_loaded', array( &$this, 'analyticstracker_settings' ), 1 );

		// Setting Initialization
		add_action( 'admin_init', array( &$this, 'analyticstracker_settings_init' ), 1 );

		// Load external JavaScript
		add_action( 'wp_enqueue_scripts', array( &$this, 'analyticstracker_load_js' ), 1 );

		// Load external JavaScript
		add_action( 'admin_enqueue_scripts', array( &$this, 'analyticstracker_enqueue_admin' ), 1 );

		//Add Comment meta
		add_action('wp_insert_comment', array(&$this, 'analyticstracker_ga_comment_meta'), 99, 2);

	}


	/**
	 * Insert Google Analytics code
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_ga_script() {
		$saved_options = get_option( 'analyticstracker_settings' );
		if(preg_match("/UA-[0-9]{3,9}-[0-9]{1,4}/", $saved_options['analyticstracker_ga']) ) { ?>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  <?php $this->analyticstracker_ga_get(); ?>
		  <?php $this->analyticstracker_ga_displayfeatures_get(); ?>
		  <?php $this->analyticstracker_ga_events_get(); ?>
		  <?php $this->analyticstracker_ga_enhancedlinkatt_get(); ?>
		  <?php $this->analyticstracker_ga_forcessl_get(); ?>
		  <?php $this->analyticstracker_ga_userid_get(); ?>
		  <?php $this->analyticstracker_ga_anonymizeip_get(); ?>
		  <?php $this->analyticstracker_ga_custom_dimension_get(); ?>
		  ga('send', 'pageview');

		</script>
	<?php }
	}


	/**
	 * Adding amp-analytics script to head
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function analyticstracker_amp_analytics_scripts(){ ?>
		<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
	<?php
	}


	/**
	 * Adding amp-analytics tracking code
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function analyticstracker_amp_analytics_code() {
		$saved_options = get_option( 'analyticstracker_settings' );
		if(preg_match("/UA-[0-9]{3,9}-[0-9]{1,4}/", $saved_options['analyticstracker_ga']) ) { ?>
			<amp-analytics type="googleanalytics" id="googleanalytics1">
				<script type="application/json">
					{
						"vars": {
							"account": "<?php echo $saved_options['analyticstracker_ga']; ?>"
						},
						"triggers": {
							"trackPageview": {
								"on": "visible",
								"request": "pageview"
							}
						}
					}
				</script>
			</amp-analytics>
		<?php
		}
	}


	/**
	 * Load language file
	 *
	 * This will load the MO file for the current locale.
	 * The translation file must be named analytics-tracker-$locale.mo.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_load_textdomain() {
		load_plugin_textdomain( 'analytics-tracker', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
	}


	/**
	 * Add settings links to plugin page
	 *
	 * @since 1.0.0
	 * @access  public
	 */
	public function analyticstracker_links( $links ) {
		$links[] = '<a href="https://wordpress.org/support/plugin/analytics-tracker" target="_blank">'.__("Support", 'analytics-tracker').'</a>';
		return $links;
	}


	/**
	 * Add plugin menu
	 *
	 * @since 1.0.0
	 * @access  public
	 */
	public function analyticstracker_admin_menu() {
		add_menu_page( __('Google Analytics', 'analytics-tracker'), __('Google Analytics', 'analytics-tracker'), 'manage_options', 'analyticstracker-menu', array( $this, 'analyticstracker_options_page'), 'dashicons-chart-line' );
		add_submenu_page( 'analyticstracker-menu', __('Other Products', 'analytics-tracker'), __('Other Products', 'analytics-tracker'), 'manage_options', 'analyticstracker-other-plugins', array( $this, 'analyticstracker_other_plugins' ) );
	}


	/**
	 * My others plugins
	 *
	 * @since 1.0.0
	 * @access  public
	 */
	public function  analyticstracker_other_plugins() { ?>
		 <?php add_thickbox(); ?>
		<div class="wrap">
			<h2><?php _e( 'My Recommendations', 'analytics-tracker' ); ?></h2>
			<div class='card pressthis'>
				<h2><?php _e( 'WordPress Plugins', 'analytics-tracker' ); ?></h2>
				<ul>
					<li>
						<a href="<?php echo network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=wplook-twitter-follow-button-new&TB_iframe=true&width=762&height=600'); ?>" class="thickbox"><?php _e('Twitter Follow Button', 'analytics-tracker'); ?></a> - <?php _e('Add the Twitter Follow Button to your blog to increase engagement and create a lasting connection with your audience.', 'analytics-tracker'); ?>
					</li>
					<li>
						<a href="<?php echo network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=auto-update&TB_iframe=true&width=762&height=600'); ?>" class="thickbox"><?php _e('Auto Update', 'analytics-tracker'); ?></a> - <?php _e('This plugin enable Auto Update for WordPress core, Themes and Plugins.', 'analytics-tracker'); ?>
					</li>
					<li>
						<a href="https://wplook.com/product/plugins/comingsoon-maintenance-mode-wordpress-plugin/?ref=104&campaign=AnalyticsTracker" target="_blank"><?php _e('ComingSoon', 'analytics-tracker'); ?></a> - <?php _e('ComingSoon is a Premium Maintenance Mode Plugin designed specifically for Editors, Designers or Developers who want to let visitors know the blog is down for Maintenance or Under Construction.', 'analytics-tracker') ?>
					</li>
				</ul>
			</div>
			<div class='card pressthis'>
				<h2><?php _e( 'WordPress Themes', 'analytics-tracker' ); ?></h2>
				<ul>
					<li>
						<a href="<?php echo network_admin_url( 'theme-install.php?theme=blogolife'); ?>"><?php _e('BlogoLife', 'analytics-tracker'); ?></a> - <?php _e('BlogoLife is a simple and perfect WordPress theme for personal blogging that supports post formats, and several customization options.', 'analytics-tracker'); ?>
					</li>
					<li>
						<a href="<?php echo network_admin_url( 'theme-install.php?theme=dailypost'); ?>"><?php _e('DailyPost', 'analytics-tracker'); ?></a> - <?php _e('DailyPost is intresting theme ideal for your everyday notes and thoughts, which supports post formats and several customisation options. The theme is a special one because of it\'s responsive design, thus you will get the pleasure to read the post with your mobile device.', 'analytics-tracker'); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( 'http://themeforest.net/item/campevent-conference-event-wordpress-theme/11439450?ref=stylishwp' ); ?>" target="_blank"><?php _e('CampEvent', 'analytics-tracker'); ?></a> - <?php _e('CampEvent is a WordPress theme that allow to easily configure event, exhibitions, conventions, trade shows, seminars, workshops, meetup by adding detailed information about speakers, sponsors, schedule and much more.', 'analytics-tracker'); ?>
					</li>
				</ul>
			</div>
			<div class='card pressthis'>
				<h2><a target="_blank" title="<?php _e('Daily Beautiful WordPress Templates for your business', 'analytics-tracker'); ?>" href="https://dailydesigncafe.com/?utm_source=DailyDesign&utm_medium=Recommendations&utm_campaign=AnalyticsTracker"><?php _e( 'Daily Design Cafe', 'analytics-tracker' ); ?> ☕</a></h2>
				<div class="dailydesign_container"></div>
			</div>
		</div>
	<?php
	}


	/**
	 * Settings
	 *
	 * @return Array with all settings
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_settings () {
		return array(
			array(
				'settings_type' => 'section',
				'id' => 'analyticstracker_section_settings_general',
				'title' => 'General Settings',
				'callback' => 'analyticstracker_description_section_callback',
				'page' => 'analyticstracker_page'
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_ga',
				'title' => __( 'Google Analytics tracking ID', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_general',
				'args' => 	array (
								'id' => 'analyticstracker_ga',
								'type' => 'text',
								'class' => '',
								'name' => 'analyticstracker_ga',
								'value' => 'analyticstracker_ga',
								'label_for' => '',
								'description' => __( 'Add Google Analytics tracking ID (UA-XXXXXXX-YY). Where can I find <a href="https://support.google.com/analytics/answer/1032385?rd=1" target="_blank">my tracking ID?</a>', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_forcessl',
				'title' => __( 'Force SSL', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_general',
				'args' => 	array (
									'id' => 'analyticstracker_forcessl',
									'type' => 'checkbox',
									'class' => '',
									'name' => 'analyticstracker_forcessl',
									'value' => 1,
									'label_for' => '',
									'description' => __( 'Setting forceSSL to true will force http pages to also send all beacons using https.', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_userid',
				'title' => __( 'User ID', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_general',
				'args' => 	array (
									'id' => 'analyticstracker_userid',
									'type' => 'checkbox',
									'class' => '',
									'name' => 'analyticstracker_userid',
									'value' => 1,
									'label_for' => '',
									'description' => __( 'This is intended to be a known identifier for a user provided by the site owner/tracking library user.', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_anonymizeip',
				'title' => __( 'Anonymize IP', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_general',
				'args' => 	array (
									'id' => 'analyticstracker_anonymizeip',
									'type' => 'checkbox',
									'class' => '',
									'name' => 'analyticstracker_anonymizeip',
									'value' => 1,
									'label_for' => '',
									'description' => __( 'The IP address of the sender will be anonymized', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_displayfeatures',
				'title' => __( 'Display Features', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_general',
				'args' => 	array (
									'id' => 'analyticstracker_displayfeatures',
									'type' => 'checkbox',
									'class' => '',
									'name' => 'analyticstracker_displayfeatures',
									'value' => 1,
									'label_for' => '',
									'description' => __( 'The plugin works by sending an additional request to stats.g.doubleclick.net that is used to provide advertising features like remarketing and demographics and interest reporting in Google Analytics.', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_enhancedlinkatt',
				'title' => __( 'Enhanced Link Attribution', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_general',
				'args' => 	array (
									'id' => 'analyticstracker_enhancedlinkatt',
									'type' => 'checkbox',
									'class' => '',
									'name' => 'analyticstracker_enhancedlinkatt',
									'value' => 1,
									'label_for' => '',
									'description' => __( 'Enhanced Link Attribution improves the accuracy of your In-Page Analytics report by automatically differentiating between multiple links to the same URL on a single page by using link element IDs.', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_events',
				'title' => __( 'Event Tracking', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_general',
				'args' => 	array (
									'id' => 'analyticstracker_events',
									'type' => 'checkbox',
									'class' => '',
									'name' => 'analyticstracker_events',
									'value' => 1,
									'label_for' => '',
									'description' => __( 'Track events feature: Downloads, Emails, Phone numbers Error 404, Search and Outbound links, Add a comment, Scroll Depth', 'analytics-tracker' ),
				)
			),

			//Custom Dimension
			array(
				'settings_type' => 'section',
				'id' => 'analyticstracker_section_settings_custom_dimension',
				'title' => 'Custom Dimension',
				'callback' => 'analyticstracker_description_section_cd_callback',
				'page' => 'analyticstracker_page'
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_custom_dimension',
				'title' => __( 'Custom Dimension', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_custom_dimension',
				'args' => 	array (
									'id' => 'analyticstracker_custom_dimension',
									'type' => 'checkbox',
									'class' => '',
									'name' => 'analyticstracker_custom_dimension',
									'value' => 1,
									'label_for' => '',
									'description' => __( 'Enable Custom Dimension', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_cu_tags',
				'title' => __( 'Tags', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_custom_dimension',
				'args' => 	array (
								'id' => 'analyticstracker_cu_tags',
								'type' => 'text',
								'class' => 'small-text',
								'name' => 'analyticstracker_cu_tags',
								'value' => '',
								'default' => '1',
								'label_for' => 'analyticstracker_cu_tags',
								'description' => __( 'The index suffix for tags', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_cu_category',
				'title' => __( 'Category', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_custom_dimension',
				'args' => 	array (
								'id' => 'analyticstracker_cu_category',
								'type' => 'text',
								'class' => 'small-text',
								'name' => 'analyticstracker_cu_category',
								'value' => '',
								'default' => '2',
								'label_for' => 'analyticstracker_cu_category',
								'description' => __( 'The index suffix for category', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_cu_archive',
				'title' => __( 'Archive', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_custom_dimension',
				'args' => 	array (
								'id' => 'analyticstracker_cu_archive',
								'type' => 'text',
								'class' => 'small-text',
								'name' => 'analyticstracker_cu_archive',
								'value' => '',
								'default' => '3',
								'label_for' => 'analyticstracker_cu_archive',
								'description' => __( 'The index suffix for archive', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_cu_author',
				'title' => __( 'Author', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_custom_dimension',
				'args' => 	array (
								'id' => 'analyticstracker_cu_author',
								'type' => 'text',
								'class' => 'small-text',
								'name' => 'analyticstracker_cu_author',
								'value' => '',
								'default' => '4',
								'label_for' => 'analyticstracker_cu_author',
								'description' => __( 'The index suffix for author', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_cu_post_format',
				'title' => __( 'Post Format', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_custom_dimension',
				'args' => 	array (
								'id' => 'analyticstracker_cu_post_format',
								'type' => 'text',
								'class' => 'small-text',
								'name' => 'analyticstracker_cu_post_format',
								'value' => '',
								'default' => '5',
								'label_for' => 'analyticstracker_cu_post_format',
								'description' => __( 'The index suffix for Post Format', 'analytics-tracker' ),
				)
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_cu_post_type',
				'title' => __( 'Post Type', 'analytics-tracker' ),
					'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_settings_custom_dimension',
				'args' => 	array (
								'id' => 'analyticstracker_cu_post_type',
								'type' => 'text',
								'class' => 'small-text',
								'name' => 'analyticstracker_cu_post_type',
								'value' => '',
								'default' => '6',
								'label_for' => 'analyticstracker_cu_post_type',
								'description' => __( 'The index suffix for Post Type', 'analytics-tracker' ),
				)
			),
		);
	}


	/**
	 * Get Goolge Analytics code
	 *
	 * @since 1.0.2
	 * @access public
	 */
	public function analyticstracker_ga_get () {
		$saved_options = get_option( 'analyticstracker_settings' );
		if ( preg_match("/UA-[0-9]{3,9}-[0-9]{1,4}/", $saved_options['analyticstracker_ga']) ) {
			echo "ga('create', '".$saved_options['analyticstracker_ga']."', 'auto');\r\n";
		}
	}


	/**
	 * Get Force SSL
	 *
	 * @since 1.0.2
	 * @access public
	 */
	public function analyticstracker_ga_forcessl_get () {
		$saved_options = get_option( 'analyticstracker_settings' );
		if ( isset($saved_options['analyticstracker_forcessl']) && $saved_options['analyticstracker_forcessl'] != '' ) {
			echo "ga('set', 'forceSSL', true);\r\n";
		}
	}


	/**
	 * Get User ID
	 *
	 * @since 1.0.2
	 * @access public
	 */
	public function analyticstracker_ga_userid_get () {
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$saved_options = get_option( 'analyticstracker_settings' );
			if ( isset($saved_options['analyticstracker_userid']) && $saved_options['analyticstracker_userid'] != '' ) {
				echo "ga('set', 'userId', '".$current_user->ID."');\r\n";
			}
		}
	}

	/**
	 * Get Anonymize IP
	 *
	 * @since 1.0.2
	 * @access public
	 */
	public function analyticstracker_ga_anonymizeip_get () {
		$saved_options = get_option( 'analyticstracker_settings' );
		if ( isset($saved_options['analyticstracker_anonymizeip']) && $saved_options['analyticstracker_anonymizeip'] != '' ) {
			echo "ga('set', 'anonymizeIp', true);\r\n";
		}
	}


	/**
	 * Get Display Features
	 *
	 * @since 1.0.2
	 * @access public
	 */
	public function analyticstracker_ga_displayfeatures_get () {
		$saved_options = get_option( 'analyticstracker_settings' );
		if ( isset($saved_options['analyticstracker_displayfeatures']) && $saved_options['analyticstracker_displayfeatures'] != '' ) {
			echo "ga('require', 'displayfeatures');\r\n";
		}
	}


	/**
	 * Get Enhanced Link Attribution
	 *
	 * @since 1.0.3
	 * @access public
	 */
	public function analyticstracker_ga_enhancedlinkatt_get () {
		$saved_options = get_option( 'analyticstracker_settings' );
		if ( isset($saved_options['analyticstracker_enhancedlinkatt']) && $saved_options['analyticstracker_enhancedlinkatt'] != '' ) {
			echo "ga('require', 'linkid');\r\n";
		}
	}


	/**
	 * Add JavaScript file
	 *
	 * @since 1.0.3
	 * @access  public
	 */
	public function analyticstracker_load_js() {
		$saved_options = get_option( 'analyticstracker_settings' );
		if ( isset($saved_options['analyticstracker_events']) && $saved_options['analyticstracker_events'] != '' ) {
			wp_enqueue_script( 'analyticstracker-js', plugins_url( '/javascripts/analyticstracker.js' , __FILE__ ), array( 'jquery', 'analyticstracker-jquery-scrolldepth' ) );
			wp_enqueue_script( 'analyticstracker-jquery-scrolldepth', plugins_url( '/javascripts/vendors/jquery-scrolldepth/jquery.scrolldepth.min.js' , __FILE__ ), array( 'jquery' ) );
		}
	}

	/**
	 * Add JavaScript and CSS files to wp-admin
	 *
	 * @since 1.0.4
	 * @access  public
	 */
	public function analyticstracker_enqueue_admin($hook) {
		if ( 'google-analytics_page_analyticstracker-other-plugins' == $hook ) {
			wp_enqueue_script( 'analyticstracker-js-admin', plugins_url( '/javascripts/analyticstracker-admin.js' , __FILE__ ), array( 'jquery' ) );

			wp_register_style( 'analyticstracker-css-admin', plugins_url( '/analytics-tracker/css/analyticstracker-admin.css', false, '1.0.0' ) );
			wp_enqueue_style( 'analyticstracker-css-admin' );
		}
	}


	/**
	 * Get Search and Add a comment Events
	 *
	 * @since 1.0.3
	 * @access  public
	 */
	public function analyticstracker_ga_events_get() {
		$saved_options = get_option( 'analyticstracker_settings' );
		if ( isset($saved_options['analyticstracker_events']) && $saved_options['analyticstracker_events'] != '' ) {
			if (  is_search() ) {
				echo "ga('send', 'event', 'Search', '".get_search_query( true )."');\r\n";
			}
		}
		//Add a comment Event
		if ( is_singular() ) {
			global $post;
			$args = array(
				'meta_key' => 'analyticstracker_comment_event',
				'post_id' => $post->ID,
			);
			$at_comments = get_comments($args);
			foreach($at_comments as $at_comment){
				echo "ga('send', 'event', 'Comments', '".$at_comment->comment_author."', 'Post ID: ".$at_comment->comment_post_ID."', '".$at_comment->comment_ID."');\r\n";
				delete_comment_meta( $at_comment->comment_ID, 'analyticstracker_comment_event');
			}
		}
	}


	/**
	 * Add comment meta for analytics tracker event
	 *
	 * @since 1.0.4
	 * @access  public
	 */
	public function analyticstracker_ga_comment_meta ( $comment_id, $comment_object ) {
		if ($comment_object->comment_approved == 1) {
			add_comment_meta( $comment_id, 'analyticstracker_comment_event', 1, true );
		}
	}


	/**
	 * Get Custom Dimension
	 *
	 * @since 1.0.2
	 * @access public
	 */
	public function analyticstracker_ga_custom_dimension_get () {
		$saved_options = get_option( 'analyticstracker_settings' );
		if ( is_singular() ) {
			global $post;
			if ( isset($saved_options['analyticstracker_custom_dimension']) && $saved_options['analyticstracker_custom_dimension'] != '' ) {
				//Tags
				if (isset($saved_options['analyticstracker_cu_tags']) && $saved_options['analyticstracker_cu_tags'] != '' ) {
					if ( (int) $saved_options['analyticstracker_cu_tags'] AND  ( $saved_options['analyticstracker_cu_tags'] > 0 && $saved_options['analyticstracker_cu_tags'] < 201 ) ) {
						$at_post_tags = get_the_tags();

						if ($at_post_tags) {
							foreach($at_post_tags as $tag) {
								$at_post_tags_array[] = $tag->name;
							}
							$at_post_tags_cu = implode( '|', $at_post_tags_array );
						} else {
							$at_post_tags_cu = __('No Tags', 'analytics-tracker');
						}
						echo "ga('set', 'dimension".$saved_options['analyticstracker_cu_tags']."', '".$at_post_tags_cu."');\r\n";
					}
				}

				//Category
				if (isset($saved_options['analyticstracker_cu_category']) && $saved_options['analyticstracker_cu_category'] != '' ) {
					if ( (int) $saved_options['analyticstracker_cu_category'] AND  ( $saved_options['analyticstracker_cu_category'] > 0 && $saved_options['analyticstracker_cu_category'] < 201 ) ) {
						$at_post_categories = get_the_category();
						if ($at_post_categories) {
							foreach($at_post_categories as $category) {
								$at_post_categories_array[] = $category->name;
							}
							$at_post_categories_cu = implode( '|', $at_post_categories_array );
							echo "ga('set', 'dimension".$saved_options['analyticstracker_cu_category']."', '".$at_post_categories_cu."');\r\n";
						}else {
							$at_post_tags_cu = __('No Category', 'analytics-tracker');
						}
					}
				}

				//Archive
				if (isset($saved_options['analyticstracker_cu_archive']) && $saved_options['analyticstracker_cu_archive'] != '' ) {
					if ( (int) $saved_options['analyticstracker_cu_archive'] AND  ( $saved_options['analyticstracker_cu_archive'] > 0 && $saved_options['analyticstracker_cu_archive'] < 201 ) ) {
						echo "ga('set', 'dimension".$saved_options['analyticstracker_cu_archive']."', '".get_the_date('Y|m|N|A')."');\r\n";
					}
				}

				//Author
				if (isset($saved_options['analyticstracker_cu_author']) && $saved_options['analyticstracker_cu_author'] != '' ) {
					if ( (int) $saved_options['analyticstracker_cu_author'] AND  ( $saved_options['analyticstracker_cu_author'] > 0 && $saved_options['analyticstracker_cu_author'] < 201 ) ) {
						echo "ga('set', 'dimension".$saved_options['analyticstracker_cu_author']."', '".get_the_author_meta( 'display_name', $post->post_author )."');\r\n";
					}
				}

				//Post Format
				if (isset($saved_options['analyticstracker_cu_post_format']) && $saved_options['analyticstracker_cu_post_format'] != '' ) {
					if ( (int) $saved_options['analyticstracker_cu_post_format'] AND  ( $saved_options['analyticstracker_cu_post_format'] > 0 && $saved_options['analyticstracker_cu_post_format'] < 201 ) ) {
						$postformat = get_post_format() ? : 'standard';
						echo "ga('set', 'dimension".$saved_options['analyticstracker_cu_post_format']."', '".$postformat."');\r\n";
					}
				}

				//Post Type
				if (isset($saved_options['analyticstracker_cu_post_type']) && $saved_options['analyticstracker_cu_post_type'] != '' ) {
					if ( (int) $saved_options['analyticstracker_cu_post_type'] AND  ( $saved_options['analyticstracker_cu_post_type'] > 0 && $saved_options['analyticstracker_cu_post_type'] < 201 ) ) {
						echo "ga('set', 'dimension".$saved_options['analyticstracker_cu_post_type']."', '".$post->post_type."');\r\n";
					}
				}
			}
		}
	}


	/**
	 * Description Section callback
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_description_section_callback( ) {	}

	/**
	 * Description Section callback for Custom Dimension
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_description_section_cd_callback( ) {
		_e('Each custom dimension has an associated index. There is a maximum of 20 custom dimensions (200 for Premium accounts). The index suffix must be a positive integer greater than 0. How to <a href="https://support.google.com/analytics/answer/2709829?hl=en#set_up_custom_dimensions" target="_blank">set up</a> custom dimensions', 'analytics-tracker' );
	}

	/*
	 * Setting Initialization
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_settings_init() {
		register_setting( 'analyticstracker_page', 'analyticstracker_settings' );
		foreach ( $this->analyticstracker_settings() AS $setting ) {
			if ($setting['settings_type'] === 'section') {
				add_settings_section(
					$setting['id'],
					$setting['title'],
					array( $this, $setting['callback'] ),
					$setting['page']
				);
			}
			if ($setting['settings_type'] === 'field') {
				add_settings_field(
					$setting['id'],
					$setting['title'],
					array( $this, $setting['callback'] ),
					$setting['page'],
					$setting['section'],
					$setting['args']
				);
			}
		}
	}


	/**
	 * Append a settings field to the the fields section.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param array $args
	 */
		public function analyticstracker_settings_field_render( array $options = array() ) {
		$saved_options = get_option( 'analyticstracker_settings' );

		$atts = array(
			'id' => $options['id'],
			'type' => ( isset( $options['type'] ) ? $options['type'] : 'text' ),
			'class' => $options['class'],
			'name' => 'analyticstracker_settings[' . $options['name'] . ']',
			'value' => ( array_key_exists( 'default', $options ) ? $options['default'] : null ),
			'label_for' => ( array_key_exists( 'label_for', $options ) ? $options['label_for'] : false ),
			'description' => ( array_key_exists( 'description', $options ) ? $options['description'] : false )
		);

		if ( isset( $options['id'] ) ) {
			if ( isset( $saved_options[$options['id']] ) AND  ( $saved_options[$options['id']] != '') )  {
				$val = $saved_options[$options['id']];
			} else {
				$val = ( array_key_exists( 'default', $options ) ? $options['default'] : '' );
			}
			$atts['value'] = $val;
		}
		if ( isset( $options['type'] ) && $options['type'] == 'checkbox' ) {
			if ( $atts['value'] ) {
				$atts['checked'] = 'checked';
			}
				$atts['value'] = true;
		}


		/**
		 * Input type Checkbox
		 */
		if ($atts['type'] == 'checkbox') {
			//var_dump( $atts);
			$html = sprintf( '<input type="%1$s" class="%2$s" id="%3$s" name="%4$s" value="%5$s" %6$s />', $atts['type'], $atts['class'], $atts['id'], $atts['name'], $atts['value'], ( isset( $atts['checked'] ) ? "checked=".$atts['checked'] : '') );
			if ( array_key_exists( 'description', $atts ) ){
				$html .= sprintf( '<p class="description">%1$s</p>', $atts['description'] );
			}
			echo $html;
		}


		/**
		 * Input type Text
		 */
		if ($atts['type'] == 'text') {
			$html = sprintf( '<input type="%1$s" class="%2$s" id="%3$s" name="%4$s" value="%5$s"/>', $atts['type'], $atts['class'], $atts['id'], $atts['name'], $atts['value'] );
			if ( array_key_exists( 'description', $atts ) ){
				$html .= sprintf( '<p class="description">%1$s</p>', $atts['description'] );
			}
			echo $html;
		}


		/**
		 * Input type Textarea
		 */
		 if ($atts['type'] == 'textarea') {
 			$html = sprintf( '<textarea cols="60" rows="5" class="%1$s" id="%2$s" name="%3$s">%4$s</textarea>', $atts['class'], $atts['id'], $atts['name'], $atts['value'] );
 			if ( array_key_exists( 'description', $atts ) ){
 				$html .= sprintf( '<p class="description">%1$s</p>', $atts['description'] );
 			}
 			echo $html;
 		}
	}


	/**
	 * Generate settings form
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_options_page() {
		?>
		<div class="wrap">
			<form action='options.php' method='post'>
				<h1><?php _e( 'Google Analytics Settings', 'analytics-tracker' ); ?></h1>
				<?php settings_errors(); ?>
				<?php
					settings_fields( 'analyticstracker_page' );
					do_settings_sections( 'analyticstracker_page' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}


}
// Instantiate the main class
 new AnalyticsTracker();
?>
