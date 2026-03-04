<?php
/**
 * Uninstall routine for Analytics Tracker.
 *
 * @package AnalyticsTracker
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( is_multisite() ) {
	$site_ids = get_sites(
		array(
			'fields' => 'ids',
		)
	);

	foreach ( $site_ids as $site_id ) {
		switch_to_blog( (int) $site_id );
		delete_option( 'analyticstracker_settings' );
		restore_current_blog();
	}
} else {
	delete_option( 'analyticstracker_settings' );
}
