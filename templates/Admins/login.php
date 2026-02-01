<?php
/**
 * Admin Login Page
 */
$this->disableAutoLayout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Access - UiTM L&F</title>
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?= $this->Html->css(['login', 'layout']) ?>
</head>

<body>
    <?= $this->Flash->render() ?>

    <nav class="top-nav">
        <div class="nav-brand">
            <?= $this->Html->image('logo.png', ['alt' => 'Logo', 'class' => 'header-logo']) ?>
            <div class="brand-text">
                UiTMPP <span style="font-weight: 400; color: #cbd5e1;">- Lost & Found</span>
            </div>
        </div>
        <div class="nav-info">
            <i class="fas fa-lock" style="margin-right: 5px; color: var(--accent);"></i> 
            SECURE ACCESS
        </div>
    </nav>

    <div class="login-wrapper">
        <div class="login-card">
            
            <div class="login-icon">
                <i class="fa-solid fa-user-shield"></i>
            </div>

            <h3 class="login-title">Admin Login</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 30px;">
                Enter your credentials to manage reports
            </p>

            <?= $this->Flash->render() ?>

            <?= $this->Form->create(null) ?>
            
                <div class="login-form-group">
                    <label>Username</label>
                    <?= $this->Form->control('username', [
                        'label' => false,
                        'placeholder' => 'e.g. admin_puncak',
                        'required' => true,
                        'autofocus' => true
                    ]) ?>
                </div>

                <div class="login-form-group">
                    <label>Password</label>
                    <?= $this->Form->control('password', [
                        'label' => false,
                        'placeholder' => '••••••••',
                        'required' => true
                    ]) ?>
                </div>

                <?= $this->Form->button(__('LOGIN'), ['class' => 'btn-login']) ?>
            
            <?= $this->Form->end() ?>

            <div class="login-links">
                <?= $this->Html->link("← Back", ['controller' => 'pages', 'action' => 'index']) ?>
                <br><br>
                <small>No account? Please contact IT Department.</small>
            </div>

        </div>
    </div>

    <footer>
        &copy; <?= date('Y') ?> UiTM Lost & Found System
    </footer>

    <?= $this->Html->script('layout') ?>

</body>
</html>