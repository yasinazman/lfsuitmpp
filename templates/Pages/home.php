<?php
/**
 * Landing Page Lost & Found
 */
$this->disableAutoLayout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome - UiTM Lost & Found</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?= $this->Html->css(['home']) ?>
</head>
<body>

    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100 position-relative">
        <div class="landing-card shadow-lg">
            
            <div class="logo-wrapper">
                <div class="icon-circle">
                    <?= $this->Html->image('logo.png', ['class' => 'custom-logo', 'alt' => 'Logo UiTM']) ?>
                </div>
            </div>

            <div class="text-center mb-5">
                <h5 class="uni-name">UiTM Puncak Perdana</h5>
                <h1 class="main-title">Lost & Found</h1>
                <p class="description">
                    The official registry for reporting lost items and claiming found properties on campus.
                </p>
            </div>

            <div class="d-grid gap-3">
                
                <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'display', 'user_dashboard']) ?>" 
                   class="btn-custom btn-user">
                    <div class="btn-content">
                        <span class="btn-icon"><i class="fa-solid fa-users"></i></span>
                        <div class="text-start">
                            <span class="d-block small opacity-75">Students & Staff</span>
                            <span class="h6 mb-0">Enter as User</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-arrow-right arrow-anim"></i>
                </a>

                <a href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'login']) ?>" 
                   class="btn-custom btn-admin">
                    <div class="btn-content">
                        <span class="btn-icon"><i class="fa-solid fa-shield-halved"></i></span>
                        <div class="text-start">
                            <span class="d-block small opacity-75">Authorized Personnel</span>
                            <span class="h6 mb-0">Admin Login</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-lock arrow-anim"></i>
                </a>

            </div>

            <div class="footer-text mt-5">
                &copy; <?= date('Y') ?> UiTM Puncak Perdana Lost & Found System
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>