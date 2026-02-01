<?php
/**
 * Admin: Monthly Statistics Page
 */
$this->disableAutoLayout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monthly Stats - UiTM L&F</title>
    
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
            <?= $this->Html->link(
                '<i class="fas fa-handshake me-3"></i> Claimed Items', 
                ['controller' => 'Admins', 'action' => 'claimedItems'], 
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>

            <div class="sidebar-label">Report Analysis</div>
            
            <a href="#" class="sidebar-link active">
                <i class="fas fa-chart-bar me-3"></i> Monthly Stats
            </a>
            
            <?= $this->Html->link(
                '<i class="fas fa-calendar-check me-3"></i> Weekly Stats', 
                ['action' => 'weeklyStats'], 
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
        
        <div class="d-flex align-items-center mb-4" style="gap: 15px;">
            <button class="mobile-toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <div id="top-header-row" class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <h2 class="page-header-title">Monthly Report Analysis</h2>
                    <p class="text-muted">Yearly Overview (Jan - Dec)</p>
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

        <?php
            // Data Logic
            $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $lData = []; 
            $fData = [];
            
            for ($i=1; $i<=12; $i++) {
                $lCount = 0; 
                foreach ($lostData as $ld) { 
                    if ($ld->month == $i) $lCount = $ld->count; 
                }
                
                $fCount = 0; 
                foreach ($foundData as $fd) { 
                    if ($fd->month == $i) $fCount = $fd->count; 
                }
                
                $lData[] = $lCount;
                $fData[] = $fCount;
            }
        ?>

        <div class="charts-flex" 
             id="statsContainer"
             data-labels='<?= json_encode($chartLabels) ?>'
             data-lost='<?= json_encode($lData) ?>'
             data-found='<?= json_encode($fData) ?>'>
             
            <div class="chart-box">
                <h5>
                    <i class="fas fa-chart-column me-2 text-primary"></i> Data Comparison
                </h5>
                <div class="chart-canvas-wrapper">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
            
            <div class="chart-box">
                <h5>
                    <i class="fas fa-chart-pie me-2 text-success"></i> Total Distribution
                </h5>
                <div class="chart-canvas-wrapper">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

        <div class="stats-full-card mt-30 p-30">
            <div class="section-header-flex">        
                <h4 class="breakdown-title">Monthly Breakdown</h4>                
                <a href="<?= $this->Url->build(['action' => 'exportMonthlyStatsPdf']) ?>" 
                   target="_blank" 
                   class="btn-download-report">
                    <i class="fas fa-file-pdf"></i> Download Report
                </a>
            </div>
            
            <div class="breakdown-grid">
                <?php foreach ($chartLabels as $index => $monthName): ?>
                    <div class="stats-summary-box">
                        <div class="summary-label info-label">
                            <?= $monthName ?>
                        </div>
                        <div class="d-flex justify-content-between text-small">
                            <span class="text-lost">Lost: <?= $lData[$index] ?></span>
                            <span class="text-found">Found: <?= $fData[$index] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?= $this->Html->script(['admin', 'admin_stats', 'layout']) ?>
</body>
</html>