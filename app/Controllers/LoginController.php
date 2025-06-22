<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Services\Interfaces\AuthServiceInterface;
use App\Repositories\UserRepository;
use App\Exceptions\DatabaseException;
use App\Exceptions\UserNotFoundException;

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
            $this->authService->login($user);
            $this->redirect('/');
        } catch (DatabaseException $e) {
            $_SESSION['error'] = 'Something went wrong';
            logError($e->getMessage());
            return $this->redirect('/');
        } catch (UserNotFoundException $e) {
            $_SESSION['error'] = 'User not found';
            return $this->redirect('/');
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Error. Something went wrong';
            logError($e->getMessage());
            return $this->redirect('/');
        }
    }

    public function logout() {
        $this->authService->logout();
        $this->redirect('/');
    }
}