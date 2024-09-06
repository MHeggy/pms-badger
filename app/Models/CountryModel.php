<?php

namespace App\Models;

use CodeIgniter\Model;

class CountryModel extends Model {
    protected $table = 'countries';
    protected $primaryKey = 'countryID';
    protected $allowedFields = ['countryName', 'continent'];
}
