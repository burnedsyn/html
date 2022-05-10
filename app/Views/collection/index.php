
<?= $this->extend('Views/collection/layout') ?>
<?= $this->section('content') ?>
 <!-- Script -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  
<script type="text/javascript">
function sendMessage(id){
  $.ajax({
     url:'/collection/tim',
     method: 'post',
     async: 'false',
     data: 'id',
     success: function(response){
       
         // Read values
          var titi=JSON.parse(response);
          
          if(titi.message != null) {
            var info = titi.message;
            addLog('<h2>Scene to go '+info.maxCall+'</h2>');
            var tempdata;
            tempdata='';
            <?php
              $config=config('NftConfig');
              $layers=$config->layers;
              $size=$config->nftCollectionSize;
            ?>
            var count=0;
            var toto=<?= $size ?> 
            tempdata+='<table style="background-color:white" >';
            if(info.error !== undefined )
            tempdata+= '<tr style="background-color:red; color:white;"><td>error</td><td>' + info.error + '</td></tr>';
            <?php foreach ($layers as $item): ?>
              count++;
              if (count%2==0) color='cyan'; else color='white'
              if(info.<?= $item ?> !== undefined) tempdata+='<tr style="background-color:'+color +'"><td><?= $item ?> </td><td>' + info.<?= $item ?>+'</td></tr>';

            <?php endforeach ?>
              count++;
              if (count%2==0) color='cyan'; else color='white'
              tempdata+='<tr style="background-color:'+color +'"><td> clearDna </td><td> '+info.clearDna+'</td></tr>';
              count++;
              if (count%2==0) color='cyan'; else color='white';
              tempdata+='<tr style="background-color:'+color +'"><td> dna </td><td> '+info.dna+'</td></tr>';
              count++;
              if (count%2==0) color='cyan'; else color='white';
              tempdata+='<tr style="background-color:'+color +'"><td> file signature</td><td>'+info.sig+'</td></tr>';       
              count++;
              if (count%2==0) color='cyan'; else color='white';
              tempdata+='<tr style="background-color:'+color +'"><td> Saved In db</td><td>'+info.saved+'</td></tr>'; 
              count++;
              if (count%2==0) color='cyan'; else color='white';
              tempdata+='<tr style="background-color:'+color +'"><td> provenancestring</td><td>'+info.provenancestring+'</td></tr>'; 
              count++;
              if (count%2==0) color='cyan'; else color='white';
              tempdata+='<tr style="background-color:'+color +'"><td> Creation Date</td><td>'+info.creationDate+'</td></tr>'; 
              count++;
              if (count%2==0) color='cyan'; else color='white';
              tempdata+='<tr style="background-color:'+color +'"><td> Image Path</td><td>'+info.imagePath+'</td></tr>'; 
              count++;
              if (count%2==0) color='cyan'; else color='white';
              tempdata+='<tr style="background-color:'+color +'"><td> Signature Path</td><td>'+info.signatureFile+'</td></tr>'; 
              count++;
              if (count%2==0) color='cyan'; else color='white';
              tempdata+='<tr style="background-color:'+color +'"><td> signature validity</td><td>'+info.validitySignature+'</td></tr>';
              count++;
              if (count%2==0) color='cyan'; else color='white';
              tempdata+='<tr style="background-color:'+color +'"><td> Json informations</td><td>'+info.jsonFile+'</td></tr>';  
              count++;
              if (count%2==0) color='cyan'; else color='white';
              tempdata+='<tr><td colspan="2" style="text-align: center; vertical-align: middle;"><img width="10%" height="auto" id="'+info.maxCall+'" src="'+info.imagePath+'"></td></tr>'; 

            tempdata+='</table>';
            addLog(tempdata);
            
            




            //..........
            var testpercent=parseFloat(titi.progress)*100;
            
            var pBar = document.getElementById('progressor');
             pBar.value = pBar.value+(testpercent);
             var perc = document.getElementById('percentage');
             var totperc = perc.innerHTML;
             var localtot=(parseInt(totperc)  +  parseInt(testpercent));
             perc.innerHTML   = localtot + "%" ;
             perc.style.width = (Math.floor(pBar.clientWidth * testpercent) + 15) + 'px';

             var scrollIntoViewOptions = { behavior: "smooth", block: "center" };   
             document.getElementById(info.maxCall).scrollIntoView(scrollIntoViewOptions); 
             if(info.operation =='complete') stopTask(info); else sendMessage(1);

           //............
          }
          
       
 
     }
   });

}
var myInterval;

 function startTask() {
  
 sendMessage(1);

        
   
}   
    
  
 function stopTask(info) {
     
     addLog('Task complete');
     var tempstr='<h3>provenanceCumulativeString<h3>';
     tempstr+='<textarea id="provenanceCumulativeString" name="provenanceCumulativeString" rows="5" cols="127">'+info.provenanceCumulativeString +'</textarea><hr>';
     tempstr+='<div>Recorded cumulative string hash : '+info.provenanceCumulativeHash +' </div><hr>';

     addLog(tempstr);

 }
  
 function addLog(message) {
     var r = document.getElementById('results');
     r.innerHTML += message + '<br>';
     r.scrollTop = r.scrollHeight;
 } 
 
 </script>

<h1>Collection</h1>
<?= $welcome?>

<br />
        <input type="button" onclick="startTask();"  value="Generate NFTs" />
        <input type="button" onclick="stopTask();"  value="Stop Task" />
        <br />
        <br />
         
        <p>Results</p>
        <br />
        <div id="results" ></div>
        <br />
       
        <progress id='progressor' value="0" max='100' style=""></progress>  
        <span id="percentage" style="text-align:leftright; display:block; margin-top:5px;">0</span>

<?= $this->endSection() ?>