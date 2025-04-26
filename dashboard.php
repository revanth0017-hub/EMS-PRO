<?php
// Include the database connection file
include('db.php');

// Check if the user is logged in (e.g., check if session exists or authentication is done)
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all employees
$employee_sql = "SELECT * FROM employee";
$employee_stmt = $pdo->prepare($employee_sql);
$employee_stmt->execute();
$employees = $employee_stmt->fetchAll();

// Fetch all departments
$department_sql = "SELECT * FROM department";
$department_stmt = $pdo->prepare($department_sql);
$department_stmt->execute();
$departments = $department_stmt->fetchAll();

// Fetch all positions
$position_sql = "SELECT * FROM positions";
$position_stmt = $pdo->prepare($position_sql);
$position_stmt->execute();
$positions = $position_stmt->fetchAll();

// Fetch all salary slips
$salary_sql = "SELECT * FROM salary_slip";
$salary_stmt = $pdo->prepare($salary_sql);
$salary_stmt->execute();
$salaries = $salary_stmt->fetchAll();

// Calculate today's attendance count
$today = date('Y-m-d');
$today_sql = "SELECT COUNT(*) as count FROM attendance WHERE DATE(date) = :today";
$today_stmt = $pdo->prepare($today_sql);
$today_stmt->execute(['today' => $today]);
$today_result = $today_stmt->fetch();
$today_attendance_count = $today_result['count'];

// Fetch recent leaves
$leaves_sql = "SELECT l.*, e.name as emp_name 
               FROM leaves l 
               LEFT JOIN employee e ON l.emp_id = e.emp_id 
               ORDER BY l.leave_from DESC";
$leaves_stmt = $pdo->prepare($leaves_sql);
$leaves_stmt->execute();
$leaves = $leaves_stmt->fetchAll();

// Initialize as empty array if no results
if (!$leaves) {
    $leaves = [];
}

// Fetch recent attendance with employee names
$attendance_sql = "SELECT a.*, e.name as emp_name 
                  FROM attendance a
                  LEFT JOIN employee e ON a.emp_id = e.emp_id 
                  ORDER BY a.date DESC";
$attendance_stmt = $pdo->prepare($attendance_sql);
$attendance_stmt->execute();
$attendance = $attendance_stmt->fetchAll();

// Initialize as empty array if no results
if (!$attendance) {
    $attendance = [];
}

// Fetch all attendance
$attendance_sql = "SELECT * FROM attendance";
$attendance_stmt = $pdo->prepare($attendance_sql);
$attendance_stmt->execute();
$attendance = $attendance_stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | EMS Pro</title>
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
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 250px;
            background-color: var(--secondary-bg);
            padding: 20px 0;
            height: 100vh;
            position: sticky;
            top: 0;
            box-shadow: var(--box-shadow);
            display: flex;
            flex-direction: column;
        }

        .system-title {
            padding: 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(102, 252, 241, 0.2);
        }

        .system-title h1 {
            color: var(--primary-accent);
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-nav {
            flex-grow: 1;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            color: var(--text-color);
            padding: 15px 25px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            gap: 12px;
        }

        .sidebar-nav a:hover {
            background-color: rgba(102, 252, 241, 0.1);
            color: var(--primary-accent);
        }

        .sidebar-nav a.active {
            background-color: rgba(102, 252, 241, 0.2);
            color: var(--primary-accent);
            border-left: 4px solid var(--primary-accent);
        }

        .sidebar-nav a.logout {
            color: var(--error-color);
            margin-top: auto;
            border-top: 1px solid rgba(102, 252, 241, 0.2);
        }

        .sidebar-nav a.logout:hover {
            background-color: rgba(255, 71, 87, 0.1);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* Headings */
        h1, h2 {
            color: var(--primary-accent);
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h2 {
            font-size: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: var(--secondary-bg);
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: var(--primary-accent);
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-card p {
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-color);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .action-btn {
            background-color: var(--primary-accent);
            color: var(--primary-bg);
            padding: 15px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: var(--transition);
            text-align: center;
        }

        .action-btn:hover {
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

        .status-pending {
            background-color: rgba(241, 196, 15, 0.1);
            color: var(--warning-color);
        }

        .status-approved {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }

        .status-rejected {
            background-color: rgba(255, 71, 87, 0.1);
            color: var(--error-color);
        }

        .status-present {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }

        .status-absent {
            background-color: rgba(255, 71, 87, 0.1);
            color: var(--error-color);
        }

        /* Responsive */
        @media (max-width: 992px) {
            body {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .sidebar-nav {
                display: flex;
                flex-wrap: wrap;
            }
            
            .sidebar-nav a {
                flex: 1 0 auto;
            }
            
            .main-content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr 1fr;
            }
            
            th, td {
                padding: 12px 15px;
            }
        }

        @media (max-width: 576px) {
            .stats-grid, .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .sidebar-nav a {
                padding: 12px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="system-title">
            <h1><i class="fas fa-users-cog"></i> Employment Management System</h1>
        </div>
        
        <div class="sidebar-nav">
            <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="employee.php"><i class="fas fa-users"></i> Employees</a>
            <a href="department.php"><i class="fas fa-building"></i> Departments</a>
            <a href="position.php"><i class="fas fa-user-tie"></i> Positions</a>
            <a href="salary_slips.php"><i class="fas fa-money-bill-wave"></i> Salary</a>
            <a href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a>
            <a href="performance.php"><i class="fas fa-chart-line"></i> Performance</a>
            <a href="leaves.php"><i class="fas fa-calendar-minus"></i> Leaves</a>
            <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3><i class="fas fa-users"></i> Total Employees</h3>
                <p><?php echo count($employees); ?></p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-building"></i> Total Departments</h3>
                <p><?php echo count($departments); ?></p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-user-tie"></i> Total Positions</h3>
                <p><?php echo count($positions); ?></p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-calendar-check"></i> Today's Attendance</h3>
                <p><?php echo $today_attendance_count; ?></p>
            </div>
        </div>

        <!-- Quick Actions (only Add Employee, Add Department, Add Position) -->
        <div class="quick-actions">
            <a href="employee.php?action=add" class="action-btn">
                <i class="fas fa-user-plus"></i> Add Employee
            </a>
            <a href="department.php?action=add" class="action-btn">
                <i class="fas fa-building"></i> Add Department
            </a>
            <a href="position.php?action=add" class="action-btn">
                <i class="fas fa-briefcase"></i> Add Position
            </a>
        </div>

        <!-- Recent Leaves Section -->
        <h2><i class="fas fa-calendar-minus"></i> Recent Leave Applications</h2>
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Leave From</th>
                    <th>Leave To</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach (array_slice($leaves, 0, 5) as $leave): ?>
                <tr>
                    <td><?php echo htmlspecialchars($leave['emp_name']); ?></td>
                    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($leave['leave_from']))); ?></td>
                    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($leave['leave_to']))); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo strtolower($leave['status']); ?>">
                            <?php echo htmlspecialchars($leave['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Recent Attendance Section -->
        <h2><i class="fas fa-calendar-check"></i> Recent Attendance Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($attendance, 0, 5) as $att): ?>
                <tr>
                    <td><?php echo htmlspecialchars($att['emp_id']); ?></td>
                    <td><?php echo htmlspecialchars($att['date']); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo strtolower($att['status']); ?>">
                            <?php echo htmlspecialchars($att['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>