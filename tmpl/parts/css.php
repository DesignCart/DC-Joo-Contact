<?php
	defined('_JEXEC') or die;

	$cssVars = [
		/* Module */
		'--joo-contact-mod-bg-1'         => (string) $params->get('mod_bg_1', '#ffffff'),
		'--joo-contact-mod-bg-2'         => (string) $params->get('mod_bg_2', '#ffffff'),
		'--joo-contact-mod-title-color'  => (string) $params->get('mod_title_color', '#111111'),
		'--joo-contact-mod-desc-color'   => (string) $params->get('mod_desc_color', '#444444'),
		'--joo-contact-mod-title-size'   => (int) $params->get('mod_title_size', 20) . 'px',
		'--joo-contact-mod-desc-size'    => (int) $params->get('mod_desc_size', 14) . 'px',

		/* Form */
		'--joo-contact-label-color'      => (string) $params->get('label_color', '#111111'),
		'--joo-contact-label-size'       => (int) $params->get('label_size', 13) . 'px',
		'--joo-contact-input-bg'         => (string) $params->get('input_bg', '#ffffff'),
		'--joo-contact-input-border'     => (string) $params->get('input_border', '#cccccc'),
		'--joo-contact-input-text-color' => (string) $params->get('input_text_color', '#111111'),
		'--joo-contact-input-text-size'  => (int) $params->get('input_text_size', 14) . 'px',
		'--joo-contact-input-radius'     => (int) $params->get('input_radius', 8) . 'px',
		'--joo-contact-input-py'         => (int) $params->get('input_padding_y', 10) . 'px',
		'--joo-contact-input-px'         => (int) $params->get('input_padding_x', 12) . 'px',

		/* Submit button */
		'--joo-contact-submit-btn-bg'         => (string) $params->get('submit_btn_bg', '#111111'),
		'--joo-contact-submit-btn-bg-hover'   => (string) $params->get('submit_btn_bg_hover', '#000000'),
		'--joo-contact-submit-btn-color'      => (string) $params->get('submit_btn_color', '#ffffff'),
		'--joo-contact-submit-btn-color-hover' => (string) $params->get('submit_btn_color_hover', '#ffffff'),
		'--joo-contact-submit-btn-size'       => (int) $params->get('submit_btn_size', 14) . 'px',
	];

	$styleAttr = '';
	foreach ($cssVars as $k => $v) {
		$styleAttr .= $k . ':' . htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8') . ';';
	}

	$uidSafe = htmlspecialchars((string) ($uid ?? ''), ENT_QUOTES, 'UTF-8');
	if ($uidSafe === '') {
		$uidSafe = 'dcb_unknown';
	}
	?>
	
	<style>
		@keyframes gradientMove<?= $uidSafe ?> {
			0% {
				background-position: 0% 50%;
			}
			50% {
				background-position: 100% 50%;
			}
			100% {
				background-position: 0% 50%;
			}
		}

		#<?= $uidSafe ?>.dc-mod-joo_contact { 
			background: var(--joo-contact-mod-bg-1);
			background: linear-gradient(135deg, var(--joo-contact-mod-bg-1) 0%, var(--joo-contact-mod-bg-2) 50%, var(--joo-contact-mod-bg-1) 100%);
			background-size: 200% 200%;
			animation: gradientMove<?= $uidSafe ?> 6s ease infinite;
		}

		#<?= $uidSafe ?> .dc-mod-joo_contact__title { 
			color: var(--joo-contact-mod-title-color); 
			font-size: var(--joo-contact-mod-title-size); 
		}
		#<?= $uidSafe ?> .dc-mod-joo_contact__desc { 
			color: var(--joo-contact-mod-desc-color); 
			font-size: var(--joo-contact-mod-desc-size); 
		}

		#<?= $uidSafe ?> .dc-joo_contact-modal__btn-submit { 
			background: var(--joo-contact-submit-btn-bg); 
			color: var(--joo-contact-submit-btn-color); 
			font-size: var(--joo-contact-submit-btn-size); 
			border-radius: var(--joo-contact-input-radius);
			padding: var(--joo-contact-input-py) var(--joo-contact-input-px);
		}
		#<?= $uidSafe ?> .dc-joo_contact-modal__btn-submit:hover { 
			background: var(--joo-contact-submit-btn-bg-hover); 
			color: var(--joo-contact-submit-btn-color-hover); 
		}

		/* Form */
		#<?= $uidSafe ?> .dc-joo_contact-form label { 
			color: var(--joo-contact-label-color); 
			font-size: var(--joo-contact-label-size); 
		}

		#<?= $uidSafe ?> .dc-joo_contact-form input[type="text"],
		#<?= $uidSafe ?> .dc-joo_contact-form input[type="email"],
		#<?= $uidSafe ?> .dc-joo_contact-form input[type="number"],
		#<?= $uidSafe ?> .dc-joo_contact-form input[type="tel"],
		#<?= $uidSafe ?> .dc-joo_contact-form input[type="url"],
		#<?= $uidSafe ?> .dc-joo_contact-form select,
		#<?= $uidSafe ?> .dc-joo_contact-form textarea {
			background: var(--joo-contact-input-bg);
			border: 1px solid var(--joo-contact-input-border);
			color: var(--joo-contact-input-text-color);
			font-size: var(--joo-contact-input-text-size);
			border-radius: var(--joo-contact-input-radius);
			padding: var(--joo-contact-input-py) var(--joo-contact-input-px);
			width: 100%;
			box-sizing: border-box;
		}

		

		#<?= $uidSafe ?> .dc-joo_contact-form textarea { min-height: 120px; resize: vertical; }
	</style>
