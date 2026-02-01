<?php
/**
 * Admin: Manage Lost Items
 */
$this->disableAutoLayout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Lost Items - UiTM L&F</title>
    
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
            
            <a href="#" class="sidebar-link active" style="background: rgba(255,255,255,0.1); border-left: 4px solid var(--uitm-yellow);">
                <i class="fas fa-exclamation-circle me-3"></i> Lost Items
            </a>

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
        
        <div class="d-flex align-items-center mb-5">
            <button class="mobile-toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div id="top-header-row" class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <h2 style="margin:0; font-weight:700; color:var(--uitm-purple)">Lost Items Management</h2>
                    <p style="margin:0; color:var(--text-gray)">Search and manage reports for items lost on campus.</p>
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
                <input type="text" id="smartSearch" class="search-input-custom" placeholder="Type to search Lost Items instantly...">
            </div>
        </div>

        <?php if (!$pendingItems->isEmpty()): ?>
            <div class="section-header">
                <div class="section-title"><i class="fas fa-bell me-2" style="color:#ea580c"></i> Pending Approvals</div>
            </div>

            <div class="table-container" style="margin-bottom: 50px;">
                <table class="custom-table border-top-orange">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Item Details</th>
                            <th>Reporter Info</th>
                            <th>Date & Location</th>
                            <th>Status</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingItems as $item): ?>
                        <tr class="row-highlight">
                            <td width="80">
                                <?= $item->image_file 
                                    ? $this->Html->image('uploads/' . $item->image_file, ['class' => 'table-img']) 
                                    : '<div class="no-img-placeholder"><i class="fas fa-image text-muted"></i></div>' 
                                ?>
                            </td>
                            <td>
                                <div class="fw-bold text-orange" style="font-size:1rem;"><?= h($item->item_name) ?></div>
                                <small class="text-muted"><?= h($item->category) ?></small>
                            </td>
                            <td>
                                <div class="fw-bold" style="color:#1e293b;"><?= h($item->reporter_name) ?></div>
                                <div class="text-small" style="font-weight:700; font-size:0.75rem; color: #ea580c;">
                                    <?= h($item->reporter_matrix_id) ?>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">
                                    <i class="fas fa-map-marker-alt me-1 text-orange"></i> <?= h($item->last_seen_location) ?>
                                </div>
                                <small class="text-muted"><?= $item->created->format('d M Y') ?></small>
                            </td>
                            <td><span class="status-badge bg-pending">PENDING</span></td>
                            <td>
                                <div class="action-flex">
                                    <a href="<?= $this->Url->build(['action' => 'viewLost', $item->id]) ?>" 
                                       class="btn-view-blue" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-check"></i>', 
                                        ['action' => 'approve', $item->id], 
                                        [
                                            'escape' => false, 
                                            'confirm' => 'Approve this item for Public Gallery?', 
                                            'class' => 'btn-approve-found', 
                                            'title' => 'Approve'
                                        ]
                                    ) ?>
                                    
                                    <?= $this->Html->link(
                                        '<i class="fas fa-pen"></i>', 
                                        ['controller' => 'Admins', 'action' => 'edit', $item->id], 
                                        [
                                            'class' => 'btn-edit-orange', 
                                            'escape' => false, 
                                            'title' => 'Edit'
                                        ]
                                    ) ?>

                                    <a href="<?= $this->Url->build(['action' => 'exportPdf', $item->id]) ?>" 
                                       class="btn-pdf-red" 
                                       title="Download PDF" 
                                       target="_blank"> 
                                       <i class="fas fa-file-pdf"></i>
                                    </a>

                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>', 
                                        ['action' => 'deleteReport', $item->id], 
                                        [
                                            'escape' => false, 
                                            'confirm' => 'Reject/Delete this report?', 
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
            <div class="section-title"><i class="fas fa-check-circle me-2" style="color:var(--uitm-blue)"></i> Published Lost Items</div>
        </div>

        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Item Details</th>
                        <th>Reporter Info</th>
                        <th>Date & Location</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($approvedItems->isEmpty()): ?>
                        <tr><td colspan="6" class="text-center text-muted" style="padding:40px;">No published lost items found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($approvedItems as $item): ?>
                        <tr>
                            <td width="80">
                                <?= $item->image_file 
                                    ? $this->Html->image('uploads/' . $item->image_file, ['class' => 'table-img']) 
                                    : '<div class="no-img-placeholder"><i class="fas fa-image text-muted"></i></div>' 
                                ?>
                            </td>
                            <td>
                                <div class="fw-bold" style="color:var(--uitm-blue); font-size:1rem;"><?= h($item->item_name) ?></div>
                                <small class="text-muted"><?= h($item->category) ?></small>
                            </td>
                            <td>
                                <div class="fw-bold"><?= h($item->reporter_name) ?></div>
                                <div class="text-small" style="font-weight:700; color: #ea580c;"><?= h($item->reporter_matrix_id) ?></div>
                            </td>
                            <td>
                                <div class="fw-bold"><i class="fas fa-map-marker-alt me-1 text-orange"></i> <?= h($item->last_seen_location) ?></div>
                                <small class="text-muted"><?= $item->created->format('d M Y') ?></small>
                            </td>
                            <td><span class="status-badge bg-lost">LOST</span></td>
                            <td>
                                <div class="action-flex">
                                    <a href="<?= $this->Url->build(['action' => 'viewLost', $item->id]) ?>" 
                                       class="btn-view-blue" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <button type="button" 
                                            class="btn-action btn-claim-teal" 
                                            title="Mark as Claimed"
                                            onclick="openClaimModal('<?= $this->Url->build(['controller' => 'Admins', 'action' => 'markLostAsClaimed', $item->id]) ?>', '<?= h($item->item_name) ?>')">
                                        <i class="fas fa-handshake"></i>
                                    </button>

                                    <?= $this->Html->link(
                                        '<i class="fas fa-pen"></i>', 
                                        ['controller' => 'Admins', 'action' => 'edit', $item->id], 
                                        [
                                            'class' => 'btn-edit-orange', 
                                            'escape' => false, 
                                            'title' => 'Edit'
                                        ]
                                    ) ?>

                                    <a href="<?= $this->Url->build(['action' => 'exportPdf', $item->id]) ?>" 
                                       class="btn-pdf-red" 
                                       title="Download PDF" 
                                       target="_blank"> 
                                       <i class="fas fa-file-pdf"></i>
                                    </a>

                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>', 
                                        ['action' => 'deleteReport', $item->id], 
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
                <h3 class="modal-title"><i class="fas fa-handshake me-2"></i> Resolve / Claim Item</h3>
                <button type="button" class="close-btn" onclick="closeClaimModal()">&times;</button>
            </div>
            
            <?= $this->Form->create(null, ['id' => 'claimForm']) ?>
            <div class="modal-body">
                <p class="mb-4" style="color:#64748b;">Enter details of the person receiving this item: <strong id="modalItemName" style="color:#0f172a;"></strong></p>
                
                <div class="mb-3">
                    <label class="form-label">Receiver Name</label>
                    <input type="text" name="claimer_name" class="form-control" required placeholder="e.g. Owner Name">
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
                <button type="submit" class="btn-confirm">Confirm</button>
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