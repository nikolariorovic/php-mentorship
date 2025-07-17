<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../public/css/login.css">
    <link rel="stylesheet" href="../../public/css/admin.css">
    <link rel="stylesheet" href="../../public/css/admin-navigation.css">
</head>
<body>
    <?php include __DIR__ . '/navigation.php'; ?>
    
    <div class="dashboard-content">
        <div class="dashboard-card">
            <div class="card-header">
                <h2>Users Management</h2>
                <button class="btn btn-primary" onclick="openCreateUserModal()">
                    <i class="btn-icon">➕</i>
                    Add New User
                </button>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user->getId()); ?></td>
                            <td><?php echo htmlspecialchars($user->getFullName()); ?></td>
                            <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
                            <td>
                                <span class="role-badge role-<?php echo strtolower($user->getRole()); ?>">
                                    <?php echo htmlspecialchars($user->getRole()); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($user->getCreatedAt()->format('Y-m-d H:i')); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/users/<?php echo $user->getId(); ?>" class="action-button edit-button">
                                        Edit
                                    </a>
                                    <form action="/admin/users/<?php echo $user->getId(); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="action-button delete-button">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #666;">
                                Nema korisnika za prikaz
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create User Modal -->
    <div id="createUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create New User</h3>
                <span class="modal-close" onclick="closeCreateUserModal()">&times;</span>
            </div>
            <form id="createUserForm" action="/admin/users" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="mentor">Mentor</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group" id="pricePerSessionGroup" style="display: none;">
                        <label for="price_per_session">Price per Session *</label>
                        <input type="number" id="price_per_session" name="price" min="0" step="0.01" placeholder="Enter price per session">
                    </div>
                    <div class="form-group" id="specializationsGroup" style="display: none;">
                        <label for="specializations">Specializations</label>
                        <select id="specializations" name="specializations[]" multiple>
                            <?php if (!empty($specializations)): ?>
                                <?php foreach ($specializations as $specialization): ?>
                                    <option value="<?php echo htmlspecialchars($specialization->getId()); ?>">
                                        <?php echo htmlspecialchars($specialization->getName()); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="form-help">Hold Ctrl (or Cmd on Mac) to select multiple specializations</small>
                    </div>
                    <div class="form-group" id="biographyGroup" style="display: none;">
                        <label for="biography">Biography</label>
                        <textarea id="biography" name="biography" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeCreateUserModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../public/js/admin.js"></script>
    <script src="../../public/js/admin-navigation.js"></script>
</body>
</html>
