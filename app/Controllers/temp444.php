$dn = array("countryName" => 'BE', "stateOrProvinceName" => 'Hainaut', "localityName" => 'Houdeng-Aimeries', "organizationName" => 'Nft Artist Alliance', "organizationalUnitName" => 'Kaerdon', "commonName" => 'Kaerdon', "emailAddress" => 'tim@1proamonservice.eu');
$privkeypass = '';
$numberofdays = 3650;

$privkey = openssl_pkey_new();
$csr = openssl_csr_new($dn, $privkey);
$sscert = openssl_csr_sign($csr, null, $privkey, $numberofdays);
openssl_x509_export($sscert, $publickey);
openssl_pkey_export($privkey, $privatekey, $privkeypass);
openssl_csr_export($csr, $csrStr);

echo ("
<hr>".$privatekey."
<hr>"); // Will hold the exported PriKey
echo ("
<hr>".$publickey."
<hr>"); // Will hold the exported PubKey
echo ("
<hr>".$csrStr."
<hr>"); // Will hold the exported Certificate


{
    "name": "Mysteries tarots cards Major Arcana edition by Kaerdon 0", 
    "description": "The messenger of the gods has has draw this Major Arcana for you : Your card is 18_TheMoon",
    "image": "https://ipfs.io/ipfs/bafybeidrze7bkafut5r4txlsqucnwe4nnt6mvihyzg3ggmx3agmf2ionue/00.png", 
    "attributes": 
    [
        {
            "trait_type": "Cardbackground", "value": "cardbackblueorbs"
        }, 
        {
            "trait_type": "Border", "value": "cardborder2"
        }, 
        {
            "trait_type": "Card", "value": "18_TheMoon"
        }
    ]
}