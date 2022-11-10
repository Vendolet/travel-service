<?php

namespace app\controller;

use database\Database;

class Controller
{
    public object $conn;
    public bool $isAuth;

    public function __construct(){

        $db_config = require_once BASE_DIR . 'app/config/db.php';

        $db = new Database($db_config['host'],
                            $db_config['dbname'],
                            $db_config['username'],
                            $db_config['password'],
                            $db_config['charset']);

        $this->conn = $db->getConnect();
        $this->isAuth = isset($_SESSION['user']);
    }
}
