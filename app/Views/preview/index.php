<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($invitation['title']) ?></title>
    <link href="<?= base_url('assets/css/tailwind.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/fancybox.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/preview.css') ?>">
    <style>
        .preview-url-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 768px) {
            .preview-url-btn {
                top: 10px;
                right: 10px;
                padding: 8px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <?php if (!empty($invitation['slug'])): ?>
    <a href="<?= base_url(esc($invitation['slug'], 'url')) ?>" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg preview-url-btn">
        <i class="fas fa-external-link-alt mr-1"></i>Buka di Tab Baru
    </a>
    <?php endif; ?>
    <?php
    if (!empty($content) && is_array($content)) {
        foreach ($content as $item) {
            if (!isset($item['type']) || !isset($item['data'])) {
                continue;
            }
            
            $type = $item['type'];
            $data = $item['data'];
            
            switch ($type) {
                case 'hero':
                    echo view_cell('App\Cells\HeroCell', $data);
                    break;
                case 'quote':
                    echo view_cell('App\Cells\QuoteCell', $data);
                    break;
                case 'gallery':
                    echo view_cell('App\Cells\GalleryCell', $data);
                    break;
                case 'rsvp':
                    echo view_cell('App\Cells\RsvpCell', $data);
                    break;
            }
        }
    } else {
        echo '<div class="max-w-7xl mx-auto py-12"><div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">Tidak ada konten untuk ditampilkan</div></div>';
    }
    ?>
    
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/moment.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/moment-id.js') ?>"></script>
    <script src="<?= base_url('assets/js/fancybox.umd.js') ?>"></script>
    <script>
        moment.locale('id');
    </script>
    <?php if (!empty($invitation['custom_js'])): ?>
    <script>
        <?= $invitation['custom_js'] ?>
    </script>
    <?php endif; ?>
    <?php if (!empty($invitation['custom_css'])): ?>
    <style>
        <?= $invitation['custom_css'] ?>
    </style>
    <?php endif; ?>
</body>
</html>

