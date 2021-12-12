<?php
class Database {
    // Parameters
    private $host = 'localhost';
    private $db_name = 'employees';
    private $user = 'root';
    private $pass = 'root';
    private $connection;

    /*
    * Returns the connection to the database
    */
    public function connect() {
        $this->connection = null;

        try {
            $this->connection = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name, 
                $this->user, 
                $this->pass
            );
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $this->connection;
    }
}