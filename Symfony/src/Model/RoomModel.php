<?php namespace App\Model;

use App\Domain\Room;
use PDOException;
use \Symfony\Component\HttpClient\Exception\InvalidArgumentException;

class RoomModel extends ModelBase implements IRoomModel {
    /**
     * @return array
     */
    public function getRooms()
    {
        $rooms = array();
        try{
            $connection = $this->connectionFactory->getConnection();

            $query = "select id, name, happinessScore 
                      from room";

            $statement = $connection->query($query);
            while($roomRow = $statement->fetch()){
                $room = $this->createRoomFromRow($roomRow);
                array_push($rooms,$room);
            }
            if(empty($rooms)){
                throw new InvalidArgumentException("The entered name is not a correct name");
            }
        } catch (PDOException $exception) {
            print $exception;
        }
        return $rooms;
    }

    /**
     * @param $roomName
     * @return Room|array
     */
    public function getByRoomName($roomName){
        $room = [];
        try{
            $connection = $this->connectionFactory->getConnection();
            $query = "select id, name, happinessScore 
                      from room
                      where name = ?";
            $statement = $connection->prepare($query);
            $statement->execute([$roomName]);
            $roomRow = $statement->fetch();
            if(empty($roomRow)){
                throw new InvalidArgumentException("The entered name is not a correct name");
            }
            $room = $this->createRoomFromRow($roomRow);
        } catch (PDOException $exception){
            print $exception;
        }
        return $room;
    }

    public function getRoomsWithHappinessScoreLowerThan($happinessScore){
        $rooms = [];
        try{
            $connection = $this->connectionFactory->getConnection();
            $query = "select *
                      from room
                      where happinessScore < ?";
            $statement = $connection->prepare($query);
            $statement->execute([$happinessScore]);
            while($roomRow = $statement->fetch()){
                $room = $this->createRoomFromRow($roomRow);
                array_push($rooms,$room);
            }
            if(empty($rooms)){
                throw new InvalidArgumentException("There are no entries lower than the given happinessScore");
            }
        } catch (PDOException $exception) {
            print $exception;
        }
        return $rooms;
    }

    public function updateHappinessScore($name, $score){
            if($score > 2 || $score < -2 || $score == 0){
                throw new InvalidArgumentException("The entered Score is not a correct value");
            }
            $room = $this->getByRoomName($name);
            $roomName = $name;
            $roomHappinessScore = $room->getHappinessScore();
            $roomId = $room->getId();
            $newHappinessScore = $roomHappinessScore + $score;

            $updatedRoom = new Room($roomName, $newHappinessScore);
            $updatedRoom->setId($roomId);

            try {
                $query = "update room 
                          set name = ?, happinessScore = ? 
                          where id = ?";
                $statement =$this->connectionFactory->getConnection()->prepare($query);
                $statement->execute([$roomName,$newHappinessScore, $roomId]);
            } catch (PDOException $exception){
                print $exception;
            }
            return $updatedRoom;
    }

    private function createRoomFromRow($roomRow){
        $id = $roomRow['id'];
        $name = $roomRow['name'];
        $happinessScore = $roomRow['happinessScore'];
        $room = new Room($name, $happinessScore);
        $room->setId($id);

        return $room;
    }
}