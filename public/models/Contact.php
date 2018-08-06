<?php
namespace di\Models;
require ('BaseModel.php');

class Contact extends BaseModel
{
    private $name;
    private $email;
    private $memo;
    private $phone;
    private $validated = FALSE;

    /**
     * Contact constructor.
     * @param $name
     * @param $email
     * @param $memo
     * @param $phone
     */
    public function __construct($name = null, $email = null, $memo = null, $phone = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->memo = $memo;
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * @param mixed $memo
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function toCSV()
    {
        return "{$this->name},{$this->email},{$this->memo},{$this->phone}";
    }

    public function __toString()
    {
        return "{$this->name},{$this->email},{$this->memo},{$this->phone}";
    }

    public function toArray()
    {
        return ["name"=>$this->name,"email"=>$this->email,"memo"=>$this->memo,"phone"=>$this->phone];
    }

    public function toJSON()
    {
        //This is very useful for MongoDB type dbs.
        return json_encode($this->toArray());
    }

    public function compare(self $subject) 
    {
        return $this->__toString() === $subject->__toString();
    }


      /**
     * @return mixed
     */
    public function getValidation()
    {
        return $this->validated;
    }

    public function setValidation($value)
    {
        return $this->validated = $value;
    }

}