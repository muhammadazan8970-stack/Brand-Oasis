(function( $, window ) {
	'use strict';

    window.BrandOasisPreviewRenderer = {

        $iframe: null,
        isUpdating: false,
        pendingUpdate: false,

        init: function() {
            this.$iframe = $('#bo-login-preview-iframe');
        },

        update: function(formData) {
            var self = this;
            if (!this.$iframe || !this.$iframe.length) return;

            // Prevent simultaneous AJAX requests; flag pending updates
            if (this.isUpdating) {
                this.pendingUpdate = true;
                return;
            }

            this.isUpdating = true;
            this.pendingUpdate = false;

            $.post(brandOasisAdmin.ajaxurl, {
                action: 'brand_oasis_preview_css',
                nonce: brandOasisAdmin.nonce,
                settings: formData
            }, function(response) {
                self.isUpdating = false;

                if (response.success) {
                    var $iframeDoc = self.$iframe.contents();
                    var $styleTag = $iframeDoc.find('#brand-oasis-login-css');

                    if ($styleTag.length) {
                        $styleTag.html(response.data);
                    } else {
                        // Fallback if tag doesn't exist yet
                        $iframeDoc.find('head').append('<style id="brand-oasis-login-css">' + response.data + '</style>');
                    }
                }

                // If another change happened while we were updating, trigger it now
                if (self.pendingUpdate) {
                    // Trigger global update event to fetch latest state
                    $(document).trigger('brandOasis:previewUpdateRequired');
                }
            });
        }
    };

})( jQuery, window );
