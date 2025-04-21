<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
requireAdmin();

// Generar nuevo QR si se solicita
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Desactivar códigos anteriores
    $stmt = $pdo->prepare("UPDATE qr_codes SET is_active = 0");
    $stmt->execute();

    // Generar nuevo código único
    $new_code = uniqid('QR_', true);
    $stmt = $pdo->prepare("INSERT INTO qr_codes (code) VALUES (?)");
    $stmt->execute([$new_code]);
}

// Obtener código QR activo
$stmt = $pdo->prepare("SELECT * FROM qr_codes WHERE is_active = 1");
$stmt->execute();
$active_qr = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generar QR - Sistema de Asistencia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../img/Senati_logo.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
</head>
<body>
    <nav class="nav">
        <div class="nav-container">
            <div>
                <a href="dashboard.php">← Volver al Panel</a>
            </div>
            <div>
                <a href="../logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Gestión de Código QR</h2>
        
        <div class="qr-container">
            <?php if ($active_qr): ?>
                <h3>Código QR Activo</h3>
                <div id="qrcode"></div>
                <p>Código: <?php echo htmlspecialchars($active_qr['code']); ?></p>
            <?php else: ?>
                <p>No hay código QR activo</p>
            <?php endif; ?>

            <form method="POST" action="">
                <button type="submit">Generar Nuevo QR</button>
            </form>
        </div>
    </div>

    <script>
        <?php if ($active_qr): ?>
        window.onload = function() {
            var qr = qrcode(0, 'M');
            qr.addData('<?php echo $active_qr['code']; ?>');
            qr.make();
            document.getElementById('qrcode').innerHTML = qr.createImgTag(5);
        };
        <?php endif; ?>
    </script>
</body>
</html>