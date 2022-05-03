<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<!-- CONTENT -->
<H1>Hello User</H1>
<p>This nft generator let you define the primary order of all your layers.<br>
For this example we use three primary layer in this order.<br></p>
<ul>
<li>Background</li>
<li>Border</li>
<li>Card</li>
</ul><p>
These layers are directory name under ressources directory.<br>
in this case [Background] => /var/www/html/app/ressources/Background/<br>
[Border] => /var/www/html/app/ressources/Border/<br>
[Card] => /var/www/html/app/ressources/Card/<br>
if a directory name end byitsel it is mandatory<br>
if the end is a # followed by a number (represent rarity factor) <br>
under this directory the app randomly choose the next level(path) <br>
and try if it is directory or file if directory is found <br>
the function recursively call itself with the new path as argument.<br>
else the final file is selected for this layer.<br>
and it cycle through all layer defined in configuration file.<br>

<p>At this time it compute the dna of the scene wich is the hash of the clear dna info you'll find later on <br>
and run it against a database of all the scene created before this one<br> 
if it find duplicate entry it call itself recursively asking for a complete new scene<br>
the app will echo something like that 
<pre>added : 8cbdd1d50cef8d8167ac96f21d984df6e418309634ab7d325de28e48d9f69a0c4a94c29a7f480afb86b7f76f5fc212edcb448078713c12cb23bd1de22918b380</pre>
</p>
<p>
    This mean the scene is accepted for the collection and added to it
</p>
<p>
    All layer source are compiled and and image is written to public/build/images
</p>
<p>Now we compute the cryptographic signature of the image file sha3-512</p>
<p>And we sign it with a collection private key (openssl is used)  <br> we save this signature in a file under public/imgsig<br>
and We verify this signature file through openssl again</p>
<p> we also link all dna created in a global provenance string hash and a local one for each image </p>
<p>Finaly we save all these information on a scene basis in public/cardinfo</p>
<p>
if everything is fine you'll see something like 
<pre>
added : 8cbdd1d50cef8d8167ac96f21d984df6e418309634ab7d325de28e48d9f69a0c4a94c29a7f480afb86b7f76f5fc212edcb448078713c12cb23bd1de22918b380
Signature valide
d467d41cdb21a73c57ee7534078a9d0b6bb0d2044ed63e8a6f81d35057629d56c3af79ab7021884b1470482616519a939b6d7a08eabf3721875a591c69c6a390

json data card File written!</pre>
</p>
<p>
in case of duplicate dna you'll see something like that
<pre>DNA EXIST !!!

generating new scene

New scene generated..
Verifying dna :00fdda58fe9d2e185461239b714df4d8eb98df7ecf6db28b37f5b3ce94df30fce1ae9f904750f10d5c29a23096f3c10fd7011550382d446a28b5353ca6260dcf
added : 00fdda58fe9d2e185461239b714df4d8eb98df7ecf6db28b37f5b3ce94df30fce1ae9f904750f10d5c29a23096f3c10fd7011550382d446a28b5353ca6260dcfSignature valide
ca84f114f0ba5e51052af066c15c24a3206d43c8503b2c81a3085a5e093abb83eec90a0ea793ad6cb95b5323482ab7d79279e193990c023f305934e3d8955e12

json data card File written!</pre>
this mean a new scene has been issued and all the process for it was reset.
</p>

or sometime one layer itself is null and in this case the layer will be reset <br>
and the scene will go pass through all the process again to compile it correctly
</p>
<p>
    <h2>finaly you are assured that the defined collection size in config is respected with no duplicate and all information ready</h2>
</p>
<p>
Provenance Cumulative String

