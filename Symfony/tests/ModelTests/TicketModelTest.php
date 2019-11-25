<?php


namespace App\Tests\RepositoryTests;


use App\Domain\Ticket;
use App\Model\ConnectionFactory;
use App\Model\TicketModel;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;

class TicketModelTest extends TestCase
{
    private $connection;
    private $connectionFactory;

    public function setUp()
    {
        $connectionFactory = new ConnectionFactory();
        $this->connection = $connectionFactory->getConnection();

        $this->dropTables();
        $this->createTables();

        foreach($this->ticketProvider() as $ticket) {
            $this->connection->exec("INSERT INTO ticket (id, assetId, numberOfVotes, description)
                                               VALUES (" . $ticket['id'] . ", " . $ticket['assetId'] . ", '" . $ticket['numberOfVotes'] . "', '" . $ticket['description'] . "');");
        }

        $this->connectionFactory = $connectionFactory;
    }

    public function testIfGetTicketsByAssetNameReturnsTickets()
    {
        $sut = new TicketModel($this->connectionFactory);
        $rows = $this->assetProvider();
        $row = $rows[0];

        $result = $sut->getTicketsByAssetName("SeppesBank");

        self::assertNotNull($result);

    }

    public function testIfGetTicketsByAssetNameReturnsAllTickets()
    {
        $sut = new TicketModel($this->connectionFactory);
        $rows = $this->assetProvider();
        $row = $rows[0];

        $expected = $this->createTicketFromRow($row);

        $result = $sut->getTicketsByAssetName("SeppesBank");
        self::assertEquals($expected,$result[0]);
    }

    public function testIfGetTicketsReturnsInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->dropTables();
        $this->createTables();
        $sut = new TicketModel($this->connectionFactory);
        $sut->getTicketsByAssetName("foutieveingave");
    }

    public function testIfGetTicketByIdReturnsTheCorrectTicket()
    {
        $sut = new TicketModel($this->connectionFactory);

        $rows = $this->assetProvider();
        $row = $rows[0];

        $expected = $this->createTicketFromRow($row);

        $result = $sut->getTicketById(1);
        self::assertEquals($expected,$result);
    }

    public function testIfGetTicketByIdThrowsInvalidArgumentExceptionWhenPassingWrongId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->dropTables();
        $this->createTables();
        $sut = new TicketModel($this->connectionFactory);
        $sut->getTicketById("NAN");
    }

    public function testIfIncreaseNumberOfVotesIncreasesVotes()
    {
        $sut = new TicketModel($this->connectionFactory);

        $rows = $this->assetProvider();
        $row = $rows[0];

        $expected = $this->createTicketFromRow($row);

        $result = $sut->increaseNumberOfVotes(1);

        self::assertNotEquals($expected,$result);
    }

    public function testIfCreateNewTicketByAssetCreatesANewTicket()
    {
        $sut = new TicketModel($this->connectionFactory);

        $rows = $this->assetProvider();
        $row = $rows[0];

        $expected = $this->createTicketFromRow($row);

        $result = $sut->createNewTicketByAssetName("SeppesBank","test");
        print_r($result);
        self::assertNotNull($result);
    }

    //Private methods
    private function createTicketFromRow($ticketRow)
    {
        $id = $ticketRow['id'];
        $assetId = $ticketRow['assetId'];
        $numberOfVotes = $ticketRow['numberOfVotes'];
        $description = $ticketRow['description'];
        $ticket = new Ticket($assetId,$numberOfVotes,$description);
        $ticket->setId($id);
        return $ticket;
    }

    public function createTables()
    {
        $schemaPath = dirname(dirname(dirname(__FILE__))) . "/sql/createData.sql";
        $schema = file_get_contents($schemaPath);
        $this->connection->exec($schema);
    }

    public function dropTables()
    {
        $schemaPath = dirname(dirname(dirname(__FILE__))) . "/sql/drop.sql";
        $dropSchema = file_get_contents($schemaPath);
        $this->connection->exec($dropSchema);
    }

    public function ticketProvider()
    {
        return [
            ["id" => 1, "assetId" => "1", "numberOfVotes" => '7', "description" => "test"],
            ["id" => 2, "assetId" => "2", "numberOfVotes" => '3', "description" => "Test"],
        ];
    }

    private function assetProvider()
    {
        return [
            ["id" => 1, "assetId" => "1", "numberOfVotes" => "7", "description" => "test"],
            ["id" => 2, "assetId" => "2", "numberOfVotes" => "2", "description" => "test"]
        ];
    }
}