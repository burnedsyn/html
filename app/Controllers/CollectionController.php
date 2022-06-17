<?php

namespace App\Controllers; 


date_default_timezone_set('Europe/Brussels');
use App\Controllers\BaseController;
use App\Models\Collection;
use App\Models\Cards;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\SceneManager;
use PhpParser\Node\Stmt\TryCatch;

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
      
      $this->collection= new Collection();
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
    
        
     $this->send_message($message , $progress); 
        
        
    }
    public function createJsonFile() {
      $config = config('NftConfig');
      $message=[];
      $imgData2Json=[];
      $progress=0;
      $maxfiles=intval($config->nftCollectionSize);
      helper('filesystem');
      $collectionId=$this->request->getPost("id");
        $collectiontable=new Collection();
        $collection=$collectiontable->get_collection_info($collectionId);
        $imgData2Json['name']='tempname';
        $imgData2Json+=['description'=> $collection['description']];

        echo("Creating json file for collection id : ".$collection['id']);

      for($i=0; $i < $maxfiles; $i++){
        $string = file_get_contents("./build/cardinfo/".$i.".json");
        $imgData=json_decode($string,true);
        
        echo("<h2>New element here</h2>");
        $imgData2Json['dna']=$imgData['dna'];
        $imgData2Json['rarity']='common';
        foreach($config->layers as $layer)
        {
          $temppath='';
         //echo($imgData[$layer]."<hr>");
         $temppath=$imgData[$layer];
         $temp=explode($layer,$temppath);
         $temp1=explode('/',$temp[1]);
         $typefull=strval(($temp1[1]));
         $itemfull=strval($temp1[2]);
         
         $temptype=explode('#',$typefull);
         $tempitem=explode('#',$itemfull);
         $type=strval($temptype[0]);
         if ($type==='rarity')$imgData2Json['rarity']=$type;
         $item=strval($tempitem[0]);
         $imgData2Json['attributes'][$layer]=['type' => "$type",'item' => "$item"];
         if($layer == 'Card'){
           $itemtmp=explode('_',$item);
           $imgData2Json['attributes']['cardName']=$itemtmp[1];
           $imgData2Json['attributes']['cardType']=$type;
           $imgData2Json['attributes']['cardOrientation']=isset($itemtmp[2])? 'reverse':'upright' ;
         }
         
        }//fin foreach
        /*
        * here we have worked out all  layer as the type common or rarity and item the filename minus rarity factor and extension
        now we begin to format the json file for this particular scene and write it out.
        */
        $imgData2Json['image']=$collection['imagesCid']."/".$i.".".$config->imgformat;
        $imgData2Json['imageSignature']=$collection['signaturesCid']."/".$i.".dat";

        
        print_r($imgData2Json);
        
       


      } //fin for each json file




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
      $coll['signaturesCid']=$this->request->getPost('signaturesCid');
      try {
        
        if($builder->insert($coll)) {

          $coll['id']=$db->insertID();
          $message['message']='Collection created';
          $message['collection']=$coll;
          $progress=100;
        }

      }
      catch (\Exception $e) {
        $message="Error while inserting datas in db".$e;
        $progress=0;
      }
      
     
      
      $this->send_message($message , $progress);
       

    }
    

    public function send_message($message, $progress) {
       
        
      $d = array('message' => $message , 'progress' => $progress);
      //$this->respond($d, 200);
      echo(json_encode($d,true));
    } 

}
    



