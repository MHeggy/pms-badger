<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\StateModel;
use App\Models\CountryModel;
use App\Models\TaskModel;
use App\Models\CategoryModel;
use App\Models\UpdatesModel;

class FileController extends BaseController {
    public function index() {
        $userID = auth()->id();

        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        return view('PMS/upload_file.php');
    }

    public function upload() {
        $session = session();
        $userID = auth()->id();
    
        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }
    
        $file = $this->request->getFile('file');
    
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Save temporarily to the server
            $filePath = WRITEPATH . 'uploads/' . $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/', $file->getName());
    
            // Prepare the MEGA upload command
            $megaCommand = "megaput --path / '$filePath'";  // Upload to MEGA's root directory
            exec($megaCommand . ' 2>&1', $output, $status);  // Capture error output
    
            if ($status === 0) {
                // Success, notify user
                $session->setFlashdata('success', 'File uploaded successfully to MEGA.');
            } else {
                // Error with MEGA upload
                $session->setFlashdata('error', 'File could not be uploaded to MEGA. Command output: ' . implode("\n", $output));
            }
    
            // Clean up local copy
            if (file_exists($filePath)) {
                if (!unlink($filePath)) {
                    log_message('error', 'Failed to delete local file: ' . $filePath);
                }
            } else {
                log_message('error', 'File not found for deletion: ' . $filePath);
            }
        } else {
            $session->setFlashdata('error', 'Failed to upload file.');
        }
    
        return redirect()->to('file/upload');
    }
        
}