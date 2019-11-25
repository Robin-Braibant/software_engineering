<?php

namespace App\Tests;

use App\Controller\RoomController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RoomControllerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $model;

    public function setUp()
    {
        $this->model = $this->getMockBuilder('App\Model\RoomModel')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function test_If_GetAllRooms_ReturnsStatusCode200()
    {
        $data = ['rooms' => $this->roomProvider(), 'statuscode' => 200];
        $expectedStatusCode = 200;

        $this->model->expects($this->any())
            ->method('getRooms')
            ->willReturn($data);

        $roomController = new RoomController($this->model);
        $statusCode = $roomController->getAllRooms()->getStatusCode();

        self::assertEquals($expectedStatusCode, $statusCode);
    }

    public function test_If_GetAllRooms_DoesNotReturnsNull()
    {
        $data = ['rooms' => $this->roomProvider(), 'statuscode' => 200];

        $this->model->expects($this->any())
            ->method('getRooms')
            ->willReturn($data);

        $roomController = new RoomController($this->model);
        $result = $roomController->getAllRooms();

        self::assertNotNull($result);
    }

    public function test_If_GetByRoomName_ReturnsThatRoom()
    {
        $request = Request::create('/getRoom','GET',['name' => 'SeppesRoom']);
        $expected = $this->roomProvider()[0];

        $this->model->expects($this->any())
            ->method('getByRoomName')
            ->with($request->get('name'))
            ->willReturn($this->roomProvider()[0]);

        $roomController = new RoomController($this->model);
        $roomEncoded = $roomController->getByRoomName($request);
        $result = json_decode($roomEncoded->getContent(), true);

        self::assertEquals($expected, $result);
    }

    public function test_If_GetRoomsWithHappinessScoreLowerThan_ReturnsCorrectRooms()
    {
        $request = Request::create('/getRoom','GET',['happinessScore' => '2']);
        $expected = $this->roomProvider();

        $this->model->expects($this->any())
            ->method('getRoomsWithHappinessScoreLowerThan')
            ->with($request->get('happinessScore'))
            ->willReturn($this->roomProvider());

        $roomController = new RoomController($this->model);
        $roomsEncoded = $roomController->getRoomsWithHappinessScoreLowerThan($request);
        $result = json_decode($roomsEncoded->getContent(), true);

        self::assertEquals($expected, $result);
    }

    public function test_If_UpdateHappinessScore_UpdatesTheHappinessScore()
    {
        $request = Request::create('/getRoom','GET',['name' => 'SeppesRoom' , 'score' => '2']);
        $expected = $this->roomProvider()[0];

        $this->model->expects($this->any())
            ->method('updateHappinessScore')
            ->with($request->get('name'), $request->get('score'))
            ->willReturn($this->roomProvider()[0]);

        $roomController = new RoomController($this->model);
        $roomsEncoded = $roomController->updateHappinessScore($request);
        $result = json_decode($roomsEncoded->getContent(), true);

        self::assertEquals($expected, $result);
    }

    private function roomProvider()
    {
        return [
            ["id" => 1, "name" => "SeppesRoom", "happinessScore" => "0"],
            ["id" => 2, "name" => "RobinRoom", "happinessScore" => "1"]
        ];
    }
}