<?php
require_once 'auth.php';
$user = getUser();
?>
<nav class="navbar navbar-expand-lg shadow-sm" style="background: linear-gradient(90deg, #e63946 60%, #ffb347 100%); border-radius: 0 0 18px 18px;">
  <div class="container py-1">
    <a class="navbar-brand fw-bold text-white px-3" style="font-size:1.5rem;letter-spacing:1px;" href="index.php">Trabalho Terceirizado</a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarMain">
      <ul class="navbar-nav align-items-center mb-2 mb-lg-0 gap-2">
        <?php if ($user): ?>
          <li class="nav-item">
            <a class="btn btn-warning rounded-pill px-4 fw-semibold" href="<?= $user['role'] === 'company' ? 'dashboard_company.php' : 'dashboard_worker.php' ?>">Painel</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-outline-light rounded-pill px-4 fw-semibold" href="logout.php">Sair</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="btn btn-outline-light rounded-pill px-4 fw-semibold" href="login.php?role=company">Login Empresa</a>
          </li>
          <li class="nav-item">
            <a class="btn rounded-pill px-4 fw-semibold" style="background:#ffb347;color:#fff;font-weight:600;border:1px solid #fff;" href="login.php?role=worker">Login Prestador</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<style>
  .navbar {
    min-height: 64px;
    box-shadow: 0 2px 12px rgba(230,57,70,0.08);
  }
  .navbar .navbar-brand {
    text-shadow: 0 1px 2px rgba(0,0,0,0.08);
  }
  .navbar .btn {
    transition: box-shadow .2s, background .2s;
  }
  .navbar .btn-warning {
    background: #ffb347;
    border: none;
    color: #fff;
  }
  .navbar .btn-warning:hover {
    background: #e63946;
    color: #fff;
    box-shadow: 0 2px 8px rgba(230,57,70,0.10);
  }
  .navbar .btn-outline-light:hover, .navbar .btn-light:hover {
    box-shadow: 0 2px 8px rgba(255,179,71,0.10);
  }
  .navbar .btn-light {
    background: #fff;
    color: #e63946;
    border: none;
  }
  .navbar .btn-light:hover {
    background: #ffb347;
    color: #fff;
    box-shadow: 0 2px 8px rgba(255,179,71,0.10);
  }
</style>
