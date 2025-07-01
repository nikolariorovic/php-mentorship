<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <link rel="stylesheet" href="../../public/css/login.css">
    <link rel="stylesheet" href="../../public/css/student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .appointments-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .appointments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .appointments-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .back-button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s;
        }
        
        .back-button:hover {
            background: #0056b3;
            color: white;
            text-decoration: none;
        }
        
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .appointments-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }
        
        .appointments-table td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        .appointments-table tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-accepted {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-finished {
            background: #d4edda;
            color: #155724;
        }
        
        .payment-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .payment-paid {
            background: #28a745;
            color: white;
        }
        
        .payment-pending {
            background: #ffc107;
            color: #212529;
        }
        
        .price {
            font-weight: 600;
            color: #28a745;
        }
        
        .rating {
            color: #ffc107;
            font-weight: 600;
        }
        
        .no-appointments {
            text-align: center;
            padding: 50px;
            color: #6c757d;
            font-size: 18px;
        }
        
        .no-appointments i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        .pay-now-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            margin-top: 5px;
            transition: background-color 0.3s;
        }
        
        .pay-now-btn:hover {
            background: #218838;
        }
        
        /* Payment Modal Styles */
        .payment-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .payment-modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .payment-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .payment-modal-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
        
        .close-modal:hover {
            color: #333;
        }
        
        .payment-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .form-group label {
            font-weight: 600;
            color: #333;
        }
        
        .form-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        .payment-amount {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        
        .payment-submit {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .payment-submit:hover {
            background: #0056b3;
        }
        
        .payment-submit:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">
            <i class="fas fa-calendar-check"></i>
            My Appointments
        </div>
        <div class="header-actions">
            <span class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['user']['first_name'] ?? 'Student'); ?>!</span>
            <a href="/home" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
            <a href="/logout" class="logout-button">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </div>

    <div class="appointments-container">
        <div class="appointments-header">
            <h1 class="appointments-title">
                <i class="fas fa-calendar-alt"></i>
                My Appointments
            </h1>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_SESSION['success']); ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_SESSION['error']); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($appointments)): ?>
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Mentor</th>
                        <th>Specialization</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Payment</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; background: #007bff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;">
                                    👨‍🏫
                                </div>
                                <div>
                                    <div style="font-weight: 600;">
                                        <?php echo htmlspecialchars($appointment['mentor_name'] . ' ' . $appointment['mentor_last_name']); ?>
                                    </div>
                                    <div style="font-size: 11px; color: #6c757d;">ID: <?php echo htmlspecialchars($appointment['mentor_id']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="background: #e9ecef; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">
                                <?php echo htmlspecialchars($appointment['specialization_name']); ?>
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 600;">
                                <?php echo date('D, M j, Y', strtotime($appointment['period'])); ?>
                            </div>
                            <div style="font-size: 12px; color: #6c757d;">
                                <?php echo date('H:i', strtotime($appointment['period'])); ?>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-<?php echo $appointment['status']; ?>">
                                <?php echo htmlspecialchars(ucfirst($appointment['status'])); ?>
                            </span>
                        </td>
                        <td>
                            <span class="price">$<?php echo number_format($appointment['price'], 2); ?></span>
                        </td>
                        <td>
                            <?php if ($appointment['payment_status'] == '1'): ?>
                                <span class="payment-badge payment-paid">Paid</span>
                            <?php else: ?>
                                <span class="payment-badge payment-pending">Pending</span>
                                <?php if ($appointment['status'] == 'accepted'): ?>
                                    <button class="pay-now-btn" onclick="openPaymentModal(<?php echo $appointment['id']; ?>, <?php echo $appointment['price']; ?>)">
                                        <i class="fas fa-credit-card"></i> Pay Now
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo date('M j, Y H:i', strtotime($appointment['created_at'])); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php 
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $results_per_page = 10;
            $has_more_results = count($appointments) === $results_per_page;
            ?>
            
            <?php if ($current_page > 1 || $has_more_results): ?>
                <div style="margin-top: 20px; text-align: center;">
                    <div style="display: inline-block; padding: 10px;">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=<?php echo $current_page - 1; ?>" style="margin: 0 5px; padding: 8px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">Previous</a>
                        <?php endif; ?>
                        
                        <span style="margin: 0 5px; padding: 8px 12px; background: #007bff; color: white; border-radius: 4px; font-weight: bold;"><?php echo $current_page; ?></span>
                        
                        <?php if ($has_more_results): ?>
                            <a href="?page=<?php echo $current_page + 1; ?>" style="margin: 0 5px; padding: 8px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">Next</a>
                        <?php endif; ?>
                    </div>
                    
                    <div style="margin-top: 10px; color: #666; font-size: 14px;">
                        Page <?php echo $current_page; ?>
                        <?php if (!empty($appointments)): ?>
                            - Showing <?php echo count($appointments); ?> appointments
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-appointments">
                <i class="fas fa-calendar-times"></i>
                <h3>No appointments found</h3>
                <p>You haven't booked any sessions yet.</p>
                <a href="/home" class="back-button" style="margin-top: 20px;">
                    <i class="fas fa-plus"></i>
                    Book Your First Session
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="payment-modal">
        <div class="payment-modal-content">
            <div class="payment-modal-header">
                <h2 class="payment-modal-title">
                    <i class="fas fa-credit-card"></i>
                    Payment Details
                </h2>
                <button class="close-modal" onclick="closePaymentModal()">&times;</button>
            </div>
            
            <div class="payment-amount">
                Amount to pay: $<span id="paymentAmount">0.00</span>
            </div>
            
            <form id="paymentForm" class="payment-form" action="/processPayment" method="POST">
                <input type="hidden" id="appointmentId" name="appointment_id">
                <input type="hidden" id="paymentAmountHidden" name="price">
                
                <div class="form-group">
                    <label for="cardNumber">Card Number</label>
                    <input type="text" id="cardNumber" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" required>
                </div>
                
                <div class="form-group">
                    <label for="expiryDate">Expiry Date</label>
                    <input type="text" id="expiryDate" name="expiry_date" placeholder="MM/YY" maxlength="5" required>
                </div>
                
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required>
                </div>
                
                <div class="form-group">
                    <label for="cardholderName">Cardholder Name</label>
                    <input type="text" id="cardholderName" name="cardholder_name" placeholder="John Doe" required>
                </div>
                
                <button type="submit" class="payment-submit" id="paymentSubmit">
                    <i class="fas fa-lock"></i>
                    Process Payment
                </button>
            </form>
        </div>
    </div>

    <script>
        // Payment Modal Functions
        function openPaymentModal(appointmentId, amount) {
            document.getElementById('appointmentId').value = appointmentId;
            document.getElementById('paymentAmount').textContent = amount.toFixed(2);
            document.getElementById('paymentAmountHidden').value = amount;
            document.getElementById('paymentModal').style.display = 'block';
        }
        
        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
            document.getElementById('paymentForm').reset();
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('paymentModal');
            if (event.target === modal) {
                closePaymentModal();
            }
        }
        
        // Card number formatting and validation
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
            
            // Luhn algorithm validation for card number
            if (value.length >= 13) {
                let sum = 0;
                let length = value.length;
                let parity = length % 2;
                
                for (let i = 0; i < length; i++) {
                    let digit = parseInt(value[i]);
                    if (i % 2 == parity) {
                        digit *= 2;
                        if (digit > 9) {
                            digit -= 9;
                        }
                    }
                    sum += digit;
                }
                
                if (sum % 10 === 0) {
                    e.target.style.borderColor = '#28a745';
                } else {
                    e.target.style.borderColor = '#dc3545';
                }
            }
        });
        
        // Expiry date formatting and validation
        document.getElementById('expiryDate').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
            
            // Validate expiry date format and validity
            if (value.length === 5) {
                let parts = value.split('/');
                let month = parseInt(parts[0]);
                let year = parseInt(parts[1]) + 2000;
                let currentYear = new Date().getFullYear();
                let currentMonth = new Date().getMonth() + 1;
                
                if (month >= 1 && month <= 12 && year >= currentYear && year <= currentYear + 10) {
                    if (year === currentYear && month < currentMonth) {
                        e.target.style.borderColor = '#dc3545'; // Expired
                    } else {
                        e.target.style.borderColor = '#28a745'; // Valid
                    }
                } else {
                    e.target.style.borderColor = '#dc3545'; // Invalid
                }
            }
        });
        
        // CVV formatting
        document.getElementById('cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
        
        // Cardholder name validation - only letters and spaces
        document.getElementById('cardholderName').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
        });
    </script>
</body>
</html> 