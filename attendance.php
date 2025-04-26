<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}


// Fetch attendance records
$sql = "SELECT * FROM attendance";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$attendances = $stmt->fetchAll();

// Insert attendance (POST method)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emp_id = $_POST['emp_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO attendance (emp_id, date, status) 
            VALUES (:emp_id, :date, :status)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['emp_id' => $emp_id, 'date' => $date, 'status' => $status]);
    header('Location: attendance.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management | EMS Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #0B0C10;
            --secondary-bg: #1F2833;
            --text-color: #C5C6C7;
            --primary-accent: #66FCF1;
            --secondary-accent: #45A29E;
            --error-color: #ff4757;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --border-radius: 8px;
            --box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--primary-bg);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 0;
        }

        /* Navigation Bar */
        .navbar {
            background-color: var(--secondary-bg);
            overflow: hidden;
            margin-bottom: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .navbar a {
            float: left;
            color: var(--text-color);
            text-align: center;
            padding: 16px 20px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: var(--transition);
        }

        .navbar a:hover {
            background-color: rgba(102, 252, 241, 0.1);
            color: var(--primary-accent);
        }

        .navbar a.active {
            background-color: rgba(102, 252, 241, 0.2);
            color: var(--primary-accent);
        }

        .navbar a.logout {
            float: right;
            color: var(--error-color);
        }

        .navbar a.logout:hover {
            background-color: rgba(255, 71, 87, 0.1);
        }

        /* Headings */
        h1, h2 {
            color: var(--primary-accent);
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2rem;
            font-weight: 600;
        }

        h2 {
            font-size: 1.5rem;
            font-weight: 500;
        }

        /* Forms */
        form {
            background-color: var(--secondary-bg);
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin: 30px 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary-accent);
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(102, 252, 241, 0.2);
            border-radius: var(--border-radius);
            background-color: var(--primary-bg);
            color: var(--text-color);
            font-size: 15px;
            transition: var(--transition);
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary-accent);
            box-shadow: 0 0 0 2px rgba(102, 252, 241, 0.2);
        }

        button {
            background-color: var(--primary-accent);
            color: var(--primary-bg);
            padding: 12px 25px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        button:hover {
            background-color: var(--secondary-accent);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 252, 241, 0.2);
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            background-color: var(--secondary-bg);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(102, 252, 241, 0.1);
        }

        th {
            background-color: rgba(102, 252, 241, 0.1);
            color: var(--primary-accent);
            font-weight: 600;
        }

        tr:hover td {
            background-color: rgba(102, 252, 241, 0.05);
        }

        /* Status Badges */
        .status-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
        }

        .status-present {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }

        .status-absent {
            background-color: rgba(255, 71, 87, 0.1);
            color: var(--error-color);
        }

        .status-leave {
            background-color: rgba(241, 196, 15, 0.1);
            color: var(--warning-color);
        }

        /* Action Links */
        .action-link {
            color: var(--primary-accent);
            text-decoration: none;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .action-link:hover {
            background-color: rgba(102, 252, 241, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar a {
                float: none;
                display: block;
                text-align: left;
            }
            
            .navbar a.logout {
                float: none;
            }
            
            th, td {
                padding: 12px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <div class="navbar">
            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="employee.php"><i class="fas fa-users"></i> Employees</a>
            <a href="department.php"><i class="fas fa-building"></i> Departments</a>
            <a href="position.php"><i class="fas fa-user-tie"></i> Positions</a>
            <a href="salary_slips.php"><i class="fas fa-money-bill-wave"></i> Salary</a>
            <a href="attendance.php" class="active"><i class="fas fa-calendar-check"></i> Attendance</a>
            <a href="performance.php"><i class="fas fa-chart-line"></i> Performance</a>
            <a href="leaves.php"><i class="fas fa-calendar-minus"></i> Leaves</a>
            <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <h1><i class="fas fa-calendar-check"></i> Employee Attendance</h1>

        <!-- Add Attendance Form -->
        <form method="POST" action="attendance.php">
            <div class="form-group">
                <label for="emp_id">Employee ID</label>
                <input type="number" id="emp_id" name="emp_id" placeholder="Enter employee ID" required>
            </div>
            
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required>
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                    <option value="Leave">Leave</option>
                </select>
            </div>
            
            <button type="submit"><i class="fas fa-plus-circle"></i> Add Attendance</button>
        </form>

        <!-- Display Attendance Records -->
        <h2><i class="fas fa-list"></i> Attendance Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendances as $attendance): ?>
                <tr>
                    <td><?php echo htmlspecialchars($attendance['emp_id']); ?></td>
                    <td><?php echo htmlspecialchars($attendance['date']); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo strtolower($attendance['status']); ?>">
                            <?php echo htmlspecialchars($attendance['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit_attendance.php?id=<?php echo $attendance['att_id']; ?>" class="action-link">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
