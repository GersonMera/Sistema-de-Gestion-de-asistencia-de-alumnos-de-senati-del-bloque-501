<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
requireAdmin();

$user_id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("
        UPDATE users 
        SET institutional_id = ?, first_name = ?, last_name = ?, role = ?
        WHERE id = ?
    ");
    
    $stmt->execute([
        $_POST['institutional_id'],
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['role'],
        $user_id
    ]);

    if (!empty($_POST['password'])) {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([password_hash($_POST['password'], PASSWORD_DEFAULT), $user_id]);
    }

    header("Location: manage_users.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario - Sistema de Asistencia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../img/Senati_logo.png" type="image/x-icon">
</head>
<body>
    <nav class="nav">
        <div class="nav-container">
            <div>
                <a href="manage_users.php">← Volver a Usuarios</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Editar Usuario</h2>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>ID Institucional:</label>
                <input type="text" name="institutional_id" value="<?php echo htmlspecialchars($user['institutional_id']); ?>" required>
            </div>

            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Rol:</label>
                <select name="role" <?php echo $user['id'] == $_SESSION['user_id'] ? 'disabled' : ''; ?>>
                    <option value="student" <?php echo $user['role'] == 'student' ? 'selected' : ''; ?>>Estudiante</option>
                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nueva Contraseña (dejar en blanco para mantener la actual):</label>
                <input type="password" name="password">
            </div>

            <button type="submit">Actualizar Usuario</button>
        </form>
    </div>
</body>
</html>