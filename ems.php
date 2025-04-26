<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS - Employee Management System</title>
    <link rel="stylesheet" href="ems.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>EMS</h2>
                <span class="sidebar-toggle" id="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </span>
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li class="active" data-page="dashboard">
                        <a href="#dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li data-page="employee">
                        <a href="#employee">
                            <i class="fas fa-users"></i>
                            <span>Employees</span>
                        </a>
                    </li>
                    <li data-page="department">
                        <a href="#department">
                            <i class="fas fa-building"></i>
                            <span>Departments</span>
                        </a>
                    </li>
                    <li data-page="position">
                        <a href="#position">
                            <i class="fas fa-briefcase"></i>
                            <span>Positions</span>
                        </a>
                    </li>
                    <li data-page="attendance">
                        <a href="#attendance">
                            <i class="fas fa-calendar-check"></i>
                            <span>Attendance</span>
                        </a>
                    </li>
                    <li data-page="salary">
                        <a href="#salary">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Salary</span>
                        </a>
                    </li>
                    <li data-page="performance">
                        <a href="#performance">
                            <i class="fas fa-chart-line"></i>
                            <span>Performance</span>
                        </a>
                    </li>
                    <li data-page="leave">
                        <a href="#leave">
                            <i class="fas fa-calendar-minus"></i>
                            <span>Leave</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Navbar -->
            <nav class="top-navbar">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <div class="nav-right">
                    <div class="notification">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="profile-dropdown">
                        <div class="profile-img">
                            <img src="/placeholder.svg?height=40&width=40" alt="Admin">
                        </div>
                        <div class="dropdown-content">
                            <a href="#profile"><i class="fas fa-user"></i> Profile</a>
                            <a href="#settings"><i class="fas fa-cog"></i> Settings</a>
                            <a href="#logout" id="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="page-content">
                <!-- Dashboard Page -->
                <div class="page active" id="dashboard-page">
                    <div class="page-header">
                        <h1>Dashboard</h1>
                        <p class="current-date" id="current-date">Loading date...</p>
                    </div>

                    <!-- Summary Cards -->
                    <div class="summary-cards">
                        <div class="card">
                            <div class="card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-info">
                                <h3>Total Employees</h3>
                                <p>248</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="card-info">
                                <h3>Departments</h3>
                                <p>12</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="card-info">
                                <h3>Positions</h3>
                                <p>36</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="card-info">
                                <h3>Present Today</h3>
                                <p>231</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-icon">
                                <i class="fas fa-calendar-minus"></i>
                            </div>
                            <div class="card-info">
                                <h3>Pending Leaves</h3>
                                <p>8</p>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="charts-section">
                        <div class="chart-container">
                            <div class="chart-header">
                                <h3>Attendance Overview</h3>
                            </div>
                            <div class="chart-body">
                                <canvas id="attendance-chart"></canvas>
                            </div>
                        </div>
                        <div class="chart-container">
                            <div class="chart-header">
                                <h3>Employees per Department</h3>
                            </div>
                            <div class="chart-body">
                                <canvas id="department-chart"></canvas>
                            </div>
                        </div>
                        <div class="chart-container full-width">
                            <div class="chart-header">
                                <h3>Monthly Salary Expenses</h3>
                            </div>
                            <div class="chart-body">
                                <div id="salary-chart"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="quick-links">
                        <h2>Quick Actions</h2>
                        <div class="links-container">
                            <div class="link-card" id="add-employee-link">
                                <div class="link-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <h3>Add Employee</h3>
                            </div>
                            <div class="link-card" id="view-attendance-link">
                                <div class="link-icon">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <h3>View Attendance</h3>
                            </div>
                            <div class="link-card" id="generate-salary-link">
                                <div class="link-icon">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <h3>Generate Salary</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employee Page -->
                <div class="page" id="employee-page">
                    <div class="page-header">
                        <h1>Employee Management</h1>
                        <button class="add-btn" id="add-employee-btn">
                            <i class="fas fa-plus"></i> Add Employee
                        </button>
                    </div>

                    <div class="search-filter">
                        <div class="search-input">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search by Name or ID...">
                        </div>
                        <div class="filter-options">
                            <select id="department-filter">
                                <option value="">All Departments</option>
                                <option value="IT">IT</option>
                                <option value="HR">HR</option>
                                <option value="Finance">Finance</option>
                                <option value="Marketing">Marketing</option>
                            </select>
                            <select id="position-filter">
                                <option value="">All Positions</option>
                                <option value="Manager">Manager</option>
                                <option value="Developer">Developer</option>
                                <option value="Analyst">Analyst</option>
                                <option value="Executive">Executive</option>
                            </select>
                        </div>
                        <div class="export-options">
                            <button class="export-btn" id="export-csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </button>
                            <button class="export-btn" id="export-pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Profile</th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><img src="/placeholder.svg?height=40&width=40" alt="Employee" class="employee-img"></td>
                                    <td>EMP001</td>
                                    <td>John Doe</td>
                                    <td>IT</td>
                                    <td>Senior Developer</td>
                                    <td><span class="status active">Active</span></td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="/placeholder.svg?height=40&width=40" alt="Employee" class="employee-img"></td>
                                    <td>EMP002</td>
                                    <td>Jane Smith</td>
                                    <td>HR</td>
                                    <td>HR Manager</td>
                                    <td><span class="status active">Active</span></td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="/placeholder.svg?height=40&width=40" alt="Employee" class="employee-img"></td>
                                    <td>EMP003</td>
                                    <td>Robert Johnson</td>
                                    <td>Finance</td>
                                    <td>Financial Analyst</td>
                                    <td><span class="status inactive">On Leave</span></td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="/placeholder.svg?height=40&width=40" alt="Employee" class="employee-img"></td>
                                    <td>EMP004</td>
                                    <td>Emily Davis</td>
                                    <td>Marketing</td>
                                    <td>Marketing Specialist</td>
                                    <td><span class="status active">Active</span></td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="/placeholder.svg?height=40&width=40" alt="Employee" class="employee-img"></td>
                                    <td>EMP005</td>
                                    <td>Michael Wilson</td>
                                    <td>IT</td>
                                    <td>UI/UX Designer</td>
                                    <td><span class="status active">Active</span></td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination">
                        <button class="pagination-btn" disabled><i class="fas fa-chevron-left"></i></button>
                        <button class="pagination-btn active">1</button>
                        <button class="pagination-btn">2</button>
                        <button class="pagination-btn">3</button>
                        <button class="pagination-btn">4</button>
                        <button class="pagination-btn">5</button>
                        <button class="pagination-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Department Page -->
                <div class="page" id="department-page">
                    <div class="page-header">
                        <h1>Department Management</h1>
                        <button class="add-btn" id="add-department-btn">
                            <i class="fas fa-plus"></i> Add Department
                        </button>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Department ID</th>
                                    <th>Department Name</th>
                                    <th>Head of Department</th>
                                    <th>Employee Count</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>DEPT001</td>
                                    <td>Information Technology</td>
                                    <td>John Doe</td>
                                    <td><span class="badge">68</span></td>
                                    <td>01/05/2023</td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DEPT002</td>
                                    <td>Human Resources</td>
                                    <td>Jane Smith</td>
                                    <td><span class="badge">24</span></td>
                                    <td>01/05/2023</td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DEPT003</td>
                                    <td>Finance</td>
                                    <td>Robert Johnson</td>
                                    <td><span class="badge">36</span></td>
                                    <td>01/05/2023</td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DEPT004</td>
                                    <td>Marketing</td>
                                    <td>Emily Davis</td>
                                    <td><span class="badge">42</span></td>
                                    <td>01/05/2023</td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DEPT005</td>
                                    <td>Sales</td>
                                    <td>Michael Wilson</td>
                                    <td><span class="badge">56</span></td>
                                    <td>01/05/2023</td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Position Page -->
                <div class="page" id="position-page">
                    <div class="page-header">
                        <h1>Position Management</h1>
                        <button class="add-btn" id="add-position-btn">
                            <i class="fas fa-plus"></i> Add Position
                        </button>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Position ID</th>
                                    <th>Position Title</th>
                                    <th>Department</th>
                                    <th>Salary Range</th>
                                    <th>Employee Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>POS001</td>
                                    <td>Senior Developer</td>
                                    <td>IT</td>
                                    <td>$80,000 - $120,000</td>
                                    <td><span class="badge">12</span></td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>POS002</td>
                                    <td>HR Manager</td>
                                    <td>HR</td>
                                    <td>$70,000 - $90,000</td>
                                    <td><span class="badge">3</span></td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>POS003</td>
                                    <td>Financial Analyst</td>
                                    <td>Finance</td>
                                    <td>$65,000 - $85,000</td>
                                    <td><span class="badge">8</span></td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>POS004</td>
                                    <td>Marketing Specialist</td>
                                    <td>Marketing</td>
                                    <td>$60,000 - $80,000</td>
                                    <td><span class="badge">10</span></td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>POS005</td>
                                    <td>UI/UX Designer</td>
                                    <td>IT</td>
                                    <td>$70,000 - $90,000</td>
                                    <td><span class="badge">6</span></td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Attendance Page -->
                <div class="page" id="attendance-page">
                    <div class="page-header">
                        <h1>Attendance Management</h1>
                        <div class="date-picker">
                            <input type="date" id="attendance-date" value="2023-05-01">
                        </div>
                    </div>

                    <div class="search-filter">
                        <div class="search-input">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search by Name or ID...">
                        </div>
                        <div class="filter-options">
                            <select id="department-filter-attendance">
                                <option value="">All Departments</option>
                                <option value="IT">IT</option>
                                <option value="HR">HR</option>
                                <option value="Finance">Finance</option>
                                <option value="Marketing">Marketing</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>EMP001</td>
                                    <td>John Doe</td>
                                    <td>IT</td>
                                    <td>09:00 AM</td>
                                    <td>06:00 PM</td>
                                    <td><span class="status active">Present</span></td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EMP002</td>
                                    <td>Jane Smith</td>
                                    <td>HR</td>
                                    <td>08:45 AM</td>
                                    <td>05:30 PM</td>
                                    <td><span class="status active">Present</span></td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EMP003</td>
                                    <td>Robert Johnson</td>
                                    <td>Finance</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td><span class="status inactive">Absent</span></td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EMP004</td>
                                    <td>Emily Davis</td>
                                    <td>Marketing</td>
                                    <td>09:15 AM</td>
                                    <td>06:15 PM</td>
                                    <td><span class="status active">Present</span></td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EMP005</td>
                                    <td>Michael Wilson</td>
                                    <td>IT</td>
                                    <td>09:30 AM</td>
                                    <td>--</td>
                                    <td><span class="status warning">Working</span></td>
                                    <td class="actions">
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="attendance-chart-container">
                        <div class="chart-header">
                            <h3>Weekly Attendance Overview</h3>
                        </div>
                        <div class="chart-body">
                            <canvas id="weekly-attendance-chart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Salary Page -->
                <div class="page" id="salary-page">
                    <div class="page-header">
                        <h1>Salary Management</h1>
                        <div class="month-picker">
                            <select id="salary-month">
                                <option value="1">January 2023</option>
                                <option value="2">February 2023</option>
                                <option value="3">March 2023</option>
                                <option value="4">April 2023</option>
                                <option value="5" selected>May 2023</option>
                            </select>
                        </div>
                    </div>

                    <div class="search-filter">
                        <div class="search-input">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search by Name or ID...">
                        </div>
                        <div class="filter-options">
                            <select id="department-filter-salary">
                                <option value="">All Departments</option>
                                <option value="IT">IT</option>
                                <option value="HR">HR</option>
                                <option value="Finance">Finance</option>
                                <option value="Marketing">Marketing</option>
                            </select>
                        </div>
                        <div class="export-options">
                            <button class="export-btn" id="generate-all-slips">
                                <i class="fas fa-file-invoice-dollar"></i> Generate All Slips
                            </button>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Basic Salary</th>
                                    <th>Allowances</th>
                                    <th>Deductions</th>
                                    <th>Net Salary</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>EMP001</td>
                                    <td>John Doe</td>
                                    <td>IT</td>
                                    <td>Senior Developer</td>
                                    <td>$8,000</td>
                                    <td>$1,200</td>
                                    <td>$1,800</td>
                                    <td>$7,400</td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn download-btn"><i class="fas fa-download"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EMP002</td>
                                    <td>Jane Smith</td>
                                    <td>HR</td>
                                    <td>HR Manager</td>
                                    <td>$7,500</td>
                                    <td>$1,000</td>
                                    <td>$1,600</td>
                                    <td>$6,900</td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn download-btn"><i class="fas fa-download"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EMP003</td>
                                    <td>Robert Johnson</td>
                                    <td>Finance</td>
                                    <td>Financial Analyst</td>
                                    <td>$6,800</td>
                                    <td>$900</td>
                                    <td>$1,400</td>
                                    <td>$6,300</td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn download-btn"><i class="fas fa-download"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Performance Page -->
                <div class="page" id="performance-page">
                    <div class="page-header">
                        <h1>Performance Management</h1>
                        <button class="add-btn" id="add-review-btn">
                            <i class="fas fa-plus"></i> Add Review
                        </button>
                    </div>

                    <div class="search-filter">
                        <div class="search-input">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search by Name or ID...">
                        </div>
                        <div class="filter-options">
                            <select id="department-filter-performance">
                                <option value="">All Departments</option>
                                <option value="IT">IT</option>
                                <option value="HR">HR</option>
                                <option value="Finance">Finance</option>
                                <option value="Marketing">Marketing</option>
                            </select>
                            <select id="rating-filter">
                                <option value="">All Ratings</option>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Review Date</th>
                                    <th>Rating</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>EMP001</td>
                                    <td>John Doe</td>
                                    <td>IT</td>
                                    <td>Senior Developer</td>
                                    <td>01/04/2023</td>
                                    <td>
                                        <div class="rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EMP002</td>
                                    <td>Jane Smith</td>
                                    <td>HR</td>
                                    <td>HR Manager</td>
                                    <td>15/04/2023</td>
                                    <td>
                                        <div class="rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                    </td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EMP003</td>
                                    <td>Robert Johnson</td>
                                    <td>Finance</td>
                                    <td>Financial Analyst</td>
                                    <td>20/04/2023</td>
                                    <td>
                                        <div class="rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                    </td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Leave Page -->
                <div class="page" id="leave-page">
                    <div class="page-header">
                        <h1>Leave Management</h1>
                        <button class="add-btn" id="add-leave-btn">
                            <i class="fas fa-plus"></i> Add Leave
                        </button>
                    </div>

                    <div class="search-filter">
                        <div class="search-input">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search by Name or ID...">
                        </div>
                        <div class="filter-options">
                            <select id="status-filter">
                                <option value="">All Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                            <select id="leave-type-filter">
                                <option value="">All Types</option>
                                <option value="Sick">Sick Leave</option>
                                <option value="Vacation">Vacation</option>
                                <option value="Personal">Personal</option>
                                <option value="Maternity">Maternity</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Leave Type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>EMP001</td>
                                    <td>John Doe</td>
                                    <td>IT</td>
                                    <td>Vacation</td>
                                    <td>10/05/2023</td>
                                    <td>15/05/2023</td>
                                    <td>5</td>
                                    <td><span class="status warning">Pending</span></td>
                                    <td class="actions">
                                        <button class="action-btn approve-btn"><i class="fas fa-check"></i></button>
                                        <button class="action-btn reject-btn"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EMP002</td>
                                    <td>Jane Smith</td>
                                    <td>HR</td>
                                    <td>Sick Leave</td>
                                    <td>05/05/2023</td>
                                    <td>06/05/2023</td>
                                    <td>2</td>
                                    <td><span class="status active">Approved</span></td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EMP003</td>
                                    <td>Robert Johnson</td>
                                    <td>Finance</td>
                                    <td>Personal</td>
                                    <td>12/05/2023</td>
                                    <td>12/05/2023</td>
                                    <td>1</td>
                                    <td><span class="status inactive">Rejected</span></td>
                                    <td class="actions">
                                        <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="leave-balance-container">
                        <h3>Leave Balance Overview</h3>
                        <div class="balance-cards">
                            <div class="balance-card">
                                <h4>Sick Leave</h4>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: 30%"></div>
                                </div>
                                <p>3/10 days used</p>
                            </div>
                            <div class="balance-card">
                                <h4>Vacation</h4>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: 60%"></div>
                                </div>
                                <p>12/20 days used</p>
                            </div>
                            <div class="balance-card">
                                <h4>Personal</h4>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: 80%"></div>
                                </div>
                                <p>4/5 days used</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <!-- Add Employee Modal -->
    <div class="modal" id="add-employee-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Employee</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="add-employee-form">
                    <div class="form-group">
                        <label for="employee-id">Employee ID</label>
                        <input type="text" id="employee-id" placeholder="Auto Generated" disabled>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" required>
                        </div>
                        <div class="form-group">
                            <label for="last-name">Last Name</label>
                            <input type="text" id="last-name" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select id="department" required>
                                <option value="">Select Department</option>
                                <option value="IT">IT</option>
                                <option value="HR">HR</option>
                                <option value="Finance">Finance</option>
                                <option value="Marketing">Marketing</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            <select id="position" required>
                                <option value="">Select Position</option>
                                <option value="Manager">Manager</option>
                                <option value="Developer">Developer</option>
                                <option value="Analyst">Analyst</option>
                                <option value="Executive">Executive</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="join-date">Join Date</label>
                            <input type="date" id="join-date" required>
                        </div>
                        <div class="form-group">
                            <label for="salary">Basic Salary</label>
                            <input type="number" id="salary" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" id="cancel-add-employee">Cancel</button>
                <button class="submit-btn" id="submit-add-employee">Add Employee</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="delete-confirmation-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm Delete</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" id="cancel-delete">Cancel</button>
                <button class="delete-btn" id="confirm-delete">Delete</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <div class="toast-content">
            <i class="fas fa-check-circle"></i>
            <div class="toast-message">Operation completed successfully!</div>
        </div>
        <div class="toast-progress"></div>
    </div>

    <!-- Login Modal (for demo purposes) -->
    <div class="modal" id="login-modal">
        <div class="modal-content login-content">
            <div class="login-header">
                <h2>EMS Login</h2>
            </div>
            <div class="login-body">
                <form id="login-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" placeholder="Enter your username" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" placeholder="Enter your password" required>
                            <i class="fas fa-eye toggle-password" id="toggle-password"></i>
                        </div>
                    </div>
                    <div class="form-group remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="login-btn">Login</button>
                </form>
            </div>
            <div class="login-footer">
                <p>© EMS System 2025</p>
            </div>
        </div>
    </div>

    <script src="ems.js"></script>
</body>
</html>

