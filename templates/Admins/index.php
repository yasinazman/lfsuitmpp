<?php
/**
 * Admin Dashboard
 */
$this->disableAutoLayout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - UiTMPP L&F</title>
    
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
            <a href="#" class="sidebar-link active" style="background: rgba(255,255,255,0.1); border-left: 4px solid var(--uitm-yellow);">
                <i class="fa-solid fa-house me-3"></i> Dashboard
            </a>

            <?= $this->Html->link(
                '<i class="fas fa-exclamation-circle me-3"></i> Lost Items',
                ['controller' => 'Admins', 'action' => 'lostItems'],
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>

            <?= $this->Html->link(
                '<i class="fas fa-search-location me-3"></i> Found Items',
                ['controller' => 'Admins', 'action' => 'foundItems'],
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
                ['controller' => 'Admins', 'action' => 'monthlyStats'],
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>
            
            <?= $this->Html->link(
                '<i class="fas fa-calendar-check me-3"></i> Weekly Stats',
                ['controller' => 'Admins', 'action' => 'weeklyStats'],
                ['class' => 'sidebar-link', 'escape' => false]
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
        
        <div class="d-flex align-items-center mb-5" style="gap: 15px;">
    
            <button class="mobile-toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <div id="top-header-row" class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <h2 style="margin:0; font-weight:700; color:var(--uitm-purple)">Dashboard Overview</h2>
                    <p style="margin:0; color:var(--text-gray)">Welcome back, Administrator.</p>
                </div>
                
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button id="dark-mode-toggle" class="dark-mode-btn" title="Toggle Theme">
                        <i class="fas fa-moon"></i>
                    </button>

                    <div class="date-badge">
                        <i class="far fa-calendar-alt me-2"></i> <?= date('d M Y') ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="stats-grid">
            <div class="stat-card card-lost-pending">
                <div class="stat-info">
                    <h3><?= $totalLostPending ?></h3>
                    <p>Lost Items<br>Need Approval</p>
                </div>
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
            </div>

            <div class="stat-card card-found-pending">
                <div class="stat-info">
                    <h3><?= $totalFoundPending ?></h3>
                    <p>Found Items<br>Need Approval</p>
                </div>
                <div class="stat-icon"><i class="fas fa-clipboard-check"></i></div>
            </div>

            <div class="stat-card card-total-lost">
                <div class="stat-info">
                    <h3><?= $totalLost ?></h3>
                    <p>Total Lost<br>Items</p>
                </div>
                <div class="stat-icon"><i class="fas fa-search"></i></div>
            </div>

            <div class="stat-card card-total-found">
                <div class="stat-info">
                    <h3><?= $totalFound ?></h3>
                    <p>Total Found<br>Items</p>
                </div>
                <div class="stat-icon"><i class="fas fa-box-open"></i></div>
            </div>
        </div>

        <div class="dashboard-charts">
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-chart-line text-orange"></i> Report Trend</div>
                <canvas id="dashboardBarChart" 
                        data-weekly="<?= (int)($weeklyTotal ?? 0) ?>" 
                        data-monthly="<?= (int)($monthlyTotal ?? 0) ?>">
                </canvas>
            </div>
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-chart-pie" style="color:#16a34a"></i> Item Distribution</div>
                <canvas id="dashboardPieChart" 
                        data-lost="<?= (int)$totalLost ?>" 
                        data-found="<?= (int)$totalFound ?>">
                </canvas>
            </div>
        </div>

        <?php if (!$pendingReports->isEmpty()): ?>
        <div class="section-header">
            <div class="section-title"><i class="fas fa-bell me-2" style="color:#ea580c"></i> Lost Items Needs Approval</div>
            <?= $this->Html->link('View All', ['action' => 'lostItems'], ['style' => 'text-decoration:none; font-weight:600; color:#ea580c;']) ?>
        </div>

        <div class="table-container mb-5">
            <table class="custom-table border-top-orange">
                <thead>
                    <tr>
                        <th width="80">Image</th>
                        <th>Item Name</th>
                        <th>Reporter Info</th>
                        <th>Date</th>
                        <th>Status</th> 
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingReports as $report): ?>
                    <tr class="row-highlight">
                        <td>
                            <?= $report->image_file 
                                ? $this->Html->image('uploads/' . $report->image_file, ['class' => 'table-img']) 
                                : '<div class="no-img-placeholder"><i class="fas fa-image text-muted"></i></div>' 
                            ?>
                        </td>
                        <td>
                            <div class="fw-bold text-orange"><?= h($report->item_name) ?></div>
                            <small class="text-muted"><?= h($report->category) ?></small>
                        </td>
                        <td>
                            <div style="font-weight:600; color:#1e293b;"><?= h($report->reporter_name) ?></div>
                            <div class="text-small" style="font-weight:700; color: #16a34a;"><?= h($report->reporter_matrix_id) ?></div>
                        </td>
                        <td><?= $report->created->format('d M, Y') ?></td>
                        <td><span class="status-badge bg-pending">PENDING</span></td> 
                        <td>
                            <div class="action-flex">
                                <a href="<?= $this->Url->build(['action' => 'viewLost', $report->id]) ?>" 
                                   class="btn-action btn-view-blue" 
                                   title="View">
                                   <i class="fas fa-eye"></i>
                                </a>
                                
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-check"></i>', 
                                    ['action' => 'approve', $report->id], 
                                    [
                                        'escape' => false, 
                                        'confirm' => 'Approve this report?', 
                                        'class' => 'btn-approve-found', 
                                        'title' => 'Approve'
                                    ]
                                ) ?>

                                <?= $this->Html->link(
                                    '<i class="fas fa-pen"></i>', 
                                    ['action' => 'edit', $report->id], 
                                    [
                                        'class' => 'btn-edit-orange', 
                                        'escape' => false, 
                                        'title' => 'Edit'
                                    ]
                                ) ?>

                                <a href="<?= $this->Url->build(['action' => 'exportPdf', $report->id]) ?>" 
                                   class="btn-pdf-red" 
                                   title="Download PDF" 
                                   target="_blank"> 
                                   <i class="fas fa-file-pdf"></i>
                                </a>
                                
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-trash"></i>', 
                                    ['action' => 'deleteReport', $report->id], 
                                    [
                                        'escape' => false, 
                                        'confirm' => 'Reject this report?', 
                                        'class' => 'btn-delete-grey', 
                                        'title' => 'Reject'
                                    ]
                                ) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php if (!$pendingFoundReports->isEmpty()): ?>
        <div class="section-header">
            <div class="section-title"><i class="fas fa-clipboard-check me-2" style="color: #166534;"></i>Found Items Needs Approval</div>
            <?= $this->Html->link('View All', ['action' => 'foundItems'], ['style' => 'text-decoration:none; font-weight:600; color:#166534;']) ?>
        </div>
        
        <div class="table-container">
            <table class="custom-table border-top-green">
                <thead>
                    <tr>
                        <th width="80">Image</th>
                        <th>Item Name</th>
                        <th>Finder Info</th>
                        <th>Location</th>
                        <th>Status</th> 
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingFoundReports as $found): ?>
                    <tr class="row-highlight-green">
                        <td>
                            <?= $found->image_file 
                                ? $this->Html->image('uploads/' . $found->image_file, ['class' => 'table-img']) 
                                : '<div class="no-img-placeholder"><i class="fas fa-box text-muted"></i></div>' 
                            ?>
                        </td>
                        <td>
                            <div class="fw-bold" style="color: #166534;"><?= h($found->item_name) ?></div>
                            <small class="text-muted"><?= h($found->category) ?></small>
                        </td>
                        <td>
                            <div style="font-weight:600; color:#1e293b;"><?= h($found->finder_name) ?></div>
                            <div class="text-small" style="font-weight:700; color: #16a34a;"><?= h($found->finder_matrix_id) ?></div>
                        </td>
                        <td><?= h($found->found_location) ?></td>
                        <td><span class="status-badge bg-found">PENDING</span></td> 
                        <td>
                            <div class="action-flex">
                                <a href="<?= $this->Url->build(['action' => 'viewFound', $found->id]) ?>" 
                                   class="btn-action btn-view-blue" 
                                   title="View">
                                   <i class="fas fa-eye"></i>
                                </a>
                                
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-check"></i>', 
                                    ['action' => 'approveFound', $found->id], 
                                    [
                                        'escape' => false, 
                                        'confirm' => 'Approve this Found Item?', 
                                        'class' => 'btn-approve-found', 
                                        'title' => 'Approve'
                                    ]
                                ) ?>

                                <?= $this->Html->link(
                                    '<i class="fas fa-pen"></i>', 
                                    ['action' => 'edit', $found->id, '?' => ['type' => 'found']], 
                                    [
                                        'class' => 'btn-edit-orange', 
                                        'escape' => false, 
                                        'title' => 'Edit'
                                    ]
                                ) ?>

                                <a href="<?= $this->Url->build(['action' => 'exportPdf', $found->id, '?' => ['type' => 'found']]) ?>" 
                                   class="btn-pdf-red" 
                                   title="Download PDF" 
                                   target="_blank"> 
                                   <i class="fas fa-file-pdf"></i>
                                </a>
                                
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-trash"></i>', 
                                    ['action' => 'deleteFoundPending', $found->id], 
                                    [
                                        'escape' => false, 
                                        'confirm' => 'Reject & delete this report?', 
                                        'class' => 'btn-delete-grey', 
                                        'title' => 'Reject'
                                    ]
                                ) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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