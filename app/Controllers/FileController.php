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
    
        // Load the ProjectModel to retrieve projects
        $projectModel = new ProjectModel();
        $projects = $projectModel->findAll(); // Retrieve all projects
    
        // Pass the projects to the view
        return view('PMS/upload_file', ['projects' => $projects]);
    }

    public function upload() {
        $session = session();
        $userID = auth()->id();
    
        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }
    
        $projectID = $this->request->getPost('project_id'); // Get the selected project ID
        $file = $this->request->getFile('file');
    
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Save temporarily to the server
            $originalName = $file->getName();
            $filePath = WRITEPATH . 'uploads/' . $originalName;
            $file->move(WRITEPATH . 'uploads/', $originalName);
    
            // Define the project directory path in MEGA
            $megaPath = "/FL Projects/$projectID";
    
            // Ensure the directory exists in MEGA
            $mkdirCommand = "megamkdir '$megaPath'";
            exec($mkdirCommand . ' 2>&1', $mkdirOutput, $mkdirStatus);
    
            if ($mkdirStatus !== 0) {
                // Handle directory creation error if necessary
                $session->setFlashdata('error', 'Failed to create directory in MEGA: ' . implode("\n", $mkdirOutput));
                return redirect()->to('file/upload');
            }
    
            // Upload file to the specified project directory
            $megaCommand = "megaput --path '$megaPath' '$filePath'";
            exec($megaCommand . ' 2>&1', $output, $status);
    
            if ($status === 0) {
                $session->setFlashdata('success', 'File uploaded successfully to MEGA.');
            } else {
                $session->setFlashdata('error', 'File could not be uploaded to MEGA. Command output: ' . implode("\n", $output));
            }
    
            // Clean up local copy
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        } else {
            $session->setFlashdata('error', 'Failed to upload file.');
        }
    
        return redirect()->to('file/upload');
    }
            
        
}