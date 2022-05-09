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
    public function __construct()
    {
        $this->config = config('NftConfig');
        $this->layers = $this->config->layers;
        $this->ressourcePath = $this->config->ressourcePath;
        $this->message=array();
        $this->collection =array();
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
        
        //verif conformity of this scene
        $scene=$this->verifScene($scene);

        $images=array();
        $imgformat=$this->config->imgformat; 
        foreach($this->layers as $layer) {

            $images[]= $this->initImage($scene[$layer],$imgformat);

        }                    
               
        $scene['maxCall']=$_SESSION['maxCall'];
        $_SESSION['maxCall']--;
        if ($_SESSION['maxCall']==0) $scene['operation']='complete';
        
        return $scene;
    } //fin getScene

}
