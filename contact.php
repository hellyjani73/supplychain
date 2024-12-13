<?php
/*
 *  CONFIGURE EVERYTHING HERE
 */

// an email address that will be in the From field of the email.
$from = 'Demo contact form <info@example.com>';

// an email address that will receive the email with the output of the form
$sendTo = 'hellyjani2002@gmail.com';

// subject of the email
$subject = 'New message from contact form';

// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array(
    'name' => 'Name',
    'email' => 'Email',
    'phone' => 'Phone',
    'message' => 'Message'
);

// message that will be displayed when everything is OK :)
// (JSON format output)
$responseArray = array(
    'type' => 'success',
    'message' => 'Contact form successfully submitted. Thank you, I will get back to you soon!'
);

// If something goes wrong, we will display this message.
// (JSON format output)
$errorMessage = array(
    'type' => 'danger',
    'message' => 'There was an error while submitting the form. Please try again later.'
);

/*
 *  LET'S DO THE SENDING
 */

try {
    if (count($_POST) == 0) throw new \Exception('Form is empty');

    $emailText = "You have a new message from your contact form\n=============================\n";

    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email 
        if (isset($fields[$key]) && !empty($value)) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }

    // Check if all required fields are filled
    foreach ($fields as $key => $label) {
        if (empty($_POST[$key])) {
            throw new \Exception("The $label field is required.");
        }
    }

    // All the necessary headers for the email.
    $headers = array(
        'Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
    );

    // Send email
    if (!mail($sendTo, $subject, $emailText, implode("\n", $headers))) {
        throw new \Exception('Mail could not be sent.');
    }

    echo json_encode($responseArray);

} catch (\Exception $e) {
    echo json_encode(array(
        'type' => 'danger',
        'message' => $e->getMessage()
    ));
}
