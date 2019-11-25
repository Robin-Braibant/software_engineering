<?php namespace App\Model;

use App\Domain\Ticket;
use \Symfony\Component\HttpClient\Exception\InvalidArgumentException;

class TicketModel extends ModelBase{

    /**
     * @param $assetName
     * @return array
     */
    public function getTicketsByAssetName($assetName){
        $tickets = [];
        try{
            $assetRepository = new AssetModel($this->connectionFactory);
            $asset = $assetRepository->getByAssetName($assetName);

            $assetId = $asset->getId();
            $connection = $this->connectionFactory->getConnection();
            $query = "select id, assetId, numberOfVotes, description
                      from ticket
                      where $assetId = :assetId";
            $statement = $connection->prepare($query);
            $statement->bindParam(":assetId",$assetId);
            $statement->execute();
            while($ticketRow = $statement->fetch()){
                $ticket = $this->createTicketFromRow($ticketRow);
                array_push($tickets,$ticket);
            }
            if(empty($tickets)){
                throw new InvalidArgumentException("The entered assetName is not a correct name");
            }
        } catch (\PDOException $exception){
            print $exception;
        }
        return $tickets;
    }

    public function createNewTicketByAssetName($assetName, $description){
        $ticket = [];
        try{
            $assetRepository = new AssetModel($this->connectionFactory);
            $asset = $assetRepository->getByAssetName($assetName);
            $assetId = $asset->getId();
            $numberOfVotes = 0;

            $ticket = new Ticket($assetId, $numberOfVotes, $description);
            $connection = $this->connectionFactory->getConnection();
            $query = "insert into ticket(assetId, numberOfVotes, description)
                      values(?,?,?)";
            $statement = $connection->prepare($query);
            $statement->execute([$assetId, $numberOfVotes, $description]);
        } catch (\PDOException $exception){
            print $exception;
        }
        return $ticket;
    }

    public function getTicketById($ticketId){
        $ticket = [];

        try{
            $connection = $this->connectionFactory->getConnection();
            $query = "select id, assetId, numberOfVotes, description
                      from ticket
                      where id = ?";
            $statement = $connection->prepare($query);
            $statement->execute([$ticketId]);
            $ticketRow = $statement->fetch();
            if(empty($ticketRow)){
                throw new InvalidArgumentException("The entered id is not a correct id");
            }
            $ticket = $this->createTicketFromRow($ticketRow);
        } catch (\PDOException $exception){
            print $exception;
        }
        return $ticket;
    }

    public function increaseNumberOfVotes($ticketId){
        $ticket = $this->getTicketById($ticketId);
        $assetId = $ticket->getAssetId();
        $newNumberOfVotes = $ticket->getNumberOfVotes() + 1;
        $description = $ticket->getDescription();
        $updatedTicket = new Ticket($assetId, $newNumberOfVotes, $description);
        $updatedTicket->setId($ticketId);
        try {
            $connection = $this->connectionFactory->getConnection();
            $query = "update ticket
                      set numberOfVotes = ?
                      where id = ?";
            $statement = $connection->prepare($query);
            $statement->execute([$newNumberOfVotes, $ticketId]);
        } catch (\PDOException $exception) {
            print $exception;
        }

        return $updatedTicket;
    }

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
}