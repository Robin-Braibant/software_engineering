<?php namespace App\Model;

abstract class ModelBase
{
    protected $connectionFactory;

    public function __construct(ConnectionFactory $connectionFactory) {
        $this->connectionFactory = $connectionFactory;
    }
}