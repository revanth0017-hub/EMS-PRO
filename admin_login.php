<?php
session_start();
ob_start();

// Include the database connection file
include('db.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Login logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        // Prepare the SQL query to fetch the user
        $sql = "SELECT * FROM admin WHERE username = :username AND password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'password' => $password
        ]);
        $admin = $stmt->fetch();

        if ($admin) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Invalid credentials!";
        }
    } catch(PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS Pro - Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-bg: #0B0C10;
            --secondary-bg: #1F2833;
            --text-color: #C5C6C7;
            --primary-accent: #66FCF1;
            --secondary-accent: #45A29E;
            --error-color: #ff4757;
            --border-radius: 8px;
            --box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--primary-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, var(--secondary-bg) 0%, var(--primary-bg) 100%);
            z-index: -1;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            position: relative;
        }

        .login-card {
            background: var(--secondary-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            border: 1px solid rgba(102, 252, 241, 0.1);
        }

        .login-header {
            background: linear-gradient(135deg, var(--secondary-bg), var(--primary-bg));
            padding: 2rem;
            text-align: center;
            border-bottom: 2px solid var(--primary-accent);
        }

        .login-header h3 {
            color: var(--primary-accent);
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 10px rgba(102, 252, 241, 0.5);
        }

        .login-header p {
            color: var(--text-color);
            opacity: 0.8;
        }

        .login-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            color: var(--text-color);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(11, 12, 16, 0.3);
            border: 1px solid rgba(102, 252, 241, 0.2);
            border-radius: var(--border-radius);
            color: var(--text-color);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-accent);
            box-shadow: 0 0 0 3px rgba(102, 252, 241, 0.1);
        }

        .form-group i {
            position: absolute;
            right: 1rem;
            top: 2.5rem;
            color: var(--secondary-accent);
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .remember-me input[type="checkbox"] {
            accent-color: var(--primary-accent);
        }

        .remember-me label {
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary-accent);
            color: var(--primary-bg);
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: var(--secondary-accent);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 252, 241, 0.2);
        }

        .error-message {
            background-color: rgba(255, 71, 87, 0.1);
            color: var(--error-color);
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 71, 87, 0.2);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 10px;
            }

            .login-header {
                padding: 1.5rem;
            }

            .login-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h3><i class="fas fa-users-cog"></i> EMS Pro</h3>
                <p>Administrator Login</p>
            </div>
            <div class="login-body">
                <?php if (isset($error_message)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>