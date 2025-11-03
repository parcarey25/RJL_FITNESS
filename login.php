<?php
include 'db.php';
require __DIR__ . '/send_mail.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, email FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // ✅ Email with logo and custom design
            $adminEmail = $row['email']; // send to user’s email
            $subject = "Welcome Back to RJL Fitness, {$row['username']}!";
            $body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px; padding: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="cid:logo_cid" alt="RJL Fitness Logo" style="width: 100px; height: auto;">
                </div>
                <h2 style="color: #d35400; text-align: center;">Welcome Back, ' . htmlspecialchars($row['username']) . '!</h2>
                <p style="text-align: center; font-size: 16px;">You have successfully logged in to your RJL Fitness account.</p>
                <hr style="margin: 20px 0;">
                <p style="text-align: center; color: #888;">© ' . date('Y') . ' RJL Fitness | Stay fit. Stay strong.</p>
            </div>';

            // Use send_mail with logo embedded
            $res = send_mail($adminEmail, $row['username'], $subject, $body, '', __DIR__ . '/photo/logo.jpg');

            header("Location: home.php");
            exit;
        } else {
            header("Location: index.php?msg=Wrong password");
            exit;
        }
    } else {
        header("Location: index.php?msg=User not found");
        exit;
    }
}
?>