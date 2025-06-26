<?php
namespace App\Controllers;

use App\Services\Interfaces\AuthServiceInterface;
use App\Exceptions\DatabaseException;
use App\Exceptions\UserNotFoundException;

class LoginController extends Controller 
{
    public function __construct(AuthServiceInterface $authService) {
        $this->authService = $authService;
    }

    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        try {
            $user = $this->authService->attempt($email, $password);
            $this->authService->login($user);
            $this->redirect('/');
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Something went wrong');
            return $this->redirect('/');
        } catch (UserNotFoundException $e) {
            $this->handleException($e, 'User not found');
            return $this->redirect('/');
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->redirect('/');
        }
    }

    public function logout() {
        $this->authService->logout();
        $this->redirect('/');
    }
}