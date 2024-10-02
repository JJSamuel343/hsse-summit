<?php

require_once '../vendor/autoload.php';
require_once "./conn.php";

use Dotenv\Dotenv;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

$dotenv = Dotenv::createImmutable(dirname(__DIR__)); // Adjust path to point to your .env
$dotenv->load();

function generateQRCode($ticket_number)
{
  $verification_url = "https://yourdomain.com/verify.php?ticket_number=" . urlencode($ticket_number);
  // Create QR code options
  $options = new QROptions([
    'eccLevel' => EccLevel::H,              // Error correction level
    'imageBase64' => true,                    // To encode image as base64
    'scale' => 10,                             // Size of the QR code
  ]);

  // Generate the QR code as a base64 encoded PNG image
  $qrCode = (new QRCode($options))->render($verification_url);

  // Return the base64 image string (without the `data:image/png;base64,` part)
  return $qrCode;
}


function prepareEmailContent($name, $ticket_number)
{
  // Load the HTML template
  $template = file_get_contents('../email_template.html');

  // Replace the placeholders
  $template = str_replace('{{ name }}', htmlspecialchars($name), $template);
  $template = str_replace('{{ ticket_number }}', htmlspecialchars($ticket_number), $template);

  // Embed the base64 QR code image inline in the email
  $qr_code_img_tag = '<img src="' . generateQRCode($ticket_number) . '" alt="QR Code">';
  $template = str_replace('{{ qr_code }}', $qr_code_img_tag, $template);

  return $template;
}

function sendEmail($recipient_email, $recipient_name, $email_content)
{
  $api_key = $_ENV['BREVO_API'];  // Replace with your Brevo API Key

  $data = array(
    "sender" => array(
      "name" => "EMRSVP",
      "email" => "Emrsvp@synapzemy.com"
    ),
    "to" => array(
      array(
        "email" => $recipient_email,
        "name" => $recipient_name
      )
    ),
    "subject" => "Your Ticket and QR Code",
    "htmlContent" => $email_content
  );

  $ch = curl_init('https://api.brevo.com/v3/smtp/email');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'api-key: ' . $api_key,
    'Content-Type: application/json'
  ));

  $response = curl_exec($ch);
  curl_close($ch);

  // You can check the response for success/failure
  if ($response) {
    echo "Email sent successfully!";
  } else {
    echo "Failed to send email!";
  }
}

echo prepareEmailContent('Test', '2552424s');
