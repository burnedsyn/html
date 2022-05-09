# Welcome to Tim's CodeIgniter 4.1.9 Nft Art Generator  
### Digital ArtWork Application
## 1proamonservice.eu
Hello User
This nft generator let you define the primary order of all your layers.
For this example we use three primary layer in this order.
1. Background
2. Border
3. Card  

These layers are directory name under  the ressources directory.  
in this case :
* [Background] => /var/www/html/app/ressources/Background/
* [Border] => /var/www/html/app/ressources/Border/
* [Card] => /var/www/html/app/ressources/Card/  

if a directory name end by itself it is mandatory.  

if at the end  there is a # followed by a number wich represent rarity factor.
under this directory the app randomly choose the next level(path)  
and try if it is a directory or a file, if a directory is found, the function recursively call itself  
with the new path as argument.  
Else the final file is selected for this layer.  
and it cycle through all layer defined in configuration file.  
At this time it compute the dna of the scene wich is the hash of the clear dna info you'll find later on  
and run it against a database of all the scene created before this one.  
if it find duplicate entry it call itself recursively asking for a complete new scene
the app will echo something like that  
added : 8cbdd1d50cef8d8167ac96f21d984df6e418309634ab7d325de28e48d9f69a0c4a94c29a7f480afb86b7f76f5fc212edcb448078713c12
cb23bd1de22918b380  
This mean the scene is accepted for the collection and added to it  
All layer source are compiled and and image is written to public/build/images  
Now we compute the cryptographic signature of the image file sha3-512  
And we sign it with a collection private key (openssl is used)  
we save this signature in a file under public/imgsig  
and We verify this signature file through openssl again  
we also link all dna created in a global provenance string hash and a local one for each image  
Finaly we save all these information on a scene basis in public/cardinfo  
if everything is fine you'll see something like  
added : 8cbdd1d50cef8d8167ac96f21d984df6e418309634ab7d325de28e48d9f69a0c4a94c29a7f480afb86b7f76f5fc212edcb448078713c12
cb23bd1de22918b380  
Signature valide  
**d467d41cdb21a73c57ee7534078a9d0b6bb0d2044ed63e8a6f81d35057629d56c3af79ab7021884b1470482616519a939b6d7a08eabf3721875a59
1c69c6a390**  
json data card File written!  
in case of duplicate dna you'll see something like that  
**DNA EXIST !!!**  
generating new scene  
New scene generated..  
Verifying dna :**00fdda58fe9d2e185461239b714df4d8eb98df7ecf6db28b37f5b3ce94df30fce1ae9f904750f10d5c29a23096f3c10fd701155
0382d446a28b5353ca6260dcf**  
added : **00fdda58fe9d2e185461239b714df4d8eb98df7ecf6db28b37f5b3ce94df30fce1ae9f904750f10d5c29a23096f3c10fd7011550382d44
6a28b5353ca6260dcf**  
Signature valide  
**ca84f114f0ba5e51052af066c15c24a3206d43c8503b2c81a3085a5e093abb83eec90a0ea793ad6cb95b5323482ab7d79279e193990c023f305934
e3d8955e12**  
json data card File written!  
this mean a new scene has been issued and all the process for it was reset.
or sometime one layer itself is null and in this case the layer will be reset
and the scene will go pass through all the process again to compile it correctly  
## finaly you are assured that the defined collection size in config is respected with no duplicate and all information ready  
Provenance Cumulative String please save this information followed by the Provenance Cumulative String wich is each dnahash concatened with the next one  
    **8cbdd1d50cef8d8167ac96f21d984df6e418309634ab7d325de28e48d9f69a0c4a94c29a7f480afb86b7f76f5fc212edcb448078713c12cb23bd1de22918b3801eea02eba4e5e495e60b09de4284e381648cf43b86e705250ad59f0c947a13a6781349308f5af515133dfc5320eadbe81d640351611df21bbfe8310f62f997c6c7f53e01e05da8f451d0d9299103a5f92c70cf1767a03ad5a1502b590a18ff3e8ce387b136ac3616654b867be1b1bddec32c0f99aaf37c592825db9eed3ec07200fdda58fe9d2e185461239b714df4d8eb98df7ecf6db28b37f5b3ce94df30fce1ae9f904750f10d5c29a23096f3c10fd7011550382d446a28b5353ca6260dcfdf383b160221282e32097753956757eab1c24caba25175d100426a579c9b26b1309d3e3ed991b5c4bed7eb8a9b392ce6bea1d69ba0509b76febff356961c481e4a2032d177d7bc7402ec411b39f0fde6ff456ae4eb07940b813933efe3885c4c60ae8a57e848abd116ba5db1a7616723779ac2b19722f074de92b29802d2f0a907863b**  
Recorded cumulative string hash :  
**16db27626b17cb78714771702c339ff0ddfa66d9fc4be4f967adc5a4b3a6c3107af03499b6892a0167a8d4a862b04a033cbc589a86108cf22723cd08a2b0**  
each scene that has been generated by it you get this pîeces of informations at the end of the global process can be time consuming  
[Background] => /var/www/html/app/ressources/Background/common#90/cardbackbluerays#10.png  
[Border] => /var/www/html/app/ressources/Border/common#90/cardborder3#20.png  
[Card] => /var/www/html/app/ressources/Card/ArcaneMajeures#20/7_TheChariot#3.png  
[clearDna] => /var/www/html/app/ressources/Background/common#90/cardbackbluerays#10.png/var/www/html/app/ressources/Bo
rder/common#90/cardborder3#20.png/var/www/html/app/ressources/Card/ArcaneMajeures#20/7_TheChariot#3.png  
[dna] => 8cbdd1d50cef8d8167ac96f21d984df6e418309634ab7d325de28e48d9f69a0c4a94c29a7f480afb86b7f76f5fc212edcb448078713c1
2cb23bd1de22918b380  
[sig] => d467d41cdb21a73c57ee7534078a9d0b6bb0d2044ed63e8a6f81d35057629d56c3af79ab7021884b1470482616519a939b6d7a08eabf3
721875a591c69c6a390  
[provenancestring] => e7fcd0d6bf59bb335ea81b533cc0ade5de730d8dc1ef80bab69232a0f296ff3d006df3ec742305216f83f4d1dd6ccb88
2c70213083947d7c0353b37b48cab8df  
[creationDate] => 2022-05-03 19:18:25  
[imagePath] => /build/images/0.png  
[signatureFile] => /build/imgsig/0.dat  
[jsonFile] => /build/cardinfo/0.json  
and the image will be in the result page  
