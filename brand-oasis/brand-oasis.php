<?php
/**
 * Plugin Name:       Brand Oasis
 * Plugin URI:        https://example.com/brand-oasis
 * Description:       A modern branding/customization plugin for WordPress admin experience.
 * Version:           2.0.0
 * Author:            Your Name
 * Author URI:        https://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       brand-oasis
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'BRAND_OASIS_VERSION', '2.0.0' );

/**
 * Plugin directory path.
 */
define( 'BRAND_OASIS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'BRAND_OASIS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_brand_oasis() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-brand-oasis-activator.php';
	Brand_Oasis_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_brand_oasis() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-brand-oasis-deactivator.php';
	Brand_Oasis_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_brand_oasis' );
register_deactivation_hook( __FILE__, 'deactivate_brand_oasis' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-brand-oasis.php';

/**
 * Begins execution of the plugin.
 */
function run_brand_oasis() {

	$plugin = new Brand_Oasis();
	$plugin->run();

}
run_brand_oasis();
