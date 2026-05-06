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

        $enable_gradient = $this->get_setting( 'enable_gradient_bg', '0' );
        $grad_type = $this->get_setting( 'gradient_type', 'linear' );
        $grad_angle = $this->get_setting( 'gradient_angle', '135' );
        $grad_color_1 = $this->get_setting( 'gradient_color_1', '#667eea' );
        $grad_color_2 = $this->get_setting( 'gradient_color_2', '#764ba2' );

		$body_css = "background-color: {$bg_color} !important;";

        if ( $enable_gradient === '1' ) {
            if ( $grad_type === 'radial' ) {
                $body_css .= "background-image: radial-gradient(circle, {$grad_color_1}, {$grad_color_2}) !important;";
            } else {
                $body_css .= "background-image: linear-gradient({$grad_angle}deg, {$grad_color_1}, {$grad_color_2}) !important;";
            }
        } elseif ( $bg_image ) {
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
        $form_border_color = $this->get_setting( 'form_border_color', 'transparent' );
        $form_border_width = $this->get_setting( 'form_border_width', '0' );

        // Convert hex to rgb for opacity
        if ( $form_opacity < 1 && preg_match('/^#([a-f0-9]{3}){1,2}$/i', $form_bg) ) {
            list($r, $g, $b) = sscanf($form_bg, "#%02x%02x%02x");
            $form_bg = "rgba($r, $g, $b, $form_opacity)";
        }

        $css .= ".login form {
            background-color: {$form_bg} !important;
            border-radius: {$form_radius}px !important;
            box-shadow: {$form_shadow} !important;
            border: {$form_border_width}px solid {$form_border_color} !important;
        }";

        // Input Styling
        $input_radius = $this->get_setting( 'input_radius', '0' );
        $css .= ".login input[type=text], .login input[type=password] {
            border-radius: {$input_radius}px !important;
        }";

        // Button Styling
        $btn_bg = $this->get_setting( 'btn_bg', '#2271b1' );
        $btn_hover_bg = $this->get_setting( 'btn_hover_bg', '#135e96' );
        $btn_color = $this->get_setting( 'btn_color', '#ffffff' );
        $btn_hover_color = $this->get_setting( 'btn_hover_color', '#ffffff' );

        $btn_radius = $this->get_setting( 'btn_radius', '3' );
        $btn_border_width = $this->get_setting( 'btn_border_width', '0' );
        $btn_border_color = $this->get_setting( 'btn_border_color', 'transparent' );

        $btn_font_weight = $this->get_setting( 'btn_font_weight', '400' );
        $btn_text_transform = $this->get_setting( 'btn_text_transform', 'none' );
        $btn_letter_spacing = $this->get_setting( 'btn_letter_spacing', '0' );

        $btn_width = $this->get_setting( 'btn_width', 'auto' );
        $btn_padding = $this->get_setting( 'btn_padding', '0 10px 1px' );
        $btn_transition_speed = $this->get_setting( 'btn_transition_speed', '0.3' );

        $btn_glow_color = $this->get_setting( 'btn_glow_color', '' );
        $btn_animation = $this->get_setting( 'btn_animation', 'none' );

        $btn_base_css = "
            background: {$btn_bg} !important;
            color: {$btn_color} !important;
            width: {$btn_width} !important;
            padding: {$btn_padding} !important;
            border-radius: {$btn_radius}px !important;
            border: {$btn_border_width}px solid {$btn_border_color} !important;
            font-weight: {$btn_font_weight} !important;
            text-transform: {$btn_text_transform} !important;
            letter-spacing: {$btn_letter_spacing}px !important;
            transition: all {$btn_transition_speed}s ease !important;
        ";

        if ( $btn_glow_color ) {
            $btn_base_css .= "box-shadow: 0 0 10px {$btn_glow_color} !important;";
        }

        $css .= ".wp-core-ui .button-primary { {$btn_base_css} }";

        // Button Hover
        $btn_hover_css = "
            background: {$btn_hover_bg} !important;
            color: {$btn_hover_color} !important;
            border-color: {$btn_border_color} !important;
        ";

        if ( $btn_glow_color ) {
            $btn_hover_css .= "box-shadow: 0 0 20px {$btn_glow_color} !important;";
        }

        if ( $btn_animation === 'scale' ) {
            $btn_hover_css .= "transform: scale(1.05) !important;";
        } elseif ( $btn_animation === 'lift' ) {
            $btn_hover_css .= "transform: translateY(-2px) !important; box-shadow: 0 5px 15px rgba(0,0,0,0.3) !important;";
        }

        $css .= ".wp-core-ui .button-primary:hover { {$btn_hover_css} }";

        if ( $btn_animation === 'pulse' ) {
            $css .= "
                @keyframes boPulseGlow {
                    0% { box-shadow: 0 0 0 0 rgba(255,255,255,0.4); }
                    70% { box-shadow: 0 0 0 10px rgba(255,255,255,0); }
                    100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); }
                }
                .wp-core-ui .button-primary:hover {
                    animation: boPulseGlow 1.5s infinite !important;
                }
            ";
        }

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
