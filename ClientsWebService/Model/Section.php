<?php
namespace ClientsWebService\Model;
class Section extends Database
{
    protected $client_error;

    public function __construct() {
        parent::__construct();

        $this->client_error = [
            'status' => 0,
            'msg' => 'The client id does not exist.'
        ];
    }

    /**
     * Check if client id exists. Returns true if it exist, and false otherwise
     * @param int $id       The client id to check
     */
    public function check_client($id) {
        $stmt = $this->pdo->prepare("SELECT id FROM " . $this->clients_tbl . " WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $client = $stmt->fetch();

        if ($client != false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add a section.
     *  @param int $id          The client id the link belongs to.
     *  @param string $name     The section name.
     */
    public function add($id, $name) {
        $client_exists = $this->check_client($id);
        
        if ($client_exists) {
            // Insert the client
            $this->pdo->prepare("INSERT INTO " . $this->sections_tbl . " (client_id, name) VALUES (:id, :name);")
                ->execute([':id' => $id, ':name' => $name]);
            $json = [
                'status' => 1, 
                'msg' => 'New section added', 
                'id' => $this->pdo->lastInsertId(), 
                'client_id' => $id
            ];
            echo json_encode($json);
        } else {
            echo json_encode($this->client_error);
        }
    }

    /**
     * Edit a section
     * @param int $id           The section id to edit.
     * @param int $client_id    The new client id.
     * @param string $name      THe new section name.
     */
    public function edit($id, $client_id, $name) {
        $client_exists = $this->check_client($id);
        
        if ($client_exists) {
            $this->pdo->prepare("UPDATE " . $this->sections_tbl . " SET name = :name, client_id = :client_id WHERE id = :id")
                ->execute([':id' => $id, ':name' => $name, ':client_id' => $client_id]);
            $json = [
                'status' => 1, 
                'msg' => 'Section edited', 
                'id' => $id, 
                'name' => $name,
                'client_id' => $client_id
            ];
            echo json_encode($json);
        } else {
            echo json_encode($this->client_error);
        }
    }

    /**
     * Delete a section
     * @param int $id               The section id to delete
     * @param bool $send_response   Will not send a JSON response if set to false. 
     */
    public function delete($id, $send_response = true) {
        // First get all links owned by section
        $stmt = $this->pdo->prepare("SELECT id FROM " . $this->links_tbl . " WHERE section_id = :id");
        $stmt->execute([':id' => $id]);
        $links_arr = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        
        // Delete all links
        $link = new Link;
        foreach($links_arr as $link_id) {
            $link->delete($link_id, false);
        }

        // Delete Section
        $this->pdo->prepare("DELETE FROM " . $this->sections_tbl . " WHERE id = :id")
            ->execute([':id' => $id]);

        if ($send_response) {
            $json = [
                'status' => 1, 
                'msg' => 'Section deleted', 
                'id' => $id
            ];
            echo json_encode($json);
        }
    }
}
