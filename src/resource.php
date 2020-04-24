<?php
namespace sailboats;
require __DIR__ . '/../vendor/autoload.php';

$db_config    = parse_ini_file('../conf/db.conf');
$dbservername = $db_config["servername"];
$dbname       = $db_config["dbname"];
$dbusername   = $db_config["username"];
$dbpassword   = $db_config["password"];
$dbport       = $db_config["port"];
$secretKey    = $db_config["secretKey"];
$jwt_algo     = $db_config["jwt_algo"];

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

use PDO;

class secretResource {
    function getToken(): string {
        @$token = substr($_SERVER['HTTP_AUTHORIZATION'], 7);  // regex was a bastard so i gave up; sscanf($_SERVER['HTTP_AUTHORIZATION'], 'Authorization: Bearer %s'); might work
        $token_segments = explode('.', $token);
        if (count($token_segments) != 3) {
            header('HTTP/1.1 401 Unauthorized', TRUE, 401);
            exit;
        }
        return $token;
    }

    function validateToken(): bool {
        $token = $this->getToken();
        try {
            global $secretKey, $jwt_algo;
            $token = JWT::decode($token, $secretKey, array($jwt_algo));
            return true;
        } 
        catch (\Exception $e) {
            header('HTTP/1.1 401 Unauthorized', TRUE, 401);
            echo($e->getMessage());
            return false; // will this even run?  not sure about the exception handling
        }
    }
}