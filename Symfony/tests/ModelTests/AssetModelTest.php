<?php

namespace App\Tests\ModelTests;

use App\Model\AssetModel;
use App\Model\ConnectionFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;

class AssetModelTest extends TestCase
{
    private $connection;
    private $connectionFactory;

    public function setUp()
    {
        $connectionFactory = new ConnectionFactory();
        $this->connection = $connectionFactory->getConnection();

        $this->dropTables();
        $this->createTables();

        foreach($this->assetProvider() as $asset) {
            $this->connection->exec("INSERT INTO asset (id, roomId, name)
                                               VALUES (" . $asset['id'] . ", " . $asset['roomId'] . ", '" . $asset['name'] . "');");
        }
        $this->connectionFactory = $connectionFactory;
    }

    public function test_If_GetAssets_ReturnsAllAssets()
    {
        $sut = new AssetModel($this->connectionFactory);
        $assortments = $sut->getByAssetName("SeppesBank");

        $this->assertNotNull($assortments);
    }

    public function test_If_GetAssets_ReturnsInvalidArgumentException()
    {
        $sut = new AssetModel($this->connectionFactory);
        $this->expectException(InvalidArgumentException::class);

        $this->dropTables();
        $this->createTables();

        $sut->getByAssetName("Seppesbank");
    }

    private function createTables()
    {
        $schemaPath = dirname(dirname(dirname(__FILE__))) . "/sql/create.sql";
        $schema = file_get_contents($schemaPath);
        $this->connection->exec($schema);
    }

    private function dropTables()
    {
        $schemaPath = dirname(dirname(dirname(__FILE__))) . "/sql/drop.sql";
        $dropSchema = file_get_contents($schemaPath);
        $this->connection->exec($dropSchema);
    }

    private function assetProvider()
    {
        return [
            ["id" => 8, "roomId" => "1", "name" => 'SeppesBank'],
            ["id" => 9, "roomId" => "2", "name" => 'RobinsBank']
        ];
    }
}