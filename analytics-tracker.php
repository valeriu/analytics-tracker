<?php
/**
 * Plugin Name: Analytics Tracker
 * Plugin URI: https://stylishwp.com/product/wordpress-plugins/google-analytics-tracker/
 * Description: Analytics Tracker makes it super easy to add Google Analytics tracking code on your site
 * Text Domain: analytics-tracker
 * Domain Path: /languages
 * Version: 3.0.2
 * Author: Valeriu Tihai
 * Author URI: https://valeriu.tihai.ca
 * Contributors: valeriutihai
 * Donate link: https://paypal.me/valeriu/25
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package AnalyticsTracker
 */

defined( 'ABSPATH' ) || die( 'Plugin file cannot be accessed directly.' );
// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Main plugin class.
 *
 * @since 1.0.0
 */
class AnalyticsTracker {
	/**
	 * Plugin version.
	 *
	 * @since 3.0.0
	 */
	const VERSION = '3.0.2';


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {

		// Load language file.
		add_action( 'init', array( $this, 'analyticstracker_load_textdomain' ) );

		// Add Support Link.
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( &$this, 'analyticstracker_links' ), 1 );

		// Add GA code.
		add_action( 'wp_enqueue_scripts', array( &$this, 'analyticstracker_ga_script' ), 999 );

		// Add Admin menu.
		add_action( 'admin_menu', array( $this, 'analyticstracker_admin_menu' ), 4 );

		// Setting Initialization.
		add_action( 'admin_init', array( &$this, 'analyticstracker_settings_init' ), 1 );

		// Load external JavaScript.
		add_action( 'admin_enqueue_scripts', array( &$this, 'analyticstracker_enqueue_admin' ), 1 );

