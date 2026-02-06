// Inicjalizacja formularza dc-joo_contact-form (jQuery)
(function($) {
	'use strict';

	// Czekaj na załadowanie DOM
	$(document).ready(function() {
		// Znajdź wszystkie instancje modułu
		$('.dc-mod-joo_contact').each(function() {
			const $root = $(this);
			const $form = $root.find('.dc-joo_contact-form');
			
			if ($form.length === 0) return;

			// Podłącz obsługę submit
			$form.on('submit', function(ev) {
				dc_sendJooContact(ev, $root);
			});
		});
	});
})(jQuery);
