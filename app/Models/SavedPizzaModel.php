<?php

namespace App\Models;

use CodeIgniter\Model;

class SavedPizzaModel extends Model
{
    protected $table            = 'saved_pizzas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'name', 'configuration', 'created_at'];

    protected $useTimestamps = false; // We use DEFAULT CURRENT_TIMESTAMP in DB, or can enable this if we want CI to handle it
    
    // Validation
    protected $validationRules      = [
        'user_id' => 'required|integer',
        'name'    => 'required|min_length[3]|max_length[255]',
        'configuration' => 'required'
    ];
}
