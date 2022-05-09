
<?= $this->extend('Views/collection/layout') ?>
<?= $this->section('content') ?>
 <!-- Script -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  
<script type="text/javascript">
function sendMessage(id){
  $.ajax({
     url:'/collection/tim',
     method: 'post',
     data: 'id',
     success: function(response){
       
         // Read values
          var titi=JSON.parse(response);
          addLog('on a une carte');
          if(titi.message != null) {
            var info = titi.message;
            var tempdata;
            tempdata='';
            <?php
              $config=config('NftConfig');
              $layers=$config->layers;
            ?>
            var count=0;
            tempdata+='<table style="background-color:white">';
            if(info.error !== undefined )
            tempdata+= '<tr style="background-color:red; color:white;"><td>' + info.error + '</td></tr>';
            <?php foreach ($layers as $item): ?>
              count++;
              if (count%2==0) color='cyan'; else color='white'
              if(info.<?= $item ?> !== undefined) tempdata+='<tr style="background-color:'+color +'"><td><?= $item ?> :' + info.<?= $item ?>+'</td></tr>';

            <?php endforeach ?>
            tempdata+='<tr><td> clearDna : '+info.clearDna+'</td></tr>';
            tempdata+='<tr><td> dna : '+info.dna+'</td></tr>';
                      
            
            tempdata+='</table>';
            addLog(tempdata);
            if(info.operation =='complete') stopTask();
            




            //..........

            var pBar = document.getElementById('progressor');
             pBar.value = pBar.value+(titi.progress);
             var perc = document.getElementById('percentage');
             var totperc = perc.innerHTML;
             perc.innerHTML   = parseInt(totperc)  +  parseInt(titi.progress) + "%" ;
             perc.style.width = (Math.floor(pBar.clientWidth * (titi.progress)) + 15) + 'px';

           //............
          }
          
       
 
     }
   });

}
var myInterval;

 function startTask() {
  
 
  myInterval=setInterval( "sendMessage(1)", 1000);

        
   
}   
    
  
 function stopTask() {
  if (typeof myInterval != 'undefined') clearTimeout(myInterval);
     clearInterval(myInterval);
     addLog('Interrupted');
 }
  
 function addLog(message) {
     var r = document.getElementById('results');
     r.innerHTML += message + '<br>';
     r.scrollTop = r.scrollHeight;
 } 
 
 </script>

<h1>Collection</h1>
<?= $welcome?>
<?php if(isset($Cards) && $Cards != null):?>
<ul>

<?php foreach ($Cards as $item): ?>

    <li><?= $item['dna'] ?></li>

<?php endforeach ?>

</ul>
<?php endif ?>
<br />
        <input type="button" onclick="startTask();"  value="Start Long Task" />
        <input type="button" onclick="stopTask();"  value="Stop Task" />
        <br />
        <br />
         
        <p>Results</p>
        <br />
        <div id="results" ></div>
        <br />
         
        <progress id='progressor' value="0" max='100' style=""></progress>  
        <span id="percentage" style="text-align:right; display:block; margin-top:5px;">0</span>

<?= $this->endSection() ?>