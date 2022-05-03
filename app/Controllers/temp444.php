$dn = array("countryName" => 'BE', "stateOrProvinceName" => 'Hainaut', "localityName" => 'Houdeng-Aimeries', "organizationName" => 'Nft Artist Alliance', "organizationalUnitName" => 'Kaerdon', "commonName" => 'Kaerdon', "emailAddress" => 'tim@1proamonservice.eu');
$privkeypass = '';
$numberofdays = 3650;

$privkey = openssl_pkey_new();
$csr = openssl_csr_new($dn, $privkey);
$sscert = openssl_csr_sign($csr, null, $privkey, $numberofdays);
openssl_x509_export($sscert, $publickey);
openssl_pkey_export($privkey, $privatekey, $privkeypass);
openssl_csr_export($csr, $csrStr);

echo ("<hr>".$privatekey."<hr>"); // Will hold the exported PriKey
echo ("<hr>".$publickey."<hr>");  // Will hold the exported PubKey
echo ("<hr>".$csrStr."<hr>");     // Will hold the exported Certificate


