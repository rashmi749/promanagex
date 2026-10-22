<?php
require 'config/database.php';

$statements = [
"CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_id INT NOT NULL,
    user_id INT NOT NULL,
    role_in_team VARCHAR(50) DEFAULT 'Member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_member (team_id, user_id)
)",

"CREATE TABLE IF NOT EXISTS time_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    hours DECIMAL(5,2) NOT NULL,
    log_date DATE NOT NULL,
    notes VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)",

"CREATE TABLE IF NOT EXISTS performance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_tasks INT DEFAULT 0,
    completed_tasks INT DEFAULT 0,
    performance_score DECIMAL(5,2) DEFAULT 0,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)",

"CREATE TABLE IF NOT EXISTS task_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)",

"CREATE TABLE IF NOT EXISTS financial_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    type ENUM('Income','Expense') NOT NULL,
    description VARCHAR(255),
    amount DECIMAL(12,2) NOT NULL,
    record_date DATE NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
)",

"CREATE TABLE IF NOT EXISTS client_invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    invoice_number VARCHAR(30) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    status ENUM('Draft','Sent','Paid','Overdue') DEFAULT 'Draft',
    issue_date DATE,
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
)",

"CREATE TABLE IF NOT EXISTS client_feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    client_name VARCHAR(150),
    rating INT DEFAULT 5,
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
)",

"CREATE TABLE IF NOT EXISTS calendar_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time TIME,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
)",

"CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    posted_by INT,
    audience ENUM('all','manager','employee') DEFAULT 'all',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE SET NULL
)",
];

$colUpdates = [
    "ALTER TABLE users ADD COLUMN phone VARCHAR(30)",
    "ALTER TABLE users ADD COLUMN department VARCHAR(100)",
];

echo "<h2>ProManageX - Setup Missing Tables</h2><pre>";

foreach ($statements as $sql) {
    preg_match('/CREATE TABLE IF NOT EXISTS (\w+)/', $sql, $m);
    try {
        $conn->query($sql);
        echo "✅ Table '{$m[1]}' ready\n";
    } catch (mysqli_sql_exception $e) {
        echo "❌ Error creating '{$m[1]}': " . $e->getMessage() . "\n";
    }
}

foreach ($colUpdates as $sql) {
    // suppress "duplicate column" errors since these may already exist
    try {
        $conn->query($sql);
        echo "✅ Column added: $sql\n";
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "ℹ️  Column already exists, skipped.\n";
        } else {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
}

echo "</pre><p style='color:green;font-weight:bold;'>Setup complete! All missing tables have been created.</p>";
echo "<a href='login.php'>Go to Login</a> | <a href='admin/performance.php'>Go to Performance Page</a>";
?>
