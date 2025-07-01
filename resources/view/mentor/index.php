<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Dashboard</title>
    <link rel="stylesheet" href="../../public/css/login.css">
</head>
<body>
    <div class="header">
        <div class="header-title">Mentor Dashboard</div>
        <a href="/logout" class="logout-button">Logout</a>
    </div>
    <div class="dashboard-content">
        <div class="dashboard-card">
            <h2>My Appointments</h2>
            
            <?php if (!empty($appointments)): ?>
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">ID</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Student</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Specialization</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Date & Time</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Status</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Price</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Payment Status</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Created At</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                        <tr id="appointment-row-<?php echo $appointment['id']; ?>">
                            <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($appointment['id']); ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 35px; height: 35px; background: #28a745; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">
                                        👨‍🎓
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">
                                            <?php echo htmlspecialchars($appointment['student_name'] . ' ' . $appointment['student_last_name']); ?>
                                        </div>
                                        <div style="font-size: 11px; color: #6c757d;">ID: <?php echo htmlspecialchars($appointment['student_id']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <span style="background: #e9ecef; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                    <?php echo htmlspecialchars($appointment['specialization_name']); ?>
                                </span>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <?php echo date('D, M j, Y', strtotime($appointment['period'])); ?><br>
                                <?php echo date('H:i', strtotime($appointment['period'])); ?>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <span id="status-<?php echo $appointment['id']; ?>" style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; 
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
                                <?php if ($appointment['payment_status'] == 'confirmed'): ?>
                                    <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Confirmed</span>
                                <?php else: ?>
                                    <span style="background: #ffc107; color: #212529; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?php echo date('M j, Y H:i', strtotime($appointment['created_at'])); ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <div id="actions-<?php echo $appointment['id']; ?>">
                                    <?php if ($appointment['status'] === 'pending'): ?>
                                        <button onclick="updateAppointmentStatus(<?php echo $appointment['id']; ?>, 'accepted')" 
                                                style="margin: 2px; padding: 6px 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                                            Approve
                                        </button>
                                        <button onclick="updateAppointmentStatus(<?php echo $appointment['id']; ?>, 'rejected')" 
                                                style="margin: 2px; padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                                            Decline
                                        </button>
                                    <?php elseif ($appointment['status'] === 'accepted' && $appointment['payment_status'] == 'confirmed'): ?>
                                        <button onclick="updateAppointmentStatus(<?php echo $appointment['id']; ?>, 'finished')" 
                                                style="margin: 2px; padding: 6px 12px; background: #17a2b8; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                                            Mark as Finished
                                        </button>
                                    <?php elseif ($appointment['status'] === 'paid' && $appointment['payment_status'] == 'confirmed'): ?>
                                        <button onclick="updateAppointmentStatus(<?php echo $appointment['id']; ?>, 'finished')" 
                                                style="margin: 2px; padding: 6px 12px; background: #17a2b8; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                                            Mark as Finished
                                        </button>
                                    <?php else: ?>
                                        <span style="color: #6c757d; font-size: 12px;">No actions available</span>
                                    <?php endif; ?>
                                </div>
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
                <p>No appointments found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        console.log('=== JAVASCRIPT TEST ===');
        console.log('If you see this, JavaScript is working');
        
        function updateAppointmentStatus(appointmentId, newStatus) {
            console.log('=== UPDATE APPOINTMENT STATUS ===');
            console.log('Appointment ID:', appointmentId);
            console.log('New Status:', newStatus);
            
            if (!confirm('Are you sure you want to ' + newStatus + ' this appointment?')) {
                console.log('User cancelled');
                return;
            }

            console.log('User confirmed, sending request...');

            // Disable the button to prevent double clicks
            const actionDiv = document.getElementById('actions-' + appointmentId);
            actionDiv.innerHTML = '<span style="color: #6c757d; font-size: 12px;">Updating...</span>';

            // Use FormData instead of JSON
            const formData = new FormData();
            formData.append('appointment_id', appointmentId);
            formData.append('status', newStatus);
            
            console.log('Sending request to: /admin/mentor/update-status-appointment');
            console.log('FormData contents:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            fetch('/admin/mentor/update-status-appointment', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                    // Removed Content-Type to let browser set it for FormData
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response status text:', response.statusText);
                
                return response.text().then(text => {
                    console.log('Raw response text:', text);
                    console.log('Response text length:', text.length);
                    
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        console.error('Failed to parse:', text);
                        throw new Error('Invalid JSON response: ' + text);
                    }
                });
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    console.log('Success! Updating UI...');
                    
                    // Update the status display
                    const statusElement = document.getElementById('status-' + appointmentId);
                    let backgroundColor, textColor, statusText;
                    
                    switch(newStatus) {
                        case 'accepted':
                            backgroundColor = '#d1ecf1';
                            textColor = '#0c5460';
                            statusText = 'Accepted';
                            break;
                        case 'paid':
                            backgroundColor = '#28a745';
                            textColor = '#ffffff';
                            statusText = 'Paid';
                            break;
                        case 'rejected':
                            backgroundColor = '#f8d7da';
                            textColor = '#721c24';
                            statusText = 'Rejected';
                            break;
                        case 'finished':
                            backgroundColor = '#d4edda';
                            textColor = '#155724';
                            statusText = 'Finished';
                            break;
                        default:
                            backgroundColor = '#fff3cd';
                            textColor = '#856404';
                            statusText = 'Pending';
                    }
                    
                    statusElement.style.background = backgroundColor;
                    statusElement.style.color = textColor;
                    statusElement.textContent = statusText;
                    
                    // Update actions based on new status
                    if (newStatus === 'paid') {
                        // Check if payment is completed before showing "Mark as Finished"
                        const paymentStatusElement = document.querySelector(`#appointment-row-${appointmentId} td:nth-child(7) span`);
                        const isConfirmed = paymentStatusElement && paymentStatusElement.textContent.trim() === 'Confirmed';
                        
                        if (isConfirmed) {
                            actionDiv.innerHTML = '<button onclick="updateAppointmentStatus(' + appointmentId + ', \'finished\')" style="margin: 2px; padding: 6px 12px; background: #17a2b8; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Mark as Finished</button>';
                        } else {
                            actionDiv.innerHTML = '<span style="color: #6c757d; font-size: 12px;">Waiting for payment confirmation</span>';
                        }
                    } else if (newStatus === 'accepted') {
                        // Check if payment is confirmed before showing "Mark as Finished"
                        const paymentStatusElement = document.querySelector(`#appointment-row-${appointmentId} td:nth-child(7) span`);
                        const isConfirmed = paymentStatusElement && paymentStatusElement.textContent.trim() === 'Confirmed';
                        
                        if (isConfirmed) {
                            actionDiv.innerHTML = '<button onclick="updateAppointmentStatus(' + appointmentId + ', \'finished\')" style="margin: 2px; padding: 6px 12px; background: #17a2b8; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Mark as Finished</button>';
                        } else {
                            actionDiv.innerHTML = '<span style="color: #6c757d; font-size: 12px;">Waiting for payment confirmation</span>';
                        }
                    } else if (newStatus === 'rejected' || newStatus === 'finished') {
                        actionDiv.innerHTML = '<span style="color: #6c757d; font-size: 12px;">No actions available</span>';
                    }
                } else {
                    console.error('Backend returned error:', data.message);
                    actionDiv.innerHTML = '<span style="color: #dc3545; font-size: 12px;">Error: ' + (data.message || 'Failed to update status') + '</span>';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                console.error('Error details:', error.message);
                actionDiv.innerHTML = '<span style="color: #dc3545; font-size: 12px;">Error: ' + error.message + '</span>';
            });
        }
        
        // Make function globally available
        window.updateAppointmentStatus = updateAppointmentStatus;
        
        console.log('JavaScript loaded successfully!');
        console.log('updateAppointmentStatus function available:', typeof window.updateAppointmentStatus);
    </script>
</body>
</html>
