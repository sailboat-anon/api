<?php
namespace sailboats;

require __DIR__ . '/../vendor/autoload.php';

$db_config  = parse_ini_file('../conf/db.conf');
$dbservername = $db_config["servername"];
$dbname       = $db_config["dbname"];
$dbusername   = $db_config["username"];
$dbpassword   = $db_config["password"];
$dbport       = $db_config["port"];
$secretKey    = $db_config["secretKey"];
$algorithm    = $db_config["jwt_algo"];

use Firebase\JWT\JWT;
use PDO;

class login_obj {
    function get($username=null, $password=null): void {
        if (isset($username) && isset($password)) {   
            if (true) { // if user and pass are filtered/sanitized
                try {
                    global $dbservername, $dbname, $dbusername, $dbpassword, $dbport;
                    $db = new PDO("mysql:host={$dbservername};port={$dbport};dbname={$dbname}", $dbusername, $dbpassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "SELECT id, password FROM tenants WHERE username = ?";    
                    $sb = $db->prepare($sql);
                    $sb->bindParam(1, $username, PDO::PARAM_STR);
                    
                    try {
                        $sb->execute();
                        $rs = $sb->fetch();
                    }
                    catch (PDOException $err) {
                        echo($err->getMessage());
                        exit;
                    }
                    
                    if ($rs) {
                        if ($rs['password'] == sha1($password)) { 
                            $tokenId    = base64_encode(openssl_random_pseudo_bytes(32));
                            $issuedAt   = time();
                            $notBefore  = $issuedAt + 0;  // seconds
                            $expire     = $notBefore + 6000000; 
                            $serverName = $_SERVER['SERVER_ADDR'];
                            
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

                            /*
                             * Encode the array to a JWT string.
                             * Second parameter is the key to encode the token.
                             * 
                             * The output string can be validated at http://jwt.io/
                             */
                            global $secretKey;
                            global $algorithm;
                            $jwt = JWT::encode(
                                $data,      //Data to be encoded in the JWT
                                $secretKey, // The signing key
                                $algorithm  // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
                                );
                                
                            $unencodedArray = ['jwt' => $jwt];
                            header('Content-type: application/json');
                            echo json_encode($unencodedArray);
                        } else {
                            header('HTTP/1.1 401 Unauthorized', TRUE, 401);
                        }
                    } else {
                        header('HTTP/1.1 404 Not Found', TRUE, 404);
                    }
                } catch (Exception $e) {
                    header('HTTP/1.1 500 Internal Server Error: $e', TRUE, 500);
                }
            } else {
                header('HTTP/1.1 400 Bad Request', TRUE, 400);
            }
        } else {
            header('HTTP/1.1 406 Not Acceptable', TRUE, 406);
        }
    }
}