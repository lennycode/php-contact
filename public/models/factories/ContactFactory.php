<?php

namespace di\Models\Factories;
require(__DIR__.'/../../models/Contact.php');

class ContactFactory
{
    static function BuildValidatedContact($contact_array, $validator)
    {

        $contact = new \di\models\Contact();
        foreach ($contact_array as $field => $value)  {
            
            $mname = "set" . ucfirst(strtolower($field));

            if( method_exists($contact_array, $mname )) {
                $value = trim($value);
            } else {
                //Maybe throw execption? Maybe not, we will just fail validation naturally if things aren't right.
            }

            $contact->{$mname}($value);
        }

        $validator->setContact($contact)->validate();

        return $validator;


    }
}