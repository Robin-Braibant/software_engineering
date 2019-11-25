<?php namespace App\Domain;

class Room extends DomainBase{
    private $name;
    private $happinessScore;


    /**
     * Room constructor.
     * @param $name
     * @param $happinessScore
     */
    public function __construct($name, $happinessScore)
    {
        $this->name = $name;
        $this->happinessScore = $happinessScore;
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
     * @return mixed
     */
    public function getHappinessScore()
    {
        return $this->happinessScore;
    }

    public function __toString()
    {
        return strval($this->name);
    }

    /**
     * @param mixed $estimation
     */
    public function setHappinessScore($estimation): void
    {
        $score = 0;
        switch ($estimation){
            case 'happy':
                $score = 2;
                break;
            case 'someWhatHappy':
                $score = 1;
                break;
            case 'someWhatUnhappy':
                $score = -1;
                break;
            case 'unhappy':
                $score = -2;
                break;
        }
        $this->happinessScore += $score;
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