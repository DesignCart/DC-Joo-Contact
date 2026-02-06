<?php
	defined('_JEXEC') or die;

	$moduleTitle = trim((string) ($data['module_title'] ?? ''));
	$moduleDesc = trim((string) ($data['module_desc'] ?? ''));
	$moduleTitleHeading = trim((string) ($params->get('module_title_heading', 'h2') ?? 'h2'));
	
	if (!in_array($moduleTitleHeading, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true)) {
		$moduleTitleHeading = 'h2';
	}
?>

<?php if ($moduleTitle !== '' || $moduleDesc !== ''): ?>
	<div class="dc-mod-joo_contact__content">
		<?php if ($moduleTitle !== ''): ?>
			<<?php echo htmlspecialchars($moduleTitleHeading, ENT_QUOTES, 'UTF-8'); ?> class="dc-mod-joo_contact__title">
				<?php echo htmlspecialchars($moduleTitle, ENT_QUOTES, 'UTF-8'); ?>
			</<?php echo htmlspecialchars($moduleTitleHeading, ENT_QUOTES, 'UTF-8'); ?>>
		<?php endif; ?>
		
		<?php if ($moduleDesc !== ''): ?>
			<div class="dc-mod-joo_contact__desc">
				<?php echo nl2br(htmlspecialchars($moduleDesc, ENT_QUOTES, 'UTF-8')); ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>
