<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model {
    protected $table = 'pcategories';
    protected $primaryKey = 'categoryID';
    protected $allowedFields = ['categoryName', 'description'];

    public function __construct() {
        parent::__construct();
    }

    // fetch all categories
    public function getCategories() {
        return $this->findAll();
    }
}