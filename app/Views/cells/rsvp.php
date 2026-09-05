<?php
$bgColor = $bg_color ?? '#f8f9fa';
$titleColor = $title_color ?? '#333';
$title = $title ?? '';
?>
<div class="rsvp-section py-5" style="background-color: <?= esc($bgColor) ?>;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <?php if (!empty($title)): ?>
                        <h3 class="text-center mb-4" style="color: <?= esc($titleColor) ?>;">
                            <?= esc($title) ?>
                        </h3>
                        <?php endif; ?>
                        <form id="rsvpForm" onsubmit="handleRsvpSubmit(event)">
                            <div class="mb-3">
                                <label for="rsvpName" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="rsvpName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="rsvpEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="rsvpEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="rsvpPhone" class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" id="rsvpPhone" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="rsvpAttendance" class="form-label">Konfirmasi Kehadiran</label>
                                <select class="form-select" id="rsvpAttendance" name="attendance" required>
                                    <option value="">Pilih...</option>
                                    <option value="yes">Ya, Saya Akan Hadir</option>
                                    <option value="no">Maaf, Saya Tidak Bisa Hadir</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="rsvpMessage" class="form-label">Pesan/Ucapan</label>
                                <textarea class="form-control" id="rsvpMessage" name="message" rows="3"></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-2"></i>Kirim Konfirmasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function handleRsvpSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    console.log('RSVP Data:', data);
    
    alert('Terima kasih! Konfirmasi kehadiran Anda telah diterima. (Ini adalah demo, data tidak disimpan)');
    
    event.target.reset();
    return false;
}
</script>

