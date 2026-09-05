<?php
$bgColor = $bg_color ?? '#ffffff';
$titleColor = $title_color ?? '#333';
$title = $title ?? '';
$images = $images ?? [];
$columns = (int)($columns ?? 3);
$colClass = 'col-md-' . (12 / $columns);
?>
<div class="gallery-section py-5" style="background-color: <?= esc($bgColor) ?>;">
    <div class="container">
        <?php if (!empty($title)): ?>
        <h2 class="text-center mb-5" style="color: <?= esc($titleColor) ?>;">
            <?= esc($title) ?>
        </h2>
        <?php endif; ?>
        <div class="row g-4">
            <?php foreach ($images as $index => $image): ?>
            <div class="<?= $colClass ?>">
                <a href="<?= esc($image['url'] ?? '') ?>" data-fancybox="gallery" data-caption="<?= esc($image['alt'] ?? 'Gallery Image', 'attr') ?>">
                    <div class="gallery-item position-relative overflow-hidden rounded" style="aspect-ratio: 1; cursor: pointer;">
                        <img src="<?= esc($image['url'] ?? '') ?>" alt="<?= esc($image['alt'] ?? 'Gallery Image') ?>" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s;">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-0" style="transition: opacity 0.3s;"></div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    Fancybox.bind('[data-fancybox="gallery"]', {
        Toolbar: {
            display: {
                left: ['infobar'],
                middle: [],
                right: ['slideshow', 'download', 'thumbs', 'close']
            }
        },
        Thumbs: {
            autoStart: true
        }
    });
    
    $('.gallery-item').hover(
        function() {
            $(this).find('img').css('transform', 'scale(1.1)');
            $(this).find('.bg-dark').css('opacity', '0.3');
        },
        function() {
            $(this).find('img').css('transform', 'scale(1)');
            $(this).find('.bg-dark').css('opacity', '0');
        }
    );
});
</script>

