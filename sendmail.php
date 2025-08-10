<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data safely
  $name = htmlspecialchars($_POST["name"]);
  $email = htmlspecialchars($_POST["email"]);
  $phone = htmlspecialchars($_POST["phone"]);
  $message = htmlspecialchars($_POST["message"]);

  // Compose email
  $to = "swethamb65@gmail.com"; // CHANGE THIS TO YOUR COMPANY EMAIL
  $subject = "New Enquiry from Website";

  $body = "You have received a new enquiry:\n\n";
  $body .= "Name: $name\n";
  $body .= "Email: $email\n";
  $body .= "Phone: $phone\n";
  $body .= "Message:\n$message\n";

  $headers = "From: $email\r\n";
  $headers .= "Reply-To: $email\r\n";

  if (mail($to, $subject, $body, $headers)) {
    header("Location: thankyou.html");
    exit;
  } else {
    echo "Sorry, your message could not be sent.";
  }
}
?>
