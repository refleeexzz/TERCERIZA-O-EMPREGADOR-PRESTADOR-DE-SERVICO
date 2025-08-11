<?php
require_once 'inc/auth.php';
require_once 'inc/db.php';
$user = getUser();
if (!$user) {
  header('Location: login.php');
  exit;
}
$role = $user['role'];
$uploadDir = 'uploads/';
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
  $file = $_FILES['photo'];
  if ($file['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($ext, $allowed)) {
      $filename = uniqid() . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
      $dest = $uploadDir . $filename;
      if (move_uploaded_file($file['tmp_name'], $dest)) {
        if ($role === 'company') {
          $stmt = $pdo->prepare('UPDATE companies SET logo = ? WHERE id = ?');
          $stmt->execute([$filename, $user['id']]);
        } else {
          $stmt = $pdo->prepare('UPDATE workers SET photo = ? WHERE id = ?');
          $stmt->execute([$filename, $user['id']]);
        }
        $success = 'Foto enviada com sucesso!';
      } else {
        $error = 'Erro ao salvar o arquivo.';
      }
    } else {
      $error = 'Formato de imagem não permitido.';
    }
  } else {
    $error = 'Erro no upload.';
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Upload de Foto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'inc/navbar.php'; ?>
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light px-2" style="background: linear-gradient(120deg, #00c3ff 0%, #ffff1c 100%);">
  <div class="card shadow-lg p-4 w-100" style="max-width: 420px; border-radius: 18px;">
    <div class="text-center mb-4">
      <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Foto" style="width:64px;">
      <h3 class="fw-bold mt-2" style="color:#00c3ff;">Enviar <?= $role === 'company' ? 'Logo da Empresa' : 'Foto de Perfil' ?></h3>
    </div>
    <?php if ($success): ?>
      <div class="alert alert-success"> <?= $success ?> </div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"> <?= $error ?> </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" autocomplete="off">
      <div class="mb-3">
        <label class="form-label fw-semibold">Selecione uma imagem</label>
        <input type="file" name="photo" class="form-control form-control-lg" accept="image/*" required>
      </div>
      <button type="submit" class="btn btn-success btn-lg w-100 mt-2" style="background: linear-gradient(90deg,#00c3ff 60%,#ffff1c 100%);color:#222;border:none;">Enviar</button>
    </form>
  </div>
</div>
</body>
</html>
