<?php
namespace di\Models;
interface iComparable 
{
    public function compare(self $subject);
}