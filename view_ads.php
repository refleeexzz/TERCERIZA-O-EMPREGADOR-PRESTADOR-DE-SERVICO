<?php
require_once 'inc/auth.php';
requireRole('worker');
require_once 'inc/db.php';
$companyId = $_GET['company_id'] ?? null;
if ($companyId) {
  $stmt = $pdo->prepare("SELECT ads.*, c.name AS company_name FROM ads JOIN companies c ON ads.company_id = c.id WHERE c.id = ?");
  $stmt->execute([$companyId]);
} else {
  $stmt = $pdo->query("SELECT ads.*, c.name AS company_name FROM ads JOIN companies c ON ads.company_id = c.id ORDER BY ads.date DESC");
}
$ads = $stmt->fetchAll();
$stmt = $pdo->query('SELECT ads.*, companies.name as company_name FROM ads JOIN companies ON ads.company_id = companies.id ORDER BY ads.created_at DESC');
$ads = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  
  <meta charset="UTF-8" />
  <title>Anúncios Disponíveis</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>
<body style="background: linear-gradient(120deg, #ffb347 0%, #e63946 100%); min-height:100vh;">
<?php include 'inc/navbar.php'; ?>
  <div class="container">
    <h2 class="mb-4" style="color:#fff;">Anúncios de Serviços Disponíveis</h2>
    <a href="dashboard_worker.php" class="btn btn-link mb-3" style="color:#fff;">Voltar ao Painel</a>

    <?php if ($ads): ?>
    <div class="row g-4">
      <?php foreach ($ads as $ad): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card shadow-lg h-100" style="border-radius:18px;">
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h5 class="fw-bold" style="color:#e63946;"><?= htmlspecialchars($ad['title']) ?></h5>
                <p class="mb-1" style="color:#222;font-size:1.1rem;">
                  <span style="color:#ffb347;font-weight:600;">Empresa:</span> <?= htmlspecialchars($ad['company_name']) ?><br>
                  <span style="color:#ffb347;font-weight:600;">Preço:</span> R$ <?= number_format($ad['price'], 2, ',', '.') ?><br>
                  <span style="color:#ffb347;font-weight:600;">Data:</span> <?= htmlspecialchars($ad['date']) ?> <br>
                  <span style="color:#ffb347;font-weight:600;">Horário:</span> <?= htmlspecialchars($ad['time']) ?>
                </p>
              </div>
              <form action="apply_ad.php" method="POST" class="mt-3">
                <input type="hidden" name="ad_id" value="<?= $ad['id'] ?>">
                <button type="submit" class="btn btn-lg w-100" style="background: linear-gradient(90deg,#ffb347 60%,#e63946 100%);color:#fff;font-weight:600;border:none;border-radius:12px;box-shadow:0 2px 8px #e6394622;">Quero este trabalho</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
  <p style="color:#fff;">Nenhum anúncio disponível no momento.</p>
    <?php endif; ?>
  </div>
</body>
</html>
