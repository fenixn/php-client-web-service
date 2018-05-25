<?php
include_once('config.php');
include_once('autoload.php');

header('Content-type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'];
$name = $data['name'];

if (isset($data['id'])) $id = $data['id'];
if (isset($data['client_id'])) $client_id = $data['client_id'];
if (isset($data['section_id'])) $section_id = $data['section_id'];

$type_error = ['status' => 0, 'msg' => 'Type error. The allowed values for type are: client, section, and link.'];
$request_error = ['status' => 0, 'msg' => 'Request error. The allowed request types are: POST, PUT, and DELETE'];

$client_obj = new \ClientsWebService\Model\Client;
$section_obj = new \ClientsWebService\Model\Section;
$link_obj = new \ClientsWebService\Model\Link;

switch ($method) {
    case 'POST':
        switch ($type) {
            case 'client':
                $client_obj->add($name);
                break;
            case 'section':
                $section_obj->add($id, $name);
                break;
            case 'link':
                $link_obj->add($id, $name);
                break;
            default:
                echo json_encode($type_error);
                break;
        }
        break;
    case 'PUT':
        switch ($type) {
            case 'client':
                $client_obj->edit($id, $name);
                break;
            case 'section':
                if (isset($data['client_id'])) {
                    $section_obj->edit($id, $client_id, $name);
                } else {
                    $error = ['status' => 0, 'msg' => 'Missing client_id.'];
                    echo json_encode($error);
                }
                break;
            case 'link':
                if (isset($data['section_id'])) {
                    $link_obj->edit($id, $section_id, $name);
                } else {
                    $error = ['status' => 0, 'msg' => 'Missing section_id.'];
                    echo json_encode($error);
                }
                break;
            default:
                echo json_encode($type_error);
                break;
        }
        break;
    case 'DELETE':
        switch ($type) {
            case 'client':
                $client_obj->delete($id);
                break;
            case 'section':
                $section_obj->delete($id);
                break;
            case 'link':
                $link_obj->delete($id);
                break;
            default:
                echo json_encode($type_error);
                break;
        }
        break;
    default:
        echo json_encode($request_error);
        break;
}
