<?php


require('validators/ContactValidator.php');
require('database/DataHandler.php');
require('models/factories/ContactFactory.php');
require('util/ContactMailer.php');
use di\Models\Factories;


//Quick and Dirty, Remova any malicious scripts that could affect email. Extra parens for other sanitizing functions.
//Post data can now be injected directly.
foreach ($_POST as $key => $value) {
    $_POST[$key] = htmlspecialchars ( (($_POST[$key])));
}


$validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($_POST, new di\validators\ContactValidator());

//Pass success if we have a vaild contact. We don't tell the user about our backend issues(e.g. down database). 
//Between the two operations, the message should get through. If there is a validation error, pass the reason to the UI. 
if ($validated_contact->validation_result() ) {
    (new di\database\DataHandler($validated_contact->getContact() ) )->create();
    (new di\utils\ContactMailer($validated_contact->getContact(), "guy-smiley@example.com") )->send_mail();
    echo("success");
    
} else {
    echo(json_encode($validated_contact->validation_failure_detail()));
}
