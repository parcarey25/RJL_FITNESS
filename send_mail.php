<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function send_mail($to_email, $to_name, $subject, $body_html, $body_plain = '', $logo_path = null) {
    $cfg = include __DIR__ . '/config.php';
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $cfg['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $cfg['smtp_user'];
        $mail->Password   = $cfg['smtp_pass'];
        $mail->SMTPSecure = $cfg['smtp_secure'];
        $mail->Port       = $cfg['smtp_port'];

        $mail->setFrom($cfg['from_email'], $cfg['from_name']);
        $mail->addAddress($to_email, $to_name);

        if ($logo_path && file_exists($logo_path)) {
            $mail->addEmbeddedImage($logo_path, 'logo_cid');
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body_html;
        $mail->AltBody = $body_plain ?: strip_tags($body_html);

        $mail->send();
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $mail->ErrorInfo];
    }
}
?>