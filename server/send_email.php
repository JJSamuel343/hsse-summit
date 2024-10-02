<?php

require_once '../vendor/autoload.php';
require_once "./conn.php";

use Dotenv\Dotenv;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

$dotenv = Dotenv::createImmutable(dirname(__DIR__)); // Adjust path to point to your .env
$dotenv->load();

function generateQRCode($ticket_number)
{
  $verification_url = "https://synapzemy.com/EMRSVP/verify.php?ticket_number=" . urlencode($ticket_number);
  // Create QR code options
  $options = new QROptions([
    'eccLevel' => EccLevel::H,              // Error correction level
    'imageBase64' => false,
    'outputType' => QRGdImagePNG::GDIMAGE_PNG,
    'scale' => 10,                             // Size of the QR code
  ]);

  // Define the path where the QR code will be saved
  $image_file = __DIR__ . '/qrcodes/' . $ticket_number . time() . '.png';  // Save in 'qrcodes' folder with ticket number as file name

  // Ensure the 'qrcodes' directory exists
  if (!file_exists(__DIR__ . '/qrcodes')) {
    mkdir(__DIR__ . '/qrcodes', 0755, true);  // Create directory if not exists
  }

  // Save the QR code as a PNG file
  (new QRCode($options))->render($verification_url, $image_file);

  // Return the relative path to the image
  return "https://synapzemy.com/EMRSVP/server" . '/qrcodes/' . $ticket_number . time() . '.png'; // The relative path to use in the email content

}


function prepareEmailContent($name, $ticket_number)
{
  // Load the HTML template
  $template = file_get_contents('../email_template.html');

  // Replace the placeholders
  $template = str_replace('{{ name }}', htmlspecialchars($name), $template);
  $template = str_replace('{{ ticket_number }}', htmlspecialchars($ticket_number), $template);

  // Embed the base64 QR code image inline in the email
  $qr_code_img_tag = '<img height="150px" width="150px" src="' . generateQRCode($ticket_number) . '" alt="QR Code">';
  $template = str_replace('{{ qr_code }}', $qr_code_img_tag, $template);

  return $template;
}

function sendEmail($recipient_email, $recipient_name, $ticket_number)
{
  $api_key = $_ENV['BREVO_API'];  // Replace with your Brevo API Key

  $data = array(
    "sender" => array(
      "name" => "HSSE Partner Summit 2024",
      "email" => "Emrsvp@synapzemy.com"
    ),
    "to" => array(
      array(
        "email" => $recipient_email,
        "name" => $recipient_name
      )
    ),
    "params" => array(
      "name" => $recipient_name,
      "photo_url" => generateQRCode($ticket_number),
    ),
    "templateId" => 42,
    "subject" => "HSSE Partner Summit 2024",
  );

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.brevo.com/v3/smtp/email",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
      "accept: application/json",
      "api-key: $api_key",
      "content-type: application/json"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  // You can check the response for success/failure
  if ($response) {
    echo "$recipient_name, $recipient_email | Email sent successfully!";
  } else {
    var_dump($response);
    var_dump($err);
    echo "$recipient_name, $recipient_email | Failed to send email!";
  }
}

function getUsers($conn)
{


  $sql = "SELECT `name`, email, ticket_number from users";
  $output = [];
  if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_row()) {
      $output[] = $row;
    }
    $result->free_result();
  }

  $conn->close();

  return $output;
}





$users = getUsers($conn);

foreach ($users as $u) {


  // $emailContent =  prepareEmailContent($u[0], $u[2]);
  // echo $emailContent;
  sendEmail($u[1], $u[0], $u[2]);
}
