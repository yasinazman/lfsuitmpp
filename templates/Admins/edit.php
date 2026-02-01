<?php
/**
 * Admin Edit Page
 */
$this->disableAutoLayout();

// 1. Determine Title
$pageTitle = ($type === 'found') ? 'Edit Found Item' : 'Edit Lost Item';

// 2. Back Button Logic
$referer = $this->request->referer(); 
$backLabel = 'Back to List';
$backUrl = ($type === 'found') ? ['action' => 'foundItems'] : ['action' => 'lostItems'];

if ($report->status === 'Claimed') {
    $backLabel = 'Back to Claimed Items';
    $backUrl = ['action' => 'claimedItems'];
} elseif (strpos($referer, 'index') !== false || $referer == '/') {
    $backLabel = 'Back to Dashboard';
    $backUrl = ['action' => 'index'];
} elseif (strpos($referer, 'lost-items') !== false) {
    $backLabel = 'Back to Lost Items';
    $backUrl = ['action' => 'lostItems'];
} elseif (strpos($referer, 'found-items') !== false) {
    $backLabel = 'Back to Found Items';
    $backUrl = ['action' => 'foundItems'];
} elseif (strpos($referer, 'view') !== false) {
    $viewAction = ($type === 'found') ? 'viewFound' : 'viewLost';
    $backLabel = 'Back to Item Details';
    $backUrl = ['action' => $viewAction, $report->id];
}

