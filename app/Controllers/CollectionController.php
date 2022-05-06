<?php

namespace App\Controllers; 


date_default_timezone_set('Europe/Brussels');
use App\Controllers\BaseController;
use App\Models\Collection;
use App\Models\Cards;
use PHPUnit\Util\Json;

class CollectionController extends BaseController

{   
    
    protected $collection;
    
    public function index()
    {    
        $test=new Cards();
        $cards=$test->findAll();
        //$cards=null;
        /* $table = new \CodeIgniter\View\Table();
        $test2=$table->generate($cards); 
        $this->collection=new Collection();,"Cards"=>$cards*/


        $data=["welcome"=>"Collection Manager"];
        return view('Views/collection/index', $data);
     

    }
 
    public function tim()
    {
      // ...
     
      if(!empty($_SESSION['lastCard'])) {
        $message=$_SESSION['lastCard'];
       // $_SESSION['lastCard'][]='';
        $this->send_message($message , 10); 
      
        
    }
    
  }

  public function send_message($message, $progress) {
       
        
    $d = array('message' => $message , 'progress' => $progress);

    echo(json_encode($d,true));
    flush();
    ob_flush();
    ob_end_flush();
    

  
}

   }
    



