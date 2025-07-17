<?php
namespace App\Controllers;

use App\Services\PaymentService;
use App\Exceptions\InvalidPaymentDataException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\DatabaseException;

class PaymentController extends Controller 
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function processPayment()
    {
        try {
            $gateway = $_POST['gateway'] ?? null;
            
            $paymentData = $_POST;
            unset($paymentData['gateway']);
            
            if ($gateway !== null && !$this->paymentService->isGatewayAvailable($gateway)) {
                $_SESSION['error'] = 'Invalid payment gateway';
                return $this->redirect('/appointments');
            }
    
            $result = $this->paymentService->processPayment($gateway, $paymentData);
            
            if ($result['success']) {
                $_SESSION['success'] = 'Payment processed successfully!';
            } else {
                $_SESSION['error'] = 'Payment failed: ' . ($result['message'] ?? 'Unknown error');
            }
            
            return $this->redirect('/appointments');
            
        } catch (InvalidArgumentException $e) {
            $this->handleException($e, 'User not authenticated. Please login again.');
            return $this->redirect('/appointments');
        } catch (InvalidPaymentDataException $e) {
            $this->handleException($e, 'Validation failed');
            return $this->redirect('/appointments');
        } catch (\Exception $e) {
            $this->handleException($e, 'Payment processing failed');
            return $this->redirect('/appointments');
        }
    }

    public function getPayments()
    {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
        ? (int) $_GET['page']
        : 1;

        try {
            $payments = $this->paymentService->getPayments($page);
            return $this->view('admin/payments', ['payments' => $payments]);
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Failed to get payments');
            return $this->redirect('/admin/payments');
        } catch (\Exception $e) {
            $this->handleException($e, 'Failed to get payments');
            return $this->redirect('/admin/payments');
        }
    }

    public function paymentsAccepted($id)
    {
        try {
            $payments = $this->paymentService->paymentsAccepted($id);
            return $this->redirect('/admin/payments');
        } catch (DatabaseException $e) {
            $this->handleException($e, 'Failed to get payments');
            return $this->redirect('/admin/payments');
        } catch (\Exception $e) {
            $this->handleException($e, 'Failed to get payments');
            return $this->redirect('/admin/payments');
        }
    }
}