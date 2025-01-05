<?php
namespace App\Controllers;

use App\Core\Application;
use App\Database\Database;

abstract class Controller
{
    protected $app;
    protected $db;

    public function __construct()
    {
        $this->app = Application::getInstance(); // Use singleton instance
        $this->app->initDatabase(); // Initialize the database
        $this->db = $this->app->getDatabase(); // Access the db using the new method
    }

    protected function view($view, $data = [])
    {
        extract($data);
        require __DIR__ . "/../../views/{$view}.php";
    }

    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    protected function validate($data, $rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $rulesArray = explode('|', $rule);
            foreach ($rulesArray as $singleRule) {
                $this->applyValidationRule($field, $singleRule, $data, $errors);
            }
        }

        return $errors;
    }

    private function applyValidationRule($field, $rule, $data, &$errors)
    {
        $value = $data[$field] ?? null;
        
        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $errors[$field][] = 'This field is required';
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'Invalid email format';
                }
                break;
            case strpos($rule, 'min:') === 0:
                $min = (int) substr($rule, 4);
                if (strlen($value) < $min) {
                    $errors[$field][] = "Must be at least {$min} characters";
                }
                break;
            case strpos($rule, 'max:') === 0:
                $max = (int) substr($rule, 4);
                if (strlen($value) > $max) {
                    $errors[$field][] = "Must be at most {$max} characters";
                }
                break;
        }
    }
}