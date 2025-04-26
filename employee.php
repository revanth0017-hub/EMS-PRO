<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}


// Fetch employees
$sql = "SELECT * FROM employee";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$employees = $stmt->fetchAll();

// Fetch departments for dropdown
$sql = "SELECT * FROM department";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$departments = $stmt->fetchAll();

// Fetch positions for dropdown
$sql = "SELECT * FROM positions";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$positions = $stmt->fetchAll();

// Insert employee (POST method)
function generateEmployeeID() {
    $prefix = 'EMP';
    $random = mt_rand(1000, 9999);
    $year = date('Y');
    return $prefix . $year . $random;
}

// Modify the insert employee section
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department_id = $_POST['department_id'];
    $position_id = $_POST['position_id'];
    $doj = $_POST['doj'];
    $employee_id = generateEmployeeID();

    $sql = "INSERT INTO employee (employee_id, name, email, phone, department_id, position_id, doj) 
            VALUES (:employee_id, :name, :email, :phone, :department_id, :position_id, :doj)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'employee_id' => $employee_id,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'department_id' => $department_id,
        'position_id' => $position_id,
        'doj' => $doj
    ]);
    header('Location: employee.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management | EMS Pro</title>
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
            position: relative;
        }

        .navbar a:hover {
            color: var(--primary-accent);
            background-color: rgba(102, 252, 241, 0.1);
        }

        .navbar a:hover::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20%;
            width: 60%;
            height: 2px;
            background-color: var(--primary-accent);
        }

        .navbar a.active {
            color: var(--primary-accent);
            background-color: rgba(102, 252, 241, 0.1);
        }

        .navbar a.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20%;
            width: 60%;
            height: 2px;
            background-color: var(--primary-accent);
        }

        .navbar a.logout {
            float: right;
            color: var(--error-color);
        }

        .navbar a.logout:hover {
            background-color: rgba(255, 71, 87, 0.1);
        }

        /* Headings */
        h1, h2, h3 {
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
            margin-top: 30px;
        }

        /* Forms */
        form {
            background-color: var(--secondary-bg);
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin: 30px 0;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary-accent);
            font-weight: 500;
            font-size: 14px;
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

        button i {
            font-size: 14px;
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
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        tr:hover td {
            background-color: rgba(102, 252, 241, 0.05);
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
            transform: translateY(-1px);
        }

        .action-link i {
            font-size: 14px;
        }

        /* Status Badges */
        .badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
        }

        .badge-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }

        .badge-warning {
            background-color: rgba(241, 196, 15, 0.1);
            color: var(--warning-color);
        }

        .badge-danger {
            background-color: rgba(255, 71, 87, 0.1);
            color: var(--error-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar a {
                float: none;
                display: block;
                text-align: left;
                padding: 12px 20px;
            }
            
            .navbar a.logout {
                float: none;
            }
            
            .form-row {
                grid-template-columns: 1fr;
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
            <a href="employee.php" class="active"><i class="fas fa-users"></i> Employees</a>
            <a href="department.php"><i class="fas fa-building"></i> Departments</a>
            <a href="position.php"><i class="fas fa-user-tie"></i> Positions</a>
            <a href="salary_slips.php"><i class="fas fa-money-bill-wave"></i> Salary</a>
            <a href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a>
            <a href="performance.php"><i class="fas fa-chart-line"></i> Performance</a>
            <a href="leaves.php"><i class="fas fa-calendar-minus"></i> Leaves</a>
            <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <h1><i class="fas fa-users"></i> Employee Management</h1>

        <!-- Add Employee Form -->
        <form method="POST" action="employee.php">
            <div class="form-row">
                <div>
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter employee full name" required>
                </div>
                <div>
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter employee email" required>
                </div>
            </div>
            
            <div class="form-row">
                <div>
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" placeholder="Enter phone number" required>
                </div>
                <div>
                    <label for="doj">Date of Joining</label>
                    <input type="date" id="doj" name="doj" required>
                </div>
            </div>
            
            <div class="form-row">
                <div>
                    <label for="department_id">Department</label>
                    <select id="department_id" name="department_id" required>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?php echo $department['dept_id']; ?>">
                                <?php echo htmlspecialchars($department['dept_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="position_id">Position</label>
                    <select id="position_id" name="position_id" required>
                        <?php foreach ($positions as $position): ?>
                            <option value="<?php echo $position['pos_id']; ?>">
                                <?php echo htmlspecialchars($position['pos_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <button type="submit"><i class="fas fa-user-plus"></i> Add Employee</button>
        </form>

        <!-- Display Employees -->
        <h2><i class="fas fa-list"></i> Employee List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?php echo htmlspecialchars($employee['employee_id']); ?></td>
                    <td><?php echo htmlspecialchars($employee['name']); ?></td>
                    <td><?php echo htmlspecialchars($employee['email']); ?></td>
                    <td><?php echo htmlspecialchars($employee['phone']); ?></td>
                    <td><?php echo htmlspecialchars(getDepartmentName($employee['department_id'])); ?></td>
                    <td><?php echo htmlspecialchars(getPositionName($employee['position_id'])); ?></td>
                    <td>
                        <a href="edit_employee.php?id=<?php echo $employee['emp_id']; ?>" class="action-link">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px;">
                        No employees found. Add your first employee using the form above.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php
    function getDepartmentName($dept_id) {
        global $pdo;
        $sql = "SELECT dept_name FROM department WHERE dept_id = :dept_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['dept_id' => $dept_id]);
        $department = $stmt->fetch();
        return $department ? $department['dept_name'] : 'N/A';
    }

    function getPositionName($pos_id) {
        global $pdo;
        $sql = "SELECT pos_name FROM positions WHERE pos_id = :pos_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['pos_id' => $pos_id]);
        $position = $stmt->fetch();
        return $position ? $position['pos_name'] : 'N/A';
    }
    ?>
</body>
</html>