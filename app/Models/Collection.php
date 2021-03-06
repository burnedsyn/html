<?php

namespace App\Models;

use CodeIgniter\Model;

class Collection extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'collections';
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
        if ($this->db->tableExists('collection')) {
           //nothing to do
           
        } 
        else {
            echo("<h1>createtable collection</h1>");
            $forge = \Config\Database::forge();
            $fields = [
                
                'title'       => [
                    'type'           => 'VARCHAR',
                    'constraint'     => '512',
                    
                ],                
                'description' => [
                    'type'           => 'TEXT',
                    'null'           => true,
                ],
                'status'      => [
                    'type'           => 'ENUM',
                    'constraint'     => ['publish', 'pending', 'draft'],
                    'default'        => 'draft',
                ],
                'cumulativeHash' => [
                 'type' => 'varchar',
                'constraint' =>'128',
                ],
                'provenanceCumulativeString' =>[
                    'type'           => 'TEXT',
                    'null'           => true,

                ],
                'imagesCid'=>[
                    'type' => 'VARCHAR',
                    'constraint' => '2083',
                    'null' => true,
                ],
                'signaturesCid'=>[
                    'type' => 'VARCHAR',
                    'constraint' => '2083',
                    'null' => true,
                ],
                'createdAt datetime default now()',
                'updatedAt datetime default now() on update now()',
                'deletedAt datetime DEFAULT NULL',
            ];
            $forge->addField('id');
            $forge->addField($fields);
            try {
                
                $forge->createTable('collection', true);
              }
              catch (\InvalidArgumentException $ex) { // "$ex" is required
                
                echo("<h1>Table collection NOT OK</h1> error : ".$ex);
              }
            
            
        }
        
        $this->allowedFields[] = ['title','description','status','cumulativeHash','provenanceCumulativeString','imagesCid','createdAt','updatedAt','deletedAt'];
    }

    function get_collection_info($collection_id) {
        $query = $this->db->query("SELECT * FROM collection WHERE id = ?", array($collection_id));
        return $query->getRowArray();
    }
}
