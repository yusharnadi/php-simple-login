<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Config;

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testGetConnection()
    {
        $connection = Database::getConnection();
        self::assertNotNull($connection);
    }

    public function testGetConnectionSingleton()
    {
        $db1 = Database::getConnection();
        $db2 = Database::getConnection();

        self::assertSame($db1, $db2);
    }
}
