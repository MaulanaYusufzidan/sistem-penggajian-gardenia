        </ul>
    </aside>

    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="header-title" id="pageTitle"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
        </div>

        <div class="header-right">
            <!-- User Dropdown -->
            <div class="user-info dropdown">
                <button class="btn btn-link text-decoration-none" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <span class="dropdown-item">
                            <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                        </span>
                    </li>
                    <li>
                        <small class="dropdown-item"><?php echo htmlspecialchars($_SESSION['email']); ?></small>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/controllers/AuthController.php?action=logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">
