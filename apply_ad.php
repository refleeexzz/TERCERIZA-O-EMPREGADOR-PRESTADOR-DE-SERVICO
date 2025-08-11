<?php
require_once 'inc/auth.php';
requireRole('worker');
require_once 'inc/db.php';
$ad_id = $_POST['ad_id'] ?? null;
$worker_id = getUser()['id'];
if ($ad_id) {
    $stmt = $pdo->prepare('SELECT * FROM applications WHERE ad_id = ? AND worker_id = ?');
    $stmt->execute([$ad_id, $worker_id]);
    $already = $stmt->fetch();
    $msg = '';
    $type = '';
    if ($already) {
        $msg = 'Você já se candidatou a este anúncio!';
        $type = 'warning';
    } else {
        $stmt = $pdo->prepare('INSERT INTO applications (ad_id, worker_id) VALUES (?, ?)');
        $stmt->execute([$ad_id, $worker_id]);
        $msg = 'Candidatura enviada com sucesso!';
        $type = 'success';
    }
    echo '<!DOCTYPE html><html lang="pt-br"><head>';
    echo '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<title>Status da Candidatura</title>';
    echo '</head><body style="background: linear-gradient(120deg, #ffb347 0%, #e63946 100%); min-height:100vh;">';
    echo '<div class="container d-flex align-items-center justify-content-center min-vh-100">';
    echo '<div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,0.15);">';
    echo '<div class="modal-dialog modal-dialog-centered">';
    echo '<div class="modal-content" style="border-radius:18px;">';
    echo '<div class="modal-header" style="background: linear-gradient(90deg,#ffb347 60%,#e63946 100%);">';
    echo '<h5 class="modal-title fw-bold text-white">Status da Candidatura</h5>';
    echo '</div>';
    echo '<div class="modal-body text-center">';
    echo '<div class="alert alert-' . $type . ' fw-semibold" style="font-size:1.2rem;">' . $msg . '</div>';
    echo '</div>';
    echo '<div class="modal-footer justify-content-center">';
    echo '<a href="view_ads.php" class="btn btn-lg" style="background: linear-gradient(90deg,#ffb347 60%,#e63946 100%);color:#fff;font-weight:600;border:none;border-radius:12px;">Voltar para anúncios</a>';
    echo '</div>';
    echo '</div></div></div></div></div>';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
    echo '</body></html>';
    exit;
} else {
    echo '<!DOCTYPE html><html lang="pt-br"><head>';
    echo '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<title>Status da Candidatura</title>';
    echo '</head><body style="background: linear-gradient(120deg, #ffb347 0%, #e63946 100%); min-height:100vh;">';
    echo '<div class="container d-flex align-items-center justify-content-center min-vh-100">';
    echo '<div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,0.15);">';
    echo '<div class="modal-dialog modal-dialog-centered">';
    echo '<div class="modal-content" style="border-radius:18px;">';
    echo '<div class="modal-header" style="background: linear-gradient(90deg,#ffb347 60%,#e63946 100%);">';
    echo '<h5 class="modal-title fw-bold text-white">Status da Candidatura</h5>';
    echo '</div>';
    echo '<div class="modal-body text-center">';
    echo '<div class="alert alert-danger fw-semibold" style="font-size:1.2rem;">Anúncio inválido.</div>';
    echo '</div>';
    echo '<div class="modal-footer justify-content-center">';
    echo '<a href="view_ads.php" class="btn btn-lg" style="background: linear-gradient(90deg,#ffb347 60%,#e63946 100%);color:#fff;font-weight:600;border:none;border-radius:12px;">Voltar para anúncios</a>';
    echo '</div>';
    echo '</div></div></div></div></div>';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
    echo '</body></html>';
    exit;
}