<?php
/**
 * User Selection Dashboard
 */
$this->disableAutoLayout();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Select Action - UiTM Lost & Found</title>
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <?= $this->Html->css(['user_dashboard']) ?>
</head>
<body>

    <div class="dashboard-container">
        
        <div class="header-section text-center fade-in-up">
            <h5 class="sub-title">UiTM Puncak Perdana</h5>
            <h1 class="main-title">What would you like to do?</h1>
            <p class="desc-text">Select an option below to proceed.</p>
        </div>
        
        <div class="options-wrapper">
            
            <a href="<?= $this->Url->build(['controller' => 'LostReports', 'action' => 'index']) ?>" class="option-card card-lost fade-in-up delay-1">
                <div class="icon-circle icon-lost">
                    <i class="fa-solid fa-magnifying-glass-minus"></i>
                </div>
                <div class="content">
                    <h3 class="card-title">I Lost Something</h3>
                    <p class="card-desc">Report a missing item and browse the found items.</p>
                    <span class="btn-fake btn-fake-lost">Report Lost Item &rarr;</span>
                </div>
            </a>

            <a href="<?= $this->Url->build(['controller' => 'FoundReports', 'action' => 'index']) ?>" class="option-card card-found fade-in-up delay-2">
                <div class="icon-circle icon-found">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <div class="content">
                    <h3 class="card-title">I Found Something</h3>
                    <p class="card-desc">Help return an item to its owner by listing it here.</p>
                    <span class="btn-fake btn-fake-found">Report Found Item &rarr;</span>
                </div>
            </a>

        </div>

        <div class="footer-action fade-in-up delay-3">
            <a href="<?= $this->Url->build('/') ?>" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Back to Home
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>