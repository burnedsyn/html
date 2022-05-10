<?php

namespace App\Controllers;
use App\Libraries\TarotCard;


ini_set('max_execution_time', 0); // 0 = Unlimited      
date_default_timezone_set('Europe/Brussels');
class UserController extends BaseController
{        
     public $cardCollection=[];   
     public $db;
     public $privKey;
     public $pubkeyid;
     /* 
     * InitImage permet de générer les layers a partir des images en ressources
     * 
     * */
    function initImage(string $source, string $format) {
       //on crée les objets images des layers de la scène finale      
        $im = new \Imagick();
        $im->setFormat($format);
        $im->readImage($source);

        return $im;
    }
   
  
 

public function verifScene(array $card, TarotCard $baseCard){
    $config=config('NftConfig');
    $adn='';
    foreach($config->layers as $layer){
        if(is_dir($card[$layer]) || is_null($card[$layer])){
            echo("<hr> Bad layer $layer <br> here : ".$card[$layer]."<br>Restoring $layer<br>");
                $tmppath=$card[$layer];
                while(is_dir($tmppath) || is_null($tmppath)) {
                    if(is_null($tmppath)) $tmppath=$config->ressourcePath.$layer;
                    $card[$layer]=$baseCard->getOne($tmppath);     
                    $tmppath=$card[$layer];
                } //fin while
            echo("layer $layer restored with : ".$card[$layer]."<br>");
                 
        } //fin if is dir or is null

        $adn=$adn.$card[$layer]; 
        
        $card["clearDna"] =$adn;
        
        $card["dna"]=hash('sha512',$adn);
        
    } //fin foreach configlayer
    
    return $card;
}//fin verifScene


    public function index()
    {
       helper('filesystem');
       $config=config('NftConfig');
       $baseCard=new TarotCard();
       $count=intval($config->nftCollectionSize);
       $_SESSION['lastCard']=array();
       $this->privKey=openssl_pkey_get_private('file://./priv.key');
       
       $this->pubkeyid = openssl_pkey_get_public("file://./pub.key");
       for($i=0; $i < $count ; $i++) {
            
        $this->cardCollection[]=$baseCard->getCard();

       }//fin boucle edition size
       $i=0;
       foreach($this->cardCollection as $card){
           //on boucle sur les layer dans la carte
           $adn='';
        
        foreach($config->layers as $layer){
            $tmppath=$card[$layer];
            if(is_dir($tmppath) || is_null($tmppath)) $card[$layer]=$baseCard->getOne($tmppath);          
            $adn=$adn.$card[$layer];

        }
        $this->cardCollection[$i]+=["clearDna" => $adn ];
        $adn=hash('sha512',$adn);
        $this->cardCollection[$i]+=["dna" => $adn];
        $i++;
       }

       $this->db = \Config\Database::connect();
       $builder = $this->db->table('cards');
       $i=0;
       
       $provenanceCumulativeString="";
       
       foreach($this->cardCollection as $card)
       {            
           $images=array();
           $imgformat=$config->imgformat;
           //ici modif verif finale
           $card=$this->verifScene($card,$baseCard);
           $this->cardCollection[$i]=$card;
           foreach($config->layers as $layer) {

               $images[]= $this->initImage($card[$layer],$imgformat);

           }                         
           $card["sig"]=null;
          
           
           $query = $builder->where(['dna' => $card['dna']]);

           
           
           $test=$query->countAllResults();

           
                                            
             while (!empty($test)) {
               
                echo "<h1>DNA EXIST !!!</h1><p>generating new scene</p><hr>";
                $images = null;
                $card = $baseCard->getCard();
                $card = $this->verifScene($card, $baseCard);
                echo("<p>New scene generated..<br> Verifying dna :".$card['dna']."</p>");
                foreach ($config->layers as $layer) {

                    $images[] = $this->initImage($card[$layer], $imgformat);
                }
                $this->cardCollection[$i] = $card;
                $query = $builder->Where(['dna' => $card['dna']]);
                $test=$query->countAllResults();


            }  
            
           if($builder->insert($card)){
               // here we got a unic adn stored in db we create the image in  build/images directory
               echo("added : ".$card['dna']."<hr>");
               //adding dna to cumulative provenance hash
               $provenanceCumulativeString.=$card['dna'];
               $provenanceCumulativeString2=hash('sha512',$provenanceCumulativeString);
               $card+=["provenancestring" => $provenanceCumulativeString2];
               $elem=count($images);
               $baseimage=$images[0];
               for($k=1; $k <=$elem-1; $k++){
                   $baseimage->compositeImage($images[$k],$images[$k]->getImageCompose(), 0, 0 );
               }
               
               $pathtoimg="./build/images/";
                          

               $baseimage->writeImage($pathtoimg.$i.".png");

               $card["sig"]=hash_file('sha3-512', $pathtoimg.$i.".png");
               $card["creationDate"]=date("Y-m-d H:i:s");
               $card["imagePath"]="/build/images/".$i.".png";
               openssl_sign($card['sig'], $hashSig, $this->privKey);
               file_put_contents('./build/imgsig/'.$i.'.dat', $hashSig);
               $verifSigData=file_get_contents('../public/build/imgsig/'.$i.'.dat');

               // indique si la signature est correcte
                $ok = openssl_verify($card['sig'],$verifSigData, $this->pubkeyid);
                if ($ok == 1) {
                    echo "Signature valide";
                    echo ("<h4>" . $card['sig'] . "</h4>");
                    $card['signatureFile'] = '/build/imgsig/' . $i . '.dat';
                } elseif ($ok == 0) {
                    echo "Signature erronée";
                } else {
                    echo "Erreur de vérification de la signature";
                }               //little tric to save the json path in the json file before being certain that it is written see the if not
                $card['jsonFile']='/build/cardinfo/'.$i.'.json';

               $card2=print_r($card,true);
               $card2=json_encode($card2);
               if ( ! write_file('./build/cardinfo/'.$i.'.json', $card2)) {
                echo 'Unable to write the file ./build/cardinfo/'.$i.'.json';
                $card['jsonFile']=null;
            } else {
                    echo '<p>json data card File written!</p>';
                }
            
               $this->cardCollection[$i]=$card;
               
                
               $i++;
               
          }else{
            echo("<hr>ICI ERROR<hr>");
            echo $this->db->error();
            continue;
          } 
          ;
       }

        $data['cumulativeString']=$provenanceCumulativeString;
        $data['provenanceCumulativeHash']=hash("sha512",$provenanceCumulativeString);
        $data['sitename']= $config->siteName;
        $data['cardCollection']=$this->cardCollection;
        
        return view('Views/user', $data);
        //return view('Views/user');
    }

    
}
