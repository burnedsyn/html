<?php

namespace App\Models;

use CodeIgniter\Model;

class Cards extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'cards';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'createdAt';
    protected $updatedField  = 'updatedAt';
    protected $deletedField  = 'deletedAt';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected $db;

    protected function initialize()
    {
        $this->db = \Config\Database::connect();
        if ($this->db->tableExists('cards')) {
            // some code...
           
        } 
        else {
            echo("<h1>createtable cards</h1>");
            $forge = \Config\Database::forge();
            $fields = [
                'id',
                'collection int unsigned DEFAULT NULL',                
                'background text NOT NULL',
                'border text NOT NULL',
                'card text NOT NULL',
                'dna varchar(255) NOT NULL',
                'clearDna text default null',
                'sig text DEFAULT NULL',
                'createdAt datetime default now()',
                'updatedAt datetime default now() on update now()',
                'deletedAt datetime DEFAULT NULL',
                'UNIQUE KEY dna (dna)'                
            ];
            $forge->addField($fields);
            try {
                // try something
                $forge->createTable('cards', true);
              }
              catch (\Exception $ex) { // "$ex" is required
                // handle the exception
                echo("<h1>Table collection NOT OK</h1>");
              }

            
            
        }
        
        $this->allowedFields[] = ['collection','background','border','card','dna','clearDna','sig'];
    }
}
