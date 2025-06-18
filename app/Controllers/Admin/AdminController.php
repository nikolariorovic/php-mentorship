<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Services\Interfaces\UserReadServiceInterface;
use App\Services\UserService;
use App\Repositories\UserRepository;
use App\Exceptions\DatabaseException;
use App\Exceptions\InvalidUserDataException;

class AdminController extends Controller
{
    private UserReadServiceInterface $userReadService;

    public function __construct()
    {
        $userService = new UserService(new UserRepository());
        $this->userReadService = $userService;
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
            return $this->view('admin/index');
        } catch (InvalidUserDataException $e) {
            $_SESSION['error'] = 'Validation error. Errors: ' . implode(', ', $e->getErrors());
            return $this->view('admin/index');
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Error. Something went wrong';
            return $this->view('admin/index');
        }
    }
}