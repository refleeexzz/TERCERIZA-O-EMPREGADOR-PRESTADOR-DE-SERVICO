<?php
require_once 'inc/auth.php';
requireRole('worker');
require_once 'inc/db.php';
$user = getUser();
$stmt = $pdo->prepare("
SELECT a.*, ads.title, ads.date, ads.time, ads.price, c.name AS company_name
FROM applications a JOIN ads ON a.ad_id = ads.id
JOIN companies c ON ads.company_id = c.id
WHERE a.worker_id=? ORDER BY a.created_at DESC
");
$stmt->execute([$user['id']]);
$applications = $stmt->fetchAll();
?>
<!DOCTYPE html><html lang="pt-br"><head>
<meta charset="UTF-8"><title>Painel do Prestador</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<body style="background: linear-gradient(120deg, #ffb347 0%, #e63946 100%); min-height:100vh;">
<?php include 'inc/navbar.php'; ?>
<div class="container-fluid p-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0" style="color:#fff;">🧰 Painel do Prestador</h2>
    <div>
      <a href="view_ads.php" class="btn btn-primary me-2">Ver Anúncios</a>
      <a href="logout.php" class="btn btn-outline-danger">Sair</a>
    </div>
  </div>
  <h4 style="color:#fff;">Minhas Candidaturas</h4>
  <div class="row">
  <?php if ($applications): foreach($applications as $app): ?>
    <div class="col-md-6 col-xl-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5><?= htmlspecialchars($app['title']) ?></h5>
          <p>🏢 <?= htmlspecialchars($app['company_name']) ?><br>
             💰 R$ <?= number_format($app['price'],2,',','.') ?><br>
             📅 <?= $app['date'] ?> às <?= $app['time'] ?><br>
             📌 Status: <strong><?= ucfirst($app['status']) ?></strong>
          </p>
        </div>
      </div>
    </div>
  <?php endforeach; else: ?>
  <p style="color:#fff;">Nenhuma candidatura feita.</p>
  <?php endif; ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
