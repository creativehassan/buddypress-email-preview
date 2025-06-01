/**
 * BuddyPress Email Preview Admin JavaScript
 */

(function($) {
    'use strict';
    
    var BPEP = {
        
        /**
         * Initialize the admin interface
         */
        init: function() {
            this.bindEvents();
            this.checkUrlParams();
        },
        
        /**
         * Bind event handlers
         */
        bindEvents: function() {
            // Email type selection
            $('#bpep-email-type').on('change', this.onEmailTypeChange.bind(this));
            
            // Preview mode change
            $('input[name="preview_mode"]').on('change', this.onPreviewModeChange.bind(this));
            
            // Test email input
            $('#bpep-test-email').on('input', this.onTestEmailInput.bind(this));
            
            // Send test email button
            $('#bpep-send-test').on('click', this.onSendTestEmail.bind(this));
            
            // Refresh preview button
            $('#bpep-refresh-preview').on('click', this.onRefreshPreview.bind(this));
            
            // Token input changes
            $(document).on('input', '.bpep-token-input', this.onTokenChange.bind(this));
        },
        
        /**
         * Check URL parameters for auto-selection
         */
        checkUrlParams: function() {
            var urlParams = new URLSearchParams(window.location.search);
            var emailType = urlParams.get('email_type');
            
            if (emailType) {
                $('#bpep-email-type').val(emailType).trigger('change');
            }
        },
        
        /**
         * Handle email type selection change
         */
        onEmailTypeChange: function() {
            var emailType = $('#bpep-email-type').val();
            
            if (emailType) {
                this.loadEmailTokens(emailType);
                this.generatePreview();
                this.updateSendTestButton();
            } else {
                this.clearPreview();
                this.clearTokens();
                this.updateSendTestButton();
            }
        },
        
        /**
         * Handle preview mode change
         */
        onPreviewModeChange: function() {
            var emailType = $('#bpep-email-type').val();
            if (emailType) {
                this.generatePreview();
            }
        },
        
        /**
         * Handle test email input
         */
        onTestEmailInput: function() {
            this.updateSendTestButton();
        },
        
        /**
         * Handle token input changes
         */
        onTokenChange: function() {
            // Debounce the preview update
            clearTimeout(this.tokenChangeTimeout);
            this.tokenChangeTimeout = setTimeout(function() {
                var emailType = $('#bpep-email-type').val();
                if (emailType) {
                    this.generatePreview();
                }
            }.bind(this), 500);
        },
        
        /**
         * Handle send test email
         */
        onSendTestEmail: function(e) {
            e.preventDefault();
            
            var emailType = $('#bpep-email-type').val();
            var testEmail = $('#bpep-test-email').val();
            var tokens = this.getCustomTokens();
            
            if (!emailType || !testEmail) {
                this.showError(bpepAjax.strings.error_occurred);
                return;
            }
            
            var $button = $('#bpep-send-test');
            var originalText = $button.text();
            
            $button.prop('disabled', true).text(bpepAjax.strings.send_test_loading);
            
            $.ajax({
                url: bpepAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'bpep_send_test_email',
                    nonce: bpepAjax.nonce,
                    email_type: emailType,
                    test_email: testEmail,
                    tokens: tokens
                },
                success: function(response) {
                    if (response.success) {
                        this.showSuccess(response.data);
                    } else {
                        this.showError(response.data || bpepAjax.strings.error_occurred);
                    }
                }.bind(this),
                error: function() {
                    this.showError(bpepAjax.strings.error_occurred);
                }.bind(this),
                complete: function() {
                    $button.prop('disabled', false).text(originalText);
                    this.updateSendTestButton();
                }.bind(this)
            });
        },
        
        /**
         * Handle refresh preview
         */
        onRefreshPreview: function(e) {
            e.preventDefault();
            
            var emailType = $('#bpep-email-type').val();
            if (emailType) {
                this.generatePreview();
            }
        },
        
        /**
         * Load email tokens
         */
        loadEmailTokens: function(emailType) {
            $.ajax({
                url: bpepAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'bpep_get_email_content',
                    nonce: bpepAjax.nonce,
                    email_type: emailType
                },
                success: function(response) {
                    if (response.success && response.data.tokens) {
                        this.renderTokens(response.data.tokens);
                    }
                }.bind(this),
                error: function() {
                    this.showError(bpepAjax.strings.error_occurred);
                }.bind(this)
            });
        },
        
        /**
         * Generate email preview
         */
        generatePreview: function() {
            var emailType = $('#bpep-email-type').val();
            var previewMode = $('input[name="preview_mode"]:checked').val();
            var tokens = this.getCustomTokens();
            
            if (!emailType) {
                return;
            }
            
            this.showLoading();
            
            $.ajax({
                url: bpepAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'bpep_preview_email',
                    nonce: bpepAjax.nonce,
                    email_type: emailType,
                    preview_mode: previewMode,
                    tokens: tokens
                },
                success: function(response) {
                    if (response.success) {
                        this.renderPreview(response.data);
                    } else {
                        this.showError(response.data || bpepAjax.strings.error_occurred);
                    }
                }.bind(this),
                error: function() {
                    this.showError(bpepAjax.strings.error_occurred);
                }.bind(this)
            });
        },
        
        /**
         * Render email preview
         */
        renderPreview: function(data) {
            var isPlaintext = data.preview_mode === 'plaintext';
            var contentClass = isPlaintext ? 'bpep-email-content plaintext' : 'bpep-email-content';
            
            var html = '<div class="bpep-email-preview">' +
                       '<div class="bpep-email-subject">' + this.escapeHtml(data.subject) + '</div>' +
                       '<div class="' + contentClass + '">' + 
                       (isPlaintext ? this.escapeHtml(data.content) : data.content) + 
                       '</div>' +
                       '</div>';
            
            $('#bpep-preview-content').html(html);
        },
        
        /**
         * Render tokens interface
         */
        renderTokens: function(tokens) {
            var html = '<div class="bpep-tokens">';
            
            $.each(tokens, function(token, description) {
                html += '<div class="bpep-token-item">' +
                        '<div class="bpep-token-label">{{{' + token + '}}}</div>' +
                        '<div class="bpep-token-description">' + description + '</div>' +
                        '<input type="text" class="bpep-token-input" data-token="' + token + '" placeholder="Custom value (optional)">' +
                        '</div>';
            });
            
            html += '</div>';
            
            $('#bpep-tokens').html(html);
        },
        
        /**
         * Get custom token values
         */
        getCustomTokens: function() {
            var tokens = {};
            
            $('.bpep-token-input').each(function() {
                var $input = $(this);
                var token = $input.data('token');
                var value = $input.val().trim();
                
                if (value) {
                    tokens[token] = value;
                }
            });
            
            return tokens;
        },
        
        /**
         * Update send test button state
         */
        updateSendTestButton: function() {
            var emailType = $('#bpep-email-type').val();
            var testEmail = $('#bpep-test-email').val();
            var isValidEmail = this.isValidEmail(testEmail);
            
            $('#bpep-send-test').prop('disabled', !emailType || !isValidEmail);
        },
        
        /**
         * Show loading state
         */
        showLoading: function() {
            $('#bpep-preview-content').html('<div class="bpep-loading">' + bpepAjax.strings.preview_loading + '</div>');
        },
        
        /**
         * Clear preview
         */
        clearPreview: function() {
            $('#bpep-preview-content').html('<div class="bpep-placeholder"><p>Select an email type to see the preview</p></div>');
        },
        
        /**
         * Clear tokens
         */
        clearTokens: function() {
            $('#bpep-tokens').html('<p class="description">Select an email type to see available tokens</p>');
        },
        
        /**
         * Show error message
         */
        showError: function(message) {
            var $container = $('#bpep-preview-content');
            var html = '<div class="bpep-error">' + this.escapeHtml(message) + '</div>';
            
            if ($container.find('.bpep-error').length) {
                $container.find('.bpep-error').html(this.escapeHtml(message));
            } else {
                $container.prepend(html);
            }
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $container.find('.bpep-error').fadeOut();
            }, 5000);
        },
        
        /**
         * Show success message
         */
        showSuccess: function(message) {
            var $container = $('#bpep-preview-content');
            var html = '<div class="bpep-success">' + this.escapeHtml(message) + '</div>';
            
            if ($container.find('.bpep-success').length) {
                $container.find('.bpep-success').html(this.escapeHtml(message));
            } else {
                $container.prepend(html);
            }
            
            // Auto-hide after 3 seconds
            setTimeout(function() {
                $container.find('.bpep-success').fadeOut();
            }, 3000);
        },
        
        /**
         * Validate email address
         */
        isValidEmail: function(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },
        
        /**
         * Escape HTML
         */
        escapeHtml: function(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            
            return text.replace(/[&<>"']/g, function(m) {
                return map[m];
            });
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        BPEP.init();
    });
    
})(jQuery); 