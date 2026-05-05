<?php
/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 */

$settings = get_option( 'brand_oasis_admin_settings', array() );

function get_bo_admin_setting( $settings, $key, $default = '' ) {
    return isset( $settings[$key] ) ? esc_attr( $settings[$key] ) : $default;
}

?>

<div class="wrap brand-oasis-wrap">
	<h1><?php esc_html_e( 'Brand Oasis - Admin Dashboard Customizer', 'brand-oasis' ); ?></h1>

    <div class="brand-oasis-container full-width">
        <div class="brand-oasis-actions" style="margin-bottom: 20px;">
            <button type="button" class="button button-secondary" id="bo-export-admin">Export</button>
            <button type="button" class="button button-secondary" id="bo-import-admin">Import</button>
            <input type="file" id="bo-import-file-admin" style="display:none;" accept=".json" />
        </div>

        <form method="post" action="options.php">
            <?php settings_fields( 'brand_oasis_admin_options' ); ?>

            <h2 class="nav-tab-wrapper">
                <a href="#tab-admin-colors" class="nav-tab nav-tab-active">Colors</a>
                <a href="#tab-admin-typography" class="nav-tab">Typography</a>
                <a href="#tab-admin-branding" class="nav-tab">Branding</a>
                <a href="#tab-admin-ui" class="nav-tab">UI Options</a>
                <a href="#tab-admin-dark" class="nav-tab">Dark Mode</a>
            </h2>

            <div class="tab-content active" id="tab-admin-colors">
                <table class="form-table">
                    <tr>
                        <th scope="row">Admin Sidebar Background</th>
                        <td><input type="text" name="brand_oasis_admin_settings[sidebar_bg]" value="<?php echo get_bo_admin_setting($settings, 'sidebar_bg', '#1d2327'); ?>" class="bo-color-picker" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Sidebar Hover Color</th>
                        <td><input type="text" name="brand_oasis_admin_settings[sidebar_hover]" value="<?php echo get_bo_admin_setting($settings, 'sidebar_hover', '#2c3338'); ?>" class="bo-color-picker" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Sidebar Active Color</th>
                        <td><input type="text" name="brand_oasis_admin_settings[sidebar_active]" value="<?php echo get_bo_admin_setting($settings, 'sidebar_active', '#2271b1'); ?>" class="bo-color-picker" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Sidebar Text Color</th>
                        <td><input type="text" name="brand_oasis_admin_settings[sidebar_text]" value="<?php echo get_bo_admin_setting($settings, 'sidebar_text', '#f0f0f1'); ?>" class="bo-color-picker" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Top Bar Background</th>
                        <td><input type="text" name="brand_oasis_admin_settings[topbar_bg]" value="<?php echo get_bo_admin_setting($settings, 'topbar_bg', '#1d2327'); ?>" class="bo-color-picker" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Content Background</th>
                        <td><input type="text" name="brand_oasis_admin_settings[content_bg]" value="<?php echo get_bo_admin_setting($settings, 'content_bg', '#f0f0f1'); ?>" class="bo-color-picker" /></td>
                    </tr>
                </table>
            </div>

            <div class="tab-content" id="tab-admin-typography">
                <table class="form-table">
                    <tr>
                        <th scope="row">Font Family</th>
                        <td>
                            <input type="text" name="brand_oasis_admin_settings[font_family]" value="<?php echo get_bo_admin_setting($settings, 'font_family'); ?>" placeholder="e.g. 'Inter', sans-serif" class="regular-text" />
                            <p class="description">You can use standard fonts or Google Fonts.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Base Font Size (px)</th>
                        <td><input type="number" name="brand_oasis_admin_settings[font_size]" value="<?php echo get_bo_admin_setting($settings, 'font_size', '13'); ?>" class="regular-text" /></td>
                    </tr>
                </table>
            </div>

            <div class="tab-content" id="tab-admin-branding">
                <table class="form-table">
                    <tr>
                        <th scope="row">Custom Admin Logo</th>
                        <td>
                            <input type="hidden" name="brand_oasis_admin_settings[admin_logo]" id="admin_logo" value="<?php echo get_bo_admin_setting($settings, 'admin_logo'); ?>" />
                            <div class="image-preview-wrapper">
                                <img src="<?php echo get_bo_admin_setting($settings, 'admin_logo'); ?>" style="max-width: 100px; display: <?php echo get_bo_admin_setting($settings, 'admin_logo') ? 'block' : 'none'; ?>;" />
                            </div>
                            <button type="button" class="button bo-upload-image">Select Image</button>
                            <button type="button" class="button bo-remove-image">Remove</button>
                            <p class="description">Replaces the WordPress logo in the top left corner.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Custom Footer Text</th>
                        <td><textarea name="brand_oasis_admin_settings[footer_text]" class="large-text"><?php echo get_bo_admin_setting($settings, 'footer_text'); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row">Hide WordPress Branding</th>
                        <td>
                            <label>
                                <input type="checkbox" name="brand_oasis_admin_settings[hide_wp_branding]" value="1" <?php checked( get_bo_admin_setting($settings, 'hide_wp_branding'), '1' ); ?> />
                                Yes
                            </label>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="tab-content" id="tab-admin-ui">
                <table class="form-table">
                    <tr>
                        <th scope="row">Rounded Admin UI</th>
                        <td>
                            <label>
                                <input type="checkbox" name="brand_oasis_admin_settings[rounded_ui]" value="1" <?php checked( get_bo_admin_setting($settings, 'rounded_ui'), '1' ); ?> />
                                Apply border-radius to cards and buttons
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Shadow Intensity</th>
                        <td>
                            <select name="brand_oasis_admin_settings[shadow_intensity]">
                                <option value="none" <?php selected( get_bo_admin_setting($settings, 'shadow_intensity'), 'none' ); ?>>None</option>
                                <option value="light" <?php selected( get_bo_admin_setting($settings, 'shadow_intensity'), 'light' ); ?>>Light</option>
                                <option value="strong" <?php selected( get_bo_admin_setting($settings, 'shadow_intensity'), 'strong' ); ?>>Strong</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Sidebar Width (px)</th>
                        <td><input type="number" name="brand_oasis_admin_settings[sidebar_width]" value="<?php echo get_bo_admin_setting($settings, 'sidebar_width', '160'); ?>" class="regular-text" /></td>
                    </tr>
                </table>
            </div>

            <div class="tab-content" id="tab-admin-dark">
                <table class="form-table">
                    <tr>
                        <th scope="row">Enable Dark Mode</th>
                        <td>
                            <label>
                                <input type="checkbox" name="brand_oasis_admin_settings[enable_dark_mode]" value="1" <?php checked( get_bo_admin_setting($settings, 'enable_dark_mode'), '1' ); ?> />
                                Enable Dark Mode
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Dark Background Color</th>
                        <td><input type="text" name="brand_oasis_admin_settings[dark_bg]" value="<?php echo get_bo_admin_setting($settings, 'dark_bg', '#121212'); ?>" class="bo-color-picker" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Dark Surface Color (Cards)</th>
                        <td><input type="text" name="brand_oasis_admin_settings[dark_surface]" value="<?php echo get_bo_admin_setting($settings, 'dark_surface', '#1e1e1e'); ?>" class="bo-color-picker" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Dark Text Color</th>
                        <td><input type="text" name="brand_oasis_admin_settings[dark_text]" value="<?php echo get_bo_admin_setting($settings, 'dark_text', '#e0e0e0'); ?>" class="bo-color-picker" /></td>
                    </tr>
                </table>
            </div>

            <?php submit_button(); ?>
        </form>
    </div>
</div>
