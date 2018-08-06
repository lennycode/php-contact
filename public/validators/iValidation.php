<?php
namespace di\Validators;
interface iValidation
{
    function validate();
    function validation_result();
    function validation_failure_detail();
}