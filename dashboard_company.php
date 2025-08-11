<?php
require_once 'inc/auth.php';
requireRole('company');
require_once 'inc/db.php';
$user = getUser();
$stmt = $pdo->prepare("SELECT * FROM ads WHERE company_id=? ORDER BY created_at DESC");
$stmt->execute([$user['id']]);
$ads = $stmt->fetchAll();
?>
<!DOCTYPE html><html lang="pt-br"><head>
<meta charset="UTF-8"><title>Painel da Empresa</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<body style="background: linear-gradient(120deg, #ffb347 0%, #e63946 100%); min-height:100vh;">
<?php include 'inc/navbar.php'; ?>
<div class="container-fluid p-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
      <?php
        $logo = '';
        if (isset($user['id'])) {
          $stmtLogo = $pdo->prepare('SELECT logo FROM companies WHERE id = ?');
          $stmtLogo->execute([$user['id']]);
          $logoRow = $stmtLogo->fetch();
          if ($logoRow && $logoRow['logo'] && file_exists('uploads/' . $logoRow['logo'])) {
            $logo = 'uploads/' . $logoRow['logo'];
          }
        }
      ?>
      <?php if ($logo): ?>
        <img src="<?= $logo ?>" alt="Logo" style="width:48px;height:48px;object-fit:cover;border-radius:50%;border:2px solid #e63946;box-shadow:0 1px 4px #e6394622;">
      <?php else: ?>
        <span style="font-size:2rem;color:#e63946;">👔</span>
      <?php endif; ?>
      <h2 class="mb-0">Painel da Empresa</h2>
    </div>
    <div>
      <a href="create_ad.php" class="btn btn-lg rounded-pill fw-semibold me-2" style="background: linear-gradient(90deg,#ffb347 0%, #e63946 100%); color:#fff; border:none; box-shadow:0 4px 16px #e6394644, 0 2px 12px #ffb34755; outline: 3px solid #fff; outline-offset: -4px; text-shadow: 0 2px 8px #e63946aa, 0 1px 4px #ffb34799;">+ Criar Anúncio</a>
      <a href="upload_docs.php" class="btn btn-lg rounded-pill fw-semibold me-2" style="background: linear-gradient(90deg,#e63946 0%, #ffb347 100%); color:#fff; border:none; box-shadow:0 4px 16px #e6394644, 0 2px 12px #ffb34755; outline: 3px solid #fff; outline-offset: -4px; text-shadow: 0 2px 8px #e63946aa, 0 1px 4px #ffb34799;">Alterar Logo</a>
      <a href="logout.php" class="btn btn-lg rounded-pill fw-semibold" style="background: #fff; color:#e63946; border:1.5px solid #e63946; box-shadow:0 2px 8px #e6394622;">Sair</a>
    </div>
  </div>
  <div class="row">
    <?php if ($ads): foreach ($ads as $ad): ?>
      <div class="col-md-6 col-xl-4 mb-4">
        <div class="card shadow-sm" style="border-radius:18px; background: linear-gradient(120deg, #fff 80%, #ffe5d0 100%); box-shadow: 0 4px 24px #e6394622, 0 1.5px 8px #ffb34733;">
          <div class="card-body" style="padding: 1.5rem 1rem;">
            <h5 style="color:#e63946; font-weight:600; text-shadow:0 1px 4px #ffb34744;"><?= htmlspecialchars($ad['title']) ?></h5>
            <p style="color:#444;">
               💰 R$ <?= number_format($ad['price'],2,',','.') ?><br>
               📅 <?= $ad['date'] ?> às <?= $ad['time'] ?>
            </p>
            <a href="view_candidates.php?ad_id=<?= $ad['id'] ?>" class="btn btn-sm rounded-pill fw-semibold" style="background: linear-gradient(90deg,#ffb347 0%, #e63946 100%); color:#fff; border:none; box-shadow:0 2px 8px #e6394622;">Ver Candidatos</a>
          </div>
        </div>
      </div>
    <?php endforeach; else: ?>
      <p>Nenhum anúncio criado.</p>
    <?php endif; ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
