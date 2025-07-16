<?php
use App\Core\Router;
use App\Middleware\AdminPanelMiddleware;
use App\Core\Container;
use App\Services\Interfaces\UserReadServiceInterface;
use App\Services\Interfaces\UserWriteServiceInterface;
use App\Services\UserReadService;
use App\Services\UserWriteService;
use App\Services\Interfaces\SpecializationServiceInterface;
use App\Validators\UserCreateValidator;
use App\Validators\UserUpdateValidator;
use App\Services\SpecializationService;
use App\Repositories\Interfaces\SpecializationRepositoryInterface;
use App\Repositories\SpecializationRepository;
use App\Repositories\UserRepository;
use App\Controllers\Admin\UserAdminController;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\AuthService;
use App\Controllers\LoginController;
use App\Controllers\StudentController;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use App\Services\Interfaces\AppointmentReadServiceInterface;
use App\Services\Interfaces\AppointmentWriteServiceInterface;
use App\Repositories\AppointmentRepository;
use App\Validators\BookingValidator;
use App\Validators\TimeSlotValidator;
use App\Validators\UpdateAppointmentStatusValidator;
use App\Controllers\Admin\MentorAdminController;
use App\Services\Interfaces\PaymentServiceInterface;
use App\Services\CardPaymentService;
use App\Validators\PaymentValidator;
use App\Controllers\PaymentController;
use App\Services\PaymentService;
use App\Services\PaymentGateway\FakePaymentGateway;
use App\Repositories\PaymentRepository;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Validators\RatingValidator;
use App\Controllers\Admin\DashboardController;
use App\Repositories\Interfaces\UserReadRepositoryInterface;
use App\Repositories\Interfaces\UserWriteRepositoryInterface;
use App\Repositories\Interfaces\UserSpecializationRepositoryInterface;
use App\Services\AppointmentReadService;
use App\Services\AppointmentWriteService;

session_start();

require_once __DIR__ . '/vendor/autoload.php';

$logsDir = __DIR__ . '/storage/logs';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0755, true);
}

function logError($message) {
    $logFile = __DIR__ . '/storage/logs/error.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}
$container = new Container();

registerDependencies($container);

$router = new Router($container);

require_once __DIR__ . '/routes/web.php';

$router->group(['prefix' => '/admin', 'middleware' => [AdminPanelMiddleware::class]], function($router) {
    require __DIR__ . '/routes/admin.php';
});

$router->setNotFoundHandler(function() {
    echo "404 - Page not found";
});

set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function($exception) {
    logError('Uncaught exception: ' . $exception->getMessage());
    
    $isApiRequest = strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') === 0;
    
    if ($isApiRequest) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'message' => 'Internal server error'
        ]);
    } else {
        http_response_code(500);
        echo '<h1>Something went wrong</h1>';
        echo '<p>An error occurred. Please try again later.</p>';
        echo '<a href="/">Go back to home</a>';
    }
    exit;
});

$router->dispatch();

