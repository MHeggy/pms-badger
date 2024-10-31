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
            $filePath = WRITEPATH . 'uploads/' . $file->getName(); // Changed to getName() for original file name
            $file->move(WRITEPATH . 'uploads/'); // Move file without generating a random name

            // Check if MEGA is already logged in (optional, depending on your use case)
            // If not, you can login here or handle it separately

            // Prepare and execute MEGAcmd upload command
            $megaCommand = "mega-put '$filePath' /"; // Uploads to MEGA's root directory
            exec($megaCommand . ' 2>&1', $output, $status); // Capture output for error handling

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
            $session->setFlashdata('error', 'Failed to upload file. The file may not be valid or may have already been moved.');
        }

        return redirect()->to('file/upload');
    }    
}