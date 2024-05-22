<?php

// Set the header to return JSON response
header('Content-Type: application/json');

// Load Composer's autoloader
require 'vendor/autoload.php';

// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Store email address and password in global variables
$GLOBALS['mail_address'] = 'your-email@gmail.com';
$GLOBALS['mail_password'] = 'your-app-password';

// Define a function to send confirmation email
function sendConfirmationEmail($email, $name, $phone, $description): bool|string
{
    // Create an instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP(); // Use SMTP
        $mail->Host = 'smtp.gmail.com'; // SMTP server address
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $GLOBALS['mail_address']; // Your Gmail address
        $mail->Password = $GLOBALS['mail_password']; // Your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'your-name'); // Set sender
        $mail->addAddress($email, $name); // Add recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Registration Confirmation'; // Email subject
        $mail->Body = "Hello $name,<br><br>Your registration with the following details has been received:<br><br>"
            . "Phone: $phone<br>"
            . "Email: $email<br>"
            . "Description: $description<br><br>"
            . "Thank you!"; // HTML email body
        $mail->AltBody = "Hello $name,\n\nYour registration with the following details has been received:\n\n"
            . "Phone: $phone\n"
            . "Email: $email\n"
            . "Description: $description\n\n"
            . "Thank you!"; // Plain text email body for non-HTML email clients

        // Send email
        $mail->send();
        return true; // Return success
    } catch (Exception $e) {
        // Log error if there is an issue
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return $mail->ErrorInfo; // Return error info for debugging
    }
}

try {
    // Create a connection to the SQLite database
    $db = new PDO('sqlite:users.db');
    // Set PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!isset($input['name'], $input['phone'], $input['email'], $input['description'])) {
        throw new Exception('Invalid input');
    }

    // Prepare and execute SQL statement to insert data into users table
    $stmt = $db->prepare("INSERT INTO users (name, phone, email, description) VALUES (:name, :phone, :email, :description)");
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();

    // Send confirmation email
    $emailStatus = sendConfirmationEmail($input['email'], $input['name'], $input['phone'], $input['description']);
    if ($emailStatus === true) {
        // Return success response if user is registered and email sent
        echo json_encode(['status' => 'success', 'message' => 'User registered and email sent']);
    } else {
        // Return success response if user is registered but email not sent
        echo json_encode(['status' => 'success', 'message' => 'User registered but email not sent', 'error' => $emailStatus, "mail" => $GLOBALS['mail_address'], "password" => $GLOBALS['mail_password']]);
    }
} catch (Exception $e) {
    // Log error if there is an issue
    error_log('Error: ' . $e->getMessage());
    // Return error response in JSON format
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}