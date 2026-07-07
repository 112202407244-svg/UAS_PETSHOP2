<?php

class AuthController extends Controller
{
    private object $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function login(): void 
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('index.php?url=product');
            return;
        }

        if ($this->isPost()) {
            $email = $this->input('email');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $this->view('auth/login', [
                    'error' => 'Email dan Password wajib diisi.'
                ]);
                return;
            }

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                $this->redirect('index.php?url=product');
                return;
            } else {
                $this->view('auth/login', [
                    'error' => 'Email atau password salah.'
                ]);
            }
            return;
        }
        $this->view('auth/login');
    }

    public function register(): void 
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('index.php?url=product');
            return;
        }

        if ($this->isPost()) {
            $name = $this->input('name');
            $email = $this->input('email');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if (empty($name) || empty($email) || empty($password)) {
                $this->view('auth/register', [
                    'error' => 'Semua field wajib diisi.'
                ]);
                return;
            }

            if ($password !== $confirm) {
                $this->view('auth/register', [
                    'error' => 'Konfirmasi password tidak cocok.'
                ]);
                return;
            }

            if (strlen($password) < 6) {
                $this->view('auth/register', [
                    'error' => 'Password minimal 6 karakter.'
                ]);
                return;
            }

            if ($this->userModel->findByEmail($email)) {
                $this->view('auth/register', [
                    'error' => 'Email sudah terdaftar.'
                ]);
                return;
            }

            $this->userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role' => 'user'
            ]);
            $this->redirect('index.php?url=auth/login');
            return;
         }

         $this->view('auth/register');
    }

    public function logout(): void 
    {
        session_destroy();
        
        $this->redirect('index.php?url=auth/login');

    }
}