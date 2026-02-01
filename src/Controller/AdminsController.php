<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Cake\Database\Expression\QueryExpression;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Admin Controller
 * Manages Dashboard, Statistics, Approvals, and Reporting.
 */
class AdminsController extends AppController
{
    protected $LostReports;
    protected $FoundReports;

    public function initialize(): void
    {
        parent::initialize();
        
        $this->loadComponent('Authentication.Authentication');
        
        $this->LostReports = $this->fetchTable('LostReports');
        $this->FoundReports = $this->fetchTable('FoundReports');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['login']);
    }

    /* --- Authentication --- */

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            return $this->redirect([
                'controller' => 'Admins', 
                'action' => 'index'
            ]);
        }
        
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid username or password'));
        }
    }

    public function logout()
    {
        $this->Authentication->logout();
        
        return $this->redirect([
            'controller' => 'Admins', 
            'action' => 'login'
        ]);
    }

    /* --- Dashboard & Listings --- */

    public function index()
    {
        // Filters
        $searchLost = $this->request->getQuery('searchLost');
        $searchFound = $this->request->getQuery('searchFound');

        // Main Stats
        $totalLostPending = $this->LostReports
            ->find()
            ->where(['status' => 'Pending'])
            ->count();

        $totalFoundPending = $this->FoundReports
            ->find()
            ->where(['status' => 'Pending'])
            ->count();

        $totalLost = $this->LostReports
            ->find()
            ->where(['status' => 'Lost'])
            ->count();

        $totalFound = $this->FoundReports
            ->find()
            ->where(['status' => 'Found'])
            ->count(); 

        // Periodic Stats
        $today = date('Y-m-d');
        $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
        $thisMonthStart = date('Y-m-01');

        $todayCount = 
            $this->LostReports->find()->where(['DATE(created)' => $today])->count() + 
            $this->FoundReports->find()->where(['DATE(created)' => $today])->count();

        $weeklyTotal = 
            $this->LostReports->find()->where(['created >=' => $thisWeekStart])->count() + 
            $this->FoundReports->find()->where(['created >=' => $thisWeekStart])->count();

        $monthlyTotal = 
            $this->LostReports->find()->where(['created >=' => $thisMonthStart])->count() + 
            $this->FoundReports->find()->where(['created >=' => $thisMonthStart])->count();

        // Data Table: Lost (Pending)
        $pendingReportsQuery = $this->LostReports
            ->find('all')
            ->where(['status' => 'Pending']);
            
        if ($searchLost) {
            $pendingReportsQuery->where(['item_name LIKE' => '%' . $searchLost . '%']);
        }
        $pendingReports = $pendingReportsQuery
            ->order(['created' => 'DESC'])
            ->all();

        // Data Table: Found (Pending)
        $pendingFoundQuery = $this->FoundReports
            ->find('all')
            ->where(['status' => 'Pending']);
            
        if ($searchFound) {
            $pendingFoundQuery->where(['item_name LIKE' => '%' . $searchFound . '%']);
        }
        $pendingFoundReports = $pendingFoundQuery
            ->order(['created' => 'DESC'])
            ->all();

        // Recent Activity
        $recentLost = $this->LostReports
            ->find('all')
            ->where(['status' => 'Lost'])
            ->order(['created' => 'DESC'])
            ->limit(5)
            ->all();

        $recentFound = $this->FoundReports
            ->find('all')
            ->where(['status' => 'Found'])
            ->order(['created' => 'DESC'])
            ->limit(5)
            ->all();

        $this->set(compact(
            'totalLostPending', 'totalFoundPending', 'totalLost', 'totalFound', 
            'pendingReports', 'pendingFoundReports', 'recentLost', 'recentFound',
            'todayCount', 'weeklyTotal', 'monthlyTotal'
        ));
    }

    public function lostItems()
    {
        $pendingItems = $this->LostReports
            ->find('all')
            ->where(['status' => 'Pending'])
            ->order(['created' => 'DESC'])
            ->all();
        
        $query = $this->LostReports
            ->find('all')
            ->where(['status' => 'Lost'])
            ->order(['created' => 'DESC']);
            
        $approvedItems = $this->paginate($query, ['limit' => 10]);

        $this->set(compact('pendingItems', 'approvedItems'));
    }

    public function foundItems()
    {
        $pendingFound = $this->FoundReports
            ->find('all')
            ->where(['status' => 'Pending'])
            ->order(['created' => 'DESC'])
            ->all();
        
        $query = $this->FoundReports
            ->find('all')
            ->where(['status' => 'Found'])
            ->order(['created' => 'DESC']);
            
        $approvedFound = $this->paginate($query, ['limit' => 10]);

        $this->set(compact('pendingFound', 'approvedFound'));
    }

    /* --- Actions (View, Edit, Delete) --- */

    public function viewLost($id = null)
    {
        $item = $this->LostReports->get($id);
        $type = 'LOST';
        
        $this->set(compact('item', 'type'));
        $this->render('view');
    }

    public function viewFound($id = null)
    {
        $item = $this->FoundReports->get($id);
        $type = 'FOUND';
        
        $this->set(compact('item', 'type'));
        $this->render('view');
    }

    public function edit($id = null)
    {
        $type = $this->request->getQuery('type', 'lost'); 
        $table = ($type === 'found') ? $this->FoundReports : $this->LostReports;

        try {
            $report = $table->get($id);
        } catch (\Exception $e) {
            $this->Flash->error(__('Record not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $report = $table->patchEntity($report, $this->request->getData());
            
            if ($table->save($report)) {
                $this->Flash->success(__('Information successfully updated.'));

                // Redirect Logic
                if ($report->status === 'Claimed') {
                    return $this->redirect(['action' => 'claimedItems']);
                }

                return $this->redirect([
                    'action' => ($type === 'found' ? 'foundItems' : 'lostItems')
                ]);
            }
            $this->Flash->error(__('Failed to update information.'));
        }
        $this->set(compact('report', 'type'));
    }

    public function approve($id = null)
    {
        $this->request->allowMethod(['post']);
        
        $report = $this->LostReports->get($id);
        $report->status = 'Lost'; 
        
        if ($this->LostReports->save($report)) {
            $this->Flash->success(__('Lost Item report has been verified & published.'));
        } else {
            $this->Flash->error(__('Failed to verify report.'));
        }
        return $this->redirect($this->referer());
    }

    public function approveFound($id = null)
    {
        $this->request->allowMethod(['post']);
        
        $report = $this->FoundReports->get($id);
        $report->status = 'Found'; 
        
        if ($this->FoundReports->save($report)) {
            $this->Flash->success(__('Found Item report has been verified & published.'));
        } else {
            $this->Flash->error(__('Failed to verify report.'));
        }
        return $this->redirect($this->referer());
    }

    public function deleteReport($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $report = $this->LostReports->get($id);
        if ($this->LostReports->delete($report)) {
            $this->Flash->success(__('Lost Item record deleted.'));
        }
        return $this->redirect($this->referer());
    }

    public function deleteLost($id = null) 
    {
        return $this->deleteReport($id); 
    }

    public function deleteFound($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $report = $this->FoundReports->get($id);
        if ($this->FoundReports->delete($report)) {
            $this->Flash->success(__('Found Item record deleted.'));    
        }
        return $this->redirect($this->referer());
    }

    public function deleteFoundPending($id = null)
    {
        return $this->deleteFound($id); 
    }

    /* --- Statistics & PDF Generation --- */

    public function monthlyStats()
    {
        $year = date('Y');
        
        $lostData = $this->LostReports
            ->find()
            ->select(['month' => 'MONTH(created)', 'count' => 'COUNT(*)'])
            ->where(['YEAR(created)' => $year])
            ->group(['MONTH(created)'])
            ->all();

        $foundData = $this->FoundReports
            ->find()
            ->select(['month' => 'MONTH(created)', 'count' => 'COUNT(*)'])
            ->where(['YEAR(created)' => $year])
            ->group(['MONTH(created)'])
            ->all();

        $this->set(compact('lostData', 'foundData', 'year'));
    }

    public function weeklyStats()
    {
        $lastSevenDays = date('Y-m-d', strtotime('-7 days'));
        
        $lostWeekly = $this->LostReports
            ->find()
            ->where(['created >=' => $lastSevenDays])
            ->all();

        $foundWeekly = $this->FoundReports
            ->find()
            ->where(['created >=' => $lastSevenDays])
            ->all();
        
        $this->set(compact('lostWeekly', 'foundWeekly'));
    }

    // Monthly PDF Export
    public function exportMonthlyStatsPdf()
    {
        $year = date('Y');
        
        $lostData = $this->LostReports
            ->find()
            ->select(['month' => 'MONTH(created)', 'count' => 'COUNT(*)'])
            ->where(['YEAR(created)' => $year])
            ->group(['MONTH(created)'])
            ->all();

        $foundData = $this->FoundReports
            ->find()
            ->select(['month' => 'MONTH(created)', 'count' => 'COUNT(*)'])
            ->where(['YEAR(created)' => $year])
            ->group(['MONTH(created)'])
            ->all();

        // Data Structure
        $stats = [];
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        foreach ($monthNames as $num => $name) {
            $stats[$num] = ['name' => $name, 'lost' => 0, 'found' => 0];
        }

        foreach ($lostData as $row) {
            $stats[$row->month]['lost'] = $row->count;
        }
        foreach ($foundData as $row) {
            $stats[$row->month]['found'] = $row->count;
        }

        // Logo Processing
        $logoPath = WWW_ROOT . 'img' . DS . 'logo.png';
        $logoBase64 = '';
        
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // Generate PDF
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        // HTML Content
        $html = '
        <style>
            body { font-family: sans-serif; color: #333; }
            .header-table { width: 100%; border-bottom: 2px solid #4a148c; padding-bottom: 10px; margin-bottom: 25px; }
            .header-table td { border: none; vertical-align: middle; }
            h1 { margin: 0; color: #000000; font-size: 1.6rem; }
            .subtitle { color: #666; font-size: 0.9rem; margin-top: 5px; }
            table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            table.data-table th { background: #f3f4f6; padding: 10px; border: 1px solid #ccc; text-align: center; font-size: 0.9rem; }
            table.data-table td { padding: 8px; border: 1px solid #ccc; text-align: center; font-size: 0.9rem; }
            .total-row { background: #fefce8; font-weight: bold; }
            .text-lost { color: #e11d48; font-weight: bold; }
            .text-found { color: #16a34a; font-weight: bold; }
        </style>';

        $html .= '<table class="header-table">
                    <tr>
                        <td width="15%" style="text-align:center;">
                            <img src="' . $logoBase64 . '" width="70px">
                        </td>
                        <td width="85%">
                            <h1>UiTM Puncak Perdana (Lost & Found)</h1>
                            <div class="subtitle">Monthly Statistics Report | Year: ' . $year . '</div>
                        </td>
                    </tr>
                  </table>';

        $html .= '<table class="data-table">';
        $html .= '<thead>
                    <tr>
                        <th>Month</th>
                        <th style="color:#e11d48">Lost Items</th>
                        <th style="color:#16a34a">Found Items</th>
                        <th>Total Activity</th>
                    </tr>
                  </thead>
                  <tbody>';

        $grandLost = 0;
        $grandFound = 0;

        foreach ($stats as $month) {
            $total = $month['lost'] + $month['found'];
            $grandLost += $month['lost'];
            $grandFound += $month['found'];
            
            $html .= '<tr>
                        <td style="text-align:left; padding-left:15px;">' . $month['name'] . '</td>
                        <td class="text-lost">' . ($month['lost'] > 0 ? $month['lost'] : '-') . '</td>
                        <td class="text-found">' . ($month['found'] > 0 ? $month['found'] : '-') . '</td>
                        <td>' . ($total > 0 ? $total : '-') . '</td>
                      </tr>';
        }

        $html .= '<tr class="total-row">
                    <td style="text-align:right; padding-right:15px;">GRAND TOTAL</td>
                    <td style="color:#e11d48;">' . $grandLost . '</td>
                    <td style="color:#16a34a;">' . $grandFound . '</td>
                    <td>' . ($grandLost + $grandFound) . '</td>
                  </tr>';

        $html .= '</tbody></table>';
        
        $html .= '<p style="margin-top:40px; font-size:0.80rem; text-align:center; color:#999; border-top:1px solid #eee; padding-top:10px;">
                    This document is computer generated by UiTM Puncak Perdana Lost & Found System on ' . date('d M Y, h:i A') . '
                  </p>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Monthly_Stats_" . $year . ".pdf", ["Attachment" => false]);

        return $this->response;
    }

    // Weekly PDF Export
    public function exportWeeklyStatsPdf()
    {
        $startDate = date('Y-m-d', strtotime('-7 days'));
        $endDate = date('Y-m-d');
        $formattedStart = date('d M Y', strtotime($startDate));
        $formattedEnd = date('d M Y', strtotime($endDate));

        $lostItems = $this->LostReports
            ->find()
            ->where(['created >=' => $startDate])
            ->order(['created' => 'DESC'])
            ->all();

        $foundItems = $this->FoundReports
            ->find()
            ->where(['created >=' => $startDate])
            ->order(['created' => 'DESC'])
            ->all();

        // Logo Processing
        $logoPath = WWW_ROOT . 'img' . DS . 'logo.png'; 
        $logoBase64 = '';
        
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        $html = '
        <style>
            body { font-family: sans-serif; color: #333; }
            .header-table { width: 100%; border-bottom: 2px solid #4a148c; padding-bottom: 10px; margin-bottom: 25px; }
            .header-table td { border: none; vertical-align: middle; }
            h1 { margin: 0; color: #000000; font-size: 1.6rem; }
            .subtitle { color: #666; font-size: 0.9rem; margin-top: 5px; }
            .summary-box { background: #f8fafc; padding: 15px; text-align: center; margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 5px; }
            h3 { border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-top: 30px; font-size: 1.1rem; }
            table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.85rem; }
            table.data-table th { background: #f3f4f6; padding: 8px; border: 1px solid #ccc; text-align: left; }
            table.data-table td { padding: 8px; border: 1px solid #ccc; }
            .badge-lost { color: #e11d48; font-weight: bold; font-size: 1.1rem; }
            .badge-found { color: #16a34a; font-weight: bold; font-size: 1.1rem; }
        </style>';

        $html .= '<table class="header-table">
                    <tr>
                        <td width="15%" style="text-align:center;">
                            <img src="' . $logoBase64 . '" width="70px">
                        </td>
                        <td width="85%">
                            <h1>UiTM Puncak Perdana (Lost & Found)</h1>
                            <div class="subtitle">Weekly Report Analysis (' . $formattedStart . ' - ' . $formattedEnd . ')</div>
                        </td>
                    </tr>
                  </table>';

        $html .= '<div class="summary-box">
                    <strong>Total Lost:</strong> <span class="badge-lost">' . $lostItems->count() . '</span> 
                    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; 
                    <strong>Total Found:</strong> <span class="badge-found">' . $foundItems->count() . '</span>
                  </div>';

        // Table 1: Lost Items
        $html .= '<h3 style="color:#e11d48;">Lost Items Reported</h3>';
        
        if ($lostItems->isEmpty()) {
            $html .= '<p style="font-style:italic; color:#777;">No lost items reported in this period.</p>';
        } else {
            $html .= '<table class="data-table">
                        <thead><tr><th>Date</th><th>Item Name</th><th>Location</th><th>Status</th></tr></thead>
                        <tbody>';
            foreach ($lostItems as $item) {
                $html .= '<tr>
                            <td>' . $item->created->format('d M, h:i A') . '</td>
                            <td>' . h($item->item_name) . '</td>
                            <td>' . h($item->last_seen_location) . '</td>
                            <td>' . h($item->status) . '</td>
                          </tr>';
            }
            $html .= '</tbody></table>';
        }

        // Table 2: Found Items
        $html .= '<h3 style="color:#16a34a;">Found Items Reported</h3>';
        
        if ($foundItems->isEmpty()) {
            $html .= '<p style="font-style:italic; color:#777;">No found items reported in this period.</p>';
        } else {
            $html .= '<table class="data-table">
                        <thead><tr><th>Date</th><th>Item Name</th><th>Location</th><th>Status</th></tr></thead>
                        <tbody>';
            foreach ($foundItems as $item) {
                $html .= '<tr>
                            <td>' . $item->created->format('d M, h:i A') . '</td>
                            <td>' . h($item->item_name) . '</td>
                            <td>' . h($item->found_location) . '</td>
                            <td>' . h($item->status) . '</td>
                          </tr>';
            }
            $html .= '</tbody></table>';
        }

        $html .= '<p style="margin-top:40px; font-size:0.80rem; text-align:center; color:#999; border-top:1px solid #eee; padding-top:10px;">
                    This document is computer generated by UiTM Puncak Perdana Lost & Found System on ' . date('d M Y, h:i A') . '
                  </p>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Weekly_Report_" . $endDate . ".pdf", ["Attachment" => false]);

        return $this->response;
    }

    /* --- Single Item PDF Report --- */

    public function exportPdf($id = null)
    {
        $type = $this->request->getQuery('type');

        if ($type === 'found') {
            $report = $this->FoundReports->get($id);
            $docTitle = 'Found Item Report';
        } else {
            $report = $this->LostReports->get($id);
            $docTitle = 'Lost Item Report';
        }

        return $this->_generatePdf($report, $docTitle); 
    }

    private function _generatePdf($report, $title)
    {
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        // Logo Processing
        $logoPath = WWW_ROOT . 'img' . DS . 'logo.png';
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $html = '
        <style>
            body { font-family: sans-serif; color: #333; }
            .header-table { width: 100%; border-bottom: 2px solid #4a148c; padding-bottom: 10px; margin-bottom: 25px; }
            .header-table td { border: none; vertical-align: middle; }
            h1 { margin: 0; color: #000000; font-size: 1.6rem; }
            .subtitle { color: #666; font-size: 0.9rem; margin-top: 5px; }
            table.info-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.95rem; }
            table.info-table th { text-align: left; background: #f3f4f6; padding: 10px; border: 1px solid #ddd; width: 35%; }
            table.info-table td { padding: 10px; border: 1px solid #ddd; }
        </style>';

        $html .= '<table class="header-table">
                    <tr>
                        <td width="15%" style="text-align:center;">
                            <img src="' . $logoBase64 . '" width="70px">
                        </td>
                        <td width="85%">
                            <h1>UiTM Puncak Perdana (Lost & Found)</h1>
                            <div class="subtitle">' . $title . ' | Generated: ' . date('d M Y, h:i A') . '</div>
                        </td>
                    </tr>
                  </table>';
        
        $html .= '<table class="info-table">';
        
        // Item Details
        $html .= '<tr class="section-header"><th colspan="2" style="background:#444547; color:#ffffff; text-align:center; text-transform:uppercase; letter-spacing:1px;">Item Information</th></tr>';
        $html .= '<tr><th>Item Name</th><td>' . h($report->item_name) . '</td></tr>';
        $html .= '<tr><th>Category</th><td>' . h($report->category) . '</td></tr>';
        $html .= '<tr><th>Date Reported</th><td>' . h($report->created->format('d M Y')) . '</td></tr>';
        $html .= '<tr><th>Status</th><td>' . h($report->status) . '</td></tr>';
        $html .= '<tr><th>Description</th><td>' . h($report->description) . '</td></tr>';
        
        // Reporter Details
        $html .= '<tr class="section-header"><th colspan="2" style="background:#444547; color:#ffffff; text-align:center; text-transform:uppercase; letter-spacing:1px;">Reporter / Finder Details</th></tr>';
        
        if (isset($report->reporter_name)) {
            $html .= '<tr><th>Reporter Name</th><td>' . h($report->reporter_name) . '</td></tr>';
            $html .= '<tr><th>Contact</th><td>' . h($report->reporter_contact) . '</td></tr>';
            $html .= '<tr><th>Last Seen Location</th><td>' . h($report->last_seen_location) . '</td></tr>';
        } else {
            $html .= '<tr><th>Finder Name</th><td>' . h($report->finder_name) . '</td></tr>';
            $html .= '<tr><th>Contact</th><td>' . h($report->finder_contact) . '</td></tr>';
            $html .= '<tr><th>Found Location</th><td>' . h($report->found_location) . '</td></tr>';
        }

        // Claim Details (If applicable)
        if ($report->status === 'Claimed') {
            $html .= '<tr class="section-header"><th colspan="2" style="background:#16a34a; color:#ffffff; text-align:center; text-transform:uppercase; letter-spacing:1px;">Claimant Details</th></tr>';
            $html .= '<tr><th>Claimer Name</th><td>' . h($report->claimer_name) . '</td></tr>';
            $html .= '<tr><th>Matrix ID</th><td>' . h($report->claimer_matrix_id) . '</td></tr>';
            $html .= '<tr><th>Contact Number</th><td>' . h($report->claimer_contact) . '</td></tr>';
            
            $formattedDate = '-';
            if ($report->claimed_date) {
                $formattedDate = $report->claimed_date->format('d F Y, h:i A');
            }

            $html .= '<tr><th>Date Claimed</th><td>' . h($formattedDate) . '</td></tr>';
        }

        $html .= '</table>';
        $html .= '<p style="margin-top:40px; font-size:0.80rem; text-align:center; color:#999; border-top:1px solid #eee; padding-top:10px;"> This document is computer generated by UiTM Puncak Perdana Lost & Found System. </p>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $fileName = str_replace(' ', '_', $title) . "_" . $report->id . ".pdf";
        $dompdf->stream($fileName, ["Attachment" => false]); 
        
        return $this->response;
    }

    /* --- Claim Actions --- */

    public function markLostAsClaimed($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $report = $this->LostReports->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            $report = $this->LostReports->patchEntity($report, $data);
            $report->status = 'Claimed'; 
            $report->claimed_date = date('Y-m-d H:i:s'); 

            if ($this->LostReports->save($report)) {
                $this->Flash->success(__('Lost item resolved/claimed successfully!'));
                return $this->redirect(['action' => 'claimedItems']);
            }
            $this->Flash->error(__('Failed to update status.'));
        }
        return $this->redirect(['action' => 'lostItems']);
    }

    public function markAsClaimed($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        $report = $this->FoundReports->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            $report = $this->FoundReports->patchEntity($report, $data);
            $report->status = 'Claimed'; 
            $report->claimed_date = date('Y-m-d H:i:s'); 

            if ($this->FoundReports->save($report)) {
                $this->Flash->success(__('Found item claimed successfully!'));
                return $this->redirect(['action' => 'claimedItems']);
            }
            $this->Flash->error(__('Failed to update status.'));
        }
        return $this->redirect(['action' => 'foundItems']);
    }

    public function claimedItems()
    {
        $search = $this->request->getQuery('search');

        // Query: Found (Claimed)
        $foundQuery = $this->FoundReports
            ->find('all')
            ->where(['status' => 'Claimed']);

        if (!empty($search)) {
            $foundQuery->where([
                'OR' => [
                    'item_name LIKE' => '%' . $search . '%',
                    'claimer_name LIKE' => '%' . $search . '%',
                    'claimer_matrix_id LIKE' => '%' . $search . '%',
                    'finder_name LIKE' => '%' . $search . '%', 
                    'description LIKE' => '%' . $search . '%'
                ]
            ]);
        }
        $foundClaims = $foundQuery
            ->order(['claimed_date' => 'DESC'])
            ->all();

        // Query: Lost (Claimed)
        $lostQuery = $this->LostReports
            ->find('all')
            ->where(['status' => 'Claimed']);

        if (!empty($search)) {
            $lostQuery->where([
                'OR' => [
                    'item_name LIKE' => '%' . $search . '%',
                    'claimer_name LIKE' => '%' . $search . '%',
                    'claimer_matrix_id LIKE' => '%' . $search . '%',
                    'reporter_name LIKE' => '%' . $search . '%', 
                    'description LIKE' => '%' . $search . '%'
                ]
            ]);
        }
        $lostClaims = $lostQuery
            ->order(['claimed_date' => 'DESC'])
            ->all();

        $this->set(compact('foundClaims', 'lostClaims', 'search'));
    }
}