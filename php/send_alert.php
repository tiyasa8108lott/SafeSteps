<?php
include 'config.php';
require '../phpmailer/PHPMailer-master/src/Exception.php';
require '../phpmailer/PHPMailer-master/src/PHPMailer.php';
require '../phpmailer/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
header('Content-Type: application/json'); 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$location = $_POST['location'];

$user_result = $conn->query("SELECT name FROM users WHERE id = $user_id");
if (!$user_result || $user_result->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit();
}

$user_data = $user_result->fetch_assoc();
$stmt = $conn->prepare("INSERT INTO alerts (user_id, location) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $location);
$stmt->execute();

$maps_url = "https://www.google.com/maps?q=" . urlencode($location);
$contacts_result = $conn->query("SELECT contact_email FROM contacts WHERE user_id = $user_id");

if ($contacts_result->num_rows > 0) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 
        $mail->SMTPAuth =
        $mail->Username = 
        $mail->Password = 
        $mail->SMTPSecure = 
        $mail->Port = 
        $mail->
        $mail->isHTML(true);
        $mail->Subject = "Emergency Alert from SafeSteps";
        $mail->Body = "
        <h3 style='color: #ff4d4d;'>Emergency Alert from SafeSteps</h3>
        <p>Dear Emergency Contact,</p>
        <p>This is a notification from SafeSteps regarding the safety of <strong>{$user_data['name']}</strong>. They may require urgent assistance.</p>
        <p><strong>Location:</strong> <a href='$maps_url' target='_blank' style='color: #00e5ff;'>View on Google Maps</a></p>
        <p>Coordinates: <strong>$location</strong></p>
        <p>Please take immediate action if needed.</p>
        <p>Stay Safe,<br><strong>The SafeSteps Team</strong></p>
        <p style='font-size: 12px; color: #999;'>This is an automated message. Do not reply.</p>
    ";

        while ($row = $contacts_result->fetch_assoc()) {
            $mail->addAddress($row['contact_email']);
        }

        $mail->send();
        echo json_encode(["status" => "success", "message" => "Alert sent successfully!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No contacts found"]);
}
?>
