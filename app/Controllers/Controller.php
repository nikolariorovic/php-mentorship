<?php
namespace App\Controllers;

class Controller {
    
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../../resources/view/' . $view . '.php';
    }
    
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function handleException(\Throwable $e, string $message = 'Something went wrong'): void
    {
        $_SESSION['error'] = $message;
        logError($e->getMessage());
    }
}