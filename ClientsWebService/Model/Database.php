<?php
namespace ClientsWebService\Model;
class Database
{
    protected $pdo;
    protected $clients_tbl = 'clients';
    protected $links_tbl = 'links';
    protected $sections_tbl = 'sections';

    public function __construct() {
        $this->pdo = $this->db_connect();
    }

    /**
     * Connect to the MySQL database via PDO
     */
    public function db_connect()
    {
        if (!defined('PDO::ATTR_DRIVER_NAME')) {
            $json = ['status' => 0, 'msg' => 'PDO unavailable.'];
            echo json_encode($json);
        }

        try {
            $pdo = new \PDO(getenv('DATABASE'), getenv('DB_USER'), getenv('DB_PASSWORD'));
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            $json = ['status' => 0, 'msg' => $e->getMessage()];
            echo json_encode($json);
        }
    }
}
