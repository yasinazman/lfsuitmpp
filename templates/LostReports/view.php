<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LostReport $lostReport
 */
?>
<link href="https://fonts.googleapis.com/css?family=Poppins:400,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?= $this->Html->css('LostReport') ?>

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
                <?= h($lostReport->status) ?>
            </div>
            
            <h1 style="font-size: 2.8rem; margin: 0; color: var(--primary); letter-spacing: -1.5px; line-height: 1.1;">
                <?= h($lostReport->item_name) ?>
            </h1>
            
            <p style="color: var(--text-muted); font-size: 1.15rem; margin-top: 10px;">
                <i class="fas fa-location-dot" style="color: #ef4444; margin-right: 6px;"></i> 
                Logged at <span style="color: var(--primary); font-weight: 700;"><?= h($lostReport->last_seen_location) ?></span>
            </p>

            <div class="meta-grid">
                <div class="meta-item">
                    <span class="meta-label">Classification</span>
                    <div class="meta-value">
                        <i class="fas fa-shapes" style="width: 20px; color: var(--accent);"></i> <?= h($lostReport->category) ?>
                    </div>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Reporting User</span>
                    <div class="meta-value">
                        <i class="fas fa-user-circle" style="width: 20px; color: var(--accent);"></i> <?= h($lostReport->reporter_name) ?>
                    </div>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Identity ID</span>
                    <div class="meta-value">
                        <i class="fas fa-id-card" style="width: 20px; color: var(--accent);"></i> <?= h($lostReport->reporter_matrix_id) ?>
                    </div>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Contact Channel</span>
                    <div class="meta-value">
                        <i class="fas fa-phone-flip" style="width: 20px; color: var(--accent);"></i> <?= h($lostReport->reporter_contact) ?>
                    </div>
                </div>
            </div>

            <div class="description-box">
                <h3 class="description-title"><i class="fas fa-align-left"></i> Detailed Log</h3>
                <div style="line-height: 1.8; color: #475569; font-size: 1.05rem;">
                    <?= $this->Text->autoParagraph(h($lostReport->description)); ?>
                </div>
            </div>

            <div class="attachment-section">
                <h3 style="font-size: 1.1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; color: var(--primary);">
                    <i class="fas fa-camera-retro"></i> Captured Evidence
                </h3>
                
                <div class="image-viewer">
                    <?php 
                        $imagePath = WWW_ROOT . 'img' . DS . 'uploads' . DS . $lostReport->image_file;
                        if ($lostReport->image_file && file_exists($imagePath)): 
                    ?>
                        <a href="<?= $this->Url->webroot('img/uploads/' . $lostReport->image_file) ?>" target="_blank">
                            <div class="image-wrapper">
                                <?= $this->Html->image('uploads/' . $lostReport->image_file, [
                                    'alt' => 'Case Photo', 
                                    'class' => 'preview-img'
                                ]) ?>
                                <div class="view-full-badge"><i class="fas fa-expand"></i> ENLARGE VIEW</div>
                            </div>
                        </a>
                        <div class="image-caption">
                            <i class="fas fa-file-image" style="color: var(--primary);"></i> <?= h($lostReport->image_file) ?>
                        </div>
                    <?php else: ?>
                        <div style="padding: 3rem; opacity: 0.6; color: var(--text-muted);">
                            <i class="fas fa-image-slash" style="font-size: 3.5rem; margin-bottom: 1rem; color: var(--primary);"></i>
                            <p style="font-weight: 700;">Log contains no visual attachments.</p>
                            <?php if ($lostReport->image_file): ?>
                                <small style="display: block; margin-top: 5px;">File Reference: <?= h($lostReport->image_file) ?></small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <footer style="margin-top: 4rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <div style="background: var(--primary); padding: 5px 15px; border-radius: 6px; color: var(--accent); font-size: 0.75rem; font-weight: 800;">
                    REFERENCE: #<?= str_pad((string)$lostReport->id, 6, '0', STR_PAD_LEFT) ?>
                </div>
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">
                    <i class="far fa-clock" style="color: var(--primary);"></i> Logged On: <?= h($lostReport->created->format('d M Y, h:i A')) ?>
                </div>
            </footer>
        </div>
    </main>
</div>