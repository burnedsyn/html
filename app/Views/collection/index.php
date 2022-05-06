
<?= $this->extend('Views/collection/layout') ?>
<?= $this->section('content') ?>
 <!-- Script -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  
<script type="text/javascript">
function sendMessage(){
  $.ajax({
     url:'/collection/tim',
     method: 'post',
     success: function(response){
       var len = response.length;
      
       if(len > 0){
         // Read values
          var titi=JSON.parse(response);
          addLog(titi.message);


          //..........

            var pBar = document.getElementById('progressor');
             pBar.value += pBar.value+(titi.progress/100);
             var perc = document.getElementById('percentage');
             perc.innerHTML   = titi.progress/100  + "%";
             perc.style.width = (Math.floor(pBar.clientWidth * (titi.progress/100)) + 15) + 'px';

           //............
         
          
       }
 
     }
   });

}
var myInterval;

 function startTask() {
  
 
  myInterval=setInterval( "sendMessage()", 1000 );

        
   
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
        <div id="results" style="border:1px solid #000; padding:10px; width:300px; height:250px; overflow:auto; background:#eee;"></div>
        <br />
         
        <progress id='progressor' value="0" max='100' style=""></progress>  
        <span id="percentage" style="text-align:right; display:block; margin-top:5px;">0</span>

<?= $this->endSection() ?>