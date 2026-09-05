# Assets Folder

Folder ini berisi semua file CSS, JS, dan library yang digunakan oleh aplikasi.

## Struktur Folder

```
assets/
├── css/          # File CSS
├── js/           # File JavaScript
├── fonts/        # Font files (Bootstrap Icons)
└── lib/          # Library files
```

## File yang Perlu Didownload

File-file berikut perlu didownload dan disimpan di folder yang sesuai:

### CSS Files
1. **bootstrap.min.css** - ✅ Sudah ada (dari Bootstrap-5.css)
2. **bootstrap-icons.css** - Download dari: https://icons.getbootstrap.com/
3. **fancybox.css** - Download dari: https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css

### JS Files
1. **bootstrap.bundle.min.js** - Download dari: https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js
2. **jquery.min.js** - Download dari: https://code.jquery.com/jquery-3.7.1.min.js
3. **clipboard.min.js** - Download dari: https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js
4. **moment.min.js** - Download dari: https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js
5. **moment-id.js** - Download dari: https://cdn.jsdelivr.net/npm/moment@2.29.4/locale/id.js
6. **fancybox.umd.js** - Download dari: https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js

### Font Files (Bootstrap Icons)
Download dari: https://icons.getbootstrap.com/
- bootstrap-icons.woff2
- bootstrap-icons.woff
Simpan di folder `fonts/`

## Cara Download

1. Buka URL di browser
2. Klik kanan → Save As
3. Simpan ke folder yang sesuai

Atau gunakan command:
```bash
# Windows PowerShell
Invoke-WebRequest -Uri "URL" -OutFile "path/file.js"
```

## File Custom

- **app.css** - CSS custom aplikasi (sidebar, notification, dll)
- **app.js** - JavaScript custom aplikasi (notification, clipboard, moment, dll)

