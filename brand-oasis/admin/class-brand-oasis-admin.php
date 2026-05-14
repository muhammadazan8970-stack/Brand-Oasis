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
            wp_enqueue_script( $this->plugin_name . '-preview-renderer', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/preview/preview-renderer.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name . '-preview-events', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/preview/preview-events.js', array( 'jquery', 'wp-color-picker', $this->plugin_name . '-preview-renderer' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name . '-template-preview', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/preview/template-preview.js', array( 'jquery', $this->plugin_name . '-preview-events' ), $this->version, false );
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
			array( $this, 'sanitize_login_settings' )
		);

		// Admin Dashboard Customizer Settings
		register_setting(
			'brand_oasis_admin_options',
			'brand_oasis_admin_settings',
			array( $this, 'sanitize_admin_settings' )
		);
	}

	public function sanitize_login_settings( $input ) {
		$schema = Brand_Oasis_Settings_Registry::get_login_schema();
        $types = Brand_Oasis_Settings_Registry::get_schema_types( $schema );
        return $this->sanitize_settings_by_schema( $input, $types );
	}

    public function sanitize_admin_settings( $input ) {
		$schema = Brand_Oasis_Settings_Registry::get_admin_schema();
        $types = Brand_Oasis_Settings_Registry::get_schema_types( $schema );
        return $this->sanitize_settings_by_schema( $input, $types );
	}

    private function sanitize_settings_by_schema( $input, $types ) {
        $sanitized = array();
		if ( ! is_array( $input ) ) return $sanitized;

		foreach ( $input as $key => $value ) {
            // Check if it's a nested array (e.g. some complex future setting)
			if ( is_array( $value ) ) {
                // If it's an array but not mapped in our simple flat schema, fall back to aggressive text sanitization recursively
                $sanitized[$key] = $this->sanitize_settings_by_schema( $value, $types );
            } else {
                // Determine type from registry or fallback to text to preserve backward compatibility for old unmapped settings
                $type = isset( $types[$key] ) ? $types[$key] : 'text';
                $sanitized[$key] = Brand_Oasis_Sanitizer::sanitize( $type, $value );
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

        $preset_key = isset( $_POST['preset'] ) ? Brand_Oasis_Sanitizer::sanitize( 'text', $_POST['preset'] ) : '';
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

        $type = isset( $_POST['type'] ) ? Brand_Oasis_Sanitizer::sanitize( 'text', $_POST['type'] ) : 'login';
        $option_name = $type === 'login' ? 'brand_oasis_login_settings' : 'brand_oasis_admin_settings';
        $settings = get_option( $option_name, array() );

        wp_send_json_success( json_encode( $settings ) );
    }

    public function ajax_preview_css() {
        check_ajax_referer( 'brand-oasis-nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $raw_settings = isset( $_POST['settings'] ) && is_array( $_POST['settings'] ) ? wp_unslash( $_POST['settings'] ) : array();
        $settings = $this->sanitize_login_settings( $raw_settings );

        // Pass temporary settings to a new instance of the Login Customizer
        $plugin_login = new Brand_Oasis_Login( $this->plugin_name, $this->version, $settings );
        $css_string = $plugin_login->generate_css_string();

        wp_send_json_success( $css_string );
    }

    public function ajax_import_settings() {
        check_ajax_referer( 'brand-oasis-nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $type = isset( $_POST['type'] ) ? Brand_Oasis_Sanitizer::sanitize( 'text', $_POST['type'] ) : 'login';
        $json = isset( $_POST['json'] ) ? stripslashes( $_POST['json'] ) : '';

        $data = json_decode( $json, true );
        if ( json_last_error() === JSON_ERROR_NONE && is_array( $data ) ) {
            $option_name = $type === 'login' ? 'brand_oasis_login_settings' : 'brand_oasis_admin_settings';

            if ( $type === 'login' ) {
                $sanitized_data = $this->sanitize_login_settings( $data );
            } else {
                $sanitized_data = $this->sanitize_admin_settings( $data );
            }

            update_option( $option_name, $sanitized_data );
            wp_send_json_success( 'Imported successfully' );
        } else {
            wp_send_json_error( 'Invalid JSON' );
        }
    }
}
