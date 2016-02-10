<?php
/*
 * Plugin Name: Analytics Tracker
 * Plugin URI: https://stylishwp.com
 * Description: This plugin add Google Analytics tracking to your WordPress
 * Text Domain: analytics-tracker
 * Domain Path: /languages
 * Version: 1.0.0
 * Author: Valeriu Tihai
 * Author URI: http://valeriu.tihai.ca
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

		// Add Admin menu
		add_action( 'admin_menu', array( $this, 'analyticstracker_admin_menu' ), 4 );

		// Load setings
		add_action( 'plugins_loaded', array( &$this, 'analyticstracker_settings' ), 1 );

		// Setting Initialization
		add_action( 'admin_init', array( &$this, 'analyticstracker_settings_init' ), 1 );

	}


	/**
	 * Insert Google Analytics code
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_ga_script() {
		$saved_options = get_option( 'analyticstracker_settings' );
		if(preg_match("/UA-[0-9]{7,}-[0-9]{1,}/", $saved_options['analyticstracker_ga']) ) { ?>
		<script>
		  (function(i,s,o,g,r,a,m){i['analyticstracker_pageObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', '<?php echo $saved_options['analyticstracker_ga']; ?>', 'auto');
		  ga('send', 'pageview');

		</script>
	<?php }
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
		add_submenu_page( 'analyticstracker-menu', __('Other Plugins', 'analytics-tracker'), __('Other Plugins', 'analytics-tracker'), 'manage_options', 'analyticstracker-other-plugins', array( $this, 'analyticstracker_other_plugins' ) );
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
			<h2><?php _e( 'Other Plugins', 'analytics-tracker' ); ?></h2>
			<div class='card pressthis'>
				<ul>
					<li>
						<a href="<?php echo network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=wplook-twitter-follow-button-new&TB_iframe=true&width=762&height=600'); ?>" class="thickbox"><?php _e('Twitter Follow Button', 'analytics-tracker'); ?></a> - <?php _e('Add the Twitter Follow Button to your blog to increase engagement and create a lasting connection with your audience.', 'analytics-tracker'); ?>
					</li>
					<li>
						<a href="<?php echo network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=auto-update&TB_iframe=true&width=762&height=600'); ?>" class="thickbox"><?php _e('Auto Update', 'analytics-tracker'); ?></a> - <?php _e('This plugin enable Auto Update for WordPress core, Themes and Plugins.', 'analytics-tracker'); ?>
					</li>
				</ul>
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
				'id' => 'analyticstracker_section_setings_general',
				'title' => '',
				'callback' => 'analyticstracker_description_section_callback',
				'page' => 'analyticstracker_page'
			),
			array (
				'settings_type' => 'field',
				'id' => 'analyticstracker_ga',
				'title' => __( 'Google Analytics ID', 'analytics-tracker' ),
				'callback' => 'analyticstracker_settings_field_render',
				'page' => 'analyticstracker_page',
				'section' => 'analyticstracker_section_setings_general',
				'args' => 	array (
								'id' => 'analyticstracker_ga',
								'type' => 'text',
								'class' => '',
								'name' => 'analyticstracker_ga',
								'value' => 'analyticstracker_ga',
								'label_for' => '',
								'description' => __( 'Add Google Analytics tracking  code (UA-XXXXXXX-YY).', 'analytics-tracker' ),
							)
						)
		);
	}


	/**
	 * Description Section callback
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_description_section_callback( ) { }


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
			if (isset($saved_options[$options['id']]) ) {
				$val = $saved_options[$options['id']];
			} else {
				$val = ( array_key_exists( 'default', $options ) ? $options['default'] : "" );
			}
			$atts['value'] = $val;
		}


		/**
		 * Input type text
		 */
		if ($atts['type'] == 'text') {
			$html = sprintf( '<input type="%1$s" class="%2$s" id="%3$s" name="%4$s" value="%5$s"/>', $atts['type'], $atts['class'], $atts['id'], $atts['name'], $atts['value'] );
			if ( array_key_exists( 'description', $atts ) ){
				$html .= sprintf( '<p class="description">%1$s</p>', $atts['description'] );
			}
			echo $html;
		}

	}


	/**
	 * Generate setings form
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function analyticstracker_options_page() {
		?>
		<div class="wrap">
			<h2><?php _e( 'Google Analytics Setings', 'analytics-tracker' ); ?></h2>
			<form action='options.php' method='post'>
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
