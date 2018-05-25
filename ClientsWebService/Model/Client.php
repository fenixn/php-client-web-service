<?php
namespace ClientsWebService\Model;
class Client extends Database
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * Add a client.
     *  @param string $name     The client name.
     */
    public function add($name) {
        $this->pdo->prepare("INSERT INTO " . $this->clients_tbl . " (name) VALUES (:name);")
            ->execute(['name' => $name]);
        $json = [
            'status' => 1, 
            'msg' => 'New client added', 
            'id' => $this->pdo->lastInsertId()
        ];
        echo json_encode($json);
    }

    /**
     * Edit a client.
     * @param int $id       The client id to edit.
     * @param string $name  The new client name.
     */
    public function edit($id, $name) {
        $this->pdo->prepare("UPDATE " . $this->clients_tbl . " SET name = :name WHERE id = :id")
            ->execute([':id' => $id, ':name' => $name]);
        $json = [
            'status' => 1, 
            'msg' => 'Client edited', 
            'id' => $id, 
            'name' => $name
        ];
        echo json_encode($json);
    }

    /**
     * Delete a client, and any sections owned by the client.
     * @param int $id       The client id to delete.
     */
    public function delete($id) {
        // First get all sections owned by client
        $stmt = $this->pdo->prepare("SELECT id FROM " . $this->sections_tbl . " WHERE client_id = :id");
        $stmt->execute([':id' => $id]);
        $sections_arr = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        
        // Delete all sections
        $section = new Section;
        foreach($sections_arr as $section_id) {
            $section->delete($section_id, false);
        }

        // Delete Client
        $this->pdo->prepare("DELETE FROM " . $this->clients_tbl . " WHERE id = :id")
            ->execute([':id' => $id]);

        $json = [
            'status' => 1, 
            'msg' => 'Client deleted', 
            'id' => $id
        ];
        echo json_encode($json);
    }
}
