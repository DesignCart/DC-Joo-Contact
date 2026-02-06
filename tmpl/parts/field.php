<?php
    defined('_JEXEC') or die;

    $label = trim((string) ($fieldData['label'] ?? ''));
    $idKey = preg_replace('/[^a-zA-Z0-9_-]+/', '_', iconv('UTF-8', 'ASCII//TRANSLIT', $label));
    $type  = trim((string) ($fieldData['type'] ?? 'input'));
    
    $optionsRaw = (string) ($fieldData['options'] ?? '');
    $required = (int) ($fieldData['required'] ?? 0);
    $isRequired = ($required === 1 || $required === '1' || $required === true);

    $requiredAttr = $isRequired ? ' required aria-required="true"' : '';
    $requiredMark = $isRequired ? ' <span class="dc-joo_contact-form__required">*</span>' : '';

    if ($idKey === '') {
        return;
    }

    $idKeySafe = preg_replace('/[^a-zA-Z0-9_-]+/', '_', $idKey) ?: ('field_' . (int) $fIdx);

    $inputIdBase = $uid . '_f_' . $idKeySafe;    
    $nameBase    = 'brief[' . $idKeySafe . ']';  

    $parseOptions = static function (string $raw): array {
        $lines = preg_split('/\R/u', $raw) ?: [];
        $out = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $parts = array_map('trim', explode('|', $line, 2));
            $optLabel = (string) ($parts[0] ?? '');
            $optValue = (string) ($parts[1] ?? $optLabel);

            if ($optLabel !== '') {
                $out[] = ['label' => $optLabel, 'value' => $optValue];
            }
        }

        return $out;
    };

    $options = $parseOptions($optionsRaw);
?>

<div class="dc-joo_contact-form__field dc-joo_contact-form__field--<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>">
    <?php
        switch ($type) {
            case 'checkbox':
                if ($label !== ''): ?>

                    <input type="hidden" name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>" value="0" />
                    <input type="checkbox"
                        id="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>"
                        name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>"
                        value="1"
                        <?php echo $requiredAttr; ?> />
                    <label for="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?><?php echo $requiredMark; ?>
                    </label>
                <?php endif;
                break;

            case 'textarea':
                if ($label !== ''): ?>
                    <label class="dc-joo-contact-form__label" for="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?><?php echo $requiredMark; ?>
                    </label>
                <?php endif; ?>
                <textarea
                    id="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>"
                    name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>"
                    rows="4"
                    <?php echo $requiredAttr; ?>></textarea>
                <?php
                break;

            case 'select':
                if ($label !== ''): ?>
                    <label class="dc-joo-contact-form__label" for="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?><?php echo $requiredMark; ?>
                    </label>
                <?php endif; ?>
                <select
                    id="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>"
                    name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>"
                    <?php echo $requiredAttr; ?>>
                    <?php foreach ($options as $opt): ?>
                        <option value="<?php echo htmlspecialchars($opt['value'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($opt['label'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php
                break;

            case 'radio':
                if ($label !== ''): ?>
                    <label class="dc-joo-contact-form__label">
                        <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?><?php echo $requiredMark; ?>
                    </label>
                <?php endif; ?>
                <div class="dc-joo-contact-form__options dc-joo-contact-form__options--radio">
                    <?php foreach ($options as $i => $opt): ?>
                        <?php $rid = $inputIdBase . '_r_' . $i; ?>
                        <label class="dc-joo-contact-form__option" for="<?php echo htmlspecialchars($rid, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="radio"
                                id="<?php echo htmlspecialchars($rid, ENT_QUOTES, 'UTF-8'); ?>"
                                name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>"
                                value="<?php echo htmlspecialchars($opt['value'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php echo $requiredAttr; ?>/>
                            <span><?php echo htmlspecialchars($opt['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <?php
                break;

            case 'input':
            default:
                if ($label !== ''): ?>
                    <label class="dc-joo-contact-form__label" for="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?><?php echo $requiredMark; ?>
                    </label>
                <?php endif; ?>
                <input type="text"
                    id="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>"
                    name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>"
                    <?php echo $requiredAttr; ?>/>
                <?php
                break;
        }
    ?>
</div>
