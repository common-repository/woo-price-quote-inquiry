(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(document).ready(function ($) {

		function get_current_page_id() {
			var page_body = $('body');
			var id = 0;
			if (page_body) {
				var classList = page_body.attr('class').split(/\s+/);
				$.each(classList, function (index, item) {
					if (item.indexOf('page-id') >= 0) {
						var item_arr = item.split('-');
						id = item_arr[item_arr.length - 1];
						return false;
					}
				});
			}
			return id;
		}
		$(document).on('click', '.wcpq-add-to-quote', function () {
			if ($('body').hasClass('logged-in')) {
				var clickd_obj = $(this);
				var current_page_id = get_current_page_id();
				var old_html = clickd_obj.html();
				clickd_obj.html('Please wait..<i class="fa fa-refresh fa-spin"></i>');
				var quote_product_id = $(this).data("id");
				jQuery.post(
					wcpq_ajax_object.ajax_url,
					{
						'action': 'wcpq_add_products_to_quote',
						'quote_product_id': quote_product_id,
						'nonce' : wcpq_ajax_object.ajax_nonce,
					},
					function (response) {
						var response = JSON.parse(response);
						if (response.quote_action == 'link') {
							if (current_page_id != wcpq_ajax_object.is_request_page) {
								clickd_obj.after(response.browse_quoted_list);
							}
							clickd_obj.replaceWith(response.remove_from_quote_btn);
						} else {
							window.location.replace(response.quote_pg_link);
						}
					}
				);
			}

		});

		$(document).on('click', '.wcpq-remove-quote', function () {
			var clickd_obj = $(this);
			clickd_obj.html('Please wait..<i class="fa fa-refresh fa-spin"></i>');
			var p_id = $(this).data("id");
			$.post(
				wcpq_ajax_object.ajax_url,
				{
					'action': 'wcpq_remove_products_from_quote',
					'p_id': p_id,
					'nonce' : wcpq_ajax_object.ajax_nonce,
				},
				function (response) {
					var response = JSON.parse(response);
					clickd_obj.next('.browse-quote-list').remove();
					clickd_obj.replaceWith(response.add_to_quote_btn);
				}
			);
		});

		$(document).on('click', '#wcpq_send_multiple_enquiry', function (e) {

			var name = $('#wcpq_enquiree_name').val();
			var email = $('#wcpq_enquiree_email').val();

			var summary = $('#wcpq_enquiree_summary').val();
			var product_id = $('#wcpq_quoted_products_id').val();

			if ($.trim(email).length == 0) {
				alert('Please enter valid email address.');
				e.preventDefault();
				return;
			}
			if ($.trim(summary).length == 0) {
				alert('Please enter quote message.');
				e.preventDefault();
				return;
			}
			if (!validateEmail(email)) {
				alert('Invalid Email Address');
				e.preventDefault();
				return;
			}
			$(this).val('Please wait');
			if ($('#wcpq_send_copy').is(':checked')) {
				var send_copy = 'yes';
			} else {
				var send_copy = 'no';
			}
			jQuery('.wait-txt').show();
			// jQuery(this).attr('disabled', true);
			jQuery.post(
				wcpq_ajax_object.ajax_url,
				{
					'action': 'wcpq_send_multiple_product_enquiry',
					'name': name,
					'email': email,
					'summary': summary,
					'product_id': product_id,
					'send_copy': send_copy,
					'ajax_nonce': wcpq_ajax_object.ajax_nonce
				},
				function (response) {
					if (response.success) {
						if (response.data.user_logged_in) {


							jQuery('#wcpq_send_multiple_enquiry').hide();
							jQuery('.wait-txt').hide();
							jQuery('.products_to_quote_html').hide();
							jQuery('.multi-quote-message').show();
						} else {
							$('.multi-quote-message').show();
							$('.wcpq-enquery-modal-box').fadeOut(500, function () {
								$(".wcpq-enquery-modal-overlay").remove();
							});
							location.reload();
						}

					}
				}
			);
		});

		function validateEmail(sEmail) {
			var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
			if (filter.test(sEmail)) {
				return true;
			}
			else {
				return false;
			}
		}



		/**
		 * Single page inquery form popup for logged out users
		 */
		if (!wcpq_ajax_object.is_user_logged_in) {
			if ($('body').hasClass('single-product')) {

				var appendthis = ("<div class='wcpq-enquery-modal-overlay'></div>");
				$(document).on('click', '.wcpq-add-to-quote', function (e) {
					e.preventDefault();
					$("body").append(appendthis);
					$(".wcpq-enquery-modal-overlay").fadeTo(500, 0.7);
					$('#wcqp_enquery_popup').fadeIn();
				});

				$(".wcpq-modal-close, .wcpq-enquery-modal-overlay").click(function () {
					$(".wcpq-enquery-modal-box, .wcpq-enquery-modal-overlay").fadeOut(500, function () {
						$(".wcpq-enquery-modal-overlay").remove();
					});
				});

				$(window).resize(function () {
					$(".wcpq-enquery-modal-box").css({
						top: ($(window).height() - $(".wcpq-enquery-modal-box").outerHeight()) / 2,
						left: ($(window).width() - $(".wcpq-enquery-modal-box").outerWidth()) / 2
					});
				});

				$(window).resize();

			}
		}


	});

})(jQuery);
