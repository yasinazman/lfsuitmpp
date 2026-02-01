<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FoundReport $foundReport
 */
?>
<link href="https://fonts.googleapis.com/css?family=Poppins:400,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?= $this->Html->css('found_report') ?>

<div class="hero-banner"></div>

<div class="content-wrapper">
    <aside>
        <div class="sidebar">
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> All Reports', 
                ['action' => 'index'], 
                ['class' => 'nav-link', 'escape' => false]
            ) ?>
        </div>
    </aside>

    <main class="main-card">
        <div class="card-header-accent"></div>
        <div class="card-body">
            
            <div class="badge-status">
                <i class="fas fa-circle" style="font-size: 0.5rem;"></i> 
                <?= h($foundReport->status ? $foundReport->status : 'FOUND') ?>
            </div>
            
            <h1 style="font-size: 2.8rem; margin: 0; color: var(--primary); letter-spacing: -1.5px; line-height: 1.1;">
                <?= h($foundReport->item_name) ?>
            </h1>
            
            <p style="color: var(--text-muted); font-size: 1.15rem; margin-top: 10px;">
                <i class="fas fa-map-pin" style="color: #166534; margin-right: 6px;"></i> 
                Found at <span style="color: var(--primary); font-weight: 700;"><?= h($foundReport->found_location) ?></span>
            </p>

            <div class="meta-grid">
                <div class="meta-item">
                    <span class="meta-label">Classification</span>
                    <div class="meta-value">
                        <i class="fas fa-shapes" style="width: 20px; color: var(--primary);"></i> <?= h($foundReport->category) ?>
                    </div>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Finder Name</span>
                    <div class="meta-value">
                        <i class="fas fa-user-check" style="width: 20px; color: var(--primary);"></i> <?= h($foundReport->finder_name) ?>
                    </div>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Finder ID</span>
                    <div class="meta-value">
                        <i class="fas fa-id-badge" style="width: 20px; color: var(--primary);"></i> <?= h($foundReport->finder_matrix_id) ?>
                    </div>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Contact Finder</span>
                    <div class="meta-value">
                        <i class="fas fa-phone" style="width: 20px; color: var(--primary);"></i> <?= h($foundReport->finder_contact) ?>
                    </div>
                </div>
            </div>

            <div class="description-box">
                <h3 class="description-title"><i class="fas fa-align-left"></i> Item Description</h3>
                <div style="line-height: 1.8; color: #475569; font-size: 1.05rem;">
                    <?= $this->Text->autoParagraph(h($foundReport->description)); ?>
                </div>
            </div>

            <div class="attachment-section">
                <h3 style="font-size: 1.1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; color: var(--primary);">
                    <i class="fas fa-camera"></i> Item Photo
                </h3>
                
                <div class="image-viewer">
                    <?php 
                        $imagePath = WWW_ROOT . 'img' . DS . 'uploads' . DS . $foundReport->image_file;
                        if ($foundReport->image_file && file_exists($imagePath)): 
                    ?>
                        <a href="<?= $this->Url->webroot('img/uploads/' . $foundReport->image_file) ?>" target="_blank">
                            <div class="image-wrapper">
                                <?= $this->Html->image('uploads/' . $foundReport->image_file, [
                                    'alt' => 'Found Item Photo', 
                                    'class' => 'preview-img'
                                ]) ?>
                                <div class="view-full-badge"><i class="fas fa-expand"></i> ENLARGE VIEW</div>
                            </div>
                        </a>
                        <div class="image-caption">
                            <i class="fas fa-file-image" style="color: var(--primary);"></i> <?= h($foundReport->image_file) ?>
                        </div>
                    <?php else: ?>
                        <div style="padding: 3rem; opacity: 0.6; color: var(--text-muted);">
                            <i class="fas fa-image-slash" style="font-size: 3.5rem; margin-bottom: 1rem; color: var(--primary);"></i>
                            <p style="font-weight: 700;">No photo available for this item.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <footer style="margin-top: 4rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <div style="background: var(--primary); padding: 5px 15px; border-radius: 6px; color: white; font-size: 0.75rem; font-weight: 800;">
                    REF: F-<?= str_pad((string)$foundReport->id, 6, '0', STR_PAD_LEFT) ?>
                </div>
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">
                    <i class="far fa-calendar-check" style="color: var(--primary);"></i> 
                    Date Found: <?= h($foundReport->found_date ? $foundReport->found_date->format('d M Y') : $foundReport->created->format('d M Y')) ?>
                </div>
            </footer>
        </div>
    </main>
</div>