<?php
/**
 * Admin: Weekly Statistics Page
 */
$this->disableAutoLayout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Weekly Stats - UiTM L&F</title>
    
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
            <?= $this->Html->link(
                '<i class="fas fa-chart-bar me-3"></i> Monthly Stats', 
                ['action' => 'monthlyStats'], 
                ['class' => 'sidebar-link', 'escape' => false]
            ) ?>
            
            <a href="#" class="sidebar-link active">
                <i class="fas fa-calendar-check me-3"></i> Weekly Stats
            </a>
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
                    <h2 class="page-header-title">Weekly Report Analysis</h2>
                    <p class="text-muted">Last 7 Days Overview</p>
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
            // Prepare Data
            $chartLabels = ['Weekly Total'];
            $lData = [count($lostWeekly)];
            $fData = [count($foundWeekly)];
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

        <div class="stats-card-block text-center">
            
            <h2 class="breakdown-title-simple">Weekly Breakdown Summary</h2>
            <p class="text-muted text-small mb-4">Total items reported in the last 7 days</p>
            
            <div class="weekly-wrapper mb-4">
                
                <div class="weekly-card lost">
                    <div class="info-label">Total Lost</div>
                    <div class="weekly-number text-lost"><?= $lData[0] ?></div>
                    <div class="sub-label text-muted">items reported</div>
                </div>

                <div class="weekly-card found">
                    <div class="info-label">Total Found</div>
                    <div class="weekly-number text-found"><?= $fData[0] ?></div>
                    <div class="sub-label text-muted">items reported</div>
                </div>

            </div>

            <div class="weekly-action-footer">
                <a href="<?= $this->Url->build(['action' => 'exportWeeklyStatsPdf']) ?>" 
                   target="_blank" 
                   class="btn-download-report btn-wide-center">
                    <i class="fas fa-file-pdf"></i> Download Weekly Report
                </a>
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