<?php
$bgColor = $bg_color ?? '#f8f9fa';
$textColor = $text_color ?? '#333';
$quote = $quote ?? 'Kata mutiara akan ditampilkan di sini';
$author = $author ?? '';
?>
<div class="quote-section py-5" style="background-color: <?= esc($bgColor) ?>;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="quote-content p-4">
                    <i class="bi bi-quote text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                    <blockquote class="blockquote mb-3" style="font-size: 1.25rem; font-style: italic; color: <?= esc($textColor) ?>;">
                        <?= esc($quote) ?>
                    </blockquote>
                    <?php if (!empty($author)): ?>
                    <footer class="blockquote-footer mt-3">
                        <cite title="Source Title"><?= esc($author) ?></cite>
                    </footer>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