function registerDependencies(Container $container): void
{
    $container->bind(SpecializationServiceInterface::class, function(Container $c) {
        return new SpecializationService(
            $c->resolve(SpecializationRepositoryInterface::class)
        );
    });

    $container->bind(SpecializationRepositoryInterface::class, function(Container $c) {
        return new SpecializationRepository();
    });

    $container->bind(UserReadRepositoryInterface::class, function(Container $c) {
        return new UserRepository();
    });

    $container->bind(UserWriteRepositoryInterface::class, function(Container $c) {
        return new UserRepository();
    });

    $container->bind(UserSpecializationRepositoryInterface::class, function(Container $c) {
        return new UserRepository();
    });

    $container->bind(AppointmentRepositoryInterface::class, function(Container $c) {
        return new AppointmentRepository();
    });

    $container->bind(AuthServiceInterface::class, function(Container $c) {
        return new AuthService(
            $c->resolve(UserReadRepositoryInterface::class)
        );
    });

    $container->bind(UserReadService::class, function(Container $c) {
        return new UserReadService(
            $c->resolve(UserReadRepositoryInterface::class),
            $c->resolve(UserSpecializationRepositoryInterface::class)
        );
    });

    $container->bind(UserWriteService::class, function(Container $c) {
        return new UserWriteService(
            $c->resolve(UserWriteRepositoryInterface::class),
            $c->resolve(UserReadRepositoryInterface::class),
            $c->resolve(UserSpecializationRepositoryInterface::class),
            $c->resolve(UserCreateValidator::class),
            $c->resolve(UserUpdateValidator::class)
        );
    });

    $container->bind(AppointmentReadService::class, function(Container $c) {
        return new AppointmentReadService(
            $c->resolve(AppointmentRepositoryInterface::class),
            $c->resolve(TimeSlotValidator::class)
        );
    });

    $container->bind(AppointmentWriteService::class, function(Container $c) {
        return new AppointmentWriteService(
            $c->resolve(AppointmentRepositoryInterface::class),
            $c->resolve(BookingValidator::class),
            $c->resolve(UpdateAppointmentStatusValidator::class),
            $c->resolve(RatingValidator::class)
        );
    });

    $container->bind(BookingValidator::class, function(Container $c) {
        return new BookingValidator();
    });

    $container->bind(TimeSlotValidator::class, function(Container $c) {
        return new TimeSlotValidator();
    });

    $container->bind(UserCreateValidator::class, function(Container $c) {
        return new UserCreateValidator();
    });

    $container->bind(UserUpdateValidator::class, function(Container $c) {
        return new UserUpdateValidator();
    });

    $container->bind(PaymentValidator::class, function(Container $c) {
        return new PaymentValidator();
    });

    $container->bind(RatingValidator::class, function(Container $c) {
        return new RatingValidator();
    });

    // Payment gateway bindings
    $container->bind(FakePaymentGateway::class, function(Container $c) {
        return new FakePaymentGateway();
    });

    // Payment repository binding
    $container->bind(PaymentRepositoryInterface::class, function(Container $c) {
        return new PaymentRepository();
    });

    $container->bind(UserReadServiceInterface::class, fn(Container $c) => $c->resolve(UserReadService::class));
    $container->bind(UserWriteServiceInterface::class, fn(Container $c) => $c->resolve(UserWriteService::class));
    $container->bind(AppointmentReadServiceInterface::class, fn(Container $c) => $c->resolve(AppointmentReadService::class));
    $container->bind(AppointmentWriteServiceInterface::class, fn(Container $c) => $c->resolve(AppointmentWriteService::class));

    // Payment service binding
    $container->bind(PaymentService::class, function(Container $c) {
        $paymentService = new PaymentService(
            $c->resolve(AppointmentWriteServiceInterface::class),
            $c->resolve(PaymentRepositoryInterface::class),
            $c->resolve(PaymentValidator::class)
        );
        
        // Register fake payment gateway
        $paymentService->registerGateway($c->resolve(FakePaymentGateway::class));
        
        return $paymentService;
    });

    $container->bind(UserAdminController::class, function(Container $container) {
        $userReadService = $container->resolve(UserReadServiceInterface::class);
        $userWriteService = $container->resolve(UserWriteServiceInterface::class);
        $specializationService = $container->resolve(SpecializationServiceInterface::class);
        
        return new \App\Controllers\Admin\UserAdminController(
            $userReadService,
            $userWriteService,
            $specializationService
        );
    });

    $container->bind(LoginController::class, function(Container $container) {
        $authService = $container->resolve(AuthServiceInterface::class);
        
        return new LoginController(
            $authService
        );
    });
    
    $container->bind(StudentController::class, function(Container $container) {
        $specializationService = $container->resolve(SpecializationServiceInterface::class);
        $userReadService = $container->resolve(UserReadServiceInterface::class);
        $appointmentReadService = $container->resolve(AppointmentReadServiceInterface::class);
        $appointmentWriteService = $container->resolve(AppointmentWriteServiceInterface::class);

        return new StudentController(
            $specializationService,
            $userReadService,
            $appointmentReadService,
            $appointmentWriteService
        );
    });

    $container->bind(MentorAdminController::class, function(Container $container) {
        $appointmentReadService = $container->resolve(AppointmentReadServiceInterface::class);
        $appointmentWriteService = $container->resolve(AppointmentWriteServiceInterface::class);

        return new MentorAdminController(
            $appointmentReadService,
            $appointmentWriteService
        );
    });

    $container->bind(DashboardController::class, function(Container $container) {
        $appointmentReadService = $container->resolve(AppointmentReadServiceInterface::class);
        return new DashboardController(
            $appointmentReadService
        );
    });

    $container->bind(PaymentController::class, function(Container $c) {
        return new PaymentController(
            $c->resolve(PaymentService::class)
        );
    });
}