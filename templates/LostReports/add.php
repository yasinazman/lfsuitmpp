<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LostReport $lostReport
 */
$this->Form->setTemplates([
    'inputContainer' => '<div class="field-wrapper">{{content}}</div>',
    'label' => '<label{{attrs}}>{{text}}</label>',
    'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}/>',
    'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
]);
?>

<link href="https://fonts.googleapis.com/css?family=Poppins:400,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?= $this->Html->css('LostReport') ?>

<div class="master-card">
    
    <div class="side-info">
        <div>
            <h1 class="brand-title">UiTM<br>Lost Item Registry</h1>
            <div class="side-desc">
                Log lost items securely. Please ensure all details match your student/staff records.
            </div>
        </div>
        
        <div style="margin-top: 30px;">
            <?= $this->Html->link(
                __('← Back to Gallery'), 
                ['action' => 'index'], 
                ['class' => 'back-btn']
            ) ?>
        </div>
    </div>

    <div class="form-body">
        <?= $this->Form->create($lostReport, ['type' => 'file']) ?>

        <div class="section-head"><i class="fas fa-box-open"></i> Item Details</div>
        
        <div class="form-grid">
            <div class="full-width">
                <?= $this->Form->control('item_name', [
                    'label' => 'Item Name', 
                    'placeholder' => 'e.g., iPhone 13 Pro Max'
                ]) ?>
            </div>
            
            <?= $this->Form->control('category', [
                'label' => 'Category',
                'options' => [
                    'Electronics' => 'Electronics', 
                    'Wallet/Bag' => 'Wallet/Bag', 
                    'Documents' => 'Documents', 
                    'Personal' => 'Personal', 
                    'Others' => 'Others'
                ],
                'empty' => 'Select Category'
            ]) ?>
            
            <?= $this->Form->control('lost_date', [
                'label' => 'Date Lost', 
                'type' => 'date'
            ]) ?>
            
            <div class="full-width">
                <?= $this->Form->control('last_seen_location', [
                    'label' => 'Last Known Location', 
                    'placeholder' => 'e.g., Library'
                ]) ?>
            </div>
            
            <div class="full-width">
                <?= $this->Form->control('description', [
                    'label' => 'Description (Unique Marks)', 
                    'type' => 'textarea', 
                    'placeholder' => 'Mention scratches, stickers, or serial numbers...'
                ]) ?>
            </div>
        </div>

        <div class="section-head"><i class="fas fa-user-shield"></i> Reporter Information</div>
        
        <div class="form-grid">
            <div class="full-width">
                <?= $this->Form->control('reporter_name', ['label' => 'Full Name']) ?>
            </div>
            
            <div class="field-wrapper">
                <label>Matrix / Staff No.</label>
                <input type="text" 
                       name="reporter_matrix_id" 
                       class="matrix-input" 
                       placeholder="20XXXXXXXX" 
                       maxlength="10" 
                       oninput="this.value = this.value.toUpperCase()">
            </div>

            <?= $this->Form->control('reporter_contact', [
                'label' => 'Contact Number', 
                'placeholder' => '0123456789'
            ]) ?>
        </div>

        <div class="section-head"><i class="fas fa-paperclip"></i> Media Attachment</div>

        <div class="form-grid">
            <div class="full-width">
                <?= $this->Form->control('image_file', [
                    'type' => 'file', 
                    'label' => 'Upload Evidence Photo'
                ]) ?>
            </div>
        </div>

        <div class="section-head"><i class="fas fa-shield-alt"></i> Security Verification</div>

        <div class="full-width">
            <div class="g-recaptcha" data-sitekey="6Lf_D1ksAAAAAG9QqY5xsGQGh5v_64ZZ_lgX5V09"></div>
        </div>

        <?= $this->Form->button(
            __('AUTHORIZE & SUBMIT'), 
            ['class' => 'btn-submit']
        ) ?>
        
        <?= $this->Form->end() ?>
    </div>
</div>