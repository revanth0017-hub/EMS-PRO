<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// Fetch performance reviews
$sql = "SELECT p.*, e.name as emp_name 
        FROM performance p
        LEFT JOIN employee e ON p.emp_id = e.emp_id 
        ORDER BY p.review_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$reviews = $stmt->fetchAll();

// Initialize as empty array if no results
if (!$reviews) {
    $reviews = [];
}

// Insert performance review (POST method)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emp_id = $_POST['emp_id'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];
    $review_date = $_POST['review_date'];

    $sql = "INSERT INTO performance (emp_id, review, rating, review_date) 
            VALUES (:emp_id, :review, :rating, :review_date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['emp_id' => $emp_id, 'review' => $review, 'rating' => $rating, 'review_date' => $review_date]);
    header('Location: performance.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Management | EMS Pro</title>
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

        /* Rating Stars */
        .rating-stars {
            color: var(--warning-color);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        .rating-stars::before {
            content: "★★★★★";
            letter-spacing: 2px;
            background: linear-gradient(90deg, var(--warning-color) var(--rating-percent), var(--text-color) var(--rating-percent));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
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
            <a href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a>
            <a href="performance.php" class="active"><i class="fas fa-chart-line"></i> Performance</a>
            <a href="leaves.php"><i class="fas fa-calendar-minus"></i> Leaves</a>
            <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <h1><i class="fas fa-chart-line"></i> Performance Reviews</h1>

        <!-- Add Performance Review Form -->
        <form method="POST" action="performance.php">
            <div class="form-group">
                <label for="emp_id">Employee ID</label>
                <input type="number" id="emp_id" name="emp_id" placeholder="Enter employee ID" required>
            </div>
            
            <div class="form-group">
                <label for="review">Review</label>
                <textarea id="review" name="review" placeholder="Enter performance review" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="rating">Rating (1-5)</label>
                <input type="number" id="rating" name="rating" min="1" max="5" placeholder="Enter rating (1-5)" required>
            </div>
            
            <div class="form-group">
                <label for="review_date">Review Date</label>
                <input type="date" id="review_date" name="review_date" required>
            </div>
            
            <button type="submit"><i class="fas fa-plus-circle"></i> Add Review</button>
        </form>

        <!-- Display Performance Reviews -->
        <h2><i class="fas fa-list"></i> Performance Reviews</h2>
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th>Review Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?php echo htmlspecialchars($review['emp_id']); ?></td>
                    <td><?php echo htmlspecialchars($review['review']); ?></td>
                    <td>
                        <div class="rating-stars" style="--rating-percent: <?php echo ($review['rating'] / 5) * 100; ?>%">
                            <span style="margin-left: 8px;">(<?php echo htmlspecialchars($review['rating']); ?>/5)</span>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($review['review_date']); ?></td>
                    <td>
                        <a href="edit_performance.php?id=<?php echo $review['perf_id']; ?>" class="action-link">
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