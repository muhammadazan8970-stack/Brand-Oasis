(function( $ ) {
	'use strict';

    $(document).ready(function() {

        // Tabs
        $('.nav-tab-wrapper a').on('click', function(e) {
            e.preventDefault();
            $('.nav-tab-wrapper a').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');

            $('.tab-content').removeClass('active');
            $($(this).attr('href')).addClass('active');
        });

        // Color Picker
        if (typeof $.wp === 'object' && typeof $.wp.wpColorPicker === 'function') {
            $('.bo-color-picker:not(.bo-preview-trigger)').wpColorPicker();
        }

        // Media Uploader
        var file_frame;

        $('.bo-upload-image').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var $input = $button.siblings('input[type="hidden"]');
            var $preview = $button.siblings('.image-preview-wrapper').find('img');

            if (file_frame) {
                file_frame.open();
                return;
            }

            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select or Upload an Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            file_frame.on('select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();
                $input.val(attachment.url).trigger('change');
                $preview.attr('src', attachment.url).show();
            });

            file_frame.open();
        });

        $('.bo-remove-image').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var $input = $button.siblings('input[type="hidden"]');
            var $preview = $button.siblings('.image-preview-wrapper').find('img');

            $input.val('').trigger('change');
            $preview.attr('src', '').hide();
        });

        // Export/Import Logic
        $('#bo-export-login').on('click', function() { exportSettings('login'); });
        $('#bo-export-admin').on('click', function() { exportSettings('admin'); });

        function exportSettings(type) {
            $.post(brandOasisAdmin.ajaxurl, {
                action: 'brand_oasis_export_settings',
                nonce: brandOasisAdmin.nonce,
                type: type
            }, function(response) {
                if (response.success) {
                    var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(response.data);
                    var downloadAnchorNode = document.createElement('a');
                    downloadAnchorNode.setAttribute("href", dataStr);
                    downloadAnchorNode.setAttribute("download", "brand-oasis-" + type + "-settings.json");
                    document.body.appendChild(downloadAnchorNode);
                    downloadAnchorNode.click();
                    downloadAnchorNode.remove();
                }
            });
        }

        $('#bo-import-login').on('click', function() { $('#bo-import-file').click(); });
        $('#bo-import-admin').on('click', function() { $('#bo-import-file-admin').click(); });

        $('#bo-import-file').on('change', function(e) { handleImport(e, 'login'); });
        $('#bo-import-file-admin').on('change', function(e) { handleImport(e, 'admin'); });

        function handleImport(e, type) {
            var file = e.target.files[0];
            if (!file) return;

            var reader = new FileReader();
            reader.onload = function(e) {
                var contents = e.target.result;
                $.post(brandOasisAdmin.ajaxurl, {
                    action: 'brand_oasis_import_settings',
                    nonce: brandOasisAdmin.nonce,
                    type: type,
                    json: contents
                }, function(response) {
                    if (response.success) {
                        alert('Settings imported successfully! Reloading page...');
                        location.reload();
                    } else {
                        alert('Error importing settings: ' + response.data);
                    }
                });
            };
            reader.readAsText(file);
        }

    });

})( jQuery );
