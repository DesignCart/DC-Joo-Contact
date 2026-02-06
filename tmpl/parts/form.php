<?php
	defined('_JEXEC') or die;

	$fieldsRaw = $data['fields'] ?? [];
	$fieldsList = json_decode(json_encode($fieldsRaw), true) ?: [];

	$hasAnyFields = !empty($fieldsList);

	$labelName  = trim((string) ($data['label_name'] ?? ''));
	$labelEmail = trim((string) ($data['label_email'] ?? ''));
	$submitText = trim((string) ($data['submit_text'] ?? ''));
	$formId = htmlspecialchars($uid, ENT_QUOTES, 'UTF-8') . '_form';

	$formStartDate = date('Y-m-d H:i:s');
?>

<form class="dc-joo_contact-form" 
	id="<?php echo $formId; ?>"
	method="post" 
	action=""
	data-start="<?php echo htmlspecialchars($formStartDate, ENT_QUOTES, 'UTF-8'); ?>"
	<?php if (!empty($data['captcha_v3_sitekey'])): ?>
		data-recaptcha-sitekey="<?php echo htmlspecialchars($data['captcha_v3_sitekey'], ENT_QUOTES, 'UTF-8'); ?>"
		data-recaptcha-action="<?php echo htmlspecialchars($data['captcha_v3_action'], ENT_QUOTES, 'UTF-8'); ?>"
	<?php endif; ?>
>
	<div class="dc-joo_contact-form__fields">

		<div class="dc-joo_contact-form__field dc-joo_contact-form__field--name">
			<label for="<?php echo $formId; ?>_input_name"><?php echo htmlspecialchars($labelName, ENT_QUOTES, 'UTF-8'); ?><?php /* to pole zawsze wymagane */ ?><span class="dc-joo_contact-form__required">*</span></label>
			<input 
				type="text"
				id="<?php echo $formId; ?>_input_name"
				name="name"
				required="required"
				autocomplete="name"
				data-type="system"
				class="dc-joo_contact-form__input"
			/>
		</div>
		<div class="dc-joo_contact-form__field dc-joo_contact-form__field--email">
			<label for="<?php echo $formId; ?>_input_email"><?php echo htmlspecialchars($labelEmail, ENT_QUOTES, 'UTF-8'); ?><?php /* to pole zawsze wymagane */ ?><span class="dc-joo_contact-form__required">*</span></label>
			<input 
				type="email"
				id="<?php echo $formId; ?>_input_email"
				name="email"
				required="required"
				autocomplete="email"
				data-type="system"
				class="dc-joo_contact-form__input"
			/>
		</div>

		<?php if ($hasAnyFields): ?>
			<?php foreach ($fieldsList as $fIdx => $fieldWrap): ?>
				<?php
					$fieldData = $fieldWrap['field']; 
					require __DIR__ . '/field.php';
				?>
			<?php endforeach; ?>
		<?php endif; ?>

		<div style="display:none;">
			<input type="text" name="dc_joo_contact_honey" value="" tabindex="-1" autocomplete="off" />
		</div>
	</div>

	<div class="dc-joo_contact-form__actions">
		<button type="submit" class="dc-joo_contact-modal__btn-submit"><?php echo htmlspecialchars($submitText, ENT_QUOTES, 'UTF-8'); ?></button>
	</div>

	<?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
	<input type="hidden" name="mail_to" value="<?php echo htmlspecialchars($data['mail_to'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
	<input type="hidden" name="mail_subject" value="<?php echo htmlspecialchars($data['mail_subject'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
	<input type="hidden" name="mail_intro" value="<?php echo htmlspecialchars($data['mail_intro'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
</form>
