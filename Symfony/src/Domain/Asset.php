<?php namespace App\Domain;

class Asset extends DomainBase{

    private $roomId;
    private $name;

    /**
     * Asset constructor.
     * @param $roomId
     * @param $name
     */
    public function __construct($roomId, $name)
    {
        $this->roomId = $roomId;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRoomId()
    {
        return $this->roomId;
    }

    /**
     * @param mixed $roomId
     */
    public function setRoomId($roomId): void
    {
        $this->roomId = $roomId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $privateFields = get_object_vars($this);

        return $privateFields;
    }
}
