<?php
// If uninstall not called form WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// delete the option from the options table
if (is_multisite()) {
	global $wpdb;
	$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);

	if(!empty($blogs)) {
		foreach($blogs as $blog) {
			switch_to_blog($blog['blog_id']);
			delete_option('analyticstracker_settings');
		}
	}
} else {
	delete_option('analyticstracker_settings');
}
?>
