<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model {
    protected $table = 'address';
    protected $primaryKey = 'addressID';
    protected $allowedFields = ['street', 'city', 'stateID', 'zipCode', 'countryID'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'street' => 'required|string|max_length[255]',
        'city' => 'required|string|max_length[255]',
        'stateID' => 'required|integer',
        'zipCode' => 'required|string|max_length[20]',
        'countryID' => 'required|integer'
    ];
}