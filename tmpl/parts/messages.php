<div class="dc-joo_contact-message-error">
	<button type="button" class="dc-joo_contact-message-close" onclick="$(this).parent().fadeOut();">&times;</button>
	<div class="dc-joo_contact-message">
		<?= htmlspecialchars($data['mail_error'], ENT_QUOTES, 'UTF-8'); ?>
	</div>
</div>

<div class="dc-joo_contact-message-success">
	<button type="button" class="dc-joo_contact-message-close" onclick="$(this).parent().fadeOut();">&times;</button>
	<div class="dc-joo_contact-message">
		<?= htmlspecialchars($data['mail_success'], ENT_QUOTES, 'UTF-8'); ?>
	</div>
</div>