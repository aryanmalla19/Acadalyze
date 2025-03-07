<?php
namespace App\Core;

class Controller {
    protected $view;

    public function __construct() {
        $this->view = new View();
    }

    public function model($model) {
        require_once "../app/Models/$model.php";
        $modelClass = "App\\Models\\$model";
        return new $modelClass();
    }

    protected function sendResponse($status, $message, $data = [], $code = 200)
    {
        http_response_code($code);
        if(empty($data)){
            echo json_encode([
                "status" => $status,
                "message" => $message,
            ]);
            exit();
        }
        echo json_encode([
            "status" => $status,
            "message" => $message,
            "data" => $data
        ]);
        exit();
    }
}