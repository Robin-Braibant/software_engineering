<?php namespace App\Model;

use PDO;

class ConnectionFactory
{
    private $database = "mysql";
    private $databaseName = "asset_management_tool";
    private $host = "localhost";
    private $username = "root";
    private $password = "root";

    /**
     * @return PDO
     */
    public function getConnection() {
        $connection = new PDO("$this->database:dbname=$this->databaseName;host=$this->host;",
            $this->username, $this->password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }

    //TODO create seeding?
}