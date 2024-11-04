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
            $originalName = $file->getName();
            $filePath = WRITEPATH . 'uploads/' . $originalName;
            $file->move(WRITEPATH . 'uploads/', $originalName);
    
            // Define paths for each directory level
            $baseDir = 'Cloud Drive/FL Projects';
            $projectDir = "$baseDir/$projectID";
    
            // Check and create the base directory if needed
            exec("megamkdir '$baseDir' 2>&1", $baseOutput, $baseStatus);
            if ($baseStatus !== 0) {
                $session->setFlashdata('error', 'Failed to create base directory in MEGA: ' . implode("\n", $baseOutput));
                return redirect()->to('file/upload');
            }
    
            // Create project directory
            exec("megamkdir '$projectDir' 2>&1", $projectOutput, $projectStatus);
            if ($projectStatus !== 0) {
                $session->setFlashdata('error', 'Failed to create project directory in MEGA: ' . implode("\n", $projectOutput));
                return redirect()->to('file/upload');
            }
    
            // Upload the file
            $megaCommand = "megaput --path '$projectDir' '$filePath'";
            exec($megaCommand . ' 2>&1', $output, $status);
    
            if ($status === 0) {
                $session->setFlashdata('success', 'File uploaded successfully to MEGA.');
            } else {
                $session->setFlashdata('error', 'File could not be uploaded to MEGA. Command output: ' . implode("\n", $output));
            }
    
            // Clean up
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        } else {
            $session->setFlashdata('error', 'Failed to upload file.');
        }
    
        return redirect()->to('file/upload');
    }
    
}