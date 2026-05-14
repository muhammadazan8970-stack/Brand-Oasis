(function( $, window ) {
	'use strict';

    $(document).ready(function() {

        // Initialize Renderer
        if (window.BrandOasisPreviewRenderer) {
            window.BrandOasisPreviewRenderer.init();
        }

        var debounceTimer;

        // Gathers all form fields and asks renderer to update the iframe
        function triggerUpdate() {
            var formData = {};

            // Serialize settings from the form into a clean object
            $('#brand-oasis-login-form').serializeArray().forEach(function(item) {
                // remove the brand_oasis_login_settings[...] wrapper for cleaner passing
                var match = item.name.match(/\[(.*?)\]/);
                if (match && match[1]) {
                    formData[match[1]] = item.value;
                }
            });

            // Special handling for unchecked checkboxes which serializeArray ignores
            $('#brand-oasis-login-form input[type="checkbox"]').each(function() {
                var match = this.name.match(/\[(.*?)\]/);
                if (match && match[1] && !this.checked) {
                    formData[match[1]] = '0';
                }
            });

            if (window.BrandOasisPreviewRenderer) {
                window.BrandOasisPreviewRenderer.update(formData);
            }
        }

        function debouncedUpdate() {
            clearTimeout(debounceTimer);
            // Quick 50ms debounce to batch multiple instant changes (e.g. template application or color slider)
            debounceTimer = setTimeout(triggerUpdate, 50);
        }

        // Listen for standard input changes
        $('#brand-oasis-login-form').on('input change', '.bo-preview-trigger, input, select, textarea', function() {
            debouncedUpdate();
        });

        // Listen for color picker changes natively via wpColorPicker API
        if (typeof $.wp === 'object' && typeof $.wp.wpColorPicker === 'function') {
            $('.bo-color-picker').wpColorPicker({
                change: function(event, ui) {
                    // Update input value so serialize grabs it correctly
                    $(this).val(ui.color.toString());
                    debouncedUpdate();
                },
                clear: function() {
                    $(this).val('');
                    debouncedUpdate();
                }
            });
        }

        // Global event listener for other modules (like template-preview.js) to trigger an update
        $(document).on('brandOasis:previewUpdateRequired', function() {
            debouncedUpdate();
        });

        // Initial gradient toggle logic (UI only, doesn't affect iframe sync directly)
        function toggleGradientUI() {
            if ($('input[name="brand_oasis_login_settings[enable_gradient_bg]"]').is(':checked')) {
                $('.bo-gradient-settings').show();
            } else {
                $('.bo-gradient-settings').hide();
            }
        }
        $('input[name="brand_oasis_login_settings[enable_gradient_bg]"]').on('change', toggleGradientUI);
        toggleGradientUI(); // run on load
    });

})( jQuery, window );
