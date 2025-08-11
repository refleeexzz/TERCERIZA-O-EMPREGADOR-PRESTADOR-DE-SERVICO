<?php
require_once 'inc/auth.php';
requireRole('company');
require_once 'inc/db.php';
$application_id = $_POST['application_id'] ?? null;
if ($application_id) {
    $stmt = $pdo->prepare('UPDATE applications SET status = "selecionado" WHERE id = ?');
    $stmt->execute([$application_id]);
    $stmt = $pdo->prepare('
        UPDATE applications 
        SET status = "recusado"
        WHERE ad_id = (SELECT ad_id FROM applications WHERE id = ?) AND id != ?
    ');
    $stmt->execute([$application_id, $application_id]);
    // Layout bonito de confirmação
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
      <meta charset="UTF-8">
      <title>Trabalhador Selecionado</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <style>
        body {
          background: linear-gradient(120deg, #ffb347 0%, #e63946 100%);
          min-height: 100vh;
        }
        .card-select {
          max-width: 400px;
          margin: 80px auto;
          border-radius: 18px;
          box-shadow: 0 4px 24px #e6394622, 0 1.5px 8px #ffb34733;
          background: linear-gradient(120deg, #fff 80%, #ffe5d0 100%);
          padding: 2rem 1.5rem;
          text-align: center;
        }
        .card-select .icon {
          font-size: 3rem;
          color: #e63946;
          margin-bottom: 1rem;
        }
        .card-select h3 {
          color: #e63946;
          font-weight: 700;
          margin-bottom: 0.5rem;
          text-shadow: 0 1px 4px #ffb34744;
        }
        .card-select p {
          color: #444;
          margin-bottom: 1.5rem;
        }
        .btn-ret {
          background: linear-gradient(90deg,#ffb347 0%, #e63946 100%);
          color: #fff;
          font-weight: 600;
          border: none;
          border-radius: 12px;
          box-shadow: 0 2px 8px #e6394622;
          padding: 0.75rem 2rem;
        }
        .btn-ret:hover {
          background: linear-gradient(90deg,#e63946 0%, #ffb347 100%);
        }
      </style>
    </head>
    <body>
      <?php include 'inc/navbar.php'; ?>
      <div class="container">
        <div class="card-select">
          <div class="icon">🎉</div>
          <h3>Trabalhador Selecionado!</h3>
          <p>Você selecionou um prestador para este anúncio.<br>Os demais foram recusados automaticamente.</p>
          <a href="dashboard_company.php" class="btn btn-ret">Voltar ao Painel</a>
        </div>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
} else {
    // Mensagem de erro estilizada
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
      <meta charset="UTF-8">
      <title>Erro</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <style>
        body {
          background: linear-gradient(120deg, #ffb347 0%, #e63946 100%);
          min-height: 100vh;
        }
        .card-select {
          max-width: 400px;
          margin: 80px auto;
          border-radius: 18px;
          box-shadow: 0 4px 24px #e6394622, 0 1.5px 8px #ffb34733;
          background: linear-gradient(120deg, #fff 80%, #ffe5d0 100%);
          padding: 2rem 1.5rem;
          text-align: center;
        }
        .card-select .icon {
          font-size: 3rem;
          color: #e63946;
          margin-bottom: 1rem;
        }
        .card-select h3 {
          color: #e63946;
          font-weight: 700;
          margin-bottom: 0.5rem;
          text-shadow: 0 1px 4px #ffb34744;
        }
        .card-select p {
          color: #444;
          margin-bottom: 1.5rem;
        }
        .btn-ret {
          background: linear-gradient(90deg,#ffb347 0%, #e63946 100%);
          color: #fff;
          font-weight: 600;
          border: none;
          border-radius: 12px;
          box-shadow: 0 2px 8px #e6394622;
          padding: 0.75rem 2rem;
        }
        .btn-ret:hover {
          background: linear-gradient(90deg,#e63946 0%, #ffb347 100%);
        }
      </style>
    </head>
    <body>
      <?php include 'inc/navbar.php'; ?>
      <div class="container">
        <div class="card-select">
          <div class="icon">⚠️</div>
          <h3>Erro!</h3>
          <p>Candidatura inválida.</p>
          <a href="dashboard_company.php" class="btn btn-ret">Voltar ao Painel</a>
        </div>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}
