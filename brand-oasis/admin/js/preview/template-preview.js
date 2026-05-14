(function( $ ) {
	'use strict';

    $(document).ready(function() {

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

                    // Reset checkbox state specifically
                    var $gradCheckbox = $('input[name="brand_oasis_login_settings[enable_gradient_bg]"]');
                    if ($gradCheckbox.length) {
                        $gradCheckbox.prop('checked', false);
                    }

                    // Loop through data and update DOM inputs
                    for (var key in data) {
                        if (excludeKeys.indexOf(key) !== -1) continue;

                        var $input = $('[name="brand_oasis_login_settings[' + key + ']"]');
                        if ($input.length) {
                            if ($input.is(':checkbox')) {
                                $input.prop('checked', data[key] == '1' || data[key] === true);
                            } else {
                                $input.val(data[key]);
                                // Update color picker UI if applicable
                                if ($input.hasClass('bo-color-picker') && typeof $.wp === 'object' && typeof $.wp.wpColorPicker === 'function') {
                                    $input.wpColorPicker('color', data[key]);
                                }
                            }
                        }
                    }

                    // Trigger the global preview update so the renderer syncs the new iframe state
                    $(document).trigger('brandOasis:previewUpdateRequired');

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
