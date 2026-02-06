// Walidacja formularza dc-joo_contact-form (jQuery)
function dc_validateJooContact($root) {
	if (!$root || $root.length === 0) return true;

	const $form = $root.find('.dc-joo_contact-form');
	if ($form.length === 0) return true;

	let isValid = true;
	const errorText = $root.attr('data-input-error') || 'To pole jest wymagane';

	// Usuń poprzednie błędy
	$form.find('.dc-joo_contact-form__field, .dc-brief-form__field').each(function() {
		const $field = $(this);
		$field.removeClass('dc-joo_contact-form__field--error dc-brief-form__field--error');
		$field.find('.dc-joo_contact-form__error, .dc-brief-form__error').remove();
	});

	function showError($fieldWrap) {
		if (!$fieldWrap || $fieldWrap.length === 0) return;
		
		$fieldWrap.addClass('dc-joo_contact-form__field--error dc-brief-form__field--error');

		if ($fieldWrap.find('.dc-joo_contact-form__error, .dc-brief-form__error').length === 0) {
			$fieldWrap.append(
				$('<div>')
					.addClass('dc-joo_contact-form__error dc-brief-form__error')
					.text(errorText)
			);
		}
	}

	// Walidacja pól systemowych i niestandardowych
	$form.find('input[required]:not([type=radio]):not([type=checkbox]), textarea[required], select[required]').each(function() {
		const $input = $(this);
		const $fieldWrap = $input.closest('.dc-joo_contact-form__field, .dc-brief-form__field');
		const value = $input.val() || '';
		
		if (value.trim() === '') {
			isValid = false;
			showError($fieldWrap);
		}
	});

	// Walidacja checkboxów
	$form.find('.dc-brief-form__checkbox-one input[type=checkbox][required]').each(function() {
		const $chk = $(this);
		const $fieldWrap = $chk.closest('.dc-brief-form__field');
		
		if (!$chk.is(':checked')) {
			isValid = false;
			showError($fieldWrap);
		}
	});

	// Walidacja radio (co najmniej jeden zaznaczony dla każdego name)
	const radioNames = new Set();
	$form.find('input[type=radio][required]').each(function() {
		radioNames.add($(this).attr('name'));
	});

	radioNames.forEach(function(name) {
		const $radios = $form.find('input[type=radio][name="' + name.replace(/"/g, '\\"') + '"][required]');
		const checked = $radios.is(':checked');
		
		if (!checked) {
			isValid = false;
			const $firstRadio = $radios.first();
			if ($firstRadio.length) {
				const $fieldWrap = $firstRadio.closest('.dc-brief-form__field');
				showError($fieldWrap);
			}
		}
	});

	return isValid;
}

// Wysyłka formularza (jQuery)
function dc_sendJooContact(ev, $root) {
	if (ev && ev.preventDefault) {
		ev.preventDefault();
	}

	if (!$root || $root.length === 0) {
		return;
	}

	const $form = $root.find('.dc-joo_contact-form');
	if ($form.length === 0) {
		return;
	}

	// Walidacja
	if (!dc_validateJooContact($root)) {
		return;
	}

	// Sprawdź honeypot (anti-bot)
	const $honeyInput = $form.find('input[name="dc_joo_contact_honey"]');
	if ($honeyInput.length && $honeyInput.val() !== '') {
		// Bot detected - nie wysyłamy
		return;
	}

	// Pola systemowe - pobierz wartości i labele
	const $nameInput = $form.find('input[name="name"]');
	const $emailInput = $form.find('input[name="email"]');
	
	const name = $nameInput.val() ? $nameInput.val().trim() : '';
	const email = $emailInput.val() ? $emailInput.val().trim() : '';
	
	// Pobierz labele pól systemowych z formularza
	const $nameLabelEl = $nameInput.closest('.dc-joo_contact-form__field').find('label');
	const $emailLabelEl = $emailInput.closest('.dc-joo_contact-form__field').find('label');
	
	const nameLabel = $nameLabelEl.length ? $nameLabelEl.text().replace(/\s*\*?\s*$/, '').trim() : 'Imię/Nazwisko';
	const emailLabel = $emailLabelEl.length ? $emailLabelEl.text().replace(/\s*\*?\s*$/, '').trim() : 'E-mail';

	// Pola niestandardowe (brief[...])
	const customFields = [];
	const processedFields = new Set(); 
	$form.find('input[name^="brief["]:not([type="hidden"]), textarea[name^="brief["], select[name^="brief["]').each(function() {
		const $field = $(this);
		const fieldName = $field.attr('name');
		const fieldType = $field.attr('type') || $field.prop('tagName').toLowerCase();
		
		// Dla radio i checkbox - przetwarzaj tylko raz na grupę (po name)
		if (fieldType === 'radio' || fieldType === 'checkbox') {
			if (processedFields.has(fieldName)) {
				return; // Już przetworzone
			}
			processedFields.add(fieldName);
		}
		
		const $fieldWrap = $field.closest('.dc-joo_contact-form__field');
		
		if ($fieldWrap.length === 0) return;
		
		// Szukaj labela - może być .dc-joo-contact-form__label lub zwykły label
		const $labelElement = $fieldWrap.find('.dc-joo-contact-form__label, label').first();
		
		if ($labelElement.length === 0) return;
		
		const label = $labelElement.text().replace(/\s*\*?\s*$/, '').trim();
		if (!label) return;
		
		let fieldValue = '';
		
		// Pobierz wartość w zależności od typu pola
		if (fieldType === 'textarea') {
			fieldValue = $field.val() ? $field.val().trim() : '';
		}
		else if (fieldType === 'select') {
			fieldValue = $field.val() || '';
		}
		else if (fieldType === 'checkbox') {
			// Dla checkboxa sprawdź czy jest zaznaczony (wartość 0 lub 1)
			fieldValue = $field.is(':checked') ? '1' : '0';
		}
		else if (fieldType === 'radio') {
			// Dla radio znajdź zaznaczony element z tej grupy
			const $checkedRadio = $form.find('input[type="radio"][name="' + fieldName.replace(/"/g, '\\"') + '"]:checked');
			if ($checkedRadio.length) {
				const $optLabel = $checkedRadio.closest('label').find('span');
				fieldValue = $optLabel.length ? $optLabel.text().trim() : $checkedRadio.val();
			}
		}
		else {
			// Input text/number/email/tel/url
			fieldValue = $field.val() ? $field.val().trim() : '';
		}
		
		// Dodaj pole tylko jeśli ma wartość (lub jeśli jest checkbox - zawsze dodaj, nawet jeśli 0)
		const isCheckbox = fieldType === 'checkbox';
		
		if (fieldValue !== '' || isCheckbox) {
			customFields.push({
				label: label,
				value: String(fieldValue).trim()
			});
		}
	});

	// Formatuj dane do struktury oczekiwanej przez helper.php
	// Helper oczekuje strukturę z grupami, więc tworzymy jedną grupę z polami
	const groups = [{
		title: '',
		description: '',
		fields: [
			// Pola systemowe (tylko jeśli wypełnione)
			...(name ? [{ label: nameLabel, value: name }] : []),
			...(email ? [{ label: emailLabel, value: email }] : []),
			// Pola niestandardowe
			...customFields
		]
	}];

	// Token CSRF - Joomla używa input[name] z wartością "1"
	const $tokenInput = $form.find('input[type="hidden"][name][value="1"]').first() 
		|| $form.find('input[type="hidden"]').first();
	const tokenName = $tokenInput.length ? $tokenInput.attr('name') : '';

	// Wyślij dane
	const payload = {
		joo_contact_groups: JSON.stringify(groups),
		mail_to: $form.find('input[name="mail_to"]').val() || '',
		mail_subject: $form.find('input[name="mail_subject"]').val() || '',
		mail_intro: $form.find('input[name="mail_intro"]').val() || ''
	};

	dc_postJooContact(payload, tokenName, $form, $root);
}

// Wysyłka AJAX (jQuery)
function dc_postJooContact(payload, tokenName, $form, $root) {
	const url = "index.php?option=com_ajax&module=dc_joo_contact&method=sendMail&format=json";

	const formData = {
		joo_contact_groups: payload.joo_contact_groups || '[]',
		mail_to: payload.mail_to || '',
		mail_subject: payload.mail_subject || '',
		mail_intro: payload.mail_intro || ''
	};

	// Token CSRF - Joomla używa wartości "1" dla tokena
	if (tokenName) {
		formData[tokenName] = '1';
	}

	const successMsg = $root.attr('data-mail_success') || 'Wiadomość została wysłana pomyślnie.';
	const errorMsg = $root.attr('data-mail_error') || 'Nie udało się wysłać wiadomości. Spróbuj ponownie.';

	$.ajax({
		url: url,
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(result) {
			// com_ajax zwraca {success: true, data: {ok: true/false}}
			const ok = result && result.success === true && result.data && (
				result.data.ok === true || 
				result.data.ok === 1 ||
				result.data === true
			);

			if (ok) {
				$root.find('.dc-joo_contact-message-success').hide().css('display', 'flex').fadeIn();
				$form[0].reset();
			} else {
				console.error('DC JOO CONTACT MAIL ERROR:', result);
				const errorMessage = (result && result.data && result.data.message) ? result.data.message : errorMsg;
				$root.find('.dc-joo_contact-message-error').hide().css('display', 'flex').fadeIn();
			}
		},
		error: function(xhr, status, error) {
			console.error('DC JOO CONTACT AJAX ERROR:', error);
			alert('Wystąpił nieoczekiwany błąd podczas wysyłania formularza.');
		}
	});
}
