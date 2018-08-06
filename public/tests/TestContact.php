<?php
declare(strict_types=1);
//require(__DIR__."/../models/Contact.php");
require(__DIR__."/../models/factories/ContactFactory.php");
require(__DIR__."/../validators/ContactValidator.php");
require(__DIR__."/../database/DataHandler.php");


use PHPUnit\Framework\TestCase;

final class ContactTest extends TestCase
{

     
   protected function setUp()
   {
 

   }

    

    public function testThatContactTableExists():void
    {
        //require(__DIR__."/../database/data_setup.php");
        
         $table_query = "SHOW CREATE TABLE contact_info";
        $this->assertTrue(strpos( trim( (new di\database\DataHandler())->adhoc($table_query)) , "CREATE TABLE `contact_info`" ));

    }

    public function testCanCreateBaseModelContact(): void
    {
            $contact = new di\Models\Contact("John", "John@Jon.com", "I am big John", "333-2322-1234");
            $this->assertEquals($contact->toCSV(), "John,John@Jon.com,I am big John,333-2322-1234");
    }


    public function testCanCreateBaseModelxContact(): void
    {
            $contact = new di\Models\Contact("John", "John@Jon.com", "I am big John", "333-2322-1234");
            $this->assertEquals($contact->toCSV(), "John,John@Jon.com,I am big John,333-2322-1234");
    }

    // public function testCanCreateContactFromFactory: void 

    // {



    // }

    // public function testCannotBeCreatedFromInvalidEmailAddress(): void
    // {
    //     $this->expectException(InvalidArgumentException::class);

    //     Email::fromString('invalid');
    // }

    // public function testCanBeUsedAsString(): void
    // {
    //     $this->assertEquals(
    //         'user@example.com',
    //         Email::fromString('user@example.com')
    //     );
    // }
}
