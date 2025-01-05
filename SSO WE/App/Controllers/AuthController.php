<?php
namespace App\Controllers;

use App\Core\Application;
use App\Database\Database;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $this->view('auth/login', [
            'title' => 'Login - SSO Webtool'
        ]);
    }

    public function login()
    {
        error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
        error_log("Request URI: " . $_SERVER['REQUEST_URI']);
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        error_log("Email: $email");
        error_log("Password: $password");
        
        $errors = $this->validate($_POST, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (empty($errors)) {
            // Authentication logic
            $db = Application::getInstance()->getDatabase();
            $query = "SELECT * FROM users WHERE email = :email";
            error_log("Executing query: $query with parameters: " . json_encode(['email' => $email]));
            $user = $db->query($query, ['email' => $email])->fetch();
            
            // Test log entry
            error_log("Login attempt for email: $email");
            error_log("User found: " . json_encode($user));
            
            if ($user && password_verify($password, $user['password'])) {
                // Start session and redirect to features page
                session_start();
                $_SESSION['user_id'] = $user['id'];
                error_log("Login successful for email: $email");
                $this->redirect('/features.php'); // Redirect to features page
            } else {
                error_log("Login failed for email: $email");
                $this->redirect('/login'); // Redirect back to login on failure
            }
        } else {
            error_log("Validation errors: " . json_encode($errors));
        }
        
        $this->view('auth/login', [
            'title' => 'Login - SSO Webtool',
            'errors' => $errors,
            'email' => $email
        ]);
    }

    public function showRegistrationForm()
    {
        $this->view('auth/register', [
            'title' => 'Register - SSO Webtool'
        ]);
    }

    public function register()
    {
        $data = [
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'password' => $_POST['password'] ?? '',
            'password_confirmation' => $_POST['password_confirmation'] ?? ''
        ];

        $errors = $this->validate($data, [
            'email' => 'required|email',
            'phone' => 'required',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        if (empty($errors)) {
            // Registration logic here
            $this->redirect('/login');
        }

        $this->view('auth/register', [
            'title' => 'Register - SSO Webtool',
            'errors' => $errors,
            'data' => $data
        ]);
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }

    public function verifyEmail()
    {
        // Email verification logic here
        $this->view('auth/verify-email', [
            'title' => 'Verify Email - SSO Webtool'
        ]);
    }

    public function verifyPhone()
    {
        // Phone verification logic here
        $this->view('auth/verify-phone', [
            'title' => 'Verify Phone - SSO Webtool'
        ]);
    }
}
