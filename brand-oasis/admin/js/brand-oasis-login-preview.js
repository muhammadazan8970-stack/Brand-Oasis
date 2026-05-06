(function( $ ) {
	'use strict';

    $(document).ready(function() {
        var $iframe = $('#bo-login-preview-iframe');

        function updateGradientBackground() {
            var $iframeDoc = $iframe.contents();
            if (!$iframeDoc.length) return;

            var enableGrad = $('input[name="brand_oasis_login_settings[enable_gradient_bg]"]').is(':checked');
            var gradType = $('select[name="brand_oasis_login_settings[gradient_type]"]').val();
            var gradAngle = $('input[name="brand_oasis_login_settings[gradient_angle]"]').val();
            var gradC1 = $('input[name="brand_oasis_login_settings[gradient_color_1]"]').val();
            var gradC2 = $('input[name="brand_oasis_login_settings[gradient_color_2]"]').val();
            var bgColor = $('input[name="brand_oasis_login_settings[bg_color]"]').val();
            var bgImage = $('input[name="brand_oasis_login_settings[bg_image]"]').val();

            if (enableGrad) {
                var gradStr = '';
                if (gradType === 'radial') {
                    gradStr = 'radial-gradient(circle, ' + gradC1 + ', ' + gradC2 + ')';
                } else {
                    gradStr = 'linear-gradient(' + gradAngle + 'deg, ' + gradC1 + ', ' + gradC2 + ')';
                }
                $iframeDoc.find('body.login').css('background-image', gradStr);
            } else if (bgImage) {
                $iframeDoc.find('body.login').css('background-image', 'url(' + bgImage + ')');
            } else {
                $iframeDoc.find('body.login').css('background-image', 'none');
                $iframeDoc.find('body.login').css('background-color', bgColor);
            }
        }

        // Toggle Gradient UI visibility
        function toggleGradientUI() {
            if ($('input[name="brand_oasis_login_settings[enable_gradient_bg]"]').is(':checked')) {
                $('.bo-gradient-settings').show();
            } else {
                $('.bo-gradient-settings').hide();
            }
        }

        // Initial setup
        toggleGradientUI();

        function updatePreview(target, prop, value, unit, type) {
            var $iframeDoc = $iframe.contents();
            if (!$iframeDoc.length) return;

            if (type === 'css') {
                if (prop) {
                    var val = value + (unit ? unit : '');
                    $iframeDoc.find(target).css(prop, val);
                }
            } else if (type === 'bg-gradient' || type === 'bg-color' || type === 'bg-image') {
                updateGradientBackground();
                if (type === 'bg-gradient') {
                    toggleGradientUI();
                }
            } else if (type === 'image') {
                if (value) {
                    $iframeDoc.find(target).css('background-image', 'url(' + value + ')');
                } else {
                    $iframeDoc.find(target).css('background-image', 'none');
                }
            } else if (type === 'bg-image') {
                if (value) {
                    $iframeDoc.find(target).css('background-image', 'url(' + value + ')');
                } else {
                    $iframeDoc.find(target).css('background-image', 'none');
                }
            } else if (type === 'alignment') {
                var alignCss = {};
                if (value === 'left') {
                    alignCss = { 'margin-left': '5%', 'margin-right': 'auto' };
                } else if (value === 'right') {
                    alignCss = { 'margin-right': '5%', 'margin-left': 'auto' };
                } else {
                    alignCss = { 'margin-left': 'auto', 'margin-right': 'auto' };
                }
                $iframeDoc.find('#login').css(alignCss);
            }
        }

        // Handle standard inputs
        $('.bo-preview-trigger').on('input change', function() {
            var $this = $(this);
            var target = $this.data('preview-target');
            var prop = $this.data('preview-prop');
            var unit = $this.data('preview-unit');
            var type = $this.data('preview-type');
            var value = $this.val();

            updatePreview(target, prop, value, unit, type);
        });

        // Intercept color picker changes
        if (typeof $.wp === 'object' && typeof $.wp.wpColorPicker === 'function') {
            $('.bo-preview-trigger.bo-color-picker').wpColorPicker({
                change: function(event, ui) {
                    var $this = $(this);
                    var target = $this.data('preview-target');
                    var prop = $this.data('preview-prop');
                    var value = ui.color.toString();
                    var type = $this.data('preview-type');
                    updatePreview(target, prop, value, '', type);
                }
            });
        }

        // Preset Gallery Filtering
        $('.bo-category-pill').on('click', function() {
            var filter = $(this).data('filter');
            $('.bo-category-pill').removeClass('active');
            $(this).addClass('active');

            if (filter === 'all') {
                $('.bo-preset-item').show();
            } else {
                $('.bo-preset-item').hide();
                $('.bo-preset-item[data-category="' + filter + '"]').show();
            }
        });

        $('#bo-preset-search').on('keyup', function() {
            var query = $(this).val().toLowerCase();
            $('.bo-category-pill[data-filter="all"]').click(); // Reset category filter on search

            $('.bo-preset-item').each(function() {
                var name = $(this).data('name').toLowerCase();
                if (name.indexOf(query) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Apply Preset via AJAX
        $('.bo-apply-preset').on('click', function(e) {
            e.preventDefault();
            var preset = $(this).data('preset');
            var btn = $(this);
            var originalText = btn.text();

            btn.text('Applying...');

            $.post(brandOasisAdmin.ajaxurl, {
                action: 'brand_oasis_apply_preset',
                nonce: brandOasisAdmin.nonce,
                preset: preset
            }, function(response) {
                if (response.success) {
                    var data = response.data;
                    var excludeKeys = ['template_version', 'plugin_version', 'template_name', 'template_category', 'thumbnail'];

                    // Reset checkbox
                    $('input[name="brand_oasis_login_settings[enable_gradient_bg]"]').prop('checked', false).trigger('change');

                    for (var key in data) {
                        if (excludeKeys.indexOf(key) !== -1) continue;

                        var $input = $('[name="brand_oasis_login_settings[' + key + ']"]');
                        if ($input.length) {
                            if ($input.is(':checkbox')) {
                                $input.prop('checked', data[key] == '1' || data[key] === true).trigger('change');
                            } else {
                                $input.val(data[key]);
                                // trigger change for preview and color pickers
                                if ($input.hasClass('bo-color-picker')) {
                                    $input.wpColorPicker('color', data[key]);
                                } else {
                                    $input.trigger('change');
                                }
                            }
                        }
                    }
                    btn.text('Applied!');
                    setTimeout(function() { btn.text(originalText); }, 2000);
                } else {
                    alert('Error applying preset.');
                    btn.text(originalText);
                }
            });
        });

    });

})( jQuery );
