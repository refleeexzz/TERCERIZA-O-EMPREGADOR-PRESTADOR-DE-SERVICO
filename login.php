<?php
$role = $_GET['role'] ?? '';
if (!in_array($role, ['company', 'worker'])) {
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - <?= ucfirst($role) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .login-card {
      max-width: 400px;
      margin: 40px auto;
      border-radius: 12px;
      box-shadow: 0 2px 16px rgba(0,0,0,0.07);
      background: #fff;
      padding: 2rem 2rem 1.5rem 2rem;
    }
  </style>
</head>
<body>
  <?php include 'inc/navbar.php'; ?>
  <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light px-2" style="background: linear-gradient(120deg, #ffb347 0%, #e63946 100%); margin-top:-18px;">
    <div class="card shadow-lg p-4 w-100" style="max-width: 420px; border-radius: 18px;">
      <div class="text-center mb-4">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png" alt="Login" style="width:64px;">
  <h2 class="fw-bold mt-2" style="color:#e63946; background: linear-gradient(90deg,#ffb347,#e63946); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Login - <?= $role === 'company' ? 'Empresa' : 'Prestador' ?></h2>
      </div>
      <form action="login_action.php" method="POST" autocomplete="off">
        <input type="hidden" name="role" value="<?= $role ?>">
        <div class="mb-3">
          <label for="email" class="form-label fw-semibold">Email</label>
          <input type="email" class="form-control form-control-lg" id="email" name="email" required placeholder="seu@email.com">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label fw-semibold">Senha</label>
          <input type="password" class="form-control form-control-lg" id="password" name="password" required placeholder="Sua senha">
        </div>
        <button type="submit" class="btn btn-lg w-100 mt-2" style="background: linear-gradient(90deg,#ffb347 0%, #e63946 100%); color:#fff; font-weight:600; border:none; border-radius:12px; box-shadow:0 2px 8px #e6394622;">Entrar</button>
        <div class="text-center mt-3">
          <a href="register.php" class="btn btn-sm rounded-pill px-4" style="background:#e63946;color:#fff;font-weight:600;border:none;">Criar conta</a>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
