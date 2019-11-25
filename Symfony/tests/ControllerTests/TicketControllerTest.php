<?php

use App\Controller\TicketController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;

class TicketControllerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $model;

    public function setUp()
    {
        $this->model = $this->getMockBuilder('App\Model\TicketModel')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function test_If_GetTicketsByAssetName_ReturnsThoseTickets()
    {
        $request = Request::create('/getTicketsByAssetName','GET',['assetName' => 'SeppesBank']);
        $expected = $this->ticketProvider();

        $this->model->expects($this->any())
            ->method('getTicketsByAssetName')
            ->with($request->get('assetName'))
            ->willReturn($this->ticketProvider());

        $ticketController = new TicketController($this->model);
        $assetEncoded = $ticketController->getTicketsByAssetName($request);
        $result = json_decode($assetEncoded->getContent(), true);

        self::assertEquals($expected, $result);
    }

    public function test_If_CreateNewTicketByAssetName_CreatesATicket()
    {
        $request = Request::create("/createNewTicketByAssetName",'GET',['assetName' => 'SeppesBank'],array(),array(),array(),'Test description');
        $expected = $this->ticketProvider()[0];

        $this->model->expects($this->any())
            ->method('createNewTicketByAssetName')
            ->with($request->get('assetName',$request->getContent()), $request->getContent())
            ->willReturn($this->ticketProvider()[0]);

        $ticketController = new TicketController($this->model);
        $ticketEncoded = $ticketController->createNewTicketByAssetName($request);
        $result = json_decode($ticketEncoded->getContent(), true);

        self::assertEquals($expected, $result);
    }

    public function test_If_UpdateNumberOfVotes_ReturnsTheUpdatedTicket()
    {
        $request = Request::create('/','GET',['ticketId' => '1']);
        $expected = $this->ticketProvider()[0];

        $this->model->expects($this->any())
            ->method('increaseNumberOfVotes')
            ->with($request->get('ticketId'))
            ->willReturn($this->ticketProvider()[0]);

        $ticketController = new TicketController($this->model);
        $assetEncoded = $ticketController->updateNumberOfVotes($request);
        $result = json_decode($assetEncoded->getContent(), true);

        self::assertEquals($expected, $result);
    }

    private function ticketProvider()
    {
        return [
            ["assetId" => 1, "numberOfVotes" => "2", "description" => "Test description."],
            ["assetId" => 2, "numberOfVotes" => "5", "description" => "Test description."]
        ];
    }
}