please save this information followed by the Provenance Cumulative String wich is each dnahash concatened with the next one<br></p> 
<textarea id="provenanceCumulativeString" name="provenanceCumulativeString" rows="5" cols="127">8cbdd1d50cef8d8167ac96f21d984df6e418309634ab7d325de28e48d9f69a0c4a94c29a7f
    480afb86b7f76f5fc212edcb448078713c12cb23bd1de22918b3801eea02eba4e5e495e60b09de4284e381648cf43b86e705250ad59f0c947a13a6781349308f5af
    515133dfc5320eadbe81d640351611df21bbfe8310f62f997c6c7f53e01e05da8f451d0d9299103a5f92c70cf1767a03ad5a1502b590a18ff3e8ce387b136ac36166
    54b867be1b1bddec32c0f99aaf37c592825db9eed3ec07200fdda58fe9d2e185461239b714df4d8eb98df7ecf6db28b37f5b3ce94df30fce1ae9f904750f10d5c29a
    23096f3c10fd7011550382d446a28b5353ca6260dcfdf383b160221282e32097753956757eab1c24caba25175d100426a579c9b26b1309d3e3ed991b5c4bed7eb8a9
    b392ce6bea1d69ba0509b76febff356961c481e4a2032d177d7bc7402ec411b39f0fde6ff456ae4eb07940b813933efe3885c4c60ae8a57e848abd116ba5db1a7616
    723779ac2b19722f074de92b29802d2f0a907863b8c880841530a02d2ffa309187de6c91f447747f515c9f2f258e80acbb2734789e7300f2dd7c120aa1b983d520a2
    5eb690734f2c37c697bd5464f1264f4b7e0f0dbe4e530d0cf3d3ba025dcbdfda3ccf88b1d459510e094af23409a9874197d3e4b7fac6cd06f497c45da2579ad9725b
    681e4d834e9daf6c6992eed51cb4f9b88e1c05a356cfe2069d163ca4f509657a774ebfd57eb688093912c3360b8da2b13922a673ec6ad5b9f888cf44ef03e74dfd3cd
    f75a76760976dbbed31c0b5a021561d4e95a4c0591ec0f623b98e8e767c49267d1e375758833c79b2680277b207e63ca59cbc96cfbddf46ef14ad9eb87c680a0f0b13
    01477a51287cb9289</textarea>
<br>Recorded cumulative string hash : 16db27626b17cb78714771702c339ff0ddfa66d9fc4be4f967adc5a4b3a6c3107af03499b6892a0167a8d4a862b04a033cbc589a86108cf22723cd08a2b080fb
</p><p>For each scene that has been generated by it you get this pîeces of informations at the end of the global process can be time consuming
</p>
<pre>
[Background] => /var/www/html/app/ressources/Background/common#90/cardbackbluerays#10.png
[Border] => /var/www/html/app/ressources/Border/common#90/cardborder3#20.png
[Card] => /var/www/html/app/ressources/Card/ArcaneMajeures#20/7_TheChariot#3.png
[clearDna] => /var/www/html/app/ressources/Background/common#90/cardbackbluerays#10.png/var/www/html/app/ressources/Border/common#90/cardborder3#20.png/var/www/html/app/ressources/Card/ArcaneMajeures#20/7_TheChariot#3.png
[dna] => 8cbdd1d50cef8d8167ac96f21d984df6e418309634ab7d325de28e48d9f69a0c4a94c29a7f480afb86b7f76f5fc212edcb448078713c12cb23bd1de22918b380
[sig] => d467d41cdb21a73c57ee7534078a9d0b6bb0d2044ed63e8a6f81d35057629d56c3af79ab7021884b1470482616519a939b6d7a08eabf3721875a591c69c6a390
[provenancestring] => e7fcd0d6bf59bb335ea81b533cc0ade5de730d8dc1ef80bab69232a0f296ff3d006df3ec742305216f83f4d1dd6ccb882c70213083947d7c0353b37b48cab8df
[creationDate] => 2022-05-03 19:18:25
[imagePath] => /build/images/0.png
[signatureFile] => /build/imgsig/0.dat
[jsonFile] => /build/cardinfo/0.json 
</pre>
and the image will be in the result page
<?= $this->endSection() ?>
