<?php

/**
 * The admin-specific functionality of the plugin.
 */
class Brand_Oasis_Admin {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles( $hook ) {
		if ( strpos( $hook, 'brand-oasis' ) === false ) {
			return;
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'admin/css/brand-oasis-admin.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'wp-color-picker' );
	}

	public function enqueue_scripts( $hook ) {
		if ( strpos( $hook, 'brand-oasis' ) === false ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/brand-oasis-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );

        if ( $hook === 'brand-oasis_page_brand-oasis-login' ) {
            wp_enqueue_script( $this->plugin_name . '-login-preview', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/brand-oasis-login-preview.js', array( 'jquery' ), $this->version, false );
        }

		wp_localize_script( $this->plugin_name, 'brandOasisAdmin', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'brand-oasis-nonce' )
		) );
	}

	public function add_plugin_admin_menu() {
		add_menu_page(
			__( 'Brand Oasis', 'brand-oasis' ),
			__( 'Brand Oasis', 'brand-oasis' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_login_customizer_page' ), // default to login customizer
			'dashicons-art',
			80
		);

		add_submenu_page(
			$this->plugin_name,
			__( 'Login Customizer', 'brand-oasis' ),
			__( 'Login Customizer', 'brand-oasis' ),
			'manage_options',
			$this->plugin_name . '-login',
			array( $this, 'display_login_customizer_page' )
		);

		add_submenu_page(
			$this->plugin_name,
			__( 'Admin Dashboard', 'brand-oasis' ),
			__( 'Admin Dashboard', 'brand-oasis' ),
			'manage_options',
			$this->plugin_name . '-dashboard',
			array( $this, 'display_admin_dashboard_page' )
		);

        // Remove the duplicated top-level menu link
        remove_submenu_page( $this->plugin_name, $this->plugin_name );
	}

	public function register_settings() {
		// Login Customizer Settings
		register_setting(
			'brand_oasis_login_options',
			'brand_oasis_login_settings',
			array( $this, 'sanitize_settings' )
		);

		// Admin Dashboard Customizer Settings
		register_setting(
			'brand_oasis_admin_options',
			'brand_oasis_admin_settings',
			array( $this, 'sanitize_settings' )
		);
	}

	public function sanitize_settings( $input ) {
		$sanitized = array();
		if ( ! is_array( $input ) ) return $sanitized;

		foreach ( $input as $key => $value ) {
			if ( is_array( $value ) ) {
                $sanitized[$key] = $this->sanitize_settings( $value );
            } else {
                $sanitized[$key] = sanitize_text_field( $value );
            }
		}
		return $sanitized;
	}

	public function display_login_customizer_page() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/brand-oasis-login-display.php';
	}

	public function display_admin_dashboard_page() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/brand-oasis-admin-display.php';
	}

    public function ajax_apply_preset() {
        check_ajax_referer( 'brand-oasis-nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $preset_key = isset( $_POST['preset'] ) ? sanitize_text_field( $_POST['preset'] ) : '';
        $presets_file = plugin_dir_path( dirname( __FILE__ ) ) . 'presets/login-presets.php';

        if ( file_exists( $presets_file ) ) {
            $presets = include $presets_file;
            if ( isset( $presets[ $preset_key ] ) ) {
                wp_send_json_success( $presets[ $preset_key ] );
            }
        }

        wp_send_json_error( 'Preset not found' );
    }

    public function ajax_export_settings() {
        check_ajax_referer( 'brand-oasis-nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : 'login';
        $option_name = $type === 'login' ? 'brand_oasis_login_settings' : 'brand_oasis_admin_settings';
        $settings = get_option( $option_name, array() );

        wp_send_json_success( json_encode( $settings ) );
    }

    public function ajax_import_settings() {
        check_ajax_referer( 'brand-oasis-nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : 'login';
        $json = isset( $_POST['json'] ) ? stripslashes( $_POST['json'] ) : '';

        $data = json_decode( $json, true );
        if ( json_last_error() === JSON_ERROR_NONE && is_array( $data ) ) {
            $option_name = $type === 'login' ? 'brand_oasis_login_settings' : 'brand_oasis_admin_settings';
            update_option( $option_name, $this->sanitize_settings( $data ) );
            wp_send_json_success( 'Imported successfully' );
        } else {
            wp_send_json_error( 'Invalid JSON' );
        }
    }
}
