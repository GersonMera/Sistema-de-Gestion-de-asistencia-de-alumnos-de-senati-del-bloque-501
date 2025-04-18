<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
requireAuth();

// Recibir y decodificar los datos JSON
$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false, 'message' => ''];

try {
    // Verificar el código QR
    $stmt = $pdo->prepare("SELECT * FROM qr_codes WHERE code = ? AND is_active = 1");
    $stmt->execute([$data['qr_code']]);
    $qr = $stmt->fetch();

    if (!$qr) {
        throw new Exception("Código QR inválido o inactivo");
    }

    // Verificar si ya existe un registro para hoy
    $stmt = $pdo->prepare("
        SELECT * FROM attendance_records 
        WHERE user_id = ? AND date = CURRENT_DATE()
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $today_record = $stmt->fetch();

    if (!$today_record) {
        // Crear nuevo registro (entrada)
        $stmt = $pdo->prepare("
            INSERT INTO attendance_records (user_id, date, check_in) 
            VALUES (?, CURRENT_DATE(), CURRENT_TIME())
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $response['message'] = "Entrada registrada exitosamente";
    } else if (!$today_record['check_out']) {
        // Actualizar registro existente (salida)
        $stmt = $pdo->prepare("
            UPDATE attendance_records 
            SET check_out = CURRENT_TIME() 
            WHERE user_id = ? AND date = CURRENT_DATE()
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $response['message'] = "Salida registrada exitosamente";
    } else {
        throw new Exception("Ya tienes registrada la entrada y salida de hoy");
    }

    $response['success'] = true;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>