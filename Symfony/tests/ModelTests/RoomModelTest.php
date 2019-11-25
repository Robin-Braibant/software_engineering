<?php

namespace App\Tests\ModelTests;

use App\Domain\Room;
use App\Model\ConnectionFactory;
use App\Model\RoomModel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;

class RoomModelTest extends TestCase
{
    private $connection;
    private $connectionFactory;

    public function setUp()
    {
        $connectionFactory = new ConnectionFactory();
        $this->connection = $connectionFactory->getConnection();

        $this->dropTables();
        $this->createTables();

        foreach($this->roomProvider() as $room) {
            $this->connection->exec("INSERT INTO room (id, name, happinessScore)
                                               VALUES (" . $room['id'] . ", '" .$room['name'] . "', " . $room['happinessScore'] . ");");
        }
        $this->connectionFactory = $connectionFactory;
    }

    //Test getRooms()
    public function test_If_GetRooms_ReturnsAllRooms()
    {
        $sut = new RoomModel($this->connectionFactory);

        $assortments = $sut->getRooms();
        $result = is_array($assortments);

        self::assertTrue($result);
    }

    public function test_If_GetRooms_ReturnsProvidedAssortmentsFromDatabase()
    {
        $sut = new RoomModel($this->connectionFactory);
        $expected = [];

        foreach($this->roomProvider() as $roomData) {
            $assortment = new Room($roomData['name'], $roomData['happinessScore']);
            $assortment->setId($roomData['id']);
            array_push($expected, $assortment);
        }

        $result = $sut->getRooms();

        self::assertEquals($expected, $result);
    }

    public function test_If_GetRooms_ReturnsInvalidArgumentExceptionWhenListIsEmpty()
    {
        $sut = new RoomModel($this->connectionFactory);
        $this->expectException(InvalidArgumentException::class);

        $this->dropTables();
        $this->createTables();

        $sut->getRooms();
    }

    //Test getByRoomName()
    public function test_If_GetByRoomName_ReturnsNotNull(){
        $sut = new RoomModel($this->connectionFactory);
        $result = $sut->getByRoomName("SeppesRoom");

        self::assertNotNull($result);
    }

    public function test_If_GetByRoomNameReturns_TheCorrectRoom(){
        $sut = new RoomModel($this->connectionFactory);
        $rows = $this->roomProvider();
        $row = $rows[0];
        $expected = $this->createRoomFromRow($row);

        $result = $sut->getByRoomName("SeppesRoom");

        self::assertEquals($expected, $result);
    }

    public function test_If_GetByRoomName_ReturnsInvalidArgumentExceptionWhenListIsEmpty()
    {
        $sut = new RoomModel($this->connectionFactory);
        $this->expectException(InvalidArgumentException::class);
        $wrongRoom = "wrongRoom";
        $sut->getByRoomName($wrongRoom);
    }

    //test getRoomsWithHappinessScoreLowerThan
    public function test_If_GetRoomsWithHappinessScoreLowerThan_ReturnsNotNull(){
        $sut = new RoomModel($this->connectionFactory);
        $happinessScore = 5;
        $result = $sut->getRoomsWithHappinessScoreLowerThan($happinessScore);

        self::assertNotNull($result);
    }

    public function test_If_GetRoomsWithHappinessScoreLowerThan_ReturnsCorrectRooms(){
        $sut = new RoomModel($this->connectionFactory);
        $happinessScore = 5;
        $rows = $this->roomProvider();
        $expected = [];
        foreach ($rows as $row){
            $room = $this->createRoomFromRow($row);
            array_push($expected,$room);
        }
        $result = $sut->getRoomsWithHappinessScoreLowerThan($happinessScore);

        self::assertEquals($expected, $result);
    }

    public function test_If_GetRoomsWithHappinessScoreLowerThan_ThrowsInvalidArgumentExceptionIfResultIsEmpty()
    {
        $sut = new RoomModel($this->connectionFactory);
        $this->expectException(InvalidArgumentException::class);
        $happinessScore = 1;
        $sut->getRoomsWithHappinessScoreLowerThan($happinessScore);
    }

    //test updateHappinessScore()
    public function test_If_updateHappinessScore_ThrowsInvalidArgumentExceptionIfScoreIsHigherThanTwo(){
        $sut = new RoomModel($this->connectionFactory);
        $this->expectException(InvalidArgumentException::class);
        $sut->updateHappinessScore("testRoom", 3);
    }

    public function test_If_updateHappinessScore_ThrowsInvalidArgumentExceptionIfScoreIsLowerThanMinusTwo(){
        $sut = new RoomModel($this->connectionFactory);
        $this->expectException(InvalidArgumentException::class);
        $sut->updateHappinessScore("testRoom", -3);
    }

    public function test_If_updateHappinessScore_ThrowsInvalidArgumentExceptionIfScoreIsZero(){
        $sut = new RoomModel($this->connectionFactory);
        $this->expectException(InvalidArgumentException::class);
        $sut->updateHappinessScore("testRoom", 0);
    }

    public function test_If_updateHappinessScore_ReturnsTheCorrectRoom(){
        $sut = new RoomModel($this->connectionFactory);
        $score = 2;
        $rows = $this->roomProvider();
        $rooms = [];
        foreach ($rows as $row){
            $room = $this->createRoomFromRow($row);
            array_push($rooms,$room);
        }
        $expected = $rooms[0]->getHappinessScore() + $score;

        $result = $sut->updateHappinessScore("SeppesRoom", $score)->getHappinessScore();

        self::assertEquals($expected, $result);
    }

    //Private methods
    private function createRoomFromRow($roomRow){
        $id = $roomRow['id'];
        $name = $roomRow['name'];
        $happinessScore = $roomRow['happinessScore'];
        $room = new Room($name, $happinessScore);
        $room->setId($id);

        return $room;
    }

    private function createTables() {
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

    private function roomProvider()
    {
        return [
            ["id" => 1, "name" => "SeppesRoom", "happinessScore" => 2],
            ["id" => 2, "name" => "RobinRoom", "happinessScore" => 4]
        ];
    }
}