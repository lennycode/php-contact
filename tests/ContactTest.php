<?php
declare(strict_types=1);
//require(__DIR__."/../models/Contact.php");
require_once(__DIR__."/../public/models/factories/ContactFactory.php");
require_once(__DIR__."/../public/validators/ContactValidator.php");
require_once(__DIR__."/../public/database/DataHandler.php");
require_once(__DIR__."/../public/util/ContactMailer.php");

use PHPUnit\Framework\TestCase;

final class ContactTest extends TestCase
{

     
    public static function setUpBeforeClass()
    {
        
        require_once(__DIR__."/../public/database/data_setup.php");
    }    

    public function testThatContactTableExists():void
    {
        //Integration Test 
        require_once(__DIR__."/../public/database/DataHandler.php");
         $table_query = "SHOW CREATE TABLE contact_info";
        $this->assertContains( "CREATE TABLE `contact_info`", trim( (new di\database\DataHandler())->adhoc($table_query)[0]["Create Table"] )   );

    }

    public function testThatProperContactTableLayoutExists():void
    {
        //Integration Test 
        require_once(__DIR__."/../public/database/DataHandler.php");
         $table_query = "SHOW CREATE TABLE contact_info";
        $this->assertContains( "`fullName` varchar(50) NOT NULL", trim ((new di\database\DataHandler())->adhoc($table_query)[0]["Create Table"]))  ;

    }

    public function testCanCreateBaseModelContact(): void
    {
            $contact = new di\Models\Contact("John", "John@Jon.com", "I am big John", "333-2322-1234");
            $this->assertEquals($contact->toCSV(), "John,John@Jon.com,I am big John,333-2322-1234");
    }


    public function testCanCreateBaseModelContactWithGettersSetters(): void
    {
            $contact = new di\Models\Contact();
            $contact->setEmail("John@Jon.com");
            $contact->setPhone("333-2322-1234");
            $contact->setMemo("I am big John");
            $contact->setName("John");
            $this->assertEquals($contact->toCSV(), "{$contact->getName()},{$contact->getEmail()},{$contact->getMemo()},{$contact->getPhone()}");
    }