// 3. Database Column Mapping
$locationField = ($type === 'found') ? 'found_location' : 'last_seen_location';
$reporterNameField = ($type === 'found') ? 'finder_name' : 'reporter_name';
$reporterContactField = ($type === 'found') ? 'finder_contact' : 'reporter_contact';
$reporterMatrixField = ($type === 'found') ? 'finder_matrix_id' : 'reporter_matrix_id';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($pageTitle) ?> - Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?= $this->Html->css(['admin_dashboard', 'layout']) ?>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <?= $this->Html->image('logo.png', ['class' => 'sidebar-logo']) ?>
            <h4 class="sidebar-title">UiTMPP <span style="color:var(--uitm-yellow)">Admin</span></h4>
        </div>
        
        <div class="sidebar-menu">
            <?= $this->Html->link('<i class="fa-solid fa-house me-3"></i> Dashboard', ['action' => 'index'], ['class' => 'sidebar-link', 'escape' => false]) ?>
            <?= $this->Html->link('<i class="fas fa-exclamation-circle me-3"></i> Lost Items', ['action' => 'lostItems'], ['class' => 'sidebar-link', 'escape' => false]) ?>
            <?= $this->Html->link('<i class="fas fa-search-location me-3"></i> Found Items', ['action' => 'foundItems'], ['class' => 'sidebar-link', 'escape' => false]) ?>
            <?= $this->Html->link('<i class="fas fa-handshake me-3"></i> Claimed Items', ['action' => 'claimedItems'], ['class' => 'sidebar-link', 'escape' => false]) ?>
            
            <div class="sidebar-label">Report Analysis</div>
            <?= $this->Html->link('<i class="fas fa-chart-bar me-3"></i> Monthly Stats', ['action' => 'monthlyStats'], ['class' => 'sidebar-link', 'escape' => false]) ?>
            <?= $this->Html->link('<i class="fas fa-calendar-check me-3"></i> Weekly Stats', ['action' => 'weeklyStats'], ['class' => 'sidebar-link', 'escape' => false]) ?>
        </div>

        <div class="sidebar-footer">
            <?= $this->Html->link('<i class="fas fa-sign-out-alt me-2"></i> Logout', ['controller' => 'Admins', 'action' => 'logout'], ['class' => 'sidebar-link logout-link', 'escape' => false]) ?>
        </div>
    </div>

    <div class="main-content">
        
        <div class="central-layout-wrapper">

            <div class="back-header-wrapper d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center" style="gap: 15px;">
                    <button class="mobile-toggle-btn" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>

                    <?= $this->Html->link(
                        '<i class="fas fa-arrow-left"></i> ' . $backLabel, 
                        $backUrl, 
                        ['class' => 'btn-back-custom', 'escape' => false]
                    ) ?>
                </div>

                <button id="dark-mode-toggle" class="dark-mode-btn" title="Toggle Theme">
                    <i class="fas fa-moon"></i>
                </button>
            </div>

            <div class="mb-4">
                <h2 style="font-weight:700; color:var(--uitm-purple); margin:0;"><?= h($pageTitle) ?></h2>
                <p class="text-muted">Editing Record ID #<?= h($report->id) ?></p>
            </div>

            <?= $this->Form->create($report, [
                'type' => 'file', 
                'url' => ['action' => 'edit', $report->id, '?' => ['type' => $type]]
            ]) ?>
            
            <div class="form-container-clean">
                
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <span class="status-badge <?= ($type == 'lost') ? 'bg-lost' : 'bg-found' ?>" style="font-size:0.9rem">
                        EDITING <?= strtoupper($type) ?> ITEM
                    </span>
                    <div class="text-muted" style="font-size:0.85rem">
                        Current Status: <strong style="color:var(--uitm-purple)"><?= strtoupper($report->status) ?></strong>
                    </div>
                </div>

                <h5 style="color:#334155; font-weight:700; margin-bottom:20px; border-bottom:1px solid #f1f5f9; padding-bottom:10px;">Item Information</h5>
                
                <div class="mb-4">
                    <label class="edit-label">Item Name</label>
                    <?= $this->Form->control('item_name', [
                        'label' => false, 
                        'class' => 'form-control-custom input-lg'
                    ]) ?>
                </div>

                <div class="info-grid mb-4">
                    <div>
                        <label class="edit-label">Category</label>
                        <?= $this->Form->select('category', 
                            [
                                'Electronics' => 'Electronics', 
                                'Wallet/Bag' => 'Wallet/Bag', 
                                'Documents' => 'Documents', 
                                'Clothing' => 'Clothing', 
                                'Others' => 'Others'
                            ], 
                            ['class' => 'form-control-custom', 'default' => $report->category]
                        ) ?>
                    </div>
                    <div>
                        <label class="edit-label"><?= ($type == 'found') ? 'Found Location' : 'Last Seen Location' ?></label>
                        <?= $this->Form->control($locationField, ['label' => false, 'class' => 'form-control-custom']) ?>
                    </div>
                </div>

                <div class="description-section">
                    <label class="edit-label">Description</label>
                    <?= $this->Form->textarea('description', ['label' => false, 'class' => 'form-control-custom', 'rows' => 4]) ?>
                </div>

                <h5 style="color:#334155; font-weight:700; margin-bottom:20px; border-bottom:1px solid #f1f5f9; padding-bottom:10px; margin-top:40px;">
                    <?= ($type == 'found') ? 'Finder Details' : 'Reporter Details' ?>
                </h5>

                <div class="info-grid">
                    <div>
                        <label class="edit-label">Name</label>
                        <?= $this->Form->control($reporterNameField, ['label' => false, 'class' => 'form-control-custom']) ?>
                    </div>
                    <div>
                        <label class="edit-label">Matrix ID</label>
                        <?= $this->Form->control($reporterMatrixField, ['label' => false, 'class' => 'form-control-custom', 'type' => 'text']) ?>
                    </div>
                    <div>
                        <label class="edit-label">Contact Number</label>
                        <?= $this->Form->control($reporterContactField, ['label' => false, 'class' => 'form-control-custom']) ?>
                    </div>
                </div>

                <?php if ($report->status === 'Claimed'): ?>
                    <div class="resolution-box-edit">
                        <h5 class="resolution-title"><i class="fas fa-handshake me-2"></i> Claimant Details</h5>
                        <p class="text-muted mb-4" style="font-size:0.8rem;">Update details of the person who claimed this item.</p>
                        
                        <div class="info-grid">
                            <div>
                                <label class="edit-label text-info">Claimant Name</label>
                                <?= $this->Form->control('claimer_name', ['label' => false, 'class' => 'form-control-custom', 'style' => 'border-color:#06b6d4']) ?>
                            </div>
                            <div>
                                <label class="edit-label text-info">Claimant Matrix ID</label>
                                <?= $this->Form->control('claimer_matrix_id', ['label' => false, 'class' => 'form-control-custom', 'style' => 'border-color:#06b6d4', 'type' => 'text']) ?>
                            </div>
                            <div>
                                <label class="edit-label text-info">Claimant Contact</label>
                                <?= $this->Form->control('claimer_contact', ['label' => false, 'class' => 'form-control-custom', 'style' => 'border-color:#06b6d4']) ?>
                            </div>
                            <div>
                                <label class="edit-label text-info">Date Claimed</label>
                                <input type="text" class="form-control-custom readonly-field" value="<?= h($report->claimed_date) ?>" readonly>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn-save-block">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
            <?= $this->Form->end() ?>

        </div> 
    </div>
    
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }
    </script>

    <?= $this->Html->script('layout') ?>

</body>
</html>