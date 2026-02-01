<?php
/**
 * Admin: Manage Found Items
 */
$this->disableAutoLayout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Found Items - UiTM L&F</title>
    
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
                ['controller' => 'Admins', 'action' => 'index'], 
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>
            
            <?= $this->Html->link(
                '<i class="fas fa-exclamation-circle me-3"></i> Lost Items', 
                ['controller' => 'Admins', 'action' => 'lostItems'], 
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>

            <a href="#" class="sidebar-link active" style="background: rgba(255,255,255,0.1); border-left: 4px solid var(--uitm-yellow);">
                <i class="fas fa-search-location me-3"></i> Found Items
            </a>
            
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
        
        <div class="d-flex align-items-center mb-5">
            <button class="mobile-toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div id="top-header-row" class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <h2 style="margin:0; font-weight:700; color:var(--uitm-purple)">Found Items Management</h2>
                    <p style="margin:0; color:var(--text-gray)">Search and manage reports for items found on campus.</p>
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

        <div class="search-container-full">
            <div class="search-box-wrapper">
                <i class="fas fa-search search-icon-inside"></i>
                <input type="text" id="smartSearch" class="search-input-custom" placeholder="Type to search Found Items instantly...">
            </div>
        </div>

        <?php if (!$pendingFound->isEmpty()): ?>
            <div class="section-header">
                <div class="section-title"><i class="fas fa-clipboard-check me-2" style="color:#166534"></i> Pending Approvals</div>
            </div>
            <div class="table-container mb-5">
                <table class="custom-table border-top-green">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Item Details</th>
                            <th>Finder Info</th>
                            <th>Date & Location</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingFound as $found): ?>
                        <tr class="row-highlight">
                            <td width="80">
                                <?= $found->image_file 
                                    ? $this->Html->image('uploads/' . $found->image_file, ['class' => 'table-img']) 
                                    : '<div class="no-img-placeholder"><i class="fas fa-box text-muted"></i></div>' 
                                ?>
                            </td>
                            <td>
                                <div class="fw-bold" style="color:#166534; font-size:1rem;"><?= h($found->item_name) ?></div>
                                <small class="text-muted"><?= h($found->category) ?></small>
                            </td>
                            <td>
                                <div class="fw-bold"><?= h($found->finder_name) ?></div>
                                <div class="text-small" style="font-weight:700; color: #16a34a;"><?= h($found->finder_matrix_id) ?></div>
                            </td>
                            <td>
                                <div class="fw-bold"><i class="fas fa-map-marker-alt me-1 text-orange"></i> <?= h($found->found_location) ?></div>
                                <small class="text-muted"><?= $found->created->format('d M Y') ?></small>
                            </td>
                            <td><span class="status-badge bg-pending">PENDING</span></td>
                            <td>
                                <div class="action-flex">
                                    <a href="<?= $this->Url->build(['action' => 'viewFound', $found->id]) ?>" 
                                       class="btn-view-blue" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-check"></i>', 
                                        ['action' => 'approveFound', $found->id], 
                                        [
                                            'escape' => false, 
                                            'confirm' => 'Approve this item report?', 
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
                                            'confirm' => 'Reject and delete this report?', 
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

        <div class="section-header">
            <div class="section-title"><i class="fas fa-box-open me-2" style="color:var(--uitm-blue)"></i> Published Found Items</div>
        </div>
        
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Item Details</th>
                        <th>Finder Info</th>
                        <th>Date & Location</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($approvedFound->isEmpty()): ?>
                        <tr><td colspan="6" class="text-center text-muted" style="padding:40px;">No published items found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($approvedFound as $found): ?>
                        <tr>
                            <td width="80">
                                <?= $found->image_file 
                                    ? $this->Html->image('uploads/' . $found->image_file, ['class' => 'table-img']) 
                                    : '<div class="no-img-placeholder"><i class="fas fa-box text-muted"></i></div>' 
                                ?>
                            </td>
                            <td>
                                <div class="fw-bold" style="color:#166534; font-size:1rem;"><?= h($found->item_name) ?></div>
                                <small class="text-muted"><?= h($found->category) ?></small>
                            </td>
                            <td>
                                <div class="fw-bold"><?= h($found->finder_name) ?></div>
                                <div class="text-small" style="font-weight:700; color: #16a34a;"><?= h($found->finder_matrix_id) ?></div>
                            </td>
                            <td>
                                <div class="fw-bold"><i class="fas fa-map-marker-alt me-1 text-orange"></i> <?= h($found->found_location) ?></div>
                                <small class="text-muted"><?= $found->created->format('d M Y') ?></small>
                            </td>
                            <td><span class="status-badge bg-found">FOUND</span></td>
                            <td>
                                <div class="action-flex">
                                    <a href="<?= $this->Url->build(['action' => 'viewFound', $found->id]) ?>" 
                                       class="btn-view-blue" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <button type="button" 
                                            class="btn-action btn-claim-teal" 
                                            title="Mark as Claimed"
                                            onclick="openClaimModal('<?= $this->Url->build(['controller' => 'Admins', 'action' => 'markAsClaimed', $found->id]) ?>', '<?= h($found->item_name) ?>')">
                                        <i class="fas fa-handshake"></i>
                                    </button>

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
                                        ['action' => 'deleteFound', $found->id], 
                                        [
                                            'escape' => false, 
                                            'confirm' => 'Permanently delete this record?', 
                                            'class' => 'btn-delete-grey', 
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

        <div class="paginator-wrapper">
            <ul class="pagination">
                <?= $this->Paginator->prev('«') ?>
                <?= $this->Paginator->numbers(['modulus' => 4]) ?>
                <?= $this->Paginator->next('»') ?>
            </ul>
        </div>
    </div>

    <div id="claimModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-handshake me-2"></i> Process Item Claim</h3>
                <button type="button" class="close-btn" onclick="closeClaimModal()">&times;</button>
            </div>
            
            <?= $this->Form->create(null, ['id' => 'claimForm']) ?>
            <div class="modal-body">
                <p class="mb-4" style="color:#64748b;">Please enter the details of the person claiming: <strong id="modalItemName" style="color:#0f172a;"></strong></p>
                
                <div class="mb-3">
                    <label class="form-label">Claimer Full Name</label>
                    <input type="text" name="claimer_name" class="form-control" required placeholder="e.g. John Doe">
                </div>

                <div style="display:flex; gap:15px;">
                    <div class="mb-3" style="flex:1;">
                        <label class="form-label">Matrix / Staff ID</label>
                        <input type="text" name="claimer_matrix_id" class="form-control" required placeholder="e.g. 2024123456">
                    </div>
                    <div class="mb-3" style="flex:1;">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="claimer_contact" class="form-control" required placeholder="e.g. 012-3456789">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeClaimModal()">Cancel</button>
                <button type="submit" class="btn-confirm">Confirm Claim</button>
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

    <?= $this->Html->script(['admin', 'layout']) ?>

</body>
</html>