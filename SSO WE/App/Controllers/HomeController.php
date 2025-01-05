<?php
namespace App\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Welcome to SSO Webtool',
            'description' => 'A secure single sign-on solution for your applications'
        ];
        
        $this->view('home/index', $data);
    }
}
