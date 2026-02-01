<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Client; 

class FoundReportsController extends AppController
{
    // Index- Public Gallery

    public function index()
    {
        $query = $this->FoundReports
            ->find('all')
            ->where([
                'status' => 'Found', 
                'status IS NOT' => 'Claimed' 
            ])
            ->order(['created' => 'DESC']);

        // Search Logic
        $keyword = $this->request->getQuery('key');
        if (!empty($keyword)) {
            $query->where([
                'OR' => [
                    'item_name LIKE' => '%' . $keyword . '%',
                    'description LIKE' => '%' . $keyword . '%',
                ]
            ]);
        }

        // Category Filter
        $category = $this->request->getQuery('cat');
        if (!empty($category)) {
            $query->where(['category' => $category]);
        }

        // Location Filter
        $location = $this->request->getQuery('loc');
        if (!empty($location)) {
            $query->where(['found_location LIKE' => '%' . $location . '%']);
        }

        $foundReports = $this->paginate($query);
        $this->set(compact('foundReports'));
    }

    public function add()
    {
        $foundReport = $this->FoundReports->newEmptyEntity();
        
        if ($this->request->is('post')) {
            
            // ReCAPTCHA Check
            $recaptchaSecret = '6Lf_D1ksAAAAALzd-ITjkbi4gFvKXBNv4WAaI-2-'; 
            $recaptchaToken = $this->request->getData('g-recaptcha-response');

            $http = new Client();
            $response = $http->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $recaptchaSecret,
                'response' => $recaptchaToken
            ]);
            $json = $response->getJson();

            if (empty($json['success']) || !$json['success']) {
                $this->Flash->error(__('Please check the "I am not a robot" box for security verification.'));
            } 
            else {

                $data = $this->request->getData();

                $image = $data['image_file'];
                unset($data['image_file']); 

                $foundReport = $this->FoundReports->patchEntity($foundReport, $data);
                
                $foundReport->status = 'Pending'; 

                if ($image && $image->getError() === 0 && $image->getSize() > 0) {
                    $name = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $image->getClientFilename());
                    $uploadPath = WWW_ROOT . 'img' . DS . 'uploads';

                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0775, true);
                    }

                    $targetPath = $uploadPath . DS . $name;
                    try {
                        $image->moveTo($targetPath);
                        $foundReport->image_file = $name;
                    } catch (\Exception $e) {
                    }
                }

                if ($this->FoundReports->save($foundReport)) {
                    $this->Flash->success(__('Report submitted successfully! Waiting for admin approval.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Failed to save report. Please try again.'));
            }
        }
        $this->set(compact('foundReport'));
    }

    public function view($id = null)
    {
        $foundReport = $this->FoundReports->get($id);
        $this->set(compact('foundReport'));
    }
}