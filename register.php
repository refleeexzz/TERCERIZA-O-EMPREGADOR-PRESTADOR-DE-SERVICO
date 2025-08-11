<?php
session_start();

$host = 'localhost';
$db   = 'terceirzação';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erro na conexão com banco: " . $e->getMessage());
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $user_type = $_POST['user_type'] ?? '';
    $area_service = trim($_POST['area_service'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $cnpj = trim($_POST['cnpj'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    // Validações básicas
    if (!$name) $errors[] = "Nome é obrigatório.";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email inválido.";
    if (strlen($password) < 6) $errors[] = "Senha deve ter ao menos 6 caracteres.";
    if ($password !== $password_confirm) $errors[] = "Senhas não conferem.";
    if ($user_type !== 'prestador' && $user_type !== 'empresa') $errors[] = "Tipo de usuário inválido.";

    if ($user_type === 'prestador') {
        if (!$area_service) $errors[] = "Área de serviço é obrigatória para prestador.";
        if (!$cpf) $errors[] = "CPF é obrigatório para prestador.";
    }
    if ($user_type === 'empresa') {
        if (!$cnpj) $errors[] = "CNPJ é obrigatório para empresa.";
    }

    // Verificar se email já existe na tabela correta
    if ($user_type === 'empresa') {
        $stmt = $pdo->prepare("SELECT id FROM companies WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) $errors[] = "Email já cadastrado para empresa.";
    } else if ($user_type === 'prestador') {
        $stmt = $pdo->prepare("SELECT id FROM workers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) $errors[] = "Email já cadastrado para prestador.";
    }   

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        if ($user_type === 'empresa') {
            $stmt = $pdo->prepare("INSERT INTO companies (name, email, password, cnpj, phone, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $name,
                $email,
                $password_hash,
                $cnpj,
                $phone ?: null
            ]);
            $_SESSION['user'] = [
                'id' => $pdo->lastInsertId(),
                'role' => 'company',
                'name' => $name,
                'email' => $email
            ];
            header('Location: dashboard_company.php');
            exit;
        } else if ($user_type === 'prestador') {
            $stmt = $pdo->prepare("INSERT INTO workers (name, email, password, cpf, area_service, phone, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $name,
                $email,
                $password_hash,
                $cpf,
                $area_service,
                $phone ?: null
            ]);
            $_SESSION['user'] = [
                'id' => $pdo->lastInsertId(),
                'role' => 'worker',
                'name' => $name,
                'email' => $email
            ];
            header('Location: dashboard_worker.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro - Seu Site</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #d94f04, #ff6e0a);
        }
    </style>
</head>
<body>
<?php include 'inc/navbar.php'; ?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light px-2" style="background: linear-gradient(120deg, #ffb347 0%, #e63946 100%); margin-top:-18px;">
    <div class="card shadow-lg p-4 w-100" style="max-width: 420px; border-radius: 18px; background: #fff; margin-top: 60px;">
        <div class="text-center mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Registrar" style="width:64px;">
            <h2 class="fw-bold mt-2" style="color:#e63946; background: linear-gradient(90deg,#ffb347,#e63946); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Crie sua Conta</h2>
            <p class="text-muted">Conecte-se a oportunidades e clientes!</p>
        </div>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="POST" id="registerForm" novalidate autocomplete="off">
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nome</label>
                <input type="text" class="form-control form-control-lg" id="name" name="name" required placeholder="Seu nome completo">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control form-control-lg" id="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Senha</label>
                <input type="password" class="form-control form-control-lg" id="password" name="password" required minlength="6" placeholder="Mínimo 6 caracteres">
            </div>
            <div class="mb-3">
                <label for="password_confirm" class="form-label fw-semibold">Confirme a Senha</label>
                <input type="password" class="form-control form-control-lg" id="password_confirm" name="password_confirm" required placeholder="Repita a senha">
            </div>
            <div class="mb-3">
                <label for="user_type" class="form-label fw-semibold">Tipo de Conta</label>
                <select class="form-select form-select-lg" id="user_type" name="user_type" required>
                    <option value="">Selecione...</option>
                    <option value="prestador">Prestador de Serviço</option>
                    <option value="empresa">Empresa</option>
                </select>
            </div>
            <div class="mb-3 prestador-field d-none">
                <label for="area_service" class="form-label fw-semibold">Área de Serviço</label>
                <input type="text" class="form-control form-control-lg" id="area_service" name="area_service" placeholder="Ex: Eletricista, Pintor">
            </div>
            <div class="mb-3 prestador-field d-none">
                <label for="cpf" class="form-label fw-semibold">CPF</label>
                <input type="text" class="form-control form-control-lg" id="cpf" name="cpf" maxlength="14" placeholder="000.000.000-00">
            </div>
            <div class="mb-3 empresa-field d-none">
                <label for="cnpj" class="form-label fw-semibold">CNPJ</label>
                <input type="text" class="form-control form-control-lg" id="cnpj" name="cnpj" maxlength="18" placeholder="00.000.000/0000-00">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label fw-semibold">Telefone</label>
                <input type="text" class="form-control form-control-lg" id="phone" name="phone" maxlength="15" pattern="\(\d{2}\) \d{5}-\d{4}" title="Telefone no formato (99) 99999-9999" placeholder="(99) 99999-9999">
            </div>
            <button type="submit" class="btn btn-lg w-100 mt-2" style="background: linear-gradient(90deg,#ffb347 0%, #e63946 100%); color:#fff; font-weight:600; border:none; border-radius:12px; box-shadow:0 2px 8px #e6394622;">Registrar</button>
        </form>
        <div class="text-center mt-3">
            <a href="login.php?role=company" class="btn btn-sm rounded-pill px-4 me-2" style="background:#e63946;color:#fff;font-weight:600;border:none;">Entrar como empresa</a>
            <a href="login.php?role=worker" class="btn btn-sm rounded-pill px-4" style="background:#ffb347;color:#fff;font-weight:600;border:none;">Já sou prestador</a>
        </div>
    </div>
</div>


      
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('#cnpj').mask('00.000.000/0000-00');
        $('#cpf').mask('000.000.000-00');
        $('#phone').mask('(00) 00000-0000');
    });
    const userTypeSelect = document.getElementById('user_type');
    const prestadorFields = document.querySelectorAll('.prestador-field');
    const empresaFields = document.querySelectorAll('.empresa-field');
    function toggleFields() {
        const val = userTypeSelect.value;
        if(val === 'prestador'){
            prestadorFields.forEach(f => f.classList.remove('d-none'));
            empresaFields.forEach(f => f.classList.add('d-none'));
            document.getElementById('area_service').setAttribute('required', 'required');
            document.getElementById('cpf').setAttribute('required', 'required');
            document.getElementById('cnpj').removeAttribute('required');
        } else if(val === 'empresa'){
            prestadorFields.forEach(f => f.classList.add('d-none'));
            empresaFields.forEach(f => f.classList.remove('d-none'));
            setTimeout(function() {
                document.getElementById('cnpj').setAttribute('required', 'required');
            }, 10);
            document.getElementById('area_service').removeAttribute('required');
            document.getElementById('cpf').removeAttribute('required');
        } else {
            prestadorFields.forEach(f => f.classList.add('d-none'));
            empresaFields.forEach(f => f.classList.add('d-none'));
            document.getElementById('cnpj').removeAttribute('required');
            document.getElementById('area_service').removeAttribute('required');
            document.getElementById('cpf').removeAttribute('required');
        }
    }
    userTypeSelect.addEventListener('change', toggleFields);
    window.addEventListener('DOMContentLoaded', toggleFields);
</script>
</body>
</html>
