<?php
require_once 'inc/auth.php';
requireRole('company');
require_once 'inc/db.php';
$ad_id = $_GET['ad_id'] ?? null;
if (!$ad_id) {
  echo "Anúncio não especificado.";
  exit;
}
$stmt = $pdo->prepare("SELECT title FROM ads WHERE id = ? AND company_id = ?");
$stmt->execute([$ad_id, getUser()['id']]);
$ad = $stmt->fetch();
if (!$ad) {
  echo "Anúncio inválido ou não autorizado.";
  exit;
}
$stmt = $pdo->prepare("
  SELECT a.id AS application_id, w.id AS worker_id, w.name, w.photo, a.status
  FROM applications a
  JOIN workers w ON a.worker_id = w.id
  WHERE a.ad_id = ?
");
$stmt->execute([$ad_id]);
$candidates = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Candidatos - <?= htmlspecialchars($ad['title']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(120deg, #ffb347 0%, #e63946 100%); min-height:100vh;">
<?php include 'inc/navbar.php'; ?>

<div class="container p-4">
  <h2 class="mb-4" style="color:#fff;">👥 Candidatos para: <?= htmlspecialchars($ad['title']) ?></h2>
  <a href="dashboard_company.php" class="btn btn-sm btn-secondary mb-3" style="color:#fff;">← Voltar ao Painel</a>

  <?php if ($candidates): ?>
    <ul class="list-group">
      <?php foreach ($candidates as $cand): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
            <?php if (!empty($cand['photo']) && file_exists('uploads/' . $cand['photo'])): ?>
              <img src="uploads/<?= htmlspecialchars($cand['photo']) ?>" alt="Foto" style="width:32px;height:32px;object-fit:cover;border-radius:50%;border:1.5px solid #e63946;">
            <?php else: ?>
              <span style="font-size:1.5rem;color:#e63946;">🧑‍🔧</span>
            <?php endif; ?>
            <span><?= htmlspecialchars($cand['name']) ?> — <strong><?= ucfirst($cand['status']) ?></strong></span>
          </div>
          <?php if ($cand['status'] === 'pendente'): ?>
            <form action="select_worker.php" method="POST" class="m-0">
              <input type="hidden" name="application_id" value="<?= $cand['application_id'] ?>">
              <button type="submit" class="btn btn-sm btn-success">Selecionar</button>
            </form>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
  <p style="color:#fff;">Nenhum candidato ainda.</p>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
