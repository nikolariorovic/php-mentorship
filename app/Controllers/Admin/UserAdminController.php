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
use App\Services\Interfaces\SpecializationServiceInterface;
use App\Services\SpecializationService;
use App\Repositories\SpecializationRepository;

class UserAdminController extends Controller
{
    public function __construct(
        UserReadServiceInterface $userReadService,
        UserWriteServiceInterface $userWriteService,
        SpecializationServiceInterface $specializationService
    ) {
        $this->userReadService = $userReadService;
        $this->userWriteService = $userWriteService;
        $this->specializationService = $specializationService;
    }

    public function index()
    {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
            ? (int) $_GET['page']
            : 1;
      
        try {
            $users = $this->userReadService->getPaginatedUsers($page);
            $specializations = $this->specializationService->getAllSpecializations();
            return $this->view('admin/index', ['users' => $users, 'specializations' => $specializations]);
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Something went wrong');
            return $this->view('admin/index');
        } catch (InvalidUserDataException $e) {
            $this->handleException($e, 'Validation error. Errors: ' . implode(', ', $e->getErrors()));
            return $this->view('admin/index');
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
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
            $this->handleException($e, 'Something went wrong');
            return $this->redirect('/admin/users');
        } catch (InvalidUserDataException $e) {
            $this->handleException($e, 'Validation error. Errors: ' . implode(', ', $e->getErrors()));
            return $this->redirect('/admin/users');
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->redirect('/admin/users'); 
        }
    }

    public function show(int $id)
    { 
        try {
            $user = $this->userReadService->getUserById($id);
            $specializations = $this->specializationService->getAllSpecializations();
            return $this->view('admin/show', ['user' => $user, 'specializations' => $specializations]);
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Something went wrong');
            return $this->view('admin/show', ['user' => null, 'specializations' => []]);
        } catch (UserNotFoundException $e) {
            $this->handleException($e, 'User not found');
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
            $this->handleException($e, 'Something went wrong');
            return $this->redirect('/admin/users/' . $id);
        } catch (InvalidUserDataException $e) {
            $this->handleException($e, 'Validation error. Errors: ' . implode(', ', $e->getErrors()));
            return $this->redirect('/admin/users/' . $id);
        } catch (UserNotFoundException $e) {
            $this->handleException($e, 'User not found');
            return $this->redirect('/admin/users');
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
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
            $this->handleException($e, 'Something went wrong');
            return $this->redirect('/admin/users');
        } catch (UserNotFoundException $e) {
            $this->handleException($e, 'User not found');
            return $this->redirect('/admin/users');
        } catch (\Throwable $e) {
            $this->handleException($e, 'Error. Something went wrong');
            return $this->redirect('/admin/users');
        }
    }
}