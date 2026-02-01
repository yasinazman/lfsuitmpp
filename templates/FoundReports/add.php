<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FoundReport $foundReport
 */
$this->Form->setTemplates([
    'inputContainer' => '<div class="field-wrapper">{{content}}</div>',
    'label' => '<label{{attrs}}>{{text}}</label>',
    'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}/>',
    'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
    'textarea' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea>',
]);
?>

<link href="https://fonts.googleapis.com/css?family=Poppins:400,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?= $this->Html->css('found_report') ?>

<div class="master-card">
    
    <div class="side-info">
        <div>
            <h1 class="brand-title">UiTM<br>Found Registry</h1>
            <div class="side-desc">
                Found something? Log it here securely to help us return it to the owner.
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
        <?= $this->Form->create($foundReport, ['type' => 'file']) ?>

        <div class="section-head"><i class="fas fa-hand-holding-heart"></i> Item Found Details</div>
        
        <div class="form-grid">
            <div class="full-width">
                <?= $this->Form->control('item_name', [
                    'label' => 'Item Name', 
                    'placeholder' => 'e.g., Blue Wallet, Keys'
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

            <?= $this->Form->control('found_date', [
                'label' => 'Date Found', 
                'type' => 'date',
                'value' => date('Y-m-d') 
            ]) ?>

            <div class="full-width">
                <?= $this->Form->control('found_location', [
                    'label' => 'Location Found', 
                    'placeholder' => 'e.g., Makmal Komputer 6'
                ]) ?>
            </div>
            
            <div class="full-width">
                <?= $this->Form->control('description', [
                    'label' => 'Description (Features)', 
                    'type' => 'textarea', 
                    'placeholder' => 'Describe the item (color, brand, specific marks)...'
                ]) ?>
            </div>
        </div>

        <div class="section-head"><i class="fas fa-user-check"></i> Finder Information</div>
        
        <div class="form-grid">
            <div class="full-width">
                <?= $this->Form->control('finder_name', ['label' => 'Your Name']) ?>
            </div>
            
            <div class="field-wrapper">
                <label>Matrix / Staff No.</label>
                <input type="text" 
                       name="finder_matrix_id" 
                       class="matrix-input" 
                       placeholder="20XXXXXXXX" 
                       maxlength="10" 
                       oninput="this.value = this.value.toUpperCase()">
            </div>

            <?= $this->Form->control('finder_contact', [
                'label' => 'Contact Number', 
                'placeholder' => '0123456789'
            ]) ?>
        </div>

        <div class="section-head"><i class="fas fa-camera"></i> Evidence Photo</div>
        
        <div class="form-grid">
            <div class="full-width">
                <?= $this->Form->control('image_file', [
                    'type' => 'file', 
                    'label' => 'Upload Item Photo'
                ]) ?>
            </div>
        </div>

        <div class="section-head"><i class="fas fa-shield-alt"></i> Security Verification</div>
        
        <div class="form-grid">
            <div class="full-width">
                <div class="recaptcha-wrapper">
                    <div class="g-recaptcha" data-sitekey="6Lf_D1ksAAAAAG9QqY5xsGQGh5v_64ZZ_lgX5V09"></div>
                </div>
            </div>
        </div>

        <?= $this->Form->button(
            __('SUBMIT FOUND ITEM'), 
            ['class' => 'btn-submit-found']
        ) ?>
        
        <?= $this->Form->end() ?>
    </div>
</div>