Write-Host "Downloading Assets..." -ForegroundColor Green

$assetsPath = "public\assets"

Write-Host "`nDownloading CSS files..." -ForegroundColor Yellow

Write-Host "  - Bootstrap CSS (already exists)" -ForegroundColor Gray

Write-Host "  - Bootstrap Icons CSS..." -ForegroundColor Cyan
$bootstrapIconsCss = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
Invoke-WebRequest -Uri $bootstrapIconsCss -OutFile "$assetsPath\css\bootstrap-icons.css" -ErrorAction SilentlyContinue

Write-Host "  - FancyBox CSS..." -ForegroundColor Cyan
$fancyboxCss = "https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
Invoke-WebRequest -Uri $fancyboxCss -OutFile "$assetsPath\css\fancybox.css" -ErrorAction SilentlyContinue

Write-Host "`nDownloading JS files..." -ForegroundColor Yellow

Write-Host "  - Bootstrap Bundle JS..." -ForegroundColor Cyan
$bootstrapJs = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
Invoke-WebRequest -Uri $bootstrapJs -OutFile "$assetsPath\js\bootstrap.bundle.min.js" -ErrorAction SilentlyContinue

Write-Host "  - jQuery..." -ForegroundColor Cyan
$jquery = "https://code.jquery.com/jquery-3.7.1.min.js"
Invoke-WebRequest -Uri $jquery -OutFile "$assetsPath\js\jquery.min.js" -ErrorAction SilentlyContinue

Write-Host "  - Clipboard.js..." -ForegroundColor Cyan
$clipboard = "https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"
Invoke-WebRequest -Uri $clipboard -OutFile "$assetsPath\js\clipboard.min.js" -ErrorAction SilentlyContinue

Write-Host "  - Moment.js..." -ForegroundColor Cyan
$moment = "https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"
Invoke-WebRequest -Uri $moment -OutFile "$assetsPath\js\moment.min.js" -ErrorAction SilentlyContinue

Write-Host "  - Moment.js ID Locale..." -ForegroundColor Cyan
$momentId = "https://cdn.jsdelivr.net/npm/moment@2.29.4/locale/id.js"
Invoke-WebRequest -Uri $momentId -OutFile "$assetsPath\js\moment-id.js" -ErrorAction SilentlyContinue

Write-Host "  - FancyBox JS..." -ForegroundColor Cyan
$fancyboxJs = "https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"
Invoke-WebRequest -Uri $fancyboxJs -OutFile "$assetsPath\js\fancybox.umd.js" -ErrorAction SilentlyContinue

Write-Host "`nDownloading Font files..." -ForegroundColor Yellow

New-Item -ItemType Directory -Force -Path "$assetsPath\fonts" | Out-Null

Write-Host "  - Bootstrap Icons WOFF2..." -ForegroundColor Cyan
$bootstrapIconsWoff2 = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff2"
Invoke-WebRequest -Uri $bootstrapIconsWoff2 -OutFile "$assetsPath\fonts\bootstrap-icons.woff2" -ErrorAction SilentlyContinue

Write-Host "  - Bootstrap Icons WOFF..." -ForegroundColor Cyan
$bootstrapIconsWoff = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff"
Invoke-WebRequest -Uri $bootstrapIconsWoff -OutFile "$assetsPath\fonts\bootstrap-icons.woff" -ErrorAction SilentlyContinue

Write-Host "`n✅ Download selesai!" -ForegroundColor Green
Write-Host "`nFile yang didownload:" -ForegroundColor Cyan
Get-ChildItem -Path "$assetsPath" -Recurse -File | Select-Object FullName, Length | Format-Table -AutoSize

