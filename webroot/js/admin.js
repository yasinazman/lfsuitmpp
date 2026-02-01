document.addEventListener('DOMContentLoaded', function() {

    // Dashboards Chart
    const ctxBar = document.getElementById('dashboardBarChart');
    const ctxPie = document.getElementById('dashboardPieChart');

    if (ctxBar && ctxPie) {
        const weeklyTotal = parseInt(ctxBar.dataset.weekly);
        const monthlyTotal = parseInt(ctxBar.dataset.monthly);
        const totalLost = parseInt(ctxPie.dataset.lost);
        const totalFound = parseInt(ctxPie.dataset.found);

        // 1. Bar Chart
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['This Week', 'This Month'],
                datasets: [{
                    label: 'Reports Filed',
                    data: [weeklyTotal, monthlyTotal],
                    backgroundColor: ['#8b5cf6', '#d946ef'],
                    borderRadius: 6,
                    barThickness: 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } },
                plugins: { legend: { display: false } }
            }
        });

        // 2. Pie Chart
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Lost Items', 'Found Items'],
                datasets: [{
                    data: [totalLost, totalFound],
                    backgroundColor: ['#f97316', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } 
                },
                cutout: '70%'
            }
        });
    }

    // Search Bar (General Tables)
    const searchInput = document.getElementById('smartSearch');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const allRows = document.querySelectorAll('table.custom-table tbody tr');
            
            allRows.forEach(row => {
                if (row.cells.length > 1) {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(searchText) ? "" : "none";
                }
            });
        });
    }

    // Stats Page Charts
    const container = document.getElementById('statsContainer');

    if (container) {
        const labels = JSON.parse(container.dataset.labels);
        const lostData = JSON.parse(container.dataset.lost);
        const foundData = JSON.parse(container.dataset.found);

        // Render Bar Chart
        const ctxBar = document.getElementById('barChart');
        if (ctxBar) {
            new Chart(ctxBar.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Lost Items',
                            data: lostData,
                            backgroundColor: '#e11d48',
                            borderRadius: 4,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Found Items',
                            data: foundData,
                            backgroundColor: '#16a34a',
                            borderRadius: 4,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { precision: 0 }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Render Pie Chart
        const ctxPie = document.getElementById('pieChart');
        if (ctxPie) {
            const totalLost = lostData.reduce((a, b) => a + b, 0);
            const totalFound = foundData.reduce((a, b) => a + b, 0);

            const chartData = (totalLost === 0 && totalFound === 0) 
                ? [1] : [totalLost, totalFound];
            
            const chartColors = (totalLost === 0 && totalFound === 0)
                ? ['#e2e8f0'] : ['#e11d48', '#16a34a'];

            new Chart(ctxPie.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Lost Items', 'Found Items'],
                    datasets: [{
                        data: chartData,
                        backgroundColor: chartColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '70%'
                }
            });
        }
    }

    // Claim logic
    window.openClaimModal = function(actionUrl, itemName) {
        const modal = document.getElementById('claimModal');
        const form = document.getElementById('claimForm');
        const itemNameLabel = document.getElementById('modalItemName');

        if (modal && form && itemNameLabel) {
            modal.style.display = 'flex'; 
            itemNameLabel.innerText = itemName; 
            form.action = actionUrl;
        }
    };

    window.closeClaimModal = function() {
        const modal = document.getElementById('claimModal');
        if (modal) {
            modal.style.display = 'none';
        }
    };

    window.onclick = function(event) {
        const modal = document.getElementById('claimModal');
        if (event.target === modal) {
            closeClaimModal();
        }
    };

    // History Search
    const historyInput = document.getElementById('searchInput');

    if (historyInput) {
        historyInput.addEventListener('keyup', function(e) {
            const term = e.target.value.toLowerCase();
            
            const tableRows = document.querySelectorAll('table tbody tr');

            tableRows.forEach(row => {
                const textContent = row.textContent.toLowerCase();
                row.style.display = textContent.includes(term) ? '' : 'none';
            });
        });
    }

});