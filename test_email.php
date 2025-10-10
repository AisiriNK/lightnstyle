<?php
// brevo_test.php

// ✅ Replace this with your actual Brevo API key
$apiKey = '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';

// ✅ Replace with your verified sender email (the one verified in Brevo)
$verifiedSender = 'lightnstyle040@gmail.com';

// ✅ Replace with your own email to test receiving
$recipient = 'adityachkumar97@gmail.com';

$test_email = [
  'sender' => [
    'name' => 'Light & Style',
    'email' => $verifiedSender
  ],
  'to' => [
    [
      'email' => $recipient,
      'name' => 'Test User'
    ]
  ],
  'subject' => '✅ Brevo API Test Email',
  'htmlContent' => '<p>Hello! This is a test email sent via Brevo SMTP API using PHP cURL. 🎉</p>'
];

// Convert to JSON
$jsonData = json_encode($test_email);

// Initialize cURL
$ch = curl_init('https://api.brevo.com/v3/smtp/email');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json',
    'api-key: ' . $apiKey
]);

// Execute request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Output results
echo "<h3>Response Code: $httpCode</h3>";
echo "<pre>$response</pre>";
?>
