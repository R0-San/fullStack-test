<?php
class Database {
    private $dblocation;
    private $dbuser;
    private $dbpasswd;
    private $dbname;
    private $conn;

    public function __construct($dblocation, $dbuser, $dbpasswd, $dbname) {
        $this->dblocation = $dblocation;
        $this->dbuser = $dbuser;
        $this->dbpasswd = $dbpasswd;
        $this->dbname = $dbname;
        $this->connect();
    }
    private function connect() {
    
        $this->conn = new mysqli($this->dblocation, $this->dbuser, $this->dbpasswd, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection error: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>