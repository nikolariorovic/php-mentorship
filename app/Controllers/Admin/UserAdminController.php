<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Services\Interfaces\UserReadServiceInterface;
use App\Services\UserService;
use App\Repositories\UserRepository;
use App\Exceptions\DatabaseException;
use App\Exceptions\InvalidUserDataException;
use App\Services\Interfaces\UserWriteServiceInterface;
use App\Validators\UserCreateValidator;
use App\Validators\UserUpdateValidator;
use App\Exceptions\UserNotFoundException;

class UserAdminController extends Controller
{
    private UserReadServiceInterface $userReadService;
    private UserWriteServiceInterface $userWriteService;

    public function __construct()
    {
        $userService = new UserService(new UserRepository(), new UserCreateValidator(), new UserUpdateValidator());
        $this->userReadService = $userService;
        $this->userWriteService = $userService;
    }

    public function index()
    {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
            ? (int) $_GET['page']
            : 1;
      
        try {
            $users = $this->userReadService->getPaginatedUsers($page);
            return $this->view('admin/index', ['users' => $users]);
        } catch (DatabaseException $e) {
            $_SESSION['error'] = 'Something went wrong';
            logError($e->getMessage());
            return $this->view('admin/index');
        } catch (InvalidUserDataException $e) {
            $_SESSION['error'] = 'Validation error. Errors: ' . implode(', ', $e->getErrors());
            return $this->view('admin/index');
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Error. Something went wrong';
            logError($e->getMessage());
            return $this->view('admin/index');
        }
    }

    public function create()
    { 
        try {
            $this->userWriteService->createUser($_POST);
            $_SESSION['success'] = 'User created successfully';
            return $this->redirect('/admin/users');
        } catch (DatabaseException $e) {
            $_SESSION['error'] = 'Something went wrong';
            logError($e->getMessage());
            return $this->redirect('/admin/users');
        } catch (InvalidUserDataException $e) {
            $_SESSION['error'] = 'Validation error. Errors: ' . implode(', ', $e->getErrors());
            return $this->redirect('/admin/users');
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Error. Something went wrong';
            logError($e->getMessage());
            return $this->redirect('/admin/users'); 
        }
    }

    public function show(int $id)
    { 
        try {
            $user = $this->userReadService->getUserById($id);
            return $this->view('admin/show', ['user' => $user]);
        } catch (DatabaseException $e) {
            $_SESSION['error'] = 'Something went wrong';
            logError($e->getMessage());
            return $this->view('admin/show', ['user' => null]);
        } catch (UserNotFoundException $e) {
            $_SESSION['error'] = 'User not found';
            logError($e->getMessage());
            return $this->redirect('/admin/users');
        }
    }

    public function update(int $id)
    {
        try {
            $this->userWriteService->updateUser($id, $_POST);
            $_SESSION['success'] = 'User updated successfully';
            return $this->redirect('/admin/users/' . $id);
        } catch (DatabaseException $e) {
            $_SESSION['error'] = 'Something went wrong';  
            logError($e->getMessage());
            return $this->redirect('/admin/users/' . $id);
        } catch (InvalidUserDataException $e) {
            $_SESSION['error'] = 'Validation error. Errors: ' . implode(', ', $e->getErrors());
            return $this->redirect('/admin/users/' . $id);
        } catch (UserNotFoundException $e) {
            $_SESSION['error'] = 'User not found';
            logError($e->getMessage());
            return $this->redirect('/admin/users');
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Error. Something went wrong';
            logError($e->getMessage());
            return $this->redirect('/admin/users');
        }
    }

    public function delete(int $id)
    {
        try {
            $this->userWriteService->deleteUser($id);
            $_SESSION['success'] = 'User deleted successfully';
            return $this->redirect('/admin/users');
        } catch (DatabaseException $e) {
            $_SESSION['error'] = 'Something went wrong';
            logError($e->getMessage());
            return $this->redirect('/admin/users');
        } catch (UserNotFoundException $e) {
            $_SESSION['error'] = 'User not found';
            logError($e->getMessage());
            return $this->redirect('/admin/users');
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Error. Something went wrong';
            logError($e->getMessage());
            return $this->redirect('/admin/users');
        }
    }
}