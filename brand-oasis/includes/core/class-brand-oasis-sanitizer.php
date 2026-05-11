<?php

/**
 * Handles all sanitization logic for the plugin settings.
 */
class Brand_Oasis_Sanitizer {

    /**
     * Sanitize a field based on its type.
     *
     * @param string $type  The type of field to sanitize (e.g., text, url, color).
     * @param mixed  $value The value to sanitize.
     * @return mixed Sanitized value.
     */
    public static function sanitize( $type, $value ) {
        if ( is_array( $value ) ) {
            $sanitized = array();
            foreach ( $value as $key => $val ) {
                $sanitized[$key] = self::sanitize( $type, $val );
            }
            return $sanitized;
        }

        switch ( $type ) {
            case 'text':
                return sanitize_text_field( $value );

            case 'url':
            case 'image': // often URLs
                return esc_url_raw( $value );

            case 'color':
                // Allow empty string to reset the color, otherwise sanitize as hex or fallback
                if ( empty( $value ) ) {
                    return '';
                }
                $color = sanitize_hex_color( $value );
                if ( empty( $color ) && function_exists('sanitize_text_field') ) {
                    // Fallback for rgb/rgba colors if passed (e.g. glassmorphism presets)
                    return sanitize_text_field( $value );
                }
                return $color;

            case 'textarea':
                return wp_kses_post( $value );

            case 'checkbox':
                return ( ! empty( $value ) && $value === '1' ) ? '1' : '0';

            case 'number':
                // Use floatval to allow decimal numbers like opacities
                return is_numeric( $value ) ? floatval( $value ) : 0;

            case 'select':
                // Select values are typically keys or short strings, so sanitize as text
                return sanitize_text_field( $value );

            default:
                // Graceful fallback if type is unknown to prevent fatal errors
                return sanitize_text_field( $value );
        }
    }

}
