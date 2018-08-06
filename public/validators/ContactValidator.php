<?php
namespace di\Validators;
require('iValidation.php');

class ContactValidator implements iValidation
{
    private $contact;


    private $validaton_reason = [];
    private $validation_result = true;
    private $phone_pattern = '/^\(*\+*[1-9]{0,3}\)*-*[1-9]{0,3}[-. ]*\(*[2-9]\d{2}\)*[-. ]*\d{3}[-. ]*\d{4} *e*x*t*\.* *\d{0,5}$/';

    public function __construct(Contact $contact = null)
    {
        $this->contact = $contact;
    }

    function validate()
    {
        if (strlen(trim($this->contact->getName())) >= 50) {
            $this->validation_result = false;
            $this->validaton_reason['name'] = "Name must be less than 50 Characters";
        }

        if (strlen(trim($this->contact->getName())) < 2) {
            $this->validation_result = false;
            $this->validaton_reason['name'] = "Name must at least 2 characters!";
        }

        if (!filter_var($this->contact->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $this->validaton_reason['email'] = "Please enter a vaild email";
            $this->validation_result = false;
        }

        $msg_len = strlen(trim($this->contact->getMemo()));
        if ($msg_len > 1000) {

            $this->validaton_reason['memo'] = "Whoa, Shakespeare! Please limit to 1000 characters! Message is {$msg_len} characters";
            $this->validation_result = false;
        }

        if (trim($this->contact->getPhone()) !== "" || strlen(trim($this->contact->getPhone())) >24  ) {
            if (!preg_match($this->phone_pattern, trim($this->contact->getPhone()))) {
                $this->validaton_reason['phone'] = "Please leave blank or enter a valid US or Canadian phone number!";
                $this->validation_result = false;
            }
        }
        $this->contact->setValidation($this->validation_result);
        return $this;
    }

    function validation_result()
    {
        return $this->validation_result;
    }

    function validation_failure_detail()
    {
        return (sizeof($this->validaton_reason) == 0) ? "Success" : $this->validaton_reason;
    }

    function getContact()
    {
        return $this->contact;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
        return $this;
    }
}