<?php
// Load Composer's autoloader if available
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Collect POST data
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$country = $_POST['country'] ?? '';
$interest = $_POST['interest'] ?? '';

// Validate required fields
if (!$first_name || !$last_name || !$email || !$interest) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

// Email content
$to = 'kabindrakoirala86@gmail.com';
$subject = 'New Contact Request from ATERN Website';
$body = "<h2>New Contact Request</h2>"
    . "<p><strong>Name:</strong> $first_name $last_name</p>"
    . "<p><strong>Email:</strong> $email</p>"
    . "<p><strong>Phone:</strong> $phone</p>"
    . "<p><strong>Country:</strong> $country</p>"
    . "<p><strong>Message:</strong> $interest</p>";

// Try PHPMailer if available
if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    $mail = new PHPMailer(true);
    try {
        // $mail->isSMTP(); // Uncomment and configure SMTP if needed
        $mail->setFrom($email, "$first_name $last_name");
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);
        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
        exit;
    } catch (Exception $e) {
        // Fallback to mail()
    }
}
// Fallback to PHP mail()
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: $first_name $last_name <$email>" . "\r\n";
if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
} 