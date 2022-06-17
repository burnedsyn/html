<?php

namespace App\Libraries;

helper('filesystem');


class SceneManager
{

    public $layers;
    public $config;
    public $ressourcePath;
    public $db;
    public $privKey;
    public $pubkeyid;
    public $message;
    public $collection;
    public $provenanceCumulativeString;
    public function __construct()
    {
        $this->config = config('NftConfig');
        $this->layers = $this->config->layers;
        $this->ressourcePath = $this->config->ressourcePath;
        $this->message=array();
        $this->collection =array();
        $this->privKey=openssl_pkey_get_private('file://./priv.key');
        $this->pubkeyid = openssl_pkey_get_public("file://./pub.key");
        $this->provenanceCumulativeString="";

    } // fin construct

    public function getOne($ressourcesPath)
    {
        //donne un élément de layer un élément unique et avec la rareté

       
        if (is_dir($ressourcesPath)) {
            $layer_info = get_dir_file_info($ressourcesPath);
        } // ressource path est un repertoire.

        $currentProb = random_int(0, 100);

        foreach ($layer_info as $currentDir => $key) {
            $test = print_r($currentDir, true);

            $currentDirProb = explode("#", $test);
            $currentDirRarity = $currentDirProb[1];
            $currentProb -= intval($currentDirRarity);
            if ($currentProb < 1) {
                $relative_path = print_r($key['relative_path'], true);


                $testingPath = $relative_path . "/" . $test;

                return $testingPath;
                break;
            } //fin if
            else {
                continue;
            }
        } //fin foreach

    } // fin getOne

    function initImage(string $source, string $format) {
        //on crée les objets images des layers de la scène finale      
         $im = new \Imagick();
         $im->setFormat($format);
         $im->readImage($source);
 
         return $im;
     }

     public function verifScene(array $card){
        $config=config('NftConfig');
        $adn='';
        foreach($config->layers as $layer){
            if(is_dir($card[$layer]) || is_null($card[$layer])){
                $card+=['error'=>"<hr> Bad layer $layer <br>Restoring $layer<br>"];
                    $tmppath=$card[$layer];
                    while(is_dir($tmppath) || is_null($tmppath)) {
                        if(is_null($tmppath)) $tmppath=$config->ressourcePath.$layer;
                        $card[$layer]=$this->getOne($tmppath);     
                        $tmppath=$card[$layer];
                    } //fin while
                    $card['error'].="layer $layer restored with  ".$card[$layer]."<br>";
                     
            } //fin if is dir or is null
    
            $adn=$adn.$card[$layer]; 
            
            $card["clearDna"] =$adn;
            
            $card["dna"]=hash('sha512',$adn);
            
        } //fin foreach configlayer
        
        return $card;
    }//fin verifScene

    public function getScene()
    {

        /*
    ** ici on selectionne les éléments de l'image finale on compile l'image
    */

        $scene = [];
        $test=1;
        $start=1;
        $this->db = \Config\Database::connect();
        $builder = $this->db->table('cards');
        $errorDna=0;
        $tempErrorString='';
        
        while (!empty($test)) {
            if($test===1 && $start===1) $start--;
            
            foreach ($this->layers as $currentLayer) {
                $pathtoscan = $this->ressourcePath . $currentLayer;
                $scene[$currentLayer]=$this->getOne($pathtoscan);
            } // fin foreach layer
    
            //create dna of this scene
            $adn='';
            foreach($this->layers as $layer){
                $tmppath=$scene[$layer];
                if(is_dir($tmppath) || is_null($tmppath)) $scene[$layer]=$this->getOne($tmppath);          
                $adn=$adn.$scene[$layer];
    
            }
            
            $scene=$this->verifScene($scene);
            $query = $builder->where(['dna' => $scene['dna']]);
            $test=$query->countAllResults();
            $errorDna+=$test-$start;
            if($errorDna > 0){
                
                $tempErrorString.="<hr> Error Dna Exist <br>Restoring scene<br> dna ".$scene['dna']."<br>";
            }
                        


        } 
        if($tempErrorString != ''){
            if(isset($scene['error'])) $scene['error'].=$tempErrorString; else $scene['error']=$tempErrorString;
        }
        
        //as we have a verified scene and thus all data for layer are ok we build the image
        $scene["sig"]=null;
        $scene["errorDna"]=$errorDna; 
        

        $images=array();
        $imgformat=$this->config->imgformat; 
        foreach($this->layers as $layer) {

            $images[]= $this->initImage($scene[$layer],$imgformat);

        } 
        $card=$scene;
        unset($card['errorDna']);
        unset($card['error']);
        if($builder->insert($card)){
            $scene['saved']='ok';
            $scene['id']=$this->db->insertID();
            $this->provenanceCumulativeString.=$scene['dna'];
            $_SESSION['provenanceCumulativeString'].=$this->provenanceCumulativeString;
            $provenanceCumulativeString2=hash('sha512',$this->provenanceCumulativeString);
            $scene["provenancestring"] = $provenanceCumulativeString2;
            $elem=count($images);
               $baseimage=$images[0];
               for($k=1; $k <=$elem-1; $k++){
                $baseimage->compositeImage($images[$k],$images[$k]->getImageCompose(), 0, 0 );
            }
            
            $pathtoimg="./build/images/";
            $i=intval($this->config->nftCollectionSize) - intval($_SESSION['maxCall']);
           //$baseimage->writeImage($pathtoimg.$i.".png");
            if(!$baseimage->writeImage($pathtoimg.$i.".png")) die('error writing image file');
            $scene["sig"]=hash_file('sha3-512', $pathtoimg.$i.".png");
               $scene["creationDate"]=date("Y-m-d H:i:s");
               $scene["imagePath"]="/build/images/".$i.".png";
               openssl_sign($scene['sig'], $hashSig, $this->privKey);
               file_put_contents('./build/imgsig/'.$i.'.dat', $hashSig);
               $verifSigData=file_get_contents('../public/build/imgsig/'.$i.'.dat');
               // indique si la signature est correcte
               $ok = openssl_verify($scene['sig'],$verifSigData, $this->pubkeyid);
               if ($ok == 1) {
                   $scene['validitySignature']= "confirmed";
                   $scene['signatureFile'] = '/build/imgsig/' . $i . '.dat';
               } elseif ($ok == 0) {
                $scene['validitySignature']= "invalid file";
               } else {
                $scene['validitySignature']= "error while verifying this file";
               }               //little tric to save the json path in the json file before being certain that it is written see the if not
               $scene['jsonFile']='/build/cardinfo/'.$i.'.json';
               $card2=$scene;
               
               $card2=json_encode($card2);
               if ( ! write_file('./build/cardinfo/'.$i.'.json', $card2)) {
               
                $card['jsonFile']=null;
                die('error writing json');
            } 

        }



               
        $scene['maxCall']=$_SESSION['maxCall'];
        $_SESSION['maxCall']--;
        if ($_SESSION['maxCall']<=0) {
            $scene['operation']='complete';
            $scene['provenanceCumulativeString']= $_SESSION['provenanceCumulativeString'];
            $scene['provenanceCumulativeHash']=hash("sha512",$_SESSION['provenanceCumulativeString']);

        }
        
        return $scene;
    } //fin getScene

}
