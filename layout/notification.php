<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Card</title>
</head>
<body>

<div class="notification-container">
    <div class="notification-icon" onclick="toggleNotifications()">
        <i class="fa fa-bell"></i> 
        <span class="notification-count">3</span> 
    </div>
    <div id="notification-card" class="notification-card">
        <?php
        $notifications = [
            "Notification 1",
            "Notification 2",
            "Notification 3"
        ];

        if (!empty($notifications)) {
            foreach ($notifications as $notification) {
                echo '<div class="notification-item">' . $notification . '</div>';
            }
        } else {
            echo '<div class="no-notifications">No new notifications</div>';
        }
        ?>
    </div>
</div>

<script src="script.js"></script>

</body>
</html>
<style>

.notification-container {
    position: relative;
    display: inline-block;
}

.notification-icon {
    position: relative;
    cursor: pointer;
    font-size: 24px;
    color: #333;
}

.notification-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
}

.notification-card {
    display: none;
    position: absolute;
    top: 30px;
    right: 0;
    width: 300px;
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.notification-item {
    padding: 10px;
    border
}
</style>
