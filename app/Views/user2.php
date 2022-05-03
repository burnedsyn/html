<H1>Hello User</H1>

<?php    
echo('thank to visiting <h1>'.$sitename.'</h1>');

echo("<h2>Collection informations</h2>");?>
<h3> Provenance Cumulative String</h3>
<p>please save this information</p>
<textarea id="provenanceCumulativeString" name="provenanceCumulativeString" rows="5" cols="127">
<?php 
echo($cumulativeString);
?>
</textarea>
<div>
    Recorded cumulative string hash : <?php echo ("$provenanceCumulativeHash");?>
</div>
<hr>
<H2>Collection </H2>
<?php 

foreach($cardCollection as $card){ 

?>
<div class="row">
<div class="left">
    <?php 
    foreach($card as $key=>$value){
        echo <<< EOF
        
            [$key] => $value <br>
            
EOF; ?>        <?php }?>   
</div>

<div class="right"> <a href="<?php echo $card['imagePath']; ?>" target="_blank"><img src="<?php echo $card['imagePath']; ?>" width="10%" ></a></div>


</div>
<hr>

<?php
}
$dn = array("countryName" => 'BE', "stateOrProvinceName" => 'Hainaut', "localityName" => 'Houdeng-Aimeries', "organizationName" => 'Nft Artist Alliance', "organizationalUnitName" => 'Kaerdon', "commonName" => 'Kaerdon', "emailAddress" => 'tim@1proamonservice.eu');
$privkeypass = '';
$numberofdays = 3650;

$privkey = openssl_pkey_new(array(
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
));
$csr = openssl_csr_new($dn, $privkey,array('digest_alg' => 'sha384') );
$sscert = openssl_csr_sign($csr, null, $privkey, $numberofdays);
openssl_x509_export($sscert, $publickey);
openssl_pkey_export($privkey, $privatekey, $privkeypass);
openssl_csr_export($csr, $csrStr);

echo ("<hr>".$privatekey."<hr>"); // Will hold the exported PriKey
echo ("<hr>".$publickey."<hr>");  // Will hold the exported PubKey
echo ("<hr>".$csrStr."<hr>");     // Will hold the exported Certificate
file_put_contents('priv.key',$privatekey);
file_put_contents('pub.key',$publickey);
file_put_contents('csr.pem', $csrStr);
openssl_pkey_export_to_file($privkey, 'example-priv.key');
// En même temps que le sujet, la CSR contient la clé publique correspondant à la clé privée
openssl_csr_export_to_file($csr, 'example-csr.pem');

?>

