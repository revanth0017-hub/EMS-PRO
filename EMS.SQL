CREATE DATABASE EMS;
USE EMS;

-- 1. ADMIN TABLE
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- 2. DEPARTMENT TABLE
CREATE TABLE IF NOT EXISTS department (
    dept_id INT AUTO_INCREMENT PRIMARY KEY,
    dept_name VARCHAR(100) NOT NULL
);

-- 3. POSITIONS TABLE (renamed from position)
CREATE TABLE IF NOT EXISTS positions (
    pos_id INT AUTO_INCREMENT PRIMARY KEY,
    pos_name VARCHAR(100) NOT NULL,
    salary INT NOT NULL
);

-- 4. EMPLOYEE TABLE
CREATE TABLE IF NOT EXISTS employee (
    emp_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    department_id INT,
    position_id INT,
    doj DATE,
    FOREIGN KEY (department_id) REFERENCES department(dept_id) ON DELETE SET NULL,
    FOREIGN KEY (position_id) REFERENCES positions(pos_id) ON DELETE SET NULL
);

-- 5. PERFORMANCE REVIEW TABLE
CREATE TABLE IF NOT EXISTS performance (
    perf_id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT,
    review TEXT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    review_date DATE,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id) ON DELETE CASCADE
);

-- 6. ATTENDANCE TABLE
CREATE TABLE IF NOT EXISTS attendance (
    att_id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT,
    date DATE,
    status ENUM('Present', 'Absent', 'Leave'),
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id) ON DELETE CASCADE
);

-- 7. PROMOTION HISTORY TABLE
CREATE TABLE IF NOT EXISTS promotion (
    promo_id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT,
    old_position VARCHAR(100),
    new_position VARCHAR(100),
    promotion_date DATE,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id) ON DELETE CASCADE
);

-- 8. LEAVE APPLICATIONS TABLE
CREATE TABLE IF NOT EXISTS leaves (
    leave_id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT,
    leave_from DATE,
    leave_to DATE,
    reason TEXT,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id) ON DELETE CASCADE
);

-- 9. SALARY SLIPS TABLE
CREATE TABLE IF NOT EXISTS salary_slip (
    slip_id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT,
    month VARCHAR(20),
    year INT,
    basic_salary INT,
    allowances INT,
    deductions INT,
    net_salary INT,
    FOREIGN KEY (emp_id) REFERENCES employee(emp_id) ON DELETE CASCADE
);

-- INSERT DEFAULT ADMIN ACCOUNT
INSERT INTO admin (username, password) VALUES ('admin', MD5('admin123'));