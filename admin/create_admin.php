<?php
require_once '../config/database.php';

try {
    $admin_id = "ADMIN001";
    $password = password_hash("admin123", PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("
        INSERT INTO users (institutional_id, first_name, last_name, password, role) 
        VALUES (?, ?, ?, ?, 'admin')
    ");
    
    $stmt->execute([$admin_id, "Admin", "System", $password]);
    echo "Administrator created successfully!";
    echo "<br>ID: " . $admin_id;
    echo "<br>Password: admin123";
    echo "<br><br><a href='../login.php'>Go to login</a>";
    
} catch(PDOException $e) {
    if($e->getCode() == 23000) {
        echo "An administrator with this ID already exists.";
    } else {
        echo "Error creating administrator: " . $e->getMessage();
    }
}
?>