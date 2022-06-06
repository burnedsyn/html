<?php

namespace App\Controllers; 


date_default_timezone_set('Europe/Brussels');
use App\Controllers\BaseController;
use App\Models\Collection;
use App\Models\Cards;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\SceneManager;


class CollectionController extends BaseController

{   
    use ResponseTrait;
    protected $collection;
    
    public function index()
    {    
      $config = config('NftConfig');
      $maxCall=intval($config->nftCollectionSize);
      $_SESSION['maxCall']=$maxCall;
      $_SESSION['provenanceCumulativeString']='';
       
        $data=["welcome"=>"Collection Manager"];
        return view('Views/collection/index', $data);
     

    }
 
    public function tim()
    { 
      $start_time = microtime(TRUE);
      $config = config('NftConfig');
      $message=[];
      $temp= new SceneManager();
      $message=$temp->getScene();
      if ($message !=''){
        
        $progress=1/intval($config->nftCollectionSize);
       
      }
      else {
        $message='No Change';
        $progress=0;
       } 
     $end_time = microtime(TRUE);
     $message['totalTime']=$end_time-$start_time;
        
     $this->send_message($message , $progress); 
        
        
    }
    
    public function postGeneration() {
      //get POST formdata and create the collection in the db. then create the final json file with cid and all stuff.
      $db      = \Config\Database::connect();
      $builder = $db->table('collection');

      $coll=[];
      $coll['id']=null;
      $coll["provenanceCumulativeString"]= $this->request->getPost("provenanceCumulativeString");
      $coll['title']= $this->request->getPost("title");
      $coll['cumulativeHash']=  $this->request->getPost("cumulativeHash");
      
      $coll['status']='draft';
      $coll['description']=$this->request->getPost("description");
      $coll['imagesCid']=$this->request->getPost('imagesCid');
      
      $builder->insert($coll);
      $message='No Change cid :'.$coll['imagesCid'];
      $progress=0;
      $this->send_message($message , $progress);
       

        }

  public function send_message($message, $progress) {
       
        
    $d = array('message' => $message , 'progress' => $progress);
    //$this->respond($d, 200);
    echo(json_encode($d,true));
    
    
    
    
    
  
}

   }
    



