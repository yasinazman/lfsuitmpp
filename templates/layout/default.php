<?php
/**
 * Layout Default: UiTMPP L&F
 */
$cakeDescription = 'UiTM Report Registry';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    
    <?= $this->Html->meta('icon', 'img/logo.png', ['type' => 'image/png']) ?>

    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?= $this->Html->css(['layout']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>

    <nav class="top-nav">
        <div class="nav-brand">
            <?= $this->Html->image('logo.png', ['alt' => 'Logo', 'class' => 'header-logo']) ?>
            
            <div class="brand-text">
                UiTMPP <span style="font-weight: 400; color: #cbd5e1;">- Lost & Found</span>
            </div>
        </div>

        <div class="nav-info" style="display: flex; align-items: center; gap: 15px;">
            <button id="dark-mode-toggle" class="dark-mode-btn" title="Toggle Theme" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; cursor: pointer; padding: 5px 10px; border-radius: 8px; transition: 0.3s;">
                <i class="fas fa-moon"></i>
            </button>

            <div class="date-text">
                <i class="far fa-calendar-check" style="margin-right: 5px; color: var(--accent);"></i> 
                <?= date('D, d M Y') ?>
            </div>
        </div>
    </nav>

    <?= $this->Flash->render() ?>

    <main>
        <?= $this->fetch('content') ?>
    </main>

    <footer style="text-align: center; padding: 20px; color: #64748b; font-size: 0.8rem; font-family: 'Poppins', sans-serif;">
        &copy; <?= date('Y') ?> UiTM Puncak Perdana - Lost & Found System
    </footer>

    <?= $this->Html->script(['layout']) ?>
    <?= $this->fetch('script') ?>

</body>
</html>