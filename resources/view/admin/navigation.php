<nav class="admin-nav">
    <div class="nav-container">
        <div class="nav-brand">
            <h2>Admin Panel</h2>
        </div>
        
        <div class="nav-toggle" id="navToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        
        <ul class="nav-menu" id="navMenu">
            <li class="nav-item">
                <a href="/admin/users" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active' : '' ?>">
                    <i class="nav-icon">👥</i>
                    <span>Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/payments" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/payments') !== false ? 'active' : '' ?>">
                    <i class="nav-icon">💳</i>
                    <span>Payments</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/dashboard" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false ? 'active' : '' ?>">
                    <i class="nav-icon">📊</i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/settings" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false ? 'active' : '' ?>">
                    <i class="nav-icon">⚙️</i>
                    <span>Settings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/reports" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/reports') !== false ? 'active' : '' ?>">
                    <i class="nav-icon">📈</i>
                    <span>Reports</span>
                </a>
            </li>
            <li class="nav-item nav-item-logout">
                <a href="/logout" class="nav-link logout-link">
                    <i class="nav-icon">🚪</i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav> 