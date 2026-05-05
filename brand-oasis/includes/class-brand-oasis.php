<?php

/**
 * The core plugin class.
 */
class Brand_Oasis {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		$this->plugin_name = 'brand-oasis';
		$this->version = BRAND_OASIS_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-brand-oasis-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-brand-oasis-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-brand-oasis-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/modules/class-brand-oasis-login.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/modules/class-brand-oasis-dashboard.php';

		$this->loader = new Brand_Oasis_Loader();

	}

	private function set_locale() {

		$plugin_i18n = new Brand_Oasis_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Brand_Oasis_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_login = new Brand_Oasis_Login( $this->get_plugin_name(), $this->get_version() );
		$plugin_dashboard = new Brand_Oasis_Dashboard( $this->get_plugin_name(), $this->get_version() );

		// Admin menu & settings
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// AJAX presets and import/export
		$this->loader->add_action( 'wp_ajax_brand_oasis_apply_preset', $plugin_admin, 'ajax_apply_preset' );
		$this->loader->add_action( 'wp_ajax_brand_oasis_export_settings', $plugin_admin, 'ajax_export_settings' );
		$this->loader->add_action( 'wp_ajax_brand_oasis_import_settings', $plugin_admin, 'ajax_import_settings' );

		// Login Customizer module
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_login, 'login_enqueue_scripts' );
		$this->loader->add_action( 'login_head', $plugin_login, 'login_head' );
		$this->loader->add_filter( 'login_headerurl', $plugin_login, 'login_headerurl' );

		// Dashboard Customizer module
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_dashboard, 'admin_enqueue_scripts' );
		$this->loader->add_action( 'admin_head', $plugin_dashboard, 'admin_head' );
		$this->loader->add_filter( 'admin_footer_text', $plugin_dashboard, 'admin_footer_text', 99 );
		$this->loader->add_action( 'wp_before_admin_bar_render', $plugin_dashboard, 'wp_before_admin_bar_render' );
	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
