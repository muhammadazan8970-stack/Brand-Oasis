<?php
/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 */

$settings = get_option( 'brand_oasis_login_settings', array() );

function get_bo_login_setting( $settings, $key, $default = '' ) {
    return isset( $settings[$key] ) ? esc_attr( $settings[$key] ) : $default;
}

?>

<div class="wrap brand-oasis-wrap">
	<h1><?php esc_html_e( 'Brand Oasis - Login Customizer', 'brand-oasis' ); ?></h1>

    <div class="brand-oasis-container">

        <div class="brand-oasis-sidebar">
            <div class="brand-oasis-actions">
                <button type="button" class="button button-secondary" id="bo-export-login">Export</button>
                <button type="button" class="button button-secondary" id="bo-import-login">Import</button>
                <input type="file" id="bo-import-file" style="display:none;" accept=".json" />
            </div>

            <form method="post" action="options.php" id="brand-oasis-login-form">
                <?php settings_fields( 'brand_oasis_login_options' ); ?>

                <h2 class="nav-tab-wrapper">
                    <a href="#tab-logo" class="nav-tab nav-tab-active">Logo</a>
                    <a href="#tab-background" class="nav-tab">Background</a>
                    <a href="#tab-form" class="nav-tab">Form</a>
                    <a href="#tab-typography" class="nav-tab">Typography</a>
                    <a href="#tab-layout" class="nav-tab">Layout</a>
                    <a href="#tab-presets" class="nav-tab">Presets</a>
                </h2>

                <div class="tab-content active" id="tab-logo">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Logo Image</th>
                            <td>
                                <input type="hidden" name="brand_oasis_login_settings[logo_url]" id="logo_url" value="<?php echo get_bo_login_setting($settings, 'logo_url'); ?>" class="bo-preview-trigger" data-preview-type="image" data-preview-target=".login h1 a" />
                                <div class="image-preview-wrapper">
                                    <img src="<?php echo get_bo_login_setting($settings, 'logo_url'); ?>" style="max-width: 100px; display: <?php echo get_bo_login_setting($settings, 'logo_url') ? 'block' : 'none'; ?>;" />
                                </div>
                                <button type="button" class="button bo-upload-image">Select Image</button>
                                <button type="button" class="button bo-remove-image">Remove</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Logo Width (px)</th>
                            <td><input type="number" name="brand_oasis_login_settings[logo_width]" value="<?php echo get_bo_login_setting($settings, 'logo_width', '84'); ?>" class="regular-text bo-preview-trigger" data-preview-type="css" data-preview-target=".login h1 a" data-preview-prop="width" data-preview-unit="px" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Logo Height (px)</th>
                            <td><input type="number" name="brand_oasis_login_settings[logo_height]" value="<?php echo get_bo_login_setting($settings, 'logo_height', '84'); ?>" class="regular-text bo-preview-trigger" data-preview-type="css" data-preview-target=".login h1 a" data-preview-prop="height" data-preview-unit="px" data-preview-css="background-size" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Logo Spacing Bottom (px)</th>
                            <td><input type="number" name="brand_oasis_login_settings[logo_spacing]" value="<?php echo get_bo_login_setting($settings, 'logo_spacing', '25'); ?>" class="regular-text bo-preview-trigger" data-preview-type="css" data-preview-target=".login h1 a" data-preview-prop="margin-bottom" data-preview-unit="px" /></td>
                        </tr>
                    </table>
                </div>

                <div class="tab-content" id="tab-background">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Background Color</th>
                            <td><input type="text" name="brand_oasis_login_settings[bg_color]" value="<?php echo get_bo_login_setting($settings, 'bg_color', '#f1f1f1'); ?>" class="bo-color-picker bo-preview-trigger" data-preview-type="css" data-preview-target="body.login" data-preview-prop="background-color" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Background Image</th>
                            <td>
                                <input type="hidden" name="brand_oasis_login_settings[bg_image]" id="bg_image" value="<?php echo get_bo_login_setting($settings, 'bg_image'); ?>" class="bo-preview-trigger" data-preview-type="bg-image" data-preview-target="body.login" />
                                <div class="image-preview-wrapper">
                                    <img src="<?php echo get_bo_login_setting($settings, 'bg_image'); ?>" style="max-width: 100px; display: <?php echo get_bo_login_setting($settings, 'bg_image') ? 'block' : 'none'; ?>;" />
                                </div>
                                <button type="button" class="button bo-upload-image">Select Image</button>
                                <button type="button" class="button bo-remove-image">Remove</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Background Position</th>
                            <td>
                                <select name="brand_oasis_login_settings[bg_position]" class="bo-preview-trigger" data-preview-type="css" data-preview-target="body.login" data-preview-prop="background-position">
                                    <option value="center center" <?php selected( get_bo_login_setting($settings, 'bg_position'), 'center center' ); ?>>Center Center</option>
                                    <option value="top center" <?php selected( get_bo_login_setting($settings, 'bg_position'), 'top center' ); ?>>Top Center</option>
                                    <option value="bottom center" <?php selected( get_bo_login_setting($settings, 'bg_position'), 'bottom center' ); ?>>Bottom Center</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Background Size</th>
                            <td>
                                <select name="brand_oasis_login_settings[bg_size]" class="bo-preview-trigger" data-preview-type="css" data-preview-target="body.login" data-preview-prop="background-size">
                                    <option value="cover" <?php selected( get_bo_login_setting($settings, 'bg_size'), 'cover' ); ?>>Cover</option>
                                    <option value="contain" <?php selected( get_bo_login_setting($settings, 'bg_size'), 'contain' ); ?>>Contain</option>
                                    <option value="auto" <?php selected( get_bo_login_setting($settings, 'bg_size'), 'auto' ); ?>>Auto</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Background Repeat</th>
                            <td>
                                <select name="brand_oasis_login_settings[bg_repeat]" class="bo-preview-trigger" data-preview-type="css" data-preview-target="body.login" data-preview-prop="background-repeat">
                                    <option value="no-repeat" <?php selected( get_bo_login_setting($settings, 'bg_repeat'), 'no-repeat' ); ?>>No Repeat</option>
                                    <option value="repeat" <?php selected( get_bo_login_setting($settings, 'bg_repeat'), 'repeat' ); ?>>Repeat</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Overlay Color</th>
                            <td><input type="text" name="brand_oasis_login_settings[overlay_color]" value="<?php echo get_bo_login_setting($settings, 'overlay_color'); ?>" class="bo-color-picker" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Overlay Opacity (0-1)</th>
                            <td><input type="number" step="0.1" min="0" max="1" name="brand_oasis_login_settings[overlay_opacity]" value="<?php echo get_bo_login_setting($settings, 'overlay_opacity', '0.5'); ?>" class="regular-text" /></td>
                        </tr>
                    </table>
                </div>

                <div class="tab-content" id="tab-form">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Form Background Color</th>
                            <td><input type="text" name="brand_oasis_login_settings[form_bg]" value="<?php echo get_bo_login_setting($settings, 'form_bg', '#ffffff'); ?>" class="bo-color-picker bo-preview-trigger" data-preview-type="css" data-preview-target=".login form" data-preview-prop="background-color" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Form Transparency (0-1)</th>
                            <td><input type="number" step="0.05" min="0" max="1" name="brand_oasis_login_settings[form_opacity]" value="<?php echo get_bo_login_setting($settings, 'form_opacity', '1'); ?>" class="regular-text bo-preview-trigger" data-preview-type="css" data-preview-target=".login form" data-preview-prop="opacity" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Border Radius (px)</th>
                            <td><input type="number" name="brand_oasis_login_settings[form_radius]" value="<?php echo get_bo_login_setting($settings, 'form_radius', '0'); ?>" class="regular-text bo-preview-trigger" data-preview-type="css" data-preview-target=".login form" data-preview-prop="border-radius" data-preview-unit="px" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Box Shadow</th>
                            <td><input type="text" name="brand_oasis_login_settings[form_shadow]" value="<?php echo get_bo_login_setting($settings, 'form_shadow', '0 1px 3px rgba(0,0,0,.13)'); ?>" class="regular-text bo-preview-trigger" data-preview-type="css" data-preview-target=".login form" data-preview-prop="box-shadow" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Button Background Color</th>
                            <td><input type="text" name="brand_oasis_login_settings[btn_bg]" value="<?php echo get_bo_login_setting($settings, 'btn_bg', '#2271b1'); ?>" class="bo-color-picker bo-preview-trigger" data-preview-type="css" data-preview-target=".wp-core-ui .button-primary" data-preview-prop="background-color" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Button Text Color</th>
                            <td><input type="text" name="brand_oasis_login_settings[btn_color]" value="<?php echo get_bo_login_setting($settings, 'btn_color', '#ffffff'); ?>" class="bo-color-picker bo-preview-trigger" data-preview-type="css" data-preview-target=".wp-core-ui .button-primary" data-preview-prop="color" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Button Border Radius (px)</th>
                            <td><input type="number" name="brand_oasis_login_settings[btn_radius]" value="<?php echo get_bo_login_setting($settings, 'btn_radius', '3'); ?>" class="regular-text bo-preview-trigger" data-preview-type="css" data-preview-target=".wp-core-ui .button-primary" data-preview-prop="border-radius" data-preview-unit="px" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Input Border Radius (px)</th>
                            <td><input type="number" name="brand_oasis_login_settings[input_radius]" value="<?php echo get_bo_login_setting($settings, 'input_radius', '0'); ?>" class="regular-text bo-preview-trigger" data-preview-type="css" data-preview-target=".login input[type=text], .login input[type=password]" data-preview-prop="border-radius" data-preview-unit="px" /></td>
                        </tr>
                    </table>
                </div>

                <div class="tab-content" id="tab-typography">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Font Family</th>
                            <td>
                                <input type="text" name="brand_oasis_login_settings[font_family]" value="<?php echo get_bo_login_setting($settings, 'font_family'); ?>" placeholder="e.g. 'Roboto', sans-serif" class="regular-text" />
                                <p class="description">You can use standard fonts or Google Fonts.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Text Color</th>
                            <td><input type="text" name="brand_oasis_login_settings[text_color]" value="<?php echo get_bo_login_setting($settings, 'text_color', '#3c434a'); ?>" class="bo-color-picker bo-preview-trigger" data-preview-type="css" data-preview-target=".login label" data-preview-prop="color" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Link Color</th>
                            <td><input type="text" name="brand_oasis_login_settings[link_color]" value="<?php echo get_bo_login_setting($settings, 'link_color', '#2271b1'); ?>" class="bo-color-picker bo-preview-trigger" data-preview-type="css" data-preview-target=".login #nav a, .login #backtoblog a" data-preview-prop="color" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Link Hover Color</th>
                            <td><input type="text" name="brand_oasis_login_settings[link_hover_color]" value="<?php echo get_bo_login_setting($settings, 'link_hover_color', '#135e96'); ?>" class="bo-color-picker" /></td>
                        </tr>
                    </table>
                </div>

                <div class="tab-content" id="tab-layout">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Form Width (px)</th>
                            <td><input type="number" name="brand_oasis_login_settings[form_width]" value="<?php echo get_bo_login_setting($settings, 'form_width', '320'); ?>" class="regular-text bo-preview-trigger" data-preview-type="css" data-preview-target="#login" data-preview-prop="width" data-preview-unit="px" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Alignment</th>
                            <td>
                                <select name="brand_oasis_login_settings[alignment]" class="bo-preview-trigger" data-preview-type="alignment">
                                    <option value="center" <?php selected( get_bo_login_setting($settings, 'alignment'), 'center' ); ?>>Center</option>
                                    <option value="left" <?php selected( get_bo_login_setting($settings, 'alignment'), 'left' ); ?>>Left</option>
                                    <option value="right" <?php selected( get_bo_login_setting($settings, 'alignment'), 'right' ); ?>>Right</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="tab-content" id="tab-presets">
                    <div class="bo-presets-grid">
                        <div class="bo-preset-card">
                            <h3>Travel Theme</h3>
                            <button type="button" class="button bo-apply-preset" data-preset="travel">Apply Preset</button>
                        </div>
                        <div class="bo-preset-card">
                            <h3>Orange Flat</h3>
                            <button type="button" class="button bo-apply-preset" data-preset="orange">Apply Preset</button>
                        </div>
                        <div class="bo-preset-card">
                            <h3>Glassmorphism</h3>
                            <button type="button" class="button bo-apply-preset" data-preset="glass">Apply Preset</button>
                        </div>
                        <div class="bo-preset-card">
                            <h3>Dark Modern</h3>
                            <button type="button" class="button bo-apply-preset" data-preset="dark">Apply Preset</button>
                        </div>
                    </div>
                </div>

                <?php submit_button(); ?>
            </form>
        </div>

        <div class="brand-oasis-preview">
            <h3>Live Preview</h3>
            <div class="iframe-container">
                <iframe src="<?php echo wp_login_url(); ?>" id="bo-login-preview-iframe" title="Login Preview"></iframe>
            </div>
        </div>

    </div>
</div>
