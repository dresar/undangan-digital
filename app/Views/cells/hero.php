<?php
$bgColorStart = $bg_color_start ?? '#667eea';
$bgColorEnd = $bg_color_end ?? '#764ba2';
$photo = $photo ?? '';
$groomName = $groom_name ?? 'Nama Mempelai Pria';
$brideName = $bride_name ?? 'Nama Mempelai Wanita';
$subtitle = $subtitle ?? '';
?>
<div class="hero-section text-center py-5" style="background: linear-gradient(135deg, <?= esc($bgColorStart) ?>, <?= esc($bgColorEnd) ?>); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (!empty($photo)): ?>
                <img src="<?= esc($photo) ?>" alt="<?= esc($groomName) ?> & <?= esc($brideName) ?>" class="img-fluid rounded-circle mb-4" style="width: 200px; height: 200px; object-fit: cover; border: 5px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                <?php endif; ?>
                <h1 class="display-4 text-white mb-3" style="font-family: 'Playfair Display', serif; font-weight: 700;">
                    <?= esc($groomName) ?>
                </h1>
                <div class="mb-3">
                    <i class="bi bi-heart-fill text-white" style="font-size: 2rem;"></i>
                </div>
                <h1 class="display-4 text-white mb-4" style="font-family: 'Playfair Display', serif; font-weight: 700;">
                    <?= esc($brideName) ?>
                </h1>
                <?php if (!empty($subtitle)): ?>
                <p class="lead text-white mb-0"><?= esc($subtitle) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

