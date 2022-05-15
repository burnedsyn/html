<?= $this->extend('Views/collection/layout') ?>
<?= $this->section('content') ?>
<!-- Script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script type="text/javascript">
  function sendMessage(id) {
    $.ajax({
      url: '/collection/tim',
      method: 'post',
      async: 'false',
      data: 'id',
      success: function(response) {

        // Read values
        var titi = JSON.parse(response);

        if (titi.message != null) {
          var info = titi.message;
          addLog('<h2>Scene to go ' + info.maxCall + '</h2>');
          var tempdata;
          tempdata = '';
          <?php
          $config = config('NftConfig');
          $layers = $config->layers;
          $size = $config->nftCollectionSize;
          ?>
          var count = 0;
          var toto = <?= $size ?>
          tempdata += '<table style="background-color:white" >';
          if (info.error !== undefined)
            tempdata += '<tr style="background-color:red; color:white;"><td>error</td><td>' + info.error + '</td></tr>';
          <?php foreach ($layers as $item) : ?>
            count++;
            if (count % 2 == 0) color = 'cyan';
            else color = 'white'
            if (info.<?= $item ?> !== undefined) tempdata += '<tr style="background-color:' + color + '"><td><?= $item ?> </td><td>' + info.<?= $item ?> + '</td></tr>';

          <?php endforeach ?>
          count++;
          if (count % 2 == 0) color = 'cyan';
          else color = 'white'
          tempdata += '<tr style="background-color:' + color + '"><td> clearDna </td><td> ' + info.clearDna + '</td></tr>';
          count++;
          if (count % 2 == 0) color = 'cyan';
          else color = 'white';
          tempdata += '<tr style="background-color:' + color + '"><td> dna </td><td> ' + info.dna + '</td></tr>';
          count++;
          if (count % 2 == 0) color = 'cyan';
          else color = 'white';
          tempdata += '<tr style="background-color:' + color + '"><td> file signature</td><td>' + info.sig + '</td></tr>';
          count++;
          if (info.saved == 'ok') {
            color = 'green';
            txt = "white"
          } else color = 'white';
          tempdata += '<tr style="background-color:' + color + '; color:' + txt + '"><td> Saved In db</td><td>' + info.saved + '</td></tr>';
          count++;
          if (count % 2 == 0) color = 'cyan';
          else color = 'white';
          tempdata += '<tr style="background-color:' + color + '"><td> provenancestring</td><td>' + info.provenancestring + '</td></tr>';
          count++;
          if (count % 2 == 0) color = 'cyan';
          else color = 'white';
          tempdata += '<tr style="background-color:' + color + '"><td> Creation Date</td><td>' + info.creationDate + '</td></tr>';
          count++;
          if (count % 2 == 0) color = 'cyan';
          else color = 'white';
          tempdata += '<tr style="background-color:' + color + '"><td> Image Path</td><td>' + info.imagePath + '</td></tr>';
          count++;
          if (count % 2 == 0) color = 'cyan';
          else color = 'white';
          tempdata += '<tr style="background-color:' + color + '"><td> Signature Path</td><td>' + info.signatureFile + '</td></tr>';
          count++;
          if (info.validitySignature == 'confirmed') {
            color = 'green';
            txt = "white"
          } else color = 'white';
          tempdata += '<tr style="background-color:' + color + '; color:' + txt + '"><td> signature validity</td><td>' + info.validitySignature + '</td></tr>';
          count++;
          if (count % 2 == 0) color = 'cyan';
          else color = 'white';
          tempdata += '<tr style="background-color:' + color + '"><td> Json informations</td><td>' + info.jsonFile + '</td></tr>';
          count++;
          if (count % 2 == 0) color = 'cyan';
          else color = 'white';
          tempdata += '<tr><td colspan="2" style="text-align: center; vertical-align: middle;"><a href="' + info.imagePath + '" target="_blank">"<img width="10%" height="auto" id="' + info.maxCall + '" src="' + info.imagePath + '"></a></td></tr>';

          tempdata += '</table>';
          addLog(tempdata);






          //..........
          var testpercent = parseFloat(titi.progress) * 100;

          var pBar = document.getElementById('progressor');
          pBar.value = pBar.value + (testpercent);
          var perc = document.getElementById('percentage');
          var totperc = perc.innerHTML;
          var localtot = (parseInt(totperc) + parseInt(testpercent));
          perc.innerHTML = localtot + "%";
          perc.style.width = (Math.floor(pBar.clientWidth * testpercent) + 15) + 'px';

          var scrollIntoViewOptions = {
            behavior: "smooth",
            block: "center"
          };
          document.getElementById(info.maxCall).scrollIntoView(scrollIntoViewOptions);
          if (info.operation === 'complete') stopTask(info);
          else sendMessage(1);

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

    addLog('<h1>Generation complete</h1>');
    var tempstr = '<div><h3>cumulative string hash :<br>' + info.provenanceCumulativeHash + ' </h3></div><hr>';

    tempstr += '<h3>provenanceCumulativeString</h3>';
    addTo('Collection', tempstr);

    //tutorial part using dom in js => dom form creation
    var br = document.createElement("br");
    var hr = document.createElement("hr");
    // create the form element dynamically
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("id","postGenForm");
    form.setAttribute("action", "/collection/postGeneration");

    //create textarea for provenanceCumulativeString
    var pcs = document.createElement("textarea");
    pcs.setAttribute("name", "provenanceCumulativeString");
    pcs.setAttribute("id", "provenanceCumulativeString");
    pcs.setAttribute("rows", '5');
    pcs.setAttribute("cols", '127');

    // Create an input element for CumulativeHashString

    var chs = document.createElement("input");
    chs.setAttribute("type", "text");
    chs.setAttribute("name", "cumulativeHashString");
    chs.setAttribute("hidden", 'hidden');
    chs.setAttribute("value", info.provenanceCumulativeHash);

    var collectionTitle = document.createElement("input");
    collectionTitle.setAttribute("type", "text");
    collectionTitle.setAttribute("name", "collectionTitle");
    collectionTitle.setAttribute("placeholder", "Collection Title");

    var g = document.createElement("LABEL");
    var ctl = document.createTextNode("Collection Title");
    g.setAttribute("for", "collectionTitle");
    g.appendChild(ctl);

    var collectionDescription = document.createElement("textarea");
    collectionDescription.setAttribute("name", "collectionDescription");
    collectionDescription.setAttribute("rows", '10');
    collectionDescription.setAttribute("cols", '127');

    var g1 = document.createElement("LABEL");
    var cdl = document.createTextNode("Collection Description");
    g1.setAttribute("for", "collectionDescription");
    g1.appendChild(cdl);

    var cid = document.createElement("input");
    cid.setAttribute('type', 'text');
    cid.setAttribute('name', "cid");
    cid.setAttribute("placeholder", "ipfs://");
    cid.setAttribute('maxLength', '2083');
    var g2 = document.createElement("LABEL");
    var cidl = document.createTextNode("insert the complete link CID of your  images directory on IPFS");
    g2.setAttribute("for", "cid");
    g2.appendChild(cidl);


    // create a submit button
    var s = document.createElement("input");
    s.setAttribute("type", "button");
    s.setAttribute("value", "Submit");
    s.setAttribute("onclick", 'createCollection()');

    // Append the the elements to the form
    form.appendChild(pcs);
    form.appendChild(br.cloneNode());
    form.appendChild(chs);
    form.appendChild(br.cloneNode());
    form.appendChild(g);
    form.appendChild(br.cloneNode());
    form.appendChild(collectionTitle);
    form.appendChild(br.cloneNode());
    form.appendChild(g1);
    form.appendChild(br.cloneNode());
    form.appendChild(collectionDescription);
    form.appendChild(br.cloneNode());
    form.appendChild(g2);
    form.appendChild(br.cloneNode());
    form.appendChild(cid);
    form.appendChild(br.cloneNode());

    form.appendChild(s);
    //Add the form to the collection div
    document.getElementById('Collection').appendChild(form);
    $('#provenanceCumulativeString').val(info.provenanceCumulativeString);




  }

  function createCollection() {
    var frm = $('#postGenForm');
    alert('test'+frm);
   // frm.submit(function (e) {

   // e.preventDefault();

    $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function(response) {
            console.log('Submission was successful.');
            console.log(response);
           },
            error: function (response) {
            console.log('An error occurred.');
            console.log(response);
           },
          });
    //} );
   

  }
  
  function addLog(message) {
    var r = document.getElementById('results');
    r.innerHTML += message + '<br>';
    r.scrollTop = r.scrollHeight;
  }

  function addTo(elem, message) {
    var r = document.getElementById(elem);
    r.innerHTML += message + '<br>';
    r.scrollTop = r.scrollHeight;
  }
</script>

<h1>Collection</h1>
<?= $welcome ?>

<br />
<input type="button" onclick="startTask();" value="Generate NFTs" />
<br />
<br />

<p>Results</p>
<br />
<div id="results"></div>
<br />

<progress id='progressor' value="0" max='100' style=""></progress>
<span id="percentage" style="text-align:left; display:block; margin-top:5px;">0</span>
<hr>
<div id="Collection"></div>
<?= $this->endSection() ?>