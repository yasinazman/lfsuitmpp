<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\FoundReport> $foundReports
 */
echo $this->Html->css('found_report', ['block' => true]);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="dashboard-container">
    
    <div class="dashboard-header">
        <div class="header-title">
            <h3><i class="fas fa-search-location"></i> Found Items Gallery</h3>
            <p>List of items found around campus.</p>
        </div>
        
        <?= $this->Html->link(
            '<i class="fas fa-hand-holding-heart"></i> I Found Something',
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
        <?php foreach ($foundReports as $foundReport): ?>
        <div class="report-card">
            
            <div class="card-img-top">
                <?php if (!empty($foundReport->image_file)): ?>
                    <?= $this->Html->image('uploads/' . $foundReport->image_file, ['alt' => $foundReport->item_name]) ?>
                <?php else: ?>
                    <div style="width:100%; height:100%; background:#f0fdf4; display:flex; align-items:center; justify-content:center; color:#bbf7d0;">
                        <i class="fas fa-box-open fa-3x"></i>
                    </div>
                <?php endif; ?>
                
                <span class="card-badge">
                    <?= h($foundReport->status) ?>
                </span>
            </div>

            <div class="card-body">
                <div class="card-meta">
                    <i class="fas fa-tag me-1"></i> <?= h($foundReport->category) ?>
                </div>
                
                <h4 class="card-title"><?= h($foundReport->item_name) ?></h4>
                
                <div class="card-info">
                    <i class="fas fa-map-marker-alt" style="color:#dc2626;"></i> 
                    <?= h($foundReport->found_location) ?>
                </div>
                
                <div class="card-info">
                    <i class="far fa-calendar-alt" style="color:#166534;"></i> 
                    <?= h($foundReport->created->format('d M Y')) ?>
                </div>

                <?= $this->Html->link(
                    'View Details <i class="fas fa-arrow-right"></i>',
                    ['action' => 'view', $foundReport->id],
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