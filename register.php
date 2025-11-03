<?php
include 'db.php';
require __DIR__ . '/send_mail.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = $_POST['password'];
    $full_name = $conn->real_escape_string($_POST['full_name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');

    if (empty($username) || empty($password)) {
        header("Location: index.php?msg=" . urlencode("Please provide username and password"));
        exit;
    }

    // check exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        header("Location: index.php?msg=" . urlencode("Username already exists"));
        exit;
    }
    $stmt->close();

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username,password,full_name,email,phone,role) VALUES (?,?,?,?,?, 'member')");
    $stmt->bind_param("sssss", $username, $hashed, $full_name, $email, $phone);
    
    if ($stmt->execute()) {
        // ✅ Send email notification to admin
        $adminEmail = 'yournotify@gmail.com'; // change this
        $subject = "New registration: {$username}";
        $body = "<p>New user registered:</p>
                 <ul>
                   <li>Username: " . htmlspecialchars($username) . "</li>
                   <li>Full name: " . htmlspecialchars($full_name) . "</li>
                   <li>Email: " . htmlspecialchars($email) . "</li>
                   <li>Phone: " . htmlspecialchars($phone) . "</li>
                 </ul>";
        send_mail($adminEmail, 'Admin', $subject, $body);

        // ✅ Send confirmation email to the user
        if (!empty($email)) {
            $userSub = "Welcome to RJL Fitness, {$full_name}";
            $userBody = "<p>Hi " . htmlspecialchars($full_name ?: $username) . ",</p>
                         <p>Thanks for registering at RJL Fitness! You can now log in using your username: <strong>" . htmlspecialchars($username) . "</strong>.</p>";
            send_mail($email, $full_name ?: $username, $userSub, $userBody);
        }

        header("Location: index.php?msg=" . urlencode("Successfully registered. Please check your email."));
        exit;
    } else {
        header("Location: index.php?msg=" . urlencode("Registration error"));
        exit;
    }
}
?>