		// Add Comment meta.
		add_action( 'wp_insert_comment', array( &$this, 'analyticstracker_ga_comment_meta' ), 99, 2 );
	}


	/**
	 * Loads plugin text domain.
	 *
	 * This will load the MO file for the current locale.
	 * The translation file must be named analytics-tracker-$locale.mo.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function analyticstracker_load_textdomain() {
		load_plugin_textdomain( 'analytics-tracker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}


	/**
	 * Adds settings links to the plugin row.
	 *
	 * @since 1.0.0
	 * @param array $links Existing plugin action links.
	 * @return array Modified plugin action links.
	 */
	public function analyticstracker_links( $links ) {
		$links[] = '<a href="https://wordpress.org/support/plugin/analytics-tracker" rel="noopener noreferrer" target="_blank">' . __( 'Support', 'analytics-tracker' ) . '</a>';
		return $links;
	}


	/**
	 * Registers plugin admin menus.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function analyticstracker_admin_menu() {
		add_menu_page( __( 'Google Analytics', 'analytics-tracker' ), __( 'Google Analytics', 'analytics-tracker' ), 'manage_options', 'analyticstracker-menu', array( $this, 'analyticstracker_options_page' ), 'dashicons-chart-line' );
		add_submenu_page( 'analyticstracker-menu', __( 'Other Products', 'analytics-tracker' ), __( 'Other Products', 'analytics-tracker' ), 'manage_options', 'analyticstracker-other-plugins', array( $this, 'analyticstracker_other_plugins' ) );
	}


	/**
	 * Renders the recommended products page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function analyticstracker_other_plugins() {

		?>
		<?php add_thickbox(); ?>
		<div class="wrap">
			<h2><?php esc_html_e( 'My Recommendations', 'analytics-tracker' ); ?></h2>
			<div class='card pressthis'>
				<h2><?php esc_html_e( 'WordPress Hosting', 'analytics-tracker' ); ?></h2>
				<ul>
					<li>

						<a href="https://kinsta.com/plans/?kaid=NDINHGAQXILS" target="_blank"><?php esc_html_e( 'Kinsta Managed WordPress Hosting', 'analytics-tracker' ); ?></a> - <?php esc_html_e( 'We have many years of experience working with WordPress and we\'ve poured all that know-how into creating the best managed WordPress hosting solution available today.', 'analytics-tracker' ); ?>
						<p>
							<a href="https://kinsta.com/plans/?kaid=NDINHGAQXILS" target="_blank">
								<img class="pressthis_img" src="https://i2.wp.com/valeriu.files.wordpress.com/2018/02/kinsta-dark.png" alt="<?php esc_attr_e( 'Kinsta Managed WordPress Hosting', 'analytics-tracker' ); ?>">
							</a>
						</p>
					</li>
				</ul>
			</div>
			<div class='card pressthis'>
				<h2><?php esc_html_e( 'WordPress Plugins', 'analytics-tracker' ); ?></h2>
				<ul>
					<li>
						<a href="<?php echo esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=wplook-twitter-follow-button-new&TB_iframe=true&width=762&height=600' ) ); ?>" class="thickbox"><?php esc_html_e( 'Twitter Follow Button', 'analytics-tracker' ); ?></a> - <?php esc_html_e( 'Add the Twitter Follow Button to your blog to increase engagement and create a lasting connection with your audience.', 'analytics-tracker' ); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=auto-update&TB_iframe=true&width=762&height=600' ) ); ?>" class="thickbox"><?php esc_html_e( 'Auto Update', 'analytics-tracker' ); ?></a> - <?php esc_html_e( 'This plugin enable Auto Update for WordPress core, Themes and Plugins.', 'analytics-tracker' ); ?>
					</li>
				</ul>
			</div>
			<div class='card pressthis'>
				<h2><?php esc_html_e( 'WordPress Themes', 'analytics-tracker' ); ?></h2>
				<ul>
					<li>
						<a href="<?php echo esc_url( network_admin_url( 'theme-install.php?theme=blogolife' ) ); ?>"><?php esc_html_e( 'BlogoLife', 'analytics-tracker' ); ?></a> - <?php esc_html_e( 'BlogoLife is a simple and perfect WordPress theme for personal blogging that supports post formats, and several customization options.', 'analytics-tracker' ); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://themeforest.net/item/avada-responsive-multipurpose-theme/2833226?ref=stylishwp' ); ?>" target="_blank"><?php esc_html_e( 'Avada', 'analytics-tracker' ); ?></a> - <?php esc_html_e( 'the #1 selling WordPress theme on the market. Simply put, it is the most versatile, easy to use multi-purpose WordPress theme. Avada is all about building unique, creative and professional websites through industry leading options network without having to touch a line of code. ', 'analytics-tracker' ); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://themeforest.net/item/the7-responsive-multipurpose-wordpress-theme/5556590?ref=stylishwp' ); ?>" target="_blank"><?php esc_html_e( 'The7', 'analytics-tracker' ); ?></a> - <?php esc_html_e( 'Its 750+ Theme Options allows to craft almost any imaginable design. And Design Wizard feature lets you create a boutique-grade website design in mere minutes.', 'analytics-tracker' ); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://themeforest.net/item/betheme-responsive-multipurpose-wordpress-theme/7758048?ref=stylishwp' ); ?>" target="_blank"><?php esc_html_e( 'BeTheme', 'analytics-tracker' ); ?></a> - <?php esc_html_e( 'This is more than just WordPress theme. Such advanced options panel and Drag&Drop builder tool give unlimited possibilities.', 'analytics-tracker' ); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://themeforest.net/item/enfold-responsive-multipurpose-theme/4519990?ref=stylishwp' ); ?>" target="_blank"><?php esc_html_e( 'Enfold', 'analytics-tracker' ); ?></a> - <?php esc_html_e( 'Enfold is a clean, super flexible and fully responsive WordPress Theme (try resizing your browser), suited for business websites, shop websites, and users who want to showcase their work on a neat portfolio site.', 'analytics-tracker' ); ?>
					</li>
				</ul>
			</div>
			<div class='card pressthis'>
				<h2><a target="_blank" title="<?php esc_attr_e( 'Daily Beautiful WordPress Templates for your business', 'analytics-tracker' ); ?>" href="https://dailydesigncafe.com/?utm_source=DailyDesign&utm_medium=Recommendations&utm_campaign=AnalyticsTracker">Daily Design Cafe ☕</a></h2>
				<div class="dailydesign_container"></div>
			</div>
		</div>
		<?php
	}


	/**
	 * Gets plugin settings definitions.
	 *
	 * @since 1.0.0
	 * @return array List of settings definitions.
	 */
	public function analyticstracker_settings() {
		return array(
			array(
				'settings_type' => 'section',
				'id'            => 'analyticstracker_section_settings_general',
				'title'         => __( 'General Settings', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_description_section_callback',
				'page'          => 'analyticstracker_page',
			),
			array(
				'settings_type' => 'field',
				'id'            => 'analyticstracker_ga',
				'title'         => __( 'GA4 Measurement ID', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_general',
				'args'          => array(
					'id'          => 'analyticstracker_ga',
					'type'        => 'text',
					'class'       => '',
					'name'        => 'analyticstracker_ga',
					'value'       => 'analyticstracker_ga',
					'label_for'   => '',
					'description' => __( 'Add GA4 Measurement ID (for example: G-XXXXXXXXXX). Where can I find <a href="https://support.google.com/analytics/answer/12270356" target="_blank">my Measurement ID?</a>', 'analytics-tracker' ),
				),
			),
			array(
				'settings_type' => 'field',
				'id'            => 'analyticstracker_userid',
				'title'         => __( 'User ID', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_general',
				'args'          => array(
					'id'          => 'analyticstracker_userid',
					'type'        => 'checkbox',
					'class'       => '',
					'name'        => 'analyticstracker_userid',
					'value'       => 1,
					'default'     => 0,
					'label_for'   => '',
					'description' => __( 'This is intended to be a known identifier for a user provided by the site owner/tracking library user.', 'analytics-tracker' ),
				),
			),
			array(
				'settings_type' => 'field',
				'id'            => 'analyticstracker_comment_event',
				'title'         => __( 'Comment Added Event', 'analytics-tracker' ),
				'callback'      => 'analyticstracker_settings_field_render',
				'page'          => 'analyticstracker_page',
				'section'       => 'analyticstracker_section_settings_general',
				'args'          => array(
					'id'          => 'analyticstracker_comment_event',
					'type'        => 'checkbox',
					'class'       => '',
					'name'        => 'analyticstracker_comment_event',
					'value'       => 1,
					'default'     => 0,
					'label_for'   => '',
					'description' => __( 'Enable or disable the event sent when a comment becomes visible on your site.', 'analytics-tracker' ),
				),
			),
		);
	}


	/**
	 * Gets Google Analytics config and events.
	 *
	 * @since 1.0.2
	 * @return array Analytics payload when tracking is enabled.
	 */
	public function analyticstracker_ga_get() {
		$saved_options                 = get_option( 'analyticstracker_settings' );
		$tracking_id                   = strtoupper( trim( (string) ( $saved_options['analyticstracker_ga'] ?? '' ) ) );
		$analyticstracker_gtag_general = array();
		$analyticstracker_gtag_events  = array();
		if ( ! preg_match( '/^G-[A-Z0-9]{4,}$/', $tracking_id ) ) {
			return array(
				'general' => array(),
				'events'  => array(),
			);
		}

		global $post;

		/* User identification. */
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			if ( isset( $saved_options['analyticstracker_userid'] ) && '' !== $saved_options['analyticstracker_userid'] ) {
				$analyticstracker_gtag_general['user_id'] = $this->analyticstracker_anon_user_id( $current_user->ID );
			}
		}

		/*
		 * Events tracking.
		 * If the legacy option key is missing, keep events enabled by default.
		 */
		$events_enabled = ! isset( $saved_options['analyticstracker_events'] ) || '' !== $saved_options['analyticstracker_events'];
		if ( $events_enabled ) {

			// Unified page_view logic for all page types.
			$page_view_params = array();

			/* Home / front page. */
			if ( is_front_page() || is_home() ) {
				$page_view_params['page_type'] = 'home';
			} elseif ( is_singular() ) {
				/* Singular: post, page, CPT. */

				global $post;

				$page_view_params['page_type']   = 'singular';
				$page_view_params['post_id']     = (int) $post->ID;
				$page_view_params['post_type']   = $post->post_type;
				$page_view_params['post_format'] = get_post_format() ? get_post_format() : 'standard';

				if ( ! empty( $post->post_author ) ) {
					$page_view_params['author_id'] = $this->analyticstracker_anon_user_id( absint( $post->post_author ) );
				}

				$tags = get_the_tags();
				if ( ! empty( $tags ) ) {
					$tag_names = array();
					foreach ( $tags as $tag ) {
						$tag_names[] = $tag->name;
					}
					$page_view_params['tags'] = implode( '|', $tag_names );
				}

				$cats = get_the_category();
				if ( ! empty( $cats ) ) {
					$cat_names = array();
					foreach ( $cats as $cat ) {
						$cat_names[] = $cat->name;
					}
					$page_view_params['categories'] = implode( '|', $cat_names );
				}

				$page_view_params['archive'] = get_the_date( 'Y|m|N|A' );

					/* GA4 comment-added event. */
				$track_comment_event = ! isset( $saved_options['analyticstracker_comment_event'] ) || '' !== $saved_options['analyticstracker_comment_event'];
				if ( $track_comment_event ) {
					$args = array(
						'meta_key' => 'analyticstracker_comment_event',
						'post_id'  => $post->ID,
					);

					$at_comments = get_comments( $args );

					foreach ( $at_comments as $at_comment ) {
						$comment_params = array(
							'post_id'    => (int) $at_comment->comment_post_ID,
							'comment_id' => (int) $at_comment->comment_ID,
						);

						if ( ! empty( $at_comment->user_id ) ) {
							$comment_params['author_id'] = $this->analyticstracker_anon_user_id( absint( $at_comment->user_id ) );
						} else {
							$comment_params['author_id'] = 'guest';
						}

						$analyticstracker_gtag_events[] = array(
							'comment_added',
							$comment_params,
						);

						delete_comment_meta( $at_comment->comment_ID, 'analyticstracker_comment_event' );
					}
				}
			} elseif ( is_category() || is_tag() || is_tax() ) {
				/* Category / tag / custom taxonomy archive. */

				$term = get_queried_object();

				if ( $term && ! is_wp_error( $term ) ) {
					$page_view_params['page_type'] = 'taxonomy';
					$page_view_params['taxonomy']  = $term->taxonomy;
					$page_view_params['term_id']   = (int) $term->term_id;
					$page_view_params['term_name'] = $term->name;
				}
			} elseif ( is_author() ) {
				/* Author archive. */

				$author = get_queried_object();

				if ( $author ) {
					$page_view_params['page_type'] = 'author';
					$page_view_params['author_id'] = $this->analyticstracker_anon_user_id( absint( $author->ID ) );
				}
			} elseif ( is_search() ) {
				/* Search results page. */

				global $wp_query;
				$search_term = get_search_query( false );
				$search_term = wp_strip_all_tags( $search_term );
				$search_term = sanitize_text_field( $search_term );

				$page_view_params['page_type']     = 'search';
				$page_view_params['search_term']   = $search_term;
				$page_view_params['results_count'] = isset( $wp_query->found_posts ) ? absint( $wp_query->found_posts ) : 0;
			} elseif ( is_404() ) {
				/* 404 page. */

				$page_view_params['page_type'] = '404';
			}

			/* Push one enriched page_view event. */
			if ( ! empty( $page_view_params ) ) {
				$analyticstracker_gtag_events[] = array(
					'page_view',
					$page_view_params,
				);
			}
		}

		return array(
			'general' => $analyticstracker_gtag_general,
			'events'  => $analyticstracker_gtag_events,
		);
	}

	/**
	 * Generate anonymous user ID for GA4.
	 *
	 * @since 3.0.0
	 * @param int $user_id WordPress user ID.
	 * @return string Anonymous user ID.
	 */
	public function analyticstracker_anon_user_id( $user_id ) {
		return 'u_' . hash( 'crc32b', (string) $user_id );
	}

	/**
	 * Prints Google Analytics script.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function analyticstracker_ga_script() {
		$saved_options = get_option( 'analyticstracker_settings' );
		$tracking_id   = strtoupper( trim( (string) ( $saved_options['analyticstracker_ga'] ?? '' ) ) );
		if ( '' === $tracking_id ) {
			return;
		}

		$is_ga4 = preg_match( '/^G-[A-Z0-9]+$/', $tracking_id );

		$script_src = 'https://www.googletagmanager.com/gtag/js?id=' . rawurlencode( $tracking_id );

		wp_register_script( 'analyticstracker-gtag', $script_src, array(), null, false );
		wp_script_add_data( 'analyticstracker-gtag', 'async', true );
		wp_enqueue_script( 'analyticstracker-gtag' );

		// Non-GA4 IDs keep a minimal setup without custom events.
		if ( ! $is_ga4 ) {
			$inline_script  = "window.dataLayer = window.dataLayer || [];\n";
			$inline_script .= "function gtag(){dataLayer.push(arguments);}\n";
			$inline_script .= "gtag('js', new Date());\n";
			$inline_script .= sprintf(
				"gtag('config', %s);\n",
				wp_json_encode( $tracking_id )
			);

			wp_add_inline_script( 'analyticstracker-gtag', $inline_script, 'after' );
			return;
		}

		$analyticstracker = $this->analyticstracker_ga_get();
		$general_config   = array();
		$events           = array();

		if ( is_array( $analyticstracker ) ) {
			$general_config = $analyticstracker['general'] ?? array();
			$events         = $analyticstracker['events'] ?? array();
		}

		// Avoid duplicate GA4 page_view: plugin sends enriched page_view manually.
		$general_config['send_page_view'] = false;

		$inline_script  = "window.dataLayer = window.dataLayer || [];\n";
		$inline_script .= "function gtag(){dataLayer.push(arguments);}\n";
		$inline_script .= "gtag('js', new Date());\n";
		$inline_script .= sprintf(
			"gtag('config', %s, %s);\n",
			wp_json_encode( $tracking_id ),
			wp_json_encode( (object) $general_config )
		);

		foreach ( $events as $event ) {
			$event_name = $event[0] ?? '';
			$event_args = $event[1] ?? array();

			if ( '' === $event_name ) {
				continue;
			}

			$inline_script .= sprintf(
				"gtag('event', %s, %s);\n",
				wp_json_encode( $event_name ),
				wp_json_encode( $event_args )
			);
		}

		wp_add_inline_script( 'analyticstracker-gtag', $inline_script, 'after' );
	}

	/**
	 * Enqueues admin JavaScript and CSS files.
	 *
	 * @since 1.0.4
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function analyticstracker_enqueue_admin( $hook ) {
		if ( 'google-analytics_page_analyticstracker-other-plugins' === $hook ) {
			wp_enqueue_script( 'analyticstracker-js-admin', plugins_url( '/javascripts/analyticstracker-admin.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );

			wp_register_style( 'analyticstracker-css-admin', plugins_url( '/css/analyticstracker-admin.css', __FILE__ ), array(), self::VERSION );
			wp_enqueue_style( 'analyticstracker-css-admin' );
		}
	}

	/**
	 * Adds comment meta for analytics tracker events.
	 *
	 * @since 1.0.4
	 * @param int        $comment_id     Comment ID.
	 * @param WP_Comment $comment_object Comment object.
	 * @return void
	 */
	public function analyticstracker_ga_comment_meta( $comment_id, $comment_object ) {
		$saved_options       = get_option( 'analyticstracker_settings' );
		$track_comment_event = ! isset( $saved_options['analyticstracker_comment_event'] ) || '' !== $saved_options['analyticstracker_comment_event'];
		if ( $track_comment_event && '1' === (string) $comment_object->comment_approved ) {
			add_comment_meta( $comment_id, 'analyticstracker_comment_event', 1, true );
		}
	}

	/**
	 * Section description callback.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function analyticstracker_description_section_callback() {  }

	/**
	 * Initializes plugin settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function analyticstracker_settings_init() {
		register_setting(
			'analyticstracker_page',
			'analyticstracker_settings',
			array(
				'sanitize_callback' => array( $this, 'analyticstracker_sanitize_settings' ),
			)
		);
		foreach ( $this->analyticstracker_settings() as $setting ) {
			if ( 'section' === $setting['settings_type'] ) {
				add_settings_section(
					$setting['id'],
					$setting['title'],
					array( $this, $setting['callback'] ),
					$setting['page']
				);
			}
			if ( 'field' === $setting['settings_type'] ) {
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
	 * Sanitizes plugin settings before saving.
	 *
	 * @since 3.0.0
	 * @param array $input Raw settings input.
	 * @return array Sanitized settings.
	 */
	public function analyticstracker_sanitize_settings( $input ) {
		$input     = is_array( $input ) ? $input : array();
		$sanitized = array();

		$sanitized['analyticstracker_ga'] = strtoupper(
			sanitize_text_field( trim( (string) ( $input['analyticstracker_ga'] ?? '' ) ) )
		);

		$sanitized['analyticstracker_userid']        = ! empty( $input['analyticstracker_userid'] ) ? '1' : '';
		$sanitized['analyticstracker_comment_event'] = ! empty( $input['analyticstracker_comment_event'] ) ? '1' : '';

		return $sanitized;
	}

	/**
	 * Renders a settings field.
	 *
	 * @since 1.0.0
	 * @param array $options Field options.
	 * @return void
	 */
	public function analyticstracker_settings_field_render( array $options = array() ) {
		$saved_options     = get_option( 'analyticstracker_settings' );
		$allowed_form_html = array(
			'input'    => array(
				'type'    => array(),
				'class'   => array(),
				'id'      => array(),
				'name'    => array(),
				'value'   => array(),
				'checked' => array(),
			),
			'textarea' => array(
				'cols'  => array(),
				'rows'  => array(),
				'class' => array(),
				'id'    => array(),
				'name'  => array(),
			),
			'p'        => array(
				'class' => array(),
			),
			'a'        => array(
				'href'   => array(),
				'target' => array(),
				'rel'    => array(),
			),
		);

		$atts = array(
			'id'          => $options['id'],
			'type'        => ( isset( $options['type'] ) ? $options['type'] : 'text' ),
			'class'       => $options['class'],
			'name'        => 'analyticstracker_settings[' . $options['name'] . ']',
			'value'       => ( array_key_exists( 'default', $options ) ? $options['default'] : null ),
			'label_for'   => ( array_key_exists( 'label_for', $options ) ? $options['label_for'] : false ),
			'description' => ( array_key_exists( 'description', $options ) ? $options['description'] : false ),
		);

		if ( isset( $options['id'] ) ) {
			if ( isset( $saved_options[ $options['id'] ] ) && ( '' !== $saved_options[ $options['id'] ] ) ) {
				$val = $saved_options[ $options['id'] ];
			} else {
				$val = ( array_key_exists( 'default', $options ) ? $options['default'] : '' );
			}
			$atts['value'] = $val;
		}
		if ( isset( $options['type'] ) && ( 'checkbox' === $options['type'] ) ) {
			if ( $atts['value'] ) {
				$atts['checked'] = 'checked';
			}
				$atts['value'] = true;
		}

		/* Input type: checkbox. */
		if ( 'checkbox' === $atts['type'] ) {
			$html = sprintf(
				'<input type="%1$s" class="%2$s" id="%3$s" name="%4$s" value="%5$s" %6$s />',
				esc_attr( $atts['type'] ),
				esc_attr( $atts['class'] ),
				esc_attr( $atts['id'] ),
				esc_attr( $atts['name'] ),
				esc_attr( $atts['value'] ),
				checked( isset( $atts['checked'] ) ? $atts['checked'] : '', 'checked', false )
			);
			if ( array_key_exists( 'description', $atts ) ) {
				$html .= sprintf( '<p class="description">%1$s</p>', wp_kses_post( $atts['description'] ) );
			}
			echo wp_kses( $html, $allowed_form_html );
		}

		/* Input type: text. */
		if ( 'text' === $atts['type'] ) {
			$html = sprintf(
				'<input type="%1$s" class="%2$s" id="%3$s" name="%4$s" value="%5$s"/>',
				esc_attr( $atts['type'] ),
				esc_attr( $atts['class'] ),
				esc_attr( $atts['id'] ),
				esc_attr( $atts['name'] ),
				esc_attr( $atts['value'] )
			);
			if ( array_key_exists( 'description', $atts ) ) {
				$html .= sprintf( '<p class="description">%1$s</p>', wp_kses_post( $atts['description'] ) );
			}
			echo wp_kses( $html, $allowed_form_html );
		}

		/* Input type: textarea. */
		if ( 'textarea' === $atts['type'] ) {
			$html = sprintf(
				'<textarea cols="60" rows="5" class="%1$s" id="%2$s" name="%3$s">%4$s</textarea>',
				esc_attr( $atts['class'] ),
				esc_attr( $atts['id'] ),
				esc_attr( $atts['name'] ),
				esc_textarea( $atts['value'] )
			);
			if ( array_key_exists( 'description', $atts ) ) {
				$html .= sprintf( '<p class="description">%1$s</p>', wp_kses_post( $atts['description'] ) );
			}
			echo wp_kses( $html, $allowed_form_html );
		}
	}

	/**
	 * Renders plugin settings form.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function analyticstracker_options_page() {
		?>
		<div class="wrap">
			<form action='options.php' method='post'>
				<h1><?php esc_html_e( 'Google Analytics Settings', 'analytics-tracker' ); ?></h1>
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
// Instantiate the main class.
new AnalyticsTracker();
