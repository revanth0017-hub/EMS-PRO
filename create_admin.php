<?php
include('db.php');

try {
    // Check if admin already exists
    $check_sql = "SELECT * FROM admin WHERE username = 'admin'";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() == 0) {
        // Create admin table if it doesn't exist
        $create_table_sql = "CREATE TABLE IF NOT EXISTS admin (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL
        )";
        $pdo->exec($create_table_sql);

        // Insert admin credentials
        $sql = "INSERT INTO admin (username, password) VALUES (:username, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => 'admin',
            'password' => 'admin123'
        ]);
        echo "Admin account created successfully!";
    } else {
        echo "Admin account already exists!";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>