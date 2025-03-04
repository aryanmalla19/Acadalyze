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
}