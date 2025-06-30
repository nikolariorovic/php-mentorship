<?php
// ... existing code ...
?>

<h2>My Appointments</h2>
<?php if (!empty($appointments)): ?>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background: #f8f9fa;">
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">ID</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Student ID</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Date & Time</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Status</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Price</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Payment Status</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Rating</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): ?>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($appointment['id']); ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($appointment['student_id']); ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <?php echo date('D, M j, Y', strtotime($appointment['period'])); ?><br>
                    <?php echo date('H:i', strtotime($appointment['period'])); ?>
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; 
                        background: <?php echo $appointment['status'] === 'pending' ? '#fff3cd' : 
                            ($appointment['status'] === 'accepted' ? '#d1ecf1' : 
                            ($appointment['status'] === 'rejected' ? '#f8d7da' : '#d4edda')); ?>; 
                        color: <?php echo $appointment['status'] === 'pending' ? '#856404' : 
                            ($appointment['status'] === 'accepted' ? '#0c5460' : 
                            ($appointment['status'] === 'rejected' ? '#721c24' : '#155724')); ?>;">
                        <?php echo htmlspecialchars(ucfirst($appointment['status'])); ?>
                    </span>
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">$<?php echo number_format($appointment['price'], 2); ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <?php if ($appointment['payment_status'] == '1'): ?>
                        <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Paid</span>
                    <?php else: ?>
                        <span style="background: #ffc107; color: #212529; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Pending</span>
                    <?php endif; ?>
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <?php if ($appointment['rating']): ?>
                        <?php echo $appointment['rating']; ?>/5
                    <?php else: ?>
                        No rating
                    <?php endif; ?>
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo date('M j, Y H:i', strtotime($appointment['created_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No appointments found.</p>
<?php endif; ?>

<!-- Pagination -->
<?php
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
?>
<div style="margin-top: 20px; text-align: center;">
    <div style="display: inline-block; padding: 10px;">
        <?php if ($current_page >= 2): ?>
            <a href="?page=<?php echo $current_page - 1; ?>" style="margin: 0 5px; padding: 8px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">Previous</a>
        <?php endif; ?>
        <span style="margin: 0 5px; padding: 8px 12px; background: #007bff; color: white; border-radius: 4px; font-weight: bold;"><?php echo $current_page; ?></span>
        <?php if (count($appointments) === 10): ?>
            <a href="?page=<?php echo $current_page + 1; ?>" style="margin: 0 5px; padding: 8px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">Next</a>
        <?php endif; ?>
    </div>
</div> 