<?php
namespace ClientsWebService\Model;
class Link extends Database
{
    protected $section_error;

    public function __construct() {
        parent::__construct();

        $this->section_error = [
            'status' => 0,
            'msg' => 'The section id does not exist.'
        ];
    }

    /**
     * Check if section id exists. Returns true if it exist, and false otherwise
     * @param int $id       The client id to check
     */
    public function check_section($id) {
        $stmt = $this->pdo->prepare("SELECT id FROM " . $this->sections_tbl . " WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $section = $stmt->fetch();

        if ($section != false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add a link.
     *  @param int $id          The section id the link belongs to.
     *  @param string $name     The link name.
     */
    public function add($id, $name) {
        $section_exists = $this->check_section($id);

        if ($section_exists) {
            $this->pdo->prepare("INSERT INTO " . $this->links_tbl . " (section_id, name) VALUES (:id, :name);")
                ->execute([':id' => $id, ':name' => $name]);
            $json = [
                'status' => 1, 
                'msg' => 'New link added', 
                'id' => $this->pdo->lastInsertId(), 
                'section_id' => $id
            ];
            echo json_encode($json);
        } else {
            echo json_encode($this->section_error);
        }
    }

    /**
     * Edit a link.
     * @param int $id           The link id to edit.
     * @param int $section_id   The new section id.
     * @param string $name      The new link name.
     */
    public function edit($id, $section_id, $name) {
        $section_exists = $this->check_section($id);

        if ($section_exists) {
            $this->pdo->prepare("UPDATE " . $this->links_tbl . " SET name = :name, section_id = :section_id  WHERE id = :id")
                ->execute([':id' => $id, ':name' => $name, ':section_id' => $section_id]);
            $json = [
                'status' => 1, 
                'msg' => 'Link edited', 
                'id' => $id, 
                'name' => $name,
                'section_id' => $section_id
            ];
            echo json_encode($json);
        } else {
            echo json_encode($this->section_error);
        }
    }

    /**
     * Delete a link
     * @param int $id               The link id to delete.
     * @param bool $send_response   Will not send a JSON response if set to false. 
     */
    public function delete($id, $send_response = true) {
        // Delete Link
        $this->pdo->prepare("DELETE FROM " . $this->links_tbl . " WHERE id = :id")
            ->execute([':id' => $id]);

        if ($send_response) {
            $json = [
                'status' => 1, 
                'msg' => 'Link deleted', 
                'id' => $id
            ];
            echo json_encode($json);
        }
    }
}
