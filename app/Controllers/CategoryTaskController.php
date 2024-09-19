<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\TaskModel;
use CodeIgniter\Controller;

class CategoryTaskController extends Controller {
    protected $taskModel;
    protected $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
        $this->taskModel = new TaskModel();
    }

    public function index() {
        $data['categories'] = $this->categoryModel->getCategories();
        $data['tasks'] = $this->taskModel->getTasks();
        return view('PMS/category_tasks', $data);
    }


    public function addCategory() {
        $data = [
            'categoryName' => $this->request->getPost('categoryName'),
            'description' => $this->request->getPost('description')
        ];

        $this->categoryModel->insert($data);
        return redirect()->to('/categories-tasks')->with('success', 'Category added successfully!');
    }

    public function addTask() {
        $data = [
            'taskName' => $this->request->getPost('taskName')
        ];

        $this->taskModel->insert($data);
        return redirect()->to('/categories-tasks')->with('success', 'Task added successfully!');
    }
}