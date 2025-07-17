<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\PaymentGateway\PaymentGatewayInterface;
use App\Services\Interfaces\AppointmentWriteServiceInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Validators\PaymentValidator;
use App\Factories\PaymentFactory;
use App\Exceptions\InvalidArgumentException;
use App\Services\Interfaces\PaymentProcessingServiceInterface;
use App\Services\Interfaces\PaymentGatewayManagementServiceInterface;
use App\Services\Interfaces\PaymentHistoryServiceInterface;
use App\Services\Interfaces\SessionServiceInterface;

final class PaymentService implements PaymentProcessingServiceInterface, PaymentGatewayManagementServiceInterface, PaymentHistoryServiceInterface
{
    private array $gateways = [];
    private AppointmentWriteServiceInterface $appointmentWriteService;
    private PaymentRepositoryInterface $paymentRepository;
    private PaymentValidator $paymentValidator;
    private SessionServiceInterface $sessionService;

    public function __construct(
        AppointmentWriteServiceInterface $appointmentWriteService,
        PaymentRepositoryInterface $paymentRepository,
        PaymentValidator $paymentValidator,
        SessionServiceInterface $sessionService
    ) {
        $this->appointmentWriteService = $appointmentWriteService;
        $this->paymentRepository = $paymentRepository;
        $this->paymentValidator = $paymentValidator;
        $this->sessionService = $sessionService;
    }

    public function registerGateway(PaymentGatewayInterface $gateway): void
    {
        $this->gateways[$gateway->getName()] = $gateway;
    }

    public function processPayment(string $gatewayName = null, array $data = []): array
    {
        $user = $this->sessionService->getSession();
        if (!$user || !$user['id'] || $user['role'] !== 'student') {
            throw new InvalidArgumentException('User not authenticated. Please login again.');
        }

        $this->paymentValidator->validate($data);
       
        if ($gatewayName === null) {
            if (empty($this->gateways)) {
                return [
                    'success' => false,
                    'message' => 'No payment gateways available'
                ];
            }
            $gatewayName = array_key_first($this->gateways);
        } elseif (!isset($this->gateways[$gatewayName])) {
            return [
                'success' => false,
                'message' => 'Invalid payment gateway'
            ];
        }

        $gateway = $this->gateways[$gatewayName];
        
        $result = $gateway->charge($data);
        
        $payment = PaymentFactory::create([
            'appointment_id' => (int) $data['appointment_id'],
            'student_id' => (int) $user['id'],
            'price' => $data['price'] ?? 0,
            'transaction_id' => $result['transaction_id'] ?? null,
            'method' => $gateway->getName(),
            'status' => $result['status'] ?? 'failed',
            'card_number' => $data['card_number'] ?  substr($data['card_number'], -4) : null
        ]);

        $paymentId = $this->paymentRepository->savePayment($payment->toArray());
        if ($result['success']) {
            $this->appointmentWriteService->updatePaymentStatus($payment->getAppointmentId(), 'paid', true);
        }
        
        return $result;
    }

    public function isGatewayAvailable(string $gatewayName): bool
    {
        return isset($this->gateways[$gatewayName]);
    }

    public function getAvailableGateways(): array
    {
        return array_keys($this->gateways);
    }

    public function getPayments(int $page): array
    {
        return $this->paymentRepository->getPayments($page);
    }

    public function paymentsAccepted(int $id): void
    {
        $this->paymentRepository->paymentsAccepted($id);
    }
} 