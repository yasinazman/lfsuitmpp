<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\LostReport> $lostReports
 */
echo $this->Html->css('LostReport', ['block' => true]);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="dashboard-container">
    
    <div class="dashboard-header">
        <div class="header-title">
            <h3><i class="fas fa-layer-group"></i> Lost Items Gallery</h3>
            <p>Browse reported lost items on campus.</p>
        </div>
        
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> Submit Lost Report',
            ['action' => 'add'],
            ['class' => 'btn-new', 'escape' => false]
        ) ?>
    </div>

    <div class="premium-search-card">
        <?= $this->Form->create(null, ['type' => 'get', 'valueSources' => ['query']]) ?>
        
        <div class="search-top-row">
            <div class="search-input-wrapper full-bar">
                <i class="fas fa-search search-icon"></i>
                <?= $this->Form->control('key', [
                    'label' => false,
                    'placeholder' => 'Search by item name...',
                    'value' => $this->request->getQuery('key'),
                    'class' => 'search-input-field' 
                ]) ?>
            </div>
        </div>

        <div class="search-bottom-row">
            
            <div class="filter-group">
                <label class="filter-label"><i class="bi bi-tag-fill"></i> CATEGORY</label>
                <?= $this->Form->select('cat',
                    [
                        'Electronics' => 'Electronics', 
                        'Wallet/Bag' => 'Wallet/Bag', 
                        'Documents' => 'Documents', 
                        'Personal' => 'Personal', 
                        'Others' => 'Others'
                    ],
                    [
                        'empty' => 'All Categories',
                        'class' => 'select-premium',
                        'value' => $this->request->getQuery('cat')
                    ]
                ) ?>
            </div>

            <div class="filter-group">
                <label class="filter-label"><i class="bi bi-geo-alt-fill"></i> LOCATION</label>
                <?= $this->Form->select('loc',
                    [
                        'Library' => 'Library (PTAR)',
                        'Dewan Kuliah' => 'Dewan Kuliah',
                        'Makmal Komputer' => 'Makmal Komputer',
                        'Bilik Kuliah' => 'Bilik Kuliah',
                        'Lab info tool' => 'Lab info tool',
                        'Cafeteria' => 'Cafeteria',
                        'Sports Complex' => 'Sports Complex',
                        'Parking Area' => 'Parking Area',
                        'Other' => 'Other'
                    ],
                    [
                        'empty' => 'All Locations',
                        'class' => 'select-premium',
                        'value' => $this->request->getQuery('loc')
                    ]
                ) ?>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-find-premium">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>

                <?= $this->Html->link(
                    '<i class="fas fa-sync-alt"></i>', 
                    ['action' => 'index'], 
                    [
                        'escape' => false,
                        'class' => 'btn-reset-circle',
                        'title' => 'Reset'
                    ]
                ) ?>
            </div>
        </div>

        <?= $this->Form->end() ?>
    </div>

    <div class="reports-grid">
        <?php foreach ($lostReports as $lostReport): ?>
        <div class="report-card">
            <div class="card-img-top">
                <?php if (!empty($lostReport->image_file)): ?>
                    <?= $this->Html->image('uploads/' . $lostReport->image_file, ['alt' => $lostReport->item_name]) ?>
                <?php else: ?>
                    <div style="width:100%; height:100%; background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#94a3b8;">
                        <i class="fas fa-image fa-3x"></i>
                    </div>
                <?php endif; ?>
                <span class="card-badge"><?= h($lostReport->status) ?></span>
            </div>

            <div class="card-body">
                <div class="card-meta">
                    <i class="fas fa-tag me-1"></i> <?= h($lostReport->category) ?>
                </div>
                <h4 class="card-title"><?= h($lostReport->item_name) ?></h4>
                <div class="card-info">
                    <i class="fas fa-map-marker-alt" style="color:#ea580c"></i> <?= h($lostReport->last_seen_location) ?>
                </div>
                <div class="card-info">
                    <i class="far fa-calendar-alt" style="color:#640e62"></i> <?= h($lostReport->lost_date) ?>
                </div>
                <?= $this->Html->link(
                    'View Details <i class="fas fa-arrow-right"></i>',
                    ['action' => 'view', $lostReport->id],
                    ['class' => 'btn-view-card', 'escape' => false]
                ) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="paginator-wrapper">
        <div style="color: #64748b; font-size: 0.9rem;">
            <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s)')) ?>
        </div>
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('Prev')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Next') . ' >') ?>
        </ul>
    </div>
</div>