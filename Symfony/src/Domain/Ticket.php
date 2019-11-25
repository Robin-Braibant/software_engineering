<?php namespace App\Domain;

class Ticket extends DomainBase{
    private $assetId;
    private $numberOfVotes;
    private $description;

    /**
     * Ticket constructor.
     * @param $assetId
     * @param $numberOfVotes
     * @param $description
     */
    public function __construct($assetId, $numberOfVotes, $description)
    {
        $this->assetId = $assetId;
        $this->numberOfVotes = $numberOfVotes;
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAssetId()
    {
        return $this->assetId;
    }

    /**
     * @param mixed $assetId
     */
    public function setAssetId($assetId): void
    {
        $this->assetId = $assetId;
    }

    /**
     * @return mixed
     */
    public function getNumberOfVotes()
    {
        return $this->numberOfVotes;
    }

    /**
     * @param mixed $numberOfVotes
     */
    public function setNumberOfVotes($numberOfVotes): void
    {
        $this->numberOfVotes = $numberOfVotes;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
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