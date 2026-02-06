<?php
	defined('_JEXEC') or die;

	use Joomla\CMS\Factory;

	$doc = Factory::getApplication()->getDocument();

	$uid = 'dcjc_' . (int) $module->id;
?>

<link id="dc-joo_contact-css-<?= $uid ?>" href="<?= $cssUrl ?>?v=<?= $ver ?>" rel="stylesheet">

<?php require __DIR__ . '/parts/css.php'; ?>

<div id="<?php echo $uid; ?>" 
	class="dc-mod-joo_contact" 
	style="<?= $styleAttr ?>" 
	data-input-error="<?= htmlspecialchars($data['input_error'], ENT_QUOTES, 'UTF-8'); ?>" 
	data-mail_success="<?= htmlspecialchars($data['mail_success'], ENT_QUOTES, 'UTF-8'); ?>" 
	data-mail_error="<?= htmlspecialchars($data['mail_error'], ENT_QUOTES, 'UTF-8'); ?>" 
	>

	<?php require __DIR__ . '/parts/content.php'; ?>
	<?php require __DIR__ . '/parts/form.php'; ?>
	<?php require __DIR__ . '/parts/messages.php'; ?>
</div>



