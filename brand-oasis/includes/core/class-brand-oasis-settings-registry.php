<?php

/**
 * Centralized settings registry and schema for the plugin.
 */
class Brand_Oasis_Settings_Registry {

    /**
     * Get the schema for the Login Customizer settings.
     *
     * @return array Array of fields defining the login settings.
     */
    public static function get_login_schema() {
        return array(
            // Logo
            array( 'id' => 'logo_url', 'type' => 'url', 'default' => '' ),
            array( 'id' => 'logo_width', 'type' => 'number', 'default' => '84' ),
            array( 'id' => 'logo_height', 'type' => 'number', 'default' => '84' ),
            array( 'id' => 'logo_spacing', 'type' => 'number', 'default' => '25' ),

            // Background
            array( 'id' => 'bg_color', 'type' => 'color', 'default' => '#f1f1f1' ),
            array( 'id' => 'bg_image', 'type' => 'url', 'default' => '' ),
            array( 'id' => 'bg_position', 'type' => 'select', 'default' => 'center center' ),
            array( 'id' => 'bg_size', 'type' => 'select', 'default' => 'cover' ),
            array( 'id' => 'bg_repeat', 'type' => 'select', 'default' => 'no-repeat' ),
            array( 'id' => 'overlay_color', 'type' => 'color', 'default' => '' ),
            array( 'id' => 'overlay_opacity', 'type' => 'number', 'default' => '0.5' ),
            array( 'id' => 'enable_gradient_bg', 'type' => 'checkbox', 'default' => '0' ),
            array( 'id' => 'gradient_type', 'type' => 'select', 'default' => 'linear' ),
            array( 'id' => 'gradient_angle', 'type' => 'number', 'default' => '135' ),
            array( 'id' => 'gradient_color_1', 'type' => 'color', 'default' => '#667eea' ),
            array( 'id' => 'gradient_color_2', 'type' => 'color', 'default' => '#764ba2' ),

            // Form
            array( 'id' => 'form_bg', 'type' => 'color', 'default' => '#ffffff' ),
            array( 'id' => 'form_opacity', 'type' => 'number', 'default' => '1' ),
            array( 'id' => 'form_radius', 'type' => 'number', 'default' => '0' ),
            array( 'id' => 'form_shadow', 'type' => 'text', 'default' => '0 1px 3px rgba(0,0,0,.13)' ),
            array( 'id' => 'form_border_color', 'type' => 'color', 'default' => 'transparent' ),
            array( 'id' => 'form_border_width', 'type' => 'number', 'default' => '0' ),
            array( 'id' => 'input_radius', 'type' => 'number', 'default' => '0' ),

            // Button
            array( 'id' => 'btn_bg', 'type' => 'color', 'default' => '#2271b1' ),
            array( 'id' => 'btn_hover_bg', 'type' => 'color', 'default' => '#135e96' ),
            array( 'id' => 'btn_color', 'type' => 'color', 'default' => '#ffffff' ),
            array( 'id' => 'btn_hover_color', 'type' => 'color', 'default' => '#ffffff' ),
            array( 'id' => 'btn_font_weight', 'type' => 'select', 'default' => '400' ),
            array( 'id' => 'btn_text_transform', 'type' => 'select', 'default' => 'none' ),
            array( 'id' => 'btn_letter_spacing', 'type' => 'number', 'default' => '0' ),
            array( 'id' => 'btn_radius', 'type' => 'number', 'default' => '3' ),
            array( 'id' => 'btn_border_color', 'type' => 'color', 'default' => 'transparent' ),
            array( 'id' => 'btn_border_width', 'type' => 'number', 'default' => '0' ),
            array( 'id' => 'btn_glow_color', 'type' => 'color', 'default' => '' ),
            array( 'id' => 'btn_animation', 'type' => 'select', 'default' => 'none' ),
            array( 'id' => 'btn_width', 'type' => 'text', 'default' => 'auto' ),
            array( 'id' => 'btn_padding', 'type' => 'text', 'default' => '0 10px 1px' ),
            array( 'id' => 'btn_transition_speed', 'type' => 'number', 'default' => '0.3' ),

            // Typography
            array( 'id' => 'font_family', 'type' => 'text', 'default' => '' ),
            array( 'id' => 'text_color', 'type' => 'color', 'default' => '#3c434a' ),
            array( 'id' => 'link_color', 'type' => 'color', 'default' => '#2271b1' ),
            array( 'id' => 'link_hover_color', 'type' => 'color', 'default' => '#135e96' ),

            // Layout
            array( 'id' => 'form_width', 'type' => 'number', 'default' => '320' ),
            array( 'id' => 'alignment', 'type' => 'select', 'default' => 'center' ),
        );
    }

    /**
     * Get the schema for the Admin Dashboard settings.
     *
     * @return array Array of fields defining the admin dashboard settings.
     */
    public static function get_admin_schema() {
        return array(
            // Colors
            array( 'id' => 'sidebar_bg', 'type' => 'color', 'default' => '#1d2327' ),
            array( 'id' => 'sidebar_hover', 'type' => 'color', 'default' => '#2c3338' ),
            array( 'id' => 'sidebar_active', 'type' => 'color', 'default' => '#2271b1' ),
            array( 'id' => 'sidebar_text', 'type' => 'color', 'default' => '#f0f0f1' ),
            array( 'id' => 'topbar_bg', 'type' => 'color', 'default' => '#1d2327' ),
            array( 'id' => 'content_bg', 'type' => 'color', 'default' => '#f0f0f1' ),

            // Typography
            array( 'id' => 'font_family', 'type' => 'text', 'default' => '' ),
            array( 'id' => 'font_size', 'type' => 'number', 'default' => '13' ),

            // Branding
            array( 'id' => 'admin_logo', 'type' => 'url', 'default' => '' ),
            array( 'id' => 'footer_text', 'type' => 'textarea', 'default' => '' ),
            array( 'id' => 'hide_wp_branding', 'type' => 'checkbox', 'default' => '0' ),

            // UI Options
            array( 'id' => 'rounded_ui', 'type' => 'checkbox', 'default' => '0' ),
            array( 'id' => 'shadow_intensity', 'type' => 'select', 'default' => 'none' ),
            array( 'id' => 'button_radius', 'type' => 'number', 'default' => '4' ),
            array( 'id' => 'container_width', 'type' => 'text', 'default' => '100%' ),
            array( 'id' => 'accent_color', 'type' => 'color', 'default' => '#2271b1' ),

            // Dark Mode
            array( 'id' => 'enable_dark_mode', 'type' => 'checkbox', 'default' => '0' ),
            array( 'id' => 'dark_bg', 'type' => 'color', 'default' => '#121212' ),
            array( 'id' => 'dark_surface', 'type' => 'color', 'default' => '#1e1e1e' ),
            array( 'id' => 'dark_text', 'type' => 'color', 'default' => '#e0e0e0' ),
        );
    }

    /**
     * Helper to map field IDs to their types based on a provided schema.
     *
     * @param array $schema The schema array.
     * @return array Array mapping field IDs to their types.
     */
    public static function get_schema_types( $schema ) {
        $types = array();
        foreach ( $schema as $field ) {
            if ( isset( $field['id'] ) && isset( $field['type'] ) ) {
                $types[ $field['id'] ] = $field['type'];
            }
        }
        return $types;
    }

}
