<?php
namespace App\Core;

class View {
    public function render($view, $data = []) {
        extract($data); // Make data available as variables in the view
        require_once "../app/Views/$view.php";
    }
}
