<?php
require 'config/database.php';

$password = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("UPDATE users SET password='$password'");

echo "✅ Setup complete!<br>";
echo "Admin: admin@promanagex.com / admin123<br>";
echo "Manager: manager@promanagex.com / admin123<br>";
echo "Employee: employee@promanagex.com / admin123<br>";
echo "<br><a href='login.php'>Go to Login</a>";
?>
