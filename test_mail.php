<?php
require __DIR__ . '/send_mail.php';

send_mail(
    'youremail@gmail.com',    // to
    'RJL Member',             // recipient name
    'Welcome to RJL Fitness!', // subject
    '
    <div style="text-align:center; font-family:Arial; border:1px solid #ccc; border-radius:8px; padding:20px;">
        <img src="cid:logo_cid" style="width:120px;"><br><br>
        <h2 style="color:#e67e22;">Welcome to RJL Fitness!</h2>
        <p>You’ve successfully logged in.</p>
        <hr>
        <small style="color:#888;">© RJL Fitness | Stay fit. Stay strong.</small>
    </div>
    ',
    '',
    __DIR__ . '/images/logo.png'
);
?>