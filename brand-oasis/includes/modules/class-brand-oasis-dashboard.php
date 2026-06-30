<?php

class Brand_Oasis_Dashboard {

	private $plugin_name;
	private $version;
	private $settings;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings = get_option( 'brand_oasis_admin_settings', array() );
	}

	public function admin_enqueue_scripts( $hook ) {
        // Enqueue font if needed
	}

	public function admin_head() {
        // Don't apply styles on our own settings pages to prevent overriding the settings UI
        // Actually, we should apply them so they see the changes, but let's be careful.
        $css = '<style id="brand-oasis-dashboard-css">';

        // Typography
        $font_family = $this->get_setting( 'font_family' );
        $font_size = $this->get_setting( 'font_size', '13' );

        if ( $font_family ) {
            $css .= "body.wp-admin, #wpadminbar * { font-family: {$font_family} !important; }";
            $clean_font = trim(explode(',', $font_family)[0], "'\"");
            echo "<link href='https://fonts.googleapis.com/css2?family=" . urlencode($clean_font) . ":wght@400;600&display=swap' rel='stylesheet'>";
        }
        $css .= "body.wp-admin { font-size: {$font_size}px !important; }";

        $enable_dark_mode = $this->get_setting( 'enable_dark_mode', '0' );

        if ( $enable_dark_mode === '1' ) {
            $dark_bg = $this->get_setting( 'dark_bg', '#121212' );
            $dark_surface = $this->get_setting( 'dark_surface', '#1e1e1e' );
            $dark_text = $this->get_setting( 'dark_text', '#e0e0e0' );

            $css .= "
                body.wp-admin { background-color: {$dark_bg} !important; color: {$dark_text} !important; }
                .wp-core-ui .postbox, .wp-core-ui .welcome-panel, #wpbody-content .meta-box-sortables .postbox { background-color: {$dark_surface} !important; color: {$dark_text} !important; border-color: #333 !important; }
                .wp-core-ui .postbox h2, .wp-core-ui .postbox .hndle { color: {$dark_text} !important; border-bottom-color: #333 !important; }
                table.widefat { background-color: {$dark_surface} !important; color: {$dark_text} !important; border-color: #333 !important; }
                table.widefat td, table.widefat th { color: {$dark_text} !important; border-color: #333 !important; }
                table.widefat thead th, table.widefat thead td { background-color: {$dark_surface} !important; border-color: #333 !important; }
                #wpcontent, #wpfooter { background-color: {$dark_bg} !important; color: {$dark_text} !important; }
                a { color: #bb86fc !important; }
                a:hover { color: #9965f4 !important; }
            ";
        } else {
            // Colors (Light mode overrides)
            $sidebar_bg = $this->get_setting( 'sidebar_bg', '#1d2327' );
            $sidebar_hover = $this->get_setting( 'sidebar_hover', '#2c3338' );
            $sidebar_active = $this->get_setting( 'sidebar_active', '#2271b1' );
            $sidebar_text = $this->get_setting( 'sidebar_text', '#f0f0f1' );
            $topbar_bg = $this->get_setting( 'topbar_bg', '#1d2327' );
            $content_bg = $this->get_setting( 'content_bg', '#f0f0f1' );

            $css .= "
                #adminmenu, #adminmenuwrap, #adminmenuback { background-color: {$sidebar_bg} !important; }
                #adminmenu a { color: {$sidebar_text} !important; }
                #adminmenu a:hover, #adminmenu li.menu-top:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus { background-color: {$sidebar_hover} !important; color: #fff !important; }
                #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, #adminmenu li.current a.menu-top, .folded #adminmenu li.wp-has-current-submenu, .folded #adminmenu li.current.menu-top { background-color: {$sidebar_active} !important; color: #fff !important; }
                #wpadminbar { background-color: {$topbar_bg} !important; }
                #wpcontent, #wpbody-content { background-color: {$content_bg} !important; }
            ";
        }

        // UI Options
        $rounded_ui = $this->get_setting( 'rounded_ui', '0' );
        if ( $rounded_ui === '1' ) {
            $css .= "
                .postbox, .welcome-panel, .card, table.widefat { border-radius: 8px !important; }
                input[type=text], input[type=search], input[type=password], input[type=email], input[type=number], textarea, select { border-radius: 4px !important; }
            ";
        }

        $button_radius = $this->get_setting( 'button_radius', '4' );
        if ( $button_radius !== '4' ) {
            $css .= "
                .button, .button-primary, .button-secondary { border-radius: {$button_radius}px !important; }
            ";
        }

        $container_width = $this->get_setting( 'container_width', '100%' );
        if ( $container_width !== '100%' ) {
            $css .= "
                #wpbody-content { max-width: {$container_width} !important; margin: 0 auto !important; }
            ";
        }

        $accent_color = $this->get_setting( 'accent_color', '#2271b1' );
        if ( $accent_color !== '#2271b1' ) {
            $css .= "
                a { color: {$accent_color}; }
                a:hover, a:active, a:focus { color: {$accent_color}; }
                .button-primary { background: {$accent_color} !important; border-color: {$accent_color} !important; }
                .button-primary:hover, .button-primary:focus, .button-primary:active { background: {$accent_color} !important; opacity: 0.9 !important; border-color: {$accent_color} !important; }
                .wp-core-ui .button-primary { background: {$accent_color} !important; border-color: {$accent_color} !important; }
            ";
        }

        $shadow_intensity = $this->get_setting( 'shadow_intensity', 'none' );
        if ( $shadow_intensity === 'light' ) {
            $css .= ".postbox, .welcome-panel, .card, table.widefat { box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important; border: none !important; }";
        } elseif ( $shadow_intensity === 'strong' ) {
            $css .= ".postbox, .welcome-panel, .card, table.widefat { box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important; border: none !important; }";
        }

        // Hide Branding
        $hide_wp_branding = $this->get_setting( 'hide_wp_branding', '0' );
        if ( $hide_wp_branding === '1' ) {
            $css .= "
                #wpadminbar #wp-admin-bar-wp-logo { display: none !important; }
                #footer-upgrade { display: none !important; }
            ";
        }

        $css .= '</style>';
        echo $css;
	}

    public function admin_footer_text( $text ) {
        $custom_footer = $this->get_setting( 'footer_text' );
        if ( ! empty( $custom_footer ) ) {
            return wp_kses_post( $custom_footer );
        }
        return $text;
    }

    public function wp_before_admin_bar_render() {
        $admin_logo = $this->get_setting( 'admin_logo' );
        if ( ! empty( $admin_logo ) ) {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('wp-logo');
            $wp_admin_bar->add_menu(array(
                'id' => 'custom-admin-logo',
                'title' => '<img src="' . esc_url($admin_logo) . '" style="max-height: 20px; vertical-align: middle; padding: 6px 0;" alt="Logo" />',
                'href' => home_url(),
                'meta' => array('class' => 'custom-logo-menu')
            ));
        }
    }

	private function get_setting( $key, $default = '' ) {
		return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $default;
	}

}
