(function ($) {
	'use strict';
	/**
	 * All of the code for your admin-facing JavaScript source
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
		$('#products-to-quote').closest('tr').removeClass('wcprq-products');
		if ($('.wcprq-quote-mode:checked').val() == 'all-products') {
			$('#products-to-quote').closest('tr').addClass('wcprq-products hide');
		}

		$('#products-to-quote').selectize({
			placeholder: "Select products",
			plugins: ['remove_button'],
		});

		var user_roles = $('#user-role-stngs').selectize({
			placeholder: "Select user roles",
			plugins: ['remove_button'],
		});

		if (user_roles[0]) {
			var user_roles_selectize = user_roles[0].selectize;
		}

		$('#quote_page').selectize({
			placeholder: "Select Page",
			plugins: ['remove_button'],
		});

		$('#quoted_prods_page').selectize({
			placeholder: "Select Page",
			plugins: ['remove_button'],
		});

		$(document).on('click', '.wcprq-quote-mode', function () {
			var quoteMode = $(this).val();
			if (quoteMode == 'selected-products') {
				$('.wcprq-products').show();
			} else {
				$('.wcprq-products').hide();
			}
		});


		$(document).on(
			'click', '#wcpq-select-all-uroles', function () {
				var pt_names = [], i;
				var pt_options = user_roles_selectize.options;
				for (i in pt_options) {
					pt_names.push(pt_options[i]['value']);
				}
				user_roles_selectize.setValue(pt_names);
			}
		);
		$(document).on(
			'click', '#wcpq-unselct-all-uroles', function () {
				user_roles_selectize.setValue([]);
			}
		);

	});

})(jQuery);
