<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Services\Interfaces\AuthServiceInterface;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Exceptions\DatabaseException;

class LoginController extends Controller {

    private AuthServiceInterface $authService;

    public function __construct() {
        $this->authService = new AuthService(new UserRepository());
    }

    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        try {
            $user = $this->authService->attempt($email, $password);
            if (!$user) throw new UserNotFoundException();
            $this->authService->login($user);
            $this->redirect('/');
        } catch (\App\Exceptions\UserNotFoundException $e) {
            $_SESSION['error'] = 'User not found';
            return $this->redirect('/');
        } catch (DatabaseException $e) {
            $_SESSION['error'] = 'Something went wrong';
            return $this->redirect('/');
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Error. Something went wrong';
            return $this->redirect('/');
        }
    }

    public function logout() {
        $this->authService->logout();
        $this->redirect('/');
    }
}