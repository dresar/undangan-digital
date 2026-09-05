<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Template Assets Check - templates1</title>
    <link rel="stylesheet" href="<?= esc($template_base) ?>css/AJ6WMXU0ifQz.css">
    <style>
        body { font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background:#0f172a; color:#e5e7eb; padding:16px; }
        .container { max-width: 900px; margin: 0 auto; }
        h1 { font-size: 24px; margin-bottom: 8px; }
        h2 { font-size: 18px; margin-top: 24px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 8px 10px; border-bottom: 1px solid rgba(148,163,184,.3); word-break: break-all; }
        th { text-align: left; background: rgba(15,23,42,.9); position: sticky; top: 0; z-index: 1; }
        .status-ok { color: #4ade80; font-weight: 600; }
        .status-error { color: #f97373; font-weight: 600; }
        .status-pending { color: #e5e7eb; opacity: .7; }
        .badge { display:inline-block; padding:2px 6px; border-radius:999px; font-size:11px; border:1px solid rgba(148,163,184,.5); margin-left:4px; }
        .tag { padding:2px 6px; border-radius:4px; font-size:11px; background:rgba(15,23,42,.9); border:1px solid rgba(148,163,184,.5); margin-right:4px; }
        .summary { margin: 16px 0; padding: 10px 12px; border-radius: 8px; background: rgba(15,23,42,.9); border:1px solid rgba(148,163,184,.4); font-size: 13px; }
        .summary span { margin-right: 12px; }
        .summary strong { margin-right: 4px; }
        .table-wrapper { max-height: 360px; overflow: auto; border-radius: 8px; border:1px solid rgba(148,163,184,.4); background: rgba(15,23,42,.8); }
        .code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size: 12px; }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 16px; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cek Assets Template <span class="tag">templates1</span></h1>
        <p class="mb-2" style="font-size:13px;">Base URL template: <span class="code"><?= esc($template_base) ?></span></p>

        <div class="summary" id="summaryBox">
            <span><strong>CSS OK:</strong> <span id="cssOk">0</span></span>
            <span><strong>CSS Error:</strong> <span id="cssError">0</span></span>
            <span><strong>JS OK:</strong> <span id="jsOk">0</span></span>
            <span><strong>JS Error:</strong> <span id="jsError">0</span></span>
        </div>

        <h2>CSS Files</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width:55%;">URL</th>
                        <th style="width:15%;">Type</th>
                        <th style="width:30%;">Status</th>
                    </tr>
                </thead>
                <tbody id="cssTable">
                    <?php foreach ($css_files as $file): ?>
                        <?php $url = $template_base . $file; ?>
                        <tr data-asset-row data-type="css" data-url="<?= esc($url) ?>">
                            <td class="code"><?= esc($url) ?></td>
                            <td><span class="badge">CSS</span></td>
                            <td class="status status-pending">Pending</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2 class="mt-4">JS Files</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width:55%;">URL</th>
                        <th style="width:15%;">Type</th>
                        <th style="width:30%;">Status</th>
                    </tr>
                </thead>
                <tbody id="jsTable">
                    <?php foreach ($js_files as $file): ?>
                        <?php $url = $template_base . $file; ?>
                        <tr data-asset-row data-type="js" data-url="<?= esc($url) ?>">
                            <td class="code"><?= esc($url) ?></td>
                            <td><span class="badge">JS</span></td>
                            <td class="status status-pending">Pending</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="mt-4" style="font-size:12px;opacity:.8;">
            Cek ini hanya melakukan HTTP HEAD request ke setiap file CSS/JS tanpa mengeksekusi isinya.
        </p>
    </div>

    <script>
        (function () {
            var rows = document.querySelectorAll('[data-asset-row]');
            var cssOk = 0, cssError = 0, jsOk = 0, jsError = 0;

            function updateSummary() {
                document.getElementById('cssOk').textContent = cssOk;
                document.getElementById('cssError').textContent = cssError;
                document.getElementById('jsOk').textContent = jsOk;
                document.getElementById('jsError').textContent = jsError;
            }

            rows.forEach(function (row) {
                var url = row.getAttribute('data-url');
                var type = row.getAttribute('data-type');
                var statusCell = row.querySelector('.status');

                fetch(url, { method: 'HEAD' }).then(function (res) {
                    if (res.ok) {
                        statusCell.textContent = 'OK ' + res.status;
                        statusCell.className = 'status status-ok';
                        if (type === 'css') cssOk++; else jsOk++;
                    } else {
                        statusCell.textContent = 'ERROR ' + res.status;
                        statusCell.className = 'status status-error';
                        if (type === 'css') cssError++; else jsError++;
                    }
                    updateSummary();
                }).catch(function (err) {
                    statusCell.textContent = 'REQUEST FAILED';
                    statusCell.className = 'status status-error';
                    if (type === 'css') cssError++; else jsError++;
                    updateSummary();
                });
            });
        })();
    </script>
</body>
</html>


