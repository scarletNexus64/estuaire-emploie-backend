<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['url']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['url']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<tr>
<td class="header">
<a href="<?php echo new \Illuminate\Support\EncodedHtmlString($url); ?>" style="display: inline-block;">
<?php if(trim($slot) === 'Laravel'): ?>
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
<?php elseif(trim($slot) === 'Estuaire Emploie' || trim($slot) === config('app.name')): ?>
<?php
    $logoPath = public_path('images/logo-estuaire-emploi.png');
    $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
?>
<img src="<?php echo new \Illuminate\Support\EncodedHtmlString($logoBase64); ?>" class="logo" alt="Estuaire Emploi Logo" style="height: 80px; width: auto;">
<?php else: ?>
<?php echo $slot; ?>

<?php endif; ?>
</a>
</td>
</tr>
<?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/vendor/mail/html/header.blade.php ENDPATH**/ ?>