#!/usr/local/psa/admin/bin/php
<?php
$ftp =array();
$dbPassword = exec ( 'cat /etc/psa/.psa.shadow' );
$con = mysql_connect ( "localhost", "admin", $dbPassword );
if ($con) {
    if (!mysql_select_db ( "psa" )) {
                echo "Unable to connect to database";
            }
            else
            {
                $query  = "SELECT sys_users.login,accounts.password FROM sys_users LEFT JOIN accounts on sys_users.account_id=accounts.id";
                $retval = mysql_query( $query, $con);
                while($row = mysql_fetch_array($retval))
                {
                    $ftpAccountspass[$row['login']] = $row['password'];
                }
    }
}
foreach ($ftpAccountspass as $key=>$value){
$ftpuser = $key;
$pass = $ftpAccountspass[$key];
$key = exec( 'cat /etc/psa/private/secret_key | base64' );
$base64encoded_ciphertext = explode('$', $pass);
$base64encoded_ciphertext =array_filter($base64encoded_ciphertext);
$passwordorginal = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, base64_decode($key), base64_decode($base64encoded_ciphertext[3]), MCRYPT_MODE_CBC, base64_decode($base64encoded_ciphertext[2]));
echo "FTP USER : $ftpuser\n";
echo "PASSWORD : $passwordorginal\n";
echo "===============\n";
}
?> 