    public function testCanEmailTemplateContainsContactInfo(): void 
    {
            $good_contact_data= ['name' => 'Sue Wilson', 'email' => 'sue@albany.org', 'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => '2122321234'];
            $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($good_contact_data, new di\validators\ContactValidator());
            $mx = (new di\utils\ContactMailer($validated_contact->getContact(),"guy-smiley@example.com"))->message();
            $this->assertContains("Sue Wilson",$mx);
            $this->assertContains("sue@albany.org",$mx);
            $this->assertContains("2122321234",$mx);
    }

    public function testCanEmaiCanSendAVaidContact(): void 
    {
            $good_contact_data= ['name' => 'Sue Wilson', 'email' => 'sue@albany.org', 'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => '2122321234'];
            $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($good_contact_data, new di\validators\ContactValidator());
            $mx = (new di\utils\ContactMailer($validated_contact->getContact(),"guy-smiley@example.com"))->send_mail();
            $this->assertEquals($mx, true);
    }

    public function testContactTemlpateContainsTable(): void
    {
            $good_contact_data= ['name' => 'Sue Wilson', 'email' => 'sue@albany.org', 'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => '2122321234'];
            $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($good_contact_data, new di\validators\ContactValidator());
            $mx = (new di\utils\ContactMailer($validated_contact->getContact(),"guy-smiley@example.com"))->message();
            $this->assertRegExp( ("/<table align\=\"center\">.*<\/table>/s" ),$mx );
    }


    public function testContactFactoryEmitsCorrectJson(): void
    {

            $good_contact_data= ['name' => 'Sue Wilson', 'email' => 'sue@albany.org', 'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => '2122321234'];
            $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($good_contact_data, new di\validators\ContactValidator());
            $this->assertEquals(json_decode( $validated_contact->getContact()->toJson()), (object)$good_contact_data);

    }

    public function testContactFactoryEmitsVaildationForValidContact(): void
    {

            $good_contact_data= ['name' => 'Sue Wilson', 'email' => 'sue@albany.org', 'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => '2122321234'];
            $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($good_contact_data, new di\validators\ContactValidator());
            $this->assertContains( $validated_contact->validation_failure_detail(), "Success");
            $this->assertEquals(  $validated_contact->validation_result(), true);  

    }



    public function testContactFailsWithShortName(): void
    {
          $shortname_contact_data= ['name' => 'S', 'email' => 'sue@albany.org', 'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => '2122321234'];
           $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($shortname_contact_data, new di\validators\ContactValidator());
           $this->assertEquals( $validated_contact->validation_failure_detail(),['name' => 'Name must at least 2 characters!' ]);
           $this->assertNotEquals(  $validated_contact->validation_result(), true);  
    }


    public function testContactFailsWithLongNameBadEmail(): void
    {
          $shortname_contact_data= ['name' => str_repeat('x', 50), 'email' => 'sue@albany@org', 'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => '2122321234'];
           $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($shortname_contact_data, new di\validators\ContactValidator());
           $this->assertEquals( $validated_contact->validation_failure_detail(),[ 'name' => 'Name must be less than 50 Characters', 'email' => 'Please enter a vaild email']);
           $this->assertNotEquals(  $validated_contact->validation_result(), true);  
    }

    public function testContactPassesWithnNoPhone(): void
    {
          $shortname_contact_data= ['name' => "Mike Smith", 'email' => 'sue@albany.org', 'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => ''];
           $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($shortname_contact_data, new di\validators\ContactValidator());
           $this->assertContains( $validated_contact->validation_failure_detail(), "Success");
           $this->assertEquals(  $validated_contact->validation_result(), true);  
    }
    public function testContactPassesWithPhoneExtension(): void
    {
          $shortname_contact_data= ['name' => "Mike Smith", 'email' => 'sue@albany.org', 'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => '(518)444-3234ext23412'];
           $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($shortname_contact_data, new di\validators\ContactValidator());
           $this->assertContains( $validated_contact->validation_failure_detail(), "Success");
           $this->assertEquals(  $validated_contact->validation_result(), true);  
    }

     public function testContactFailsWithBadPhone(): void
    {
          $shortname_contact_data= ['name' => "Mike Smith", 'email' => 'sue@albany.org', 'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => '23423'];
           $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($shortname_contact_data, new di\validators\ContactValidator());
           $this->assertEquals( $validated_contact->validation_failure_detail(),[ 'phone' => 'Please leave blank or enter a valid US or Canadian phone number!']);
           $this->assertNotEquals(  $validated_contact->validation_result(), true);  
    }

     public function testContactFailsWithBadPhoneExtension(): void
    {
          $shortname_contact_data= ['name' => "Mike Smith", 'email' => 'sue@albany.org', 'memo' => 'Need info asap about your Finance product. Please call or email!', 'phone' => '2123451234ext3249402983894719434572083475024835208'];
           $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($shortname_contact_data, new di\validators\ContactValidator());
           $this->assertEquals( $validated_contact->validation_failure_detail(),[ 'phone' => 'Please leave blank or enter a valid US or Canadian phone number!']);
           $this->assertNotEquals(  $validated_contact->validation_result(), true);  
    }

       public function testContactFailsWithLongMemoandReportsLength(): void
    {
          $shortname_contact_data= ['name' => "Mike Smith", 'email' => 'sue@albany.org', 'memo' => str_repeat('X', 1001) , 'phone' => ''];
           $validated_contact = di\Models\Factories\ContactFactory::BuildValidatedContact($shortname_contact_data, new di\validators\ContactValidator());
           $this->assertEquals( $validated_contact->validation_failure_detail(),[  'memo' => 'Whoa, Shakespeare! Please limit to 1000 characters! Message is 1001 characters']);
           $this->assertNotEquals(  $validated_contact->validation_result(), true);  
    }


  
}
