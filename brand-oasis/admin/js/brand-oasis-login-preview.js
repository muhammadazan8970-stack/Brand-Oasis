(function( $ ) {
	'use strict';

    $(document).ready(function() {
        var $iframe = $('#bo-login-preview-iframe');

        function updatePreview(target, prop, value, unit, type) {
            var $iframeDoc = $iframe.contents();
            if (!$iframeDoc.length) return;

            if (type === 'css') {
                if (prop) {
                    var val = value + (unit ? unit : '');
                    $iframeDoc.find(target).css(prop, val);
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
                    for (var key in data) {
                        var $input = $('[name="brand_oasis_login_settings[' + key + ']"]');
                        if ($input.length) {
                            $input.val(data[key]);
                            // trigger change for preview and color pickers
                            if ($input.hasClass('bo-color-picker')) {
                                $input.wpColorPicker('color', data[key]);
                            } else {
                                $input.trigger('change');
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
