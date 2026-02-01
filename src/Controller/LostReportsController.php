<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Client; 

// LostReports Controller - Handles Public submissions, browsing, and filtering.

class LostReportsController extends AppController
{
    // Public Gallery with Search & Filtering
    public function index()
    {   
        $query = $this->LostReports
            ->find('all')
            ->where([
                'status !=' => 'Pending',
                'status IS NOT' => 'Claimed'
            ]) 
            ->order(['created' => 'DESC']);

        $keyword = $this->request->getQuery('key');
        if (!empty($keyword)) {
            $query->where([
                'OR' => [
                    'item_name LIKE' => '%' . $keyword . '%',
                    'description LIKE' => '%' . $keyword . '%',
                ]
            ]);
        }

        $category = $this->request->getQuery('cat');
        if (!empty($category)) {
            $query->where(['category' => $category]);
        }

        $location = $this->request->getQuery('loc');
        if (!empty($location)) {
            $query->where(['last_seen_location LIKE' => '%' . $location . '%']);
        }

        $lostReports = $this->paginate($query);
        $this->set(compact('lostReports'));
    }

    public function view($id = null)
    {
        $lostReport = $this->LostReports->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('lostReport'));
    }

    public function add()
    {
        $lostReport = $this->LostReports->newEmptyEntity();
        
        if ($this->request->is('post')) {
            
            // ReCAPTCHA Verification 
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
                $data['status'] = 'Pending';

                $imageFile = $data['image_file']; 
                unset($data['image_file']);

                $lostReport = $this->LostReports->patchEntity($lostReport, $data);

                if ($imageFile && is_object($imageFile) && $imageFile->getError() === 0) {
                    $name = $imageFile->getClientFilename();
                    $fileName = time() . '_' . $name; 
                    $targetPath = WWW_ROOT . 'img' . DS . 'uploads' . DS . $fileName;

                    try {
                        $imageFile->moveTo($targetPath);
                        $lostReport->image_file = $fileName; 
                    } catch (\Exception $e) {
                    }
                }

                if ($this->LostReports->save($lostReport)) {
                    $this->Flash->success(__('Report submitted successfully! Waiting for admin approval.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Failed to save report. Please try again.'));
            }
        }
        $this->set(compact('lostReport'));
    }
}