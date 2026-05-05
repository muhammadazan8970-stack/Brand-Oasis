<?php

class Brand_Oasis_Login {

	private $plugin_name;
	private $version;
	private $settings;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings = get_option( 'brand_oasis_login_settings', array() );
	}

	public function login_enqueue_scripts() {
		$font_family = $this->get_setting( 'font_family' );
		if ( ! empty( $font_family ) ) {
			// Basic google font enqueue logic if needed.
            // A more robust implementation would parse the font family to build the Google Fonts URL.
            // For now, we'll try a naive approach or let users enqueue their own.
            // wp_enqueue_style( 'brand-oasis-login-font', "https://fonts.googleapis.com/css?family=" . urlencode( $font_family ), array(), null );
		}
	}

	public function login_headerurl( $url ) {
		return home_url();
	}

	public function login_head() {
		$css = '<style id="brand-oasis-login-css">';

        // Logo
		$logo_url = $this->get_setting( 'logo_url' );
		if ( $logo_url ) {
			$logo_width = $this->get_setting( 'logo_width', '84' );
			$logo_height = $this->get_setting( 'logo_height', '84' );
			$logo_spacing = $this->get_setting( 'logo_spacing', '25' );

			$css .= ".login h1 a {
				background-image: url('{$logo_url}') !important;
				background-size: {$logo_width}px {$logo_height}px !important;
				width: {$logo_width}px !important;
				height: {$logo_height}px !important;
				margin-bottom: {$logo_spacing}px !important;
			}";
		}

        // Background
		$bg_color = $this->get_setting( 'bg_color', '#f1f1f1' );
		$bg_image = $this->get_setting( 'bg_image' );
		$bg_position = $this->get_setting( 'bg_position', 'center center' );
		$bg_size = $this->get_setting( 'bg_size', 'cover' );
		$bg_repeat = $this->get_setting( 'bg_repeat', 'no-repeat' );

		$body_css = "background-color: {$bg_color} !important;";
		if ( $bg_image ) {
			$body_css .= "
				background-image: url('{$bg_image}') !important;
				background-position: {$bg_position} !important;
				background-size: {$bg_size} !important;
				background-repeat: {$bg_repeat} !important;
			";
		}
		$css .= "body.login { {$body_css} }";

        // Overlay
        $overlay_color = $this->get_setting( 'overlay_color' );
        $overlay_opacity = $this->get_setting( 'overlay_opacity', '0.5' );
        if ( $overlay_color ) {
            $css .= "
                body.login::before {
                    content: '';
                    position: fixed;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background-color: {$overlay_color};
                    opacity: {$overlay_opacity};
                    z-index: -1;
                }
            ";
        }

        // Form Styling
        $form_bg = $this->get_setting( 'form_bg', '#ffffff' );
        $form_opacity = $this->get_setting( 'form_opacity', '1' );
        $form_radius = $this->get_setting( 'form_radius', '0' );
        $form_shadow = $this->get_setting( 'form_shadow', '0 1px 3px rgba(0,0,0,.13)' );

        // Convert hex to rgb for opacity
        if ( $form_opacity < 1 && preg_match('/^#([a-f0-9]{3}){1,2}$/i', $form_bg) ) {
            list($r, $g, $b) = sscanf($form_bg, "#%02x%02x%02x");
            $form_bg = "rgba($r, $g, $b, $form_opacity)";
        }

        $css .= ".login form {
            background-color: {$form_bg} !important;
            border-radius: {$form_radius}px !important;
            box-shadow: {$form_shadow} !important;
        }";

        // Input Styling
        $input_radius = $this->get_setting( 'input_radius', '0' );
        $css .= ".login input[type=text], .login input[type=password] {
            border-radius: {$input_radius}px !important;
        }";

        // Button Styling
        $btn_bg = $this->get_setting( 'btn_bg', '#2271b1' );
        $btn_color = $this->get_setting( 'btn_color', '#ffffff' );
        $btn_radius = $this->get_setting( 'btn_radius', '3' );
        $css .= ".wp-core-ui .button-primary {
            background-color: {$btn_bg} !important;
            border-color: {$btn_bg} !important;
            color: {$btn_color} !important;
            border-radius: {$btn_radius}px !important;
        }
        .wp-core-ui .button-primary:hover {
            opacity: 0.9 !important;
        }";

        // Typography
        $font_family = $this->get_setting( 'font_family' );
        $text_color = $this->get_setting( 'text_color', '#3c434a' );
        $link_color = $this->get_setting( 'link_color', '#2271b1' );
        $link_hover_color = $this->get_setting( 'link_hover_color', '#135e96' );

        if ( $font_family ) {
            $css .= "body.login, .login label, .login input, .login .button { font-family: {$font_family} !important; }";
        }
        $css .= ".login label { color: {$text_color} !important; }";
        $css .= ".login #nav a, .login #backtoblog a { color: {$link_color} !important; }";
        $css .= ".login #nav a:hover, .login #backtoblog a:hover { color: {$link_hover_color} !important; }";

        // Layout
        $form_width = $this->get_setting( 'form_width', '320' );
        $alignment = $this->get_setting( 'alignment', 'center' );

        $css .= "#login { width: {$form_width}px !important; }";

        if ( $alignment === 'left' ) {
            $css .= "#login { margin-left: 5% !important; margin-right: auto !important; }";
        } elseif ( $alignment === 'right' ) {
            $css .= "#login { margin-right: 5% !important; margin-left: auto !important; }";
        }

		$css .= '</style>';

        // Output custom google font if needed
        if ( $font_family ) {
            $clean_font = trim(explode(',', $font_family)[0], "'\"");
            echo "<link href='https://fonts.googleapis.com/css2?family=" . urlencode($clean_font) . ":wght@400;600&display=swap' rel='stylesheet'>";
        }

		echo $css;
	}

	private function get_setting( $key, $default = '' ) {
		return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $default;
	}

}
