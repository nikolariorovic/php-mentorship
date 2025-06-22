<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - Admin Dashboard</title>
    <link rel="stylesheet" href="../../public/css/login.css">
    <link rel="stylesheet" href="../../public/css/admin.css">
    <link rel="stylesheet" href="../../public/css/admin-navigation.css">
</head>
<body>
    <?php include __DIR__ . '/navigation.php'; ?>
    
    <div class="dashboard-content">
        <div class="dashboard-card">
            <div class="card-header">
                <h2>User Details</h2>
                <div class="header-actions">
                    <a href="/admin/users" class="btn btn-secondary">
                        <i class="btn-icon">←</i>
                        Back to Users
                    </a>
                    <button class="btn btn-primary" onclick="editUser(<?php echo $user->getId(); ?>, '<?php echo htmlspecialchars($user->getFirstName()); ?>', '<?php echo htmlspecialchars($user->getLastName()); ?>', '<?php echo htmlspecialchars($user->getEmail()); ?>', '<?php echo htmlspecialchars($user->getRole()); ?>', '<?php echo htmlspecialchars($user->getBiography()); ?>', '<?php echo htmlspecialchars($user->getPrice() ?? ''); ?>')">
                        <i class="btn-icon">✏️</i>
                        Edit User
                    </button>
                </div>
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

            <div class="user-details-container">
                <div class="user-profile-section">
                    <div class="user-avatar">
                        <div class="avatar-placeholder">
                            <?php echo strtoupper(substr($user->getFirstName(), 0, 1) . substr($user->getLastName(), 0, 1)); ?>
                        </div>
                    </div>
                    <div class="user-basic-info">
                        <h3><?php echo htmlspecialchars($user->getFullName()); ?></h3>
                        <span class="role-badge role-<?php echo strtolower($user->getRole()); ?>">
                            <?php echo htmlspecialchars(ucfirst($user->getRole())); ?>
                        </span>
                    </div>
                </div>

                <div class="user-details-grid">
                    <div class="detail-section">
                        <h4>Personal Information</h4>
                        <div class="detail-item">
                            <label>First Name:</label>
                            <span><?php echo htmlspecialchars($user->getFirstName()); ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Last Name:</label>
                            <span><?php echo htmlspecialchars($user->getLastName()); ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Email:</label>
                            <a href="mailto:<?php echo htmlspecialchars($user->getEmail()); ?>" class="email-link">
                                <span><?php echo htmlspecialchars($user->getEmail()); ?></span>
                            </a>
                        </div>
                        <div class="detail-item">
                            <label>Role:</label>
                            <span class="role-badge role-<?php echo strtolower($user->getRole()); ?>">
                                <?php echo htmlspecialchars(ucfirst($user->getRole())); ?>
                            </span>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4>Account Information</h4>
                        <div class="detail-item">
                            <label>User ID:</label>
                            <span>#<?php echo htmlspecialchars($user->getId()); ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Created At:</label>
                            <span class="date-value">
                                <?php echo htmlspecialchars($user->getCreatedAt()->format('F j, Y')); ?>
                                <br>
                                <small><?php echo htmlspecialchars($user->getCreatedAt()->format('g:i A')); ?></small>
                            </span>
                        </div>
                        <div class="detail-item">
                            <label>Last Updated:</label>
                            <span class="date-value">
                                <?php echo htmlspecialchars($user->getUpdatedAt()->format('F j, Y')); ?>
                                <br>
                                <small><?php echo htmlspecialchars($user->getUpdatedAt()->format('g:i A')); ?></small>
                            </span>
                        </div>
                        <?php if ($user->getRole() === 'mentor' && $user->getPrice() !== null): ?>
                        <div class="detail-item">
                            <label>Price per Session:</label>
                            <span class="price">$<?php echo htmlspecialchars(number_format($user->getPrice(), 2)); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($user->getBiography())): ?>
                    <div class="detail-section full-width">
                        <h4>Biography</h4>
                        <div class="biography-content">
                            <?php echo nl2br(htmlspecialchars($user->getBiography())); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="user-actions">
                <form action="/admin/users/<?php echo $user->getId(); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="action-button delete-button">
                        <i class="btn-icon">🗑️</i>
                        Delete User
                    </button>
                </form>
                    <!-- <button class="btn btn-warning" onclick="deleteUser(<?php echo $user->getId(); ?>)">
                        <i class="btn-icon">🗑️</i>
                        Delete User
                    </button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit User</h3>
                <span class="modal-close" onclick="closeEditUserModal()">&times;</span>
            </div>
            <form id="editUserForm" action="/admin/users/<?php echo $user->getId(); ?>"  method="POST">
                <input type="hidden" name="_method" value="PATCH">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_first_name">First Name *</label>
                        <input type="text" id="edit_first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_last_name">Last Name *</label>
                        <input type="text" id="edit_last_name" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email *</label>
                        <input type="email" id="edit_email" name="email" disabled required>
                    </div>
                    <div class="form-group">
                        <label for="edit_role">Role *</label>
                        <select id="edit_role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="mentor">Mentor</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group" id="editPricePerSessionGroup" style="display: none;">
                        <label for="edit_price_per_session">Price per Session *</label>
                        <input type="number" id="edit_price_per_session" name="price" min="0" step="0.01" placeholder="Enter price per session">
                    </div>
                    <div class="form-group">
                        <label for="edit_biography">Biography</label>
                        <textarea id="edit_biography" name="biography" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditUserModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../public/js/admin.js"></script>
    <script src="../../public/js/admin-navigation.js"></script>
</body>
</html> 