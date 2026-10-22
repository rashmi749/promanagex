<?php
$role = $_SESSION['role'];
$current = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="logo"><i class="fas fa-project-diagram"></i> ProManageX</div>
    <ul>
        <?php if ($role == 'admin'): ?>
            <li><a href="<?=BASE_URL?>admin/dashboard.php" class="<?=$current=='dashboard.php'?'active':''?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?=BASE_URL?>admin/users.php" class="<?=$current=='users.php'?'active':''?>"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="<?=BASE_URL?>admin/employees.php" class="<?=$current=='employees.php'?'active':''?>"><i class="fas fa-user-tie"></i> Employees</a></li>
            <li><a href="<?=BASE_URL?>admin/projects.php" class="<?=$current=='projects.php'?'active':''?>"><i class="fas fa-briefcase"></i> Projects</a></li>
            <li><a href="<?=BASE_URL?>admin/tasks.php" class="<?=$current=='tasks.php'?'active':''?>"><i class="fas fa-tasks"></i> Tasks</a></li>
            <li><a href="<?=BASE_URL?>admin/teams.php" class="<?=$current=='teams.php'?'active':''?>"><i class="fas fa-user-friends"></i> Teams</a></li>
            <li><a href="<?=BASE_URL?>admin/files.php" class="<?=$current=='files.php'?'active':''?>"><i class="fas fa-folder"></i> Files</a></li>
            <li><a href="<?=BASE_URL?>admin/notifications.php" class="<?=$current=='notifications.php'?'active':''?>"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="<?=BASE_URL?>admin/performance.php" class="<?=$current=='performance.php'?'active':''?>"><i class="fas fa-chart-bar"></i> Performance</a></li>
            <li><a href="<?=BASE_URL?>admin/reports.php" class="<?=$current=='reports.php'?'active':''?>"><i class="fas fa-chart-line"></i> Reports</a></li>
            <li><a href="<?=BASE_URL?>admin/financials.php" class="<?=$current=='financials.php'?'active':''?>"><i class="fas fa-dollar-sign"></i> Financials</a></li>
            <li><a href="<?=BASE_URL?>admin/invoices.php" class="<?=$current=='invoices.php'?'active':''?>"><i class="fas fa-file-invoice"></i> Invoices</a></li>
            <li><a href="<?=BASE_URL?>admin/feedback.php" class="<?=$current=='feedback.php'?'active':''?>"><i class="fas fa-comment-dots"></i> Client Feedback</a></li>
            <li><a href="<?=BASE_URL?>admin/calendar.php" class="<?=$current=='calendar.php'?'active':''?>"><i class="fas fa-calendar-alt"></i> Calendar</a></li>
            <li><a href="<?=BASE_URL?>admin/announcements.php" class="<?=$current=='announcements.php'?'active':''?>"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="<?=BASE_URL?>admin/settings.php" class="<?=$current=='settings.php'?'active':''?>"><i class="fas fa-cog"></i> Settings</a></li>
        <?php elseif ($role == 'manager'): ?>
            <li><a href="<?=BASE_URL?>manager/dashboard.php" class="<?=$current=='dashboard.php'?'active':''?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?=BASE_URL?>manager/projects.php" class="<?=$current=='projects.php'?'active':''?>"><i class="fas fa-briefcase"></i> My Projects</a></li>
            <li><a href="<?=BASE_URL?>manager/tasks.php" class="<?=$current=='tasks.php'?'active':''?>"><i class="fas fa-tasks"></i> Tasks</a></li>
            <li><a href="<?=BASE_URL?>manager/teams.php" class="<?=$current=='teams.php'?'active':''?>"><i class="fas fa-user-friends"></i> Teams</a></li>
            <li><a href="<?=BASE_URL?>manager/files.php" class="<?=$current=='files.php'?'active':''?>"><i class="fas fa-folder"></i> Files</a></li>
            <li><a href="<?=BASE_URL?>manager/notifications.php" class="<?=$current=='notifications.php'?'active':''?>"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="<?=BASE_URL?>manager/reports.php" class="<?=$current=='reports.php'?'active':''?>"><i class="fas fa-chart-line"></i> Reports</a></li>
            <li><a href="<?=BASE_URL?>manager/announcements.php" class="<?=$current=='announcements.php'?'active':''?>"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="<?=BASE_URL?>manager/profile.php" class="<?=$current=='profile.php'?'active':''?>"><i class="fas fa-user"></i> Profile</a></li>
        <?php else: ?>
            <li><a href="<?=BASE_URL?>employee/dashboard.php" class="<?=$current=='dashboard.php'?'active':''?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?=BASE_URL?>employee/tasks.php" class="<?=$current=='tasks.php'?'active':''?>"><i class="fas fa-tasks"></i> My Tasks</a></li>
            <li><a href="<?=BASE_URL?>employee/timelog.php" class="<?=$current=='timelog.php'?'active':''?>"><i class="fas fa-clock"></i> Time Log</a></li>
            <li><a href="<?=BASE_URL?>employee/files.php" class="<?=$current=='files.php'?'active':''?>"><i class="fas fa-folder"></i> Files</a></li>
            <li><a href="<?=BASE_URL?>employee/notifications.php" class="<?=$current=='notifications.php'?'active':''?>"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="<?=BASE_URL?>employee/announcements.php" class="<?=$current=='announcements.php'?'active':''?>"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <li><a href="<?=BASE_URL?>employee/profile.php" class="<?=$current=='profile.php'?'active':''?>"><i class="fas fa-user"></i> Profile</a></li>
        <?php endif; ?>
        <li><a href="<?=BASE_URL?>logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>
