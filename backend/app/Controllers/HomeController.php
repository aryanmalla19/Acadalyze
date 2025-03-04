<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        $userModel = $this->model('User');
        $data = [
            'title' => 'Home Page',
            'users' => $userModel->getAllUsers()
        ];
        $this->view->render('home/index', $data);
    }

    public function about($param = '') {
        $data = ['title' => 'About Page', 'param' => $param];
        $this->view->render('home/index', $data);
    }
}