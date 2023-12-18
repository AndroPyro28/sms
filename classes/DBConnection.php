<?php
if (!defined('DB_SERVER_1') || !defined('DB_SERVER_2')) {
    require_once("../initialize.php");
}

class DBConnection
{
    private $host1 = DB_SERVER_1;
    private $username1 = DB_USERNAME_1;
    private $password1 = DB_PASSWORD_1;
    private $database1 = DB_NAME_1;

    private $host2 = DB_SERVER_2;
    private $username2 = DB_USERNAME_2;
    private $password2 = DB_PASSWORD_2;
    private $database2 = DB_NAME_2;

    public $conn1;
    public $conn2;

    public function __construct()
    {
        if (!isset($this->conn1)) {
            $this->conn1 = new mysqli($this->host1, $this->username1, $this->password1, $this->database1);

            if (!$this->conn1) {
                echo 'Cannot connect to database server 1';
                exit;
            }
        }

        if (!isset($this->conn2)) {
            $this->conn2 = new mysqli($this->host2, $this->username2, $this->password2, $this->database2);

            if (!$this->conn2) {
                echo 'Cannot connect to database server 2';
                exit;
            }
        }
    }

    public function __destruct()
    {
        $this->conn1->close();
        $this->conn2->close();
    }
}
?>