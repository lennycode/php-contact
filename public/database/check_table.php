<?php
 
require(__DIR__."/DataHandler.php");
      $table_query = "SHOW CREATE TABLE contact_info";

//echo trim(preg_replace('/ENGINE.*$/',  '', (new di\database\DataHandler())->adhoc("SHOW CREATE TABLE contact_info;")[0]["Create Table"]));
print_r (  ( trim( (new di\database\DataHandler())->adhoc($table_query)[0]["Create Table"] )   ));

