<?php
/**
 * Admin: Multi-Purpose View
 */
$this->disableAutoLayout();

$action = $this->request->getParam('action');
$isMonthlyStats = ($action === 'monthlyStats');
$isWeeklyStats = ($action === 'weeklyStats');
$isItemView = !$isMonthlyStats && !$isWeeklyStats;
$isLostType = (isset($type) && $type === 'LOST');

// Back Button Logic
$referer = $this->request->referer(); 
$backLabel = 'Back to Dashboard';
$backUrl = ['action' => 'index'];

if ($item->status === 'Claimed') {
    $backLabel = 'Back to Claimed Items';
    $backUrl = ['action' => 'claimedItems'];
} elseif (strpos($referer, 'lost-items') !== false) {
    $backLabel = 'Back to Lost Items List';
    $backUrl = ['action' => 'lostItems'];
} elseif (strpos($referer, 'found-items') !== false) {
    $backLabel = 'Back to Found Items List';
    $backUrl = ['action' => 'foundItems'];
} elseif (strpos($referer, 'claimed-items') !== false) {
    $backLabel = 'Back to Claimed Items';
    $backUrl = ['action' => 'claimedItems'];
} elseif ($item->status === 'PENDING' && $isLostType) {
    $backLabel = 'Back to Lost Items';
    $backUrl = ['action' => 'lostItems'];
} elseif ($item->status === 'PENDING' && !$isLostType) {
    $backLabel = 'Back to Found Items';
    $backUrl = ['action' => 'foundItems'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $isItemView ? 'View Item Details' : 'Statistics Report' ?> - UiTMPP L&F</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?= $this->Html->css(['admin_dashboard', 'layout']) ?>
</head>
<body>
    <?= $this->Flash->render() ?>

    <div class="sidebar">
        <div class="sidebar-header">
            <?= $this->Html->image('logo.png', ['class' => 'sidebar-logo']) ?>
            <h4 class="sidebar-title">UiTMPP <span style="color:var(--uitm-yellow)">Admin</span></h4>
        </div>
        
        <div class="sidebar-menu">
            <?= $this->Html->link(
                '<i class="fa-solid fa-house me-3"></i> Dashboard', 
                ['action' => 'index'], 
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-exclamation-circle me-3"></i> Lost Items', 
                ['action' => 'lostItems'], 
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-search-location me-3"></i> Found Items', 
                ['action' => 'foundItems'], 
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-handshake me-3"></i> Claimed Items', 
                ['controller' => 'Admins', 'action' => 'claimedItems'], 
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>

            <div class="sidebar-label">Report Analysis</div>
            
            <?= $this->Html->link(
                '<i class="fas fa-chart-bar me-3"></i> Monthly Stats', 
                ['action' => 'monthlyStats'], 
                ['class' => 'sidebar-link ' . ($isMonthlyStats ? 'active' : ''), 'escape' => false]
            ) ?>

            <?= $this->Html->link(
                '<i class="fas fa-calendar-check me-3"></i> Weekly Stats', 
                ['action' => 'weeklyStats'], 
                ['class' => 'sidebar-link ' . ($isWeeklyStats ? 'active' : ''), 'escape' => false]
            ) ?>
        </div>
        
        <div class="sidebar-footer">
            <?= $this->Html->link(
                '<i class="fas fa-sign-out-alt me-2"></i> Logout', 
                ['controller' => 'Admins', 'action' => 'logout'], 
                ['class' => 'sidebar-link logout-link', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="main-content">
        
        <div class="d-flex align-items-center justify-content-between mb-4" style="margin-bottom: 40px !important;">
            <div class="d-flex align-items-center" style="gap: 15px;">
                <button class="mobile-toggle-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                
                <?= $this->Html->link(
                    '<i class="fas fa-arrow-left me-2"></i>' . $backLabel, 
                    $backUrl, 
                    ['class' => 'btn-back', 'escape' => false]
                ) ?>
            </div>

            <button id="dark-mode-toggle" class="dark-mode-btn" title="Toggle Theme">
                <i class="fas fa-moon"></i>
            </button>
        </div>

        <?php if ($isItemView): ?>
            <div class="mb-4">
                <h2 style="font-weight:700; color:var(--uitm-purple); margin:0;">Item Detailed Information</h2>
                <p class="text-muted">Viewing record for Case ID #<?= h($item->id) ?></p>
            </div>

            <div class="view-card">
                <div class="img-container">
                    <?php if ($item->image_file): ?>
                        <?= $this->Html->image('uploads/' . $item->image_file, ['class' => 'detail-img']) ?>
                    <?php else: ?>
                        <div class="text-center text-muted">
                            <i class="fas fa-image fa-5x mb-3" style="opacity:0.2"></i>
                            <p>No Evidence Photo</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="view-info-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="status-badge <?= $isLostType ? 'bg-lost' : 'bg-found' ?>" style="font-size:0.9rem">
                            <?= h($type) ?> ITEM
                        </span>
                        <div class="text-muted" style="font-size:0.85rem">
                            <i class="far fa-calendar-alt me-1"></i> Reported: <?= $item->created->format('d M Y') ?>
                        </div>
                    </div>

                    <h1 style="font-weight:700; color:#1e293b; margin-bottom:25px; font-size:1.8rem;">
                        <?= h($item->item_name) ?>
                    </h1>

                    <div class="info-grid">
                        <div>
                            <div class="info-label">Category</div>
                            <div class="info-value"><i class="fas fa-tag me-2 text-muted"></i><?= h($item->category) ?></div>
                        </div>
                        <div>
                            <div class="info-label"><?= $isLostType ? 'Last Seen Location' : 'Found Location' ?></div>
                            <div class="info-value">
                                <i class="fas fa-map-marker-alt me-2 text-orange"></i><?= h($isLostType ? $item->last_seen_location : $item->found_location) ?>
                            </div>
                        </div>
                        <div>
                            <div class="info-label"><?= $isLostType ? 'Reporter Name' : 'Finder Name' ?></div>
                            <div class="info-value"><?= h($isLostType ? $item->reporter_name : $item->finder_name) ?></div>
                        </div>
                        <div>
                            <div class="info-label">ID Number</div>
                            <div class="info-value" style="color:#16a34a; font-weight:700;">
                                <?= h($isLostType ? $item->reporter_matrix_id : $item->finder_matrix_id) ?>
                            </div>
                        </div>
                        <div>
                            <div class="info-label">Contact Number</div>
                            <div class="info-value">
                                <i class="fas fa-phone me-2 text-muted"></i><?= h($isLostType ? $item->reporter_contact : $item->finder_contact) ?>
                            </div>
                        </div>
                        <div>
                            <div class="info-label">Current Status</div>
                            <div class="info-value">
                                <?php 
                                    $status = strtoupper(trim($item->status)); 
                                ?>
                                <?php if ($status === 'PENDING'): ?>
                                    <span style="color:#ea580c; font-weight:700;">
                                        <i class="fas fa-hourglass-half me-2"></i> Pending Approval
                                    </span>
                                <?php elseif ($status === 'CLAIMED'): ?>
                                    <span style="color:#16a34a; font-weight:700;">
                                        <i class="fas fa-handshake me-2"></i> Claimed
                                    </span>
                                <?php elseif ($status === 'APPROVED' || $status === 'PUBLISHED' || $status === 'LOST' || $status === 'FOUND'): ?>
                                    <span style="color:#2563eb; font-weight:700;">
                                        <i class="fas fa-check-circle me-2"></i> Published (Active)
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= h($item->status) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($item->status === 'Claimed'): ?>
                        <div class="resolution-box">
                            <h5 class="resolution-title">
                                <i class="fas fa-handshake me-2"></i> 
                                <?php if ($type === 'LOST'): ?>
                                    Returned to Owner Details
                                <?php else: ?>
                                    Claimant Details (Claimed By)
                                <?php endif; ?>
                            </h5>
                            <div class="info-grid">
                                <div>
                                    <div class="info-label">Name</div>
                                    <div class="info-value"><?= h($item->claimer_name) ?></div>
                                </div>
                                <div>
                                    <div class="info-label">Matrix ID</div>
                                    <div class="info-value"><?= h($item->claimer_matrix_id) ?></div>
                                </div>
                                <div>
                                    <div class="info-label">Contact Number</div>
                                    <div class="info-value"><?= h($item->claimer_contact) ?></div>
                                </div>
                                <div>
                                    <div class="info-label">Date Claimed</div>
                                    <div class="info-value"><?= h($item->claimed_date) ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="desc-area">
                        <div class="info-label" style="color:var(--uitm-purple)">Description / Notes</div>
                        <div class="info-value" style="font-style:italic; line-height:1.6; font-size:0.95rem;">
                            "<?= $item->description ? h($item->description) : 'No specific description provided.' ?>"
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-info">
                This view handles item details only. Please use the specific Monthly/Weekly stats pages.
            </div>
        <?php endif; ?>

    </div>
    
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }
    </script>

    <?= $this->Html->script(['admin', 'layout']) ?>

</body>
</html>