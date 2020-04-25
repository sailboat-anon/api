<?php
namespace sailboats;
include __DIR__ . '/../src/naughty.php'; // bad word filter
require __DIR__ . '/../vendor/autoload.php';
// require __DIR__ . '/resource.php';

use sailboats\secretResource;
use sailboats\sanitizeText;
use \PDO;

$db_config  = parse_ini_file('../conf/db.conf');
$servername = $db_config["servername"];
$dbname     = $db_config["dbname"];
$username   = $db_config["username"];
$password   = $db_config["password"];
$port       = $db_config["port"];

$BOARD_LIMIT = 1000;

class sharedBoard {
	function get() { // open to all for now
        //$auth = new secretResource();
        //if ($auth->validateToken()) {
            $sanitize = new sanitizeText();
       	    global $servername, $dbname, $username, $password, $port;
            global $BOARD_LIMIT;
        	$limit = intval($_GET['num'] ?? $BOARD_LIMIT);
        	if (intval($limit > $BOARD_LIMIT)) { $limit = $BOARD_LIMIT; }

    	    $conn = new PDO("mysql:host={$servername};port={$port};dbname={$dbname}", $username, $password);
            $sql = "SELECT id, content, replyTo, bumpCount, time FROM s WHERE replyTo=? OR id=? LIMIT ?";
            $s = $conn->prepare($sql);
            $s->bindParam(1, intval($_GET["replyTo"] ?? 0),	PDO::PARAM_INT);
            $s->bindParam(2, intval($_GET["replyTo"] ?? 0),	PDO::PARAM_INT);
            $s->bindParam(3, intval($limit ?? $BOARD_LIMIT),PDO::PARAM_INT);
            $s->execute();
            $r = $s->fetchAll();
            $a = [];
    	    foreach ($r as $result) {
            	$a[] = [
    	            "id"        => $result["id"],
    	            "content"   => $sanitize->profanity($result["content"]),
    	            "replyTo"   => $result["replyTo"],
    	            "bumpCount" => $result["bumpCount"],
    	            "time"      => $result["time"],
    	    	    ];
        	}
            echo(json_encode($a));
        //}
        //else { header('HTTP/1.1 401 Unauthorized', TRUE, 401); }
	}

	function post() {
        $auth = new secretResource();
        if ($auth->validateToken()) {
            if (isset($_POST['replyTo'])) {
                $thread = $_POST['replyTo'];
            }
            else {
                $thread = $_POST['thread'];
            } 
            $thread = intval($thread ?? 0);
    	    
            global $servername, $dbname, $username, $password, $port;
            if (!isset($_POST['content']) || empty($_POST['content'])) { header('HTTP/1.1 400 Bad Request', TRUE, 400); exit; }

        	$bumpCount = 0;
            // need to query the last index id to see if the 'replyTo' value is legit (don't want to reply to future posts)
    	    $conn = new PDO("mysql:host={$servername};port={$port};dbname={$dbname}", $username, $password);
    	    $sql = "INSERT INTO s (content, replyTo) VALUES (?,?)";

    	    $s = $conn->prepare($sql);
    	    $s->bindParam(1, $_POST['content'],     PDO::PARAM_STR);
    	    $s->bindParam(2, $thread,               PDO::PARAM_INT);
    	    $s->execute();
    	    $s->fetch();

            // if the reply wasn't to a board itself, bump the associated reply
            if ($thread != 0) {
                $s = $conn->prepare("UPDATE s SET bumpCount = bumpCount + 1 WHERE id = ?");
                $s->bindParam(1, $thread,  PDO::PARAM_INT);
                $s->execute();
                $s->fetch();
            }
        }
    }
}