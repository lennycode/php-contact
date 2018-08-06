<?php
namespace di\utils;

require_once (__dir__.'/../models/Contact.php');

class ContactMailer
{
    private $contact;
    private $to;
    private $message;
    private $sender;
     

    public function __construct( $contact, $to, $template = null)
    {
        $this->contact = $contact;
      
        $this->to = $to;
    }

    public function send_mail()
    {
        //Only send if contact passes strict validation. UI will block any invalid submissions        
        if($this->contact->getValidation()){
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            $headers .= "To: Guy <{$this->to}>" . "\r\n";
            $headers .= "From: {$this->contact->getName()} <{$this->contact->getEmail()}>" . "\r\n";
            return mail($this->to, "Website Contact From Customer", $this->message(), $headers);
        } else{
            return false;
        }
    }

    public function message()
    {
        return <<<MESSAGE
 
<html>
<style>
body {font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;}
h3, td.data {text-align:center;}
td.head {text-align:left}
h3 {font-weight:800}
table, th, td {
   border: 1px solid black;
   padding: 8px;
}
table {
    border-collapse: collapse;
}
th {
    height: 25px;
}
 tr:nth-child(even) {background-color: #DDDDDD;}
</style>
<head>
  <title>Website Contact</title>
</head>
<body>
  <h3>Mr. Smiley! A Customer has requested a response</p>
  <table align="center">
    <col align="left">
    <col align="center">
    <tr>
      <th>Field</th><th>Information</th>
    </tr>
    <tr>
      <td class="head">Customer Name</td><td class="data">{$this->contact->getName()}</td>
    </tr>
    <tr>
      <td class="head" >Email</td><td class="data"><a href="{$this->contact->getEmail()}">{$this->contact->getEmail()}</a></td> 
    </tr>
    <tr>
      <td class="head">Phone</td><td class="data">{$this->contact->getPhone()}</td> 
    </tr>
    <tr>
      <td class="head">Reason</td><td class="data">{$this->contact->getMemo()}</td> 
    </tr>
  </table>
</body>
</html>
MESSAGE;
    }

}