<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Payments</title>
    <link rel="stylesheet" href="../../public/css/login.css">
    <link rel="stylesheet" href="../../public/css/admin.css">
    <link rel="stylesheet" href="../../public/css/admin-navigation.css">
</head>
<body>
    <?php include __DIR__ . '/navigation.php'; ?>
    
    <div class="dashboard-content">
        <div class="dashboard-card">
            <div class="card-header">
                <h2>Payments Management</h2>
            </div>
            
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']); ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Appointment ID</th>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Gateway</th>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    try {
                        if (isset($payments) && !empty($payments)): ?>
                            <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['id'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($payment['appointment_id'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($payment['student_name'] ?? 'N/A'); ?></td>
                                <td>$<?php 
                                    try {
                                        echo number_format(is_numeric($payment['amount']) ? (float)$payment['amount'] : 0, 2);
                                    } catch (Exception $e) {
                                        echo '0.00';
                                    }
                                ?></td>
                                <td><?php echo htmlspecialchars($payment['method'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($payment['status'] ?? 'pending'); ?>">
                                        <?php echo htmlspecialchars(ucfirst($payment['status'] ?? 'pending')); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($payment['created_at'] ?? ''); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if (($payment['status'] ?? '') === 'pending'): ?>
                                            <form action="/admin/paymentsAccepted/<?php echo $payment['id'] ?? ''; ?>" method="POST" style="display:inline;">
                                                <button type="submit" class="action-button accept-button" onclick="return confirm('Are you sure you want to accept this payment?');">
                                                    Accept
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 2rem; color: #666;">
                                    No payments found
                                </td>
                            </tr>
                        <?php endif;
                    } catch (Exception $e) {
                        echo '<tr><td colspan="9" style="text-align: center; padding: 2rem; color: #666;">Error loading payments</td></tr>';
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $results_per_page = 10;
            $has_more_results = isset($payments) && count($payments) === $results_per_page;
            ?>
            <?php if ($current_page > 1 || $has_more_results): ?>
                <div style="margin-top: 20px; text-align: center;">
                    <div style="display: inline-block; padding: 10px;">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=<?php echo $current_page - 1; ?>" class="pagination-link">Previous</a>
                        <?php endif; ?>
                        <span style="margin: 0 5px; padding: 8px 12px; background: #007bff; color: white; border-radius: 4px; font-weight: bold;">
                            <?php echo $current_page; ?>
                        </span>
                        <?php if ($has_more_results): ?>
                            <a href="?page=<?php echo $current_page + 1; ?>" class="pagination-link">Next</a>
                        <?php endif; ?>
                    </div>
                    <div style="margin-top: 10px; color: #666; font-size: 14px;">
                        Page <?php echo $current_page; ?>
                        <?php if (!empty($payments)): ?>
                            - Showing <?php echo count($payments); ?> payments
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            

        </div>
    </div>

    <script src="../../public/js/admin.js"></script>
    <script src="../../public/js/admin-navigation.js"></script>
</body>
</html> 