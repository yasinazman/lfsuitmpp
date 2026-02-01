<?php
/**
 * Admin: Claimed Items History
 */
$this->disableAutoLayout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claimed Items History - UiTM L&F</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            
            <a href="#" class="sidebar-link active" style="background: rgba(255,255,255,0.1); border-left: 4px solid var(--uitm-yellow);">
                <i class="fas fa-handshake me-3"></i> Claimed Items
            </a>

            <div class="sidebar-label">Report Analysis</div>
            
            <?= $this->Html->link(
                '<i class="fas fa-chart-bar me-3"></i> Monthly Stats', 
                ['action' => 'monthlyStats'], 
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>
            
            <?= $this->Html->link(
                '<i class="fas fa-calendar-check me-3"></i> Weekly Stats', 
                ['action' => 'weeklyStats'], 
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>
        </div>
        
        <div class="sidebar-footer">
            <?= $this->Html->link(
                '<i class="fas fa-sign-out-alt me-2"></i> Logout', 
                ['action' => 'logout'], 
                ['class' => 'sidebar-link logout-link', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="main-content">
        
        <div class="d-flex align-items-center mb-5">
            <button class="mobile-toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div id="top-header-row" class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <h2 style="margin:0; font-weight:700; color:var(--uitm-purple)">Claimed Items History</h2>
                    <p style="margin:0; color:var(--text-gray)">Separated list of claimed Lost reports and claimed Found items.</p>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button id="dark-mode-toggle" class="dark-mode-btn" title="Toggle Theme">
                        <i class="fas fa-moon"></i>
                    </button>
                    <div class="date-badge d-none d-md-flex">
                        <i class="fas fa-check-double me-2"></i> All Completed
                    </div>
                </div>                
            </div>
        </div>

        <div class="search-container-full">
            <div class="search-box-wrapper">
                <i class="fas fa-search search-icon-inside"></i>
                <input type="text" id="searchInput" class="search-input-custom" 
                       placeholder="Type to search history instantly..." autocomplete="off">
            </div>
        </div>

        <div class="section-title text-warning">
            <i class="fas fa-exclamation-circle me-2"></i> Claimed Lost Reports
        </div>
        
        <div class="table-container mb-5">
            <table class="custom-table border-top-orange">
                <thead>
                    <tr>
                        <th width="80">Image</th>
                        <th>Item Details</th>
                        <th>Claimed By</th>
                        <th>Date Claimed</th>
                        <th>Status</th>
                        <th width="160" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($lostClaims->isEmpty()): ?>
                        <tr><td colspan="6" class="text-center text-muted" style="padding:30px;">No claimed lost items yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($lostClaims as $item): ?>
                        <tr>
                            <td>
                                <?php if ($item->image_file): ?>
                                    <?= $this->Html->image('uploads/' . $item->image_file, ['class' => 'table-img']) ?>
                                <?php else: ?>
                                    <div class="no-img-placeholder"><i class="fas fa-box text-muted"></i></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= h($item->item_name) ?></div>
                                <div class="text-small text-muted mt-1">Reporter: <?= h($item->reporter_name) ?></div>
                            </td>
                            <td>
                                <div class="fw-bold" style="color:#0f172a"><?= h($item->claimer_name) ?></div>
                                <div class="text-small" style="color:#ea580c; font-weight:700"><?= h($item->claimer_matrix_id) ?></div>
                                <div class="text-small text-muted"><i class="fas fa-phone-alt me-1"></i> <?= h($item->claimer_contact) ?></div>
                            </td>
                            <td>
                                <div class="fw-bold"><?= $item->claimed_date ? $item->claimed_date->format('d M Y') : '-' ?></div>
                            </td>
                            <td>
                                <span class="status-badge bg-pending">CLAIMED</span>
                            </td>
                            <td class="text-center">
                                <div class="action-flex justify-center">
                                    <a href="<?= $this->Url->build(['action' => 'viewLost', $item->id]) ?>" 
                                       class="btn-action btn-view-blue" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="<?= $this->Url->build(['action' => 'edit', $item->id, '?' => ['type' => 'lost']]) ?>" 
                                       class="btn-action btn-edit-orange" 
                                       title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    
                                    <a href="<?= $this->Url->build(['action' => 'exportPdf', $item->id]) ?>" 
                                       target="_blank" 
                                       class="btn-action btn-pdf-red" 
                                       title="Download PDF">
                                       <i class="fas fa-file-pdf"></i>
                                    </a>
                                    
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash-alt"></i>',
                                        ['action' => 'deleteLost', $item->id],
                                        [
                                            'confirm' => __('Are you sure you want to delete this record?'), 
                                            'class' => 'btn-action btn-delete-grey', 
                                            'escape' => false, 
                                            'title' => 'Delete'
                                        ]
                                    ) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="section-title text-info" style="margin-top: 40px;">
            <i class="fas fa-search-location me-2"></i> Claimed Found Items
        </div>

        <div class="table-container mb-5">
            <table class="custom-table border-top-teal">
                <thead>
                    <tr>
                        <th width="80">Image</th>
                        <th>Item Details</th>
                        <th>Claimed By</th>
                        <th>Date Claimed</th>
                        <th>Status</th>
                        <th width="160" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($foundClaims->isEmpty()): ?>
                        <tr><td colspan="6" class="text-center text-muted" style="padding:30px;">No found items claimed yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($foundClaims as $item): ?>
                        <tr>
                            <td>
                                <?php if ($item->image_file): ?>
                                    <?= $this->Html->image('uploads/' . $item->image_file, ['class' => 'table-img']) ?>
                                <?php else: ?>
                                    <div class="no-img-placeholder"><i class="fas fa-box text-muted"></i></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= h($item->item_name) ?></div>
                                <div class="text-small text-muted mt-1">Finder: <?= h($item->finder_name) ?></div>
                            </td>
                            <td>
                                <div class="fw-bold" style="color:#0f172a"><?= h($item->claimer_name) ?></div>
                                <div class="text-small" style="color:#16a34a; font-weight:700"><?= h($item->claimer_matrix_id) ?></div>
                                <div class="text-small text-muted"><i class="fas fa-phone-alt me-1"></i> <?= h($item->claimer_contact) ?></div>
                            </td>
                            <td>
                                <div class="fw-bold"><?= $item->claimed_date ? $item->claimed_date->format('d M Y') : '-' ?></div>
                            </td>
                            <td>
                                <span class="status-badge bg-found">CLAIMED</span>
                            </td>
                            <td class="text-center">
                                <div class="action-flex justify-center">
                                    <a href="<?= $this->Url->build(['action' => 'viewFound', $item->id]) ?>" 
                                       class="btn-action btn-view-blue" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="<?= $this->Url->build(['action' => 'edit', $item->id, '?' => ['type' => 'found']]) ?>" 
                                       class="btn-action btn-edit-orange" 
                                       title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    
                                    <a href="<?= $this->Url->build(['action' => 'exportPdf', $item->id, '?' => ['type' => 'found']]) ?>" 
                                       target="_blank" 
                                       class="btn-action btn-pdf-red" 
                                       title="Download PDF">
                                       <i class="fas fa-file-pdf"></i>
                                    </a>
                                    
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash-alt"></i>',
                                        ['action' => 'deleteFound', $item->id],
                                        [
                                            'confirm' => __('Are you sure you want to delete this record?'), 
                                            'class' => 'btn-action btn-delete-grey', 
                                            'escape' => false, 
                                            'title' => 'Delete'
                                        ]
                                    ) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
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