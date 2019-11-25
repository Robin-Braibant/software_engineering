<?php

use App\Model\ConnectionFactory;
use PHPUnit\Framework\TestCase;

class ConnectionFactoryTest extends TestCase
{
    public function test_getConnection_isNotNull(){
        $sut = new ConnectionFactory();

        $connection = $sut->getConnection();

        $this->assertNotNull($connection);
    }

    public function test_getConnection_returnsConnectionOfTypePDO()
    {
        $sut = new ConnectionFactory();

        $connection = $sut->getConnection();

        $this->assertTrue($connection instanceof PDO);
    }
}