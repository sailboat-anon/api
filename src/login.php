<?php

require __DIR__ . '/../vendor/autoload.php';
$db_config  = parse_ini_file('../conf/db.conf');
$dbservername = $db_config["servername"];
$dbname       = $db_config["dbname"];
$dbusername   = $db_config["username"];
$dbpassword   = $db_config["password"];
$dbport       = $db_config["port"];

use Firebase\JWT\JWT;

/*
 * Validate that the request was made using HTTP POST method
 */
if (true) { // ispost
    /*
     * Simple sanitation
     */
    //$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    //$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $username = 'sba';
    $password = 'cyberland';

    if (true) { // if user and pass are filtered/sanitized
        try {
            /*
             * Connect to database to validate credentials
             */
            global $dbservername, $dbname, $dbusername, $dbpassword, $pdbort;

            $db = new PDO("mysql:host={$dbservername};port={$dbport};dbname={$dbname}", $dbusername, $dbpassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            /*
             * We will fetch user id and password fields for the given username
             */
            $sql = <<<EOL
            SELECT id,
                   password
            FROM   tenants
            WHERE  username = ?
EOL;
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $username, PDO::PARAM_STR);
            
            try {
                $stmt->execute();
                $rs = $stmt->fetch();

                }
            catch (PDOException $err) {
                echo 'Connection failed: ' . $err->getMessage();
                exit;
                

            }
            
            if ($rs) {
                /*
                 * Password was generated by password_hash(), so we need to use
                 * password_verify() to check it.
                 * 
                 * @see http://php.net/manual/en/ref.password.php
                 */
                //if (password_verify($password, $rs['password'])) {
                if (true) {

                    $tokenId    = base64_encode(openssl_random_pseudo_bytes(32));
                    $issuedAt   = time();
                    $notBefore  = $issuedAt + 10;  //Adding 10 seconds
                    $expire     = $notBefore + 60; // Adding 60 seconds
                    $serverName = 'sailboat.local';
                    
                    /*
                     * Create the token as an array
                     */
                    $data = [
                        'iat'  => $issuedAt,         // Issued at: time when the token was generated
                        'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                        'iss'  => $serverName,       // Issuer
                        'nbf'  => $notBefore,        // Not before
                        'exp'  => $expire,           // Expire
                        'data' => [                  // Data related to the signer user
                            'userId'   => $rs['id'], // userid from the users table
                            'userName' => $username, // User name
                        ]
                    ];
                    
                    header('Content-type: application/json');
                    
                    /*
                     * Extract the key, which is coming from the config file. 
                     * 
                     * Best suggestion is the key to be a binary string and 
                     * store it in encoded in a config file. 
                     *
                     * Can be generated with base64_encode(openssl_random_pseudo_bytes(64));
                     *
                     * keep it secure! You'll need the exact key to verify the 
                     * token later.
                     */
                    //$secretKey = base64_decode($config->get('jwt')->get('key'));
                    $secretKey = 'sailboatsfloat';
                    /*
                     * Extract the algorithm from the config file too
                     */
                    //$algorithm = $config->get('jwt')->get('algorithm');
                    $algorithm = 'HS512'; // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
                    /*
                     * Encode the array to a JWT string.
                     * Second parameter is the key to encode the token.
                     * 
                     * The output string can be validated at http://jwt.io/
                     */
                    $jwt = JWT::encode(
                        $data,      //Data to be encoded in the JWT
                        $secretKey, // The signing key
                        $algorithm  // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
                        );
                        
                    $unencodedArray = ['jwt' => $jwt];
                    echo json_encode($unencodedArray);
                } else {
                    header('HTTP/1.0 401 Unauthorized');
                }
            } else {
                header('HTTP/1.0 404 Not Found');
            }
        } catch (Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
        }
    } else {
        header('HTTP/1.0 400 Bad Request');
    }
} else {
    header('HTTP/1.0 405 Method Not Allowed');
}