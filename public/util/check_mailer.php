<?php

$good_contact_data= ['name' => 'Sue Wilson', 'email' => 'sue@albany.org' ,'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => '2122321234'];


require(__DIR__.'/../validators/ContactValidator.php');
require(__DIR__.'/../database/DataHandler.php');
require(__DIR__.'/../models/factories/ContactFactory.php');
require(__DIR__.'/../util/ContactMailer.php');

use di\Models\Factories;

$validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($good_contact_data, new di\validators\ContactValidator());
 
 $mx = new di\utils\ContactMailer($validated_contact->getContact(),"guy-smiley@example.com");
 	echo $mx->message();
     echo( ($mx->send_mail() == 1)? "SENT" : "PROBLEM"); 

echo "<hr>";
  