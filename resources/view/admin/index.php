<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../public/css/login.css">
    <link rel="stylesheet" href="../../public/css/admin.css">
</head>
<body>
    <div class="header">
        <div class="header-title">Admin Dashboard</div>
        <a href="/logout" class="logout-button">Logout</a>
    </div>
    <div class="dashboard-content">
        <div class="dashboard-card">
            <h2>Users Management</h2>
            
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
                                    <button class="action-button edit-button" onclick="editUser(<?php echo $user->getId(); ?>)">
                                        Edit
                                    </button>
                                    <button class="action-button delete-button" onclick="deleteUser(<?php echo $user->getId(); ?>)">
                                        Delete
                                    </button>
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

    <script src="../../public/js/admin.js"></script>
</body>
</html>
