<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Autumndev\MWS\MWSClient;

final class MWSClientTest extends TestCase {
  private $credentials = [
    'Marketplace_Id'    => 'A1PA6795UKMFR9',
    'Seller_Id'         => 'SOME_SELLER_ID',
    'Access_Key_ID'     => 'SOME_ACCESS_KEY',
    'Secret_Access_Key' => 'SOME_SECRET_ACCESS_KEY',
    'MWSAuthToken'      => '',
    'Application_Name'  => 'Tester',
  ];

  public function testValidateCredentialsOnWrongCredentials(): void {
    $stub = $this->createStub(MWSClient::class)
      ->disableOriginalConstructor()
      ->setMethods(['ListOrderItems'])
      ->getMock();

    $stub->method('ListOrderItems')
      ->will($this->throwException(new Exception('Some Exception')));

    $this->assertFalse($stub->validateCredentials());
  }

  public function testValidateCredentialsOnRightCredentials(): void {
    $stub = $this->createStub(MWSClient::class)
      ->disableOriginalConstructor()
      ->setMethods(['ListOrderItems'])
      ->getMock();

    $stub->method('ListOrderItems')
      ->will($this->throwException(new Exception('Invalid AmazonOrderId: validate')));

    $this->assertTrue($stub->validateCredentials());
  }
}


