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
    
        // Fetch the project name from the database based on projectID
        $projectModel = new ProjectModel();
        $project = $projectModel->find($projectID);
        $projectName = $project ? $project['projectName'] : null;
    
        if (!$projectName) {
            $session->setFlashdata('error', 'Project not found.');
            return redirect()->to('file/upload');
        }
    
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $originalName = $file->getName();
            $filePath = WRITEPATH . 'uploads/' . $originalName;
            $file->move(WRITEPATH . 'uploads/', $originalName);
    
            // MEGA credentials
            $megaUsername = 'mhegeduis@gmail.com';  // Replace with your MEGA username
            $megaPassword = 'Podpod345';            // Replace with your MEGA password
    
            // Paths for MEGA directories
            $baseDir = '/Root/Projects'; 
            $projectDir = "$baseDir/$projectName"; 
    
            // Check if directory exists
            $checkDirCommand = "megals -u $megaUsername -p $megaPassword '$baseDir'";
            exec($checkDirCommand . ' 2>&1', $output, $status);

            file_put_contents(WRITEPATH . 'logs/mega.log', "Check Dir Output:\n" . implode("\n", $output) . "\n", FILE_APPEND);

            $dirExists = false;
            $projectPath = "$baseDir/$projectName"; // Construct the expected full path

            if ($status === 0) {
                foreach ($output as $line) {
                    if (trim($line) === $projectPath) {
                        $dirExists = true;
                        break;
                    }
                }

                if (!$dirExists) {
                    $createDirCommand = "megamkdir -u $megaUsername -p $megaPassword '$projectPath'";
                    exec($createDirCommand . ' 2>&1', $dirOutput, $dirStatus);
                    file_put_contents(WRITEPATH . 'logs/mega.log', "Create Dir Output:\n" . implode("\n", $dirOutput) . "\n", FILE_APPEND);

                    if ($dirStatus !== 0) {
                        $session->setFlashdata('error', 'Failed to create project directory in MEGA.');
                        return redirect()->to('file/upload');
                    }
                }
            } else {
                $session->setFlashdata('error', 'Failed to check MEGA directories.');
                return redirect()->to('file/upload');
            }

    
            // Upload the file to the project directory
            $megaCommand = "megaput -u $megaUsername -p $megaPassword --path '$projectDir' '$filePath'";
            exec($megaCommand . ' 2>&1', $uploadOutput, $uploadStatus);
    
            if ($uploadStatus === 0) {
                $session->setFlashdata('success', 'File uploaded successfully to MEGA.');
            } else {
                $session->setFlashdata('error', 'File could not be uploaded to MEGA. Command output: ' . implode("\n", $uploadOutput));
            }
    
            // Clean up the file after upload
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        } else {
            $session->setFlashdata('error', 'Failed to upload file.');
        }
    
        return redirect()->to('file/upload');
    }               
    
}