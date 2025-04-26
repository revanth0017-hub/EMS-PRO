<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// Fetch salary slips with employee names
$sql = "SELECT s.slip_id, s.emp_id, s.month, s.year, s.basic_salary, 
               s.allowances, s.deductions, s.net_salary,
               e.name as emp_name,
               CONCAT(s.year, '-', LPAD(s.month, 2, '0'), '-01') as payment_date,
               CASE 
                   WHEN s.basic_salary > 0 THEN 'Paid'
                   ELSE 'Pending'
               END as status
        FROM salary_slip s 
        LEFT JOIN employee e ON s.emp_id = e.emp_id 
        ORDER BY s.year DESC, s.month DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$salaries = $stmt->fetchAll();

// Initialize as empty array if no results
if (!$salaries) {
    $salaries = [];
}

// Fetch employees for dropdown (only once)
$sql = "SELECT emp_id, name as emp_name FROM employee";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$employees = $stmt->fetchAll();

// Handle form submission for new salary slip
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emp_id = $_POST['emp_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    
    $sql = "INSERT INTO salary_slip (emp_id, month, year, basic_salary, allowances, deductions, net_salary) 
            VALUES (:emp_id, :month, :year, :basic_salary, :allowances, :deductions, :net_salary)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'emp_id' => $emp_id,
        'month' => date('m', strtotime($payment_date)),
        'year' => date('Y', strtotime($payment_date)),
        'basic_salary' => $amount,
        'allowances' => $_POST['allowances'] ?? 0,
        'deductions' => $_POST['deductions'] ?? 0,
        'net_salary' => $amount + ($_POST['allowances'] ?? 0) - ($_POST['deductions'] ?? 0)
    ]);
    
    header('Location: salary_slips.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Management | EMS Pro</title>
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

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(102, 252, 241, 0.2);
            border-radius: var(--border-radius);
            background-color: var(--primary-bg);
            color: var(--text-color);
            font-size: 15px;
            transition: var(--transition);
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus, select:focus, textarea:focus {
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

        .status-pending {
            background-color: rgba(241, 196, 15, 0.1);
            color: var(--warning-color);
        }

        .status-paid {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
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

        .action-link.delete {
            color: var(--error-color);
        }

        .action-link.delete:hover {
            background-color: rgba(255, 71, 87, 0.1);
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
            <a href="salary_slips.php" class="active"><i class="fas fa-money-bill-wave"></i> Salary</a>
            <a href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a>
            <a href="performance.php"><i class="fas fa-chart-line"></i> Performance</a>
            <a href="leaves.php"><i class="fas fa-calendar-minus"></i> Leaves</a>
            <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <h1><i class="fas fa-money-bill-wave"></i> Salary Management</h1>

        <!-- Add Salary Form -->
        <form method="POST" action="salary_slips.php">
            <div class="form-group">
                <label for="emp_id">Employee</label>
                <select id="emp_id" name="emp_id" required>
                    <option value="">Select Employee</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo $employee['emp_id']; ?>">
                            <?php echo htmlspecialchars($employee['emp_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="basic_salary">Basic Salary</label>
                <input type="number" id="basic_salary" name="basic_salary" placeholder="Enter basic salary" min="0" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="allowances">Allowances</label>
                <input type="number" id="allowances" name="allowances" placeholder="Enter allowances" min="0" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="deductions"> Deductions</label>
                <input type="number" id="deductions" name="deductions" placeholder="Enter deductions" min="0" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="payment_date"> Payment Date</label>
                <input type="date" id="payment_date" name="payment_date" required>
            </div>
            
            <button type="submit">Generate Salary Slip</button>
        </form>

        <!-- Display Salary Records -->
        <h2><i class="fas fa-list"></i> Salary Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Basic Salary</th>
                    <th>Allowances</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Payment Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salaries as $salary): ?>
                <tr>
                    <td><?php echo htmlspecialchars($salary['emp_name']); ?></td>
                    <td><?php echo number_format($salary['basic_salary'], 2); ?></td>
                    <td><?php echo number_format($salary['allowances'], 2); ?></td>
                    <td><?php echo number_format($salary['deductions'], 2); ?></td>
                    <td><?php echo number_format($salary['net_salary'], 2); ?></td>
                    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($salary['payment_date']))); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo strtolower($salary['status']); ?>">
                            <?php echo htmlspecialchars($salary['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit_salary.php?id=<?php echo $salary['slip_id']; ?>" class="action-link">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete_salary.php?id=<?php echo $salary['slip_id']; ?>" class="action-link delete">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>