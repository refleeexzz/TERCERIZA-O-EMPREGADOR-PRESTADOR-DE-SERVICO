<?php
require_once 'inc/auth.php';
requireRole('company');
require_once 'inc/db.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Criar Anúncio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'inc/navbar.php'; ?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light px-2" style="background: linear-gradient(120deg, #00c3ff 0%, #ffff1c 100%);">
  <div class="card shadow-lg p-4 w-100" style="max-width: 420px; border-radius: 18px;">
    <div class="text-center mb-4">
      <img src="https://cdn-icons-png.flaticon.com/512/3135/3135789.png" alt="Anúncio" style="width:64px;">
      <h2 class="fw-bold mt-2" style="color:#00c3ff;">📢 Criar Novo Anúncio</h2>
    </div>
    <form action="create_ad_action.php" method="POST" autocomplete="off">
      <div class="mb-3">
        <label class="form-label fw-semibold">Título do Serviço</label>
        <input type="text" name="title" class="form-control form-control-lg" required placeholder="Ex: Pintura de apartamento">
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Preço (R$)</label>
        <input type="number" name="price" class="form-control form-control-lg" step="0.01" required placeholder="Ex: 150.00">
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Data</label>
        <input type="date" name="date" class="form-control form-control-lg" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Horário</label>
        <input type="time" name="time" class="form-control form-control-lg" required>
      </div>
      <button type="submit" class="btn btn-success btn-lg w-100 mt-2" style="background: linear-gradient(90deg,#00c3ff 60%,#ffff1c 100%);color:#222;border:none;">Publicar Anúncio</button>
      <a href="dashboard_company.php" class="btn btn-outline-secondary btn-lg w-100 mt-2">Cancelar</a>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
