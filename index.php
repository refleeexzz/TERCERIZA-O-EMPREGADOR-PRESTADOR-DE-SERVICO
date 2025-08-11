<?php
require_once 'inc/db.php';
require_once 'inc/auth.php';
$stmt = $pdo->query("SELECT id, name, logo FROM companies ORDER BY id DESC");
$companies = $stmt->fetchAll();
?>
<!DOCTYPE html><html lang="pt-br"><head>
<meta charset="UTF-8"><title>Trabalho Terceirizado</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.empresa-card {
  transition:transform .2s;
  border:none;
  border-radius:18px;
  background: linear-gradient(120deg, #fff 80%, #ffe5d0 100%);
  box-shadow: 0 4px 24px #e6394622, 0 1.5px 8px #ffb34733;
  min-width:0;
}
.empresa-card:hover {
  transform:scale(1.04);
  box-shadow: 0 8px 32px #e6394644, 0 2px 12px #ffb34755;
}
.empresa-card .card-body {
  padding: 1.5rem 1rem;
}
.empresa-card img {
  width:64px;
  height:64px;
  object-fit:cover;
  border-radius:50%;
  border:2px solid #e63946;
  box-shadow:0 2px 8px #e6394622;
  margin-bottom: 0.5rem;
  background: #fff;
}
.empresa-card .empresa-nome {
  font-size:1.1rem;
  font-weight:600;
  color:#e63946;
  margin-top:0.5rem;
  margin-bottom:0;
  text-shadow:0 1px 4px #ffb34744;
}
.empresa-card .empresa-icon {
  font-size:56px;
  color:#e63946;
  margin-bottom:0.5rem;
}
@media (max-width: 575px) {
  .empresa-card .card-body { padding: 1rem 0.5rem; }
  .empresa-card img, .empresa-card .empresa-icon { width:48px; height:48px; font-size:40px; }
}
</style>
</head><body>
<body style="background: linear-gradient(120deg, #ffb347 0%, #e63946 100%); min-height:100vh;">
<?php include 'inc/navbar.php'; ?>
<div class="container text-center mt-5" style="margin-top:-18px;">
  <h2 style="color:#fff;text-shadow:0 2px 8px #e63946aa;">Conecte empresas e trabalhadores com agilidade</h2>
</div>
<div class="container mt-5" id="empresas" style="margin-top:-18px;">
  <h4 style="color:#fff;text-shadow:0 2px 8px #e63946aa;">Empresas Registradas</h4>
  <?php if ($companies): ?>
  <div class="row g-4 justify-content-center">
    <?php
      $total = count($companies);
      foreach ($companies as $c):
        if ($total <= 2) {
          $colClass = 'col-12 col-md-6';
        } elseif ($total <= 4) {
          $colClass = 'col-12 col-sm-6 col-md-4';
        } elseif ($total <= 8) {
          $colClass = 'col-12 col-sm-6 col-md-4 col-lg-3';
        } else {
          $colClass = 'col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2';
        }
    ?>
      <div class="<?= $colClass ?> d-flex align-items-stretch">
        <a href="view_ads.php?company_id=<?= $c['id'] ?>" class="text-decoration-none text-dark w-100">
          <div class="card empresa-card shadow-sm w-100 h-100">
            <div class="card-body text-center">
              <?php
                $logo = '';
                if (isset($c['logo']) && $c['logo'] && file_exists('uploads/' . $c['logo'])) {
                  $logo = 'uploads/' . $c['logo'];
                }
              ?>
              <?php if ($logo): ?>
                <img src="<?= $logo ?>" alt="Logo" class="empresa-logo">
              <?php else: ?>
                <span class="empresa-icon">🏢</span>
              <?php endif; ?>
              <h6 class="empresa-nome"><?= htmlspecialchars($c['name']) ?></h6>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <p style="color:#fff;">Nenhuma empresa registrada.</p>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body></html>