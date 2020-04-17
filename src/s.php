<?php
namespace sailboats;
use \PDO;

$db_config  = parse_ini_file('../conf/db.conf');
$servername = $db_config["servername"];
$dbname     = $db_config["dbname"];
$username   = $db_config["username"];
$password   = $db_config["password"];
$port       = $db_config["port"];

class sharedBoard {
	function get($thread=null, $limit=null) {
   	    global $servername;
    	global $dbname;
    	global $username;
    	global $password;
    	global $port;

    	if (intval($_GET["num"]) > 100) { $num = 100; }
    	else { $num = intval($_GET["num"] ?? 100); }

	    $conn = new PDO("mysql:host={$servername};port={$port};dbname={$dbname}", $username, $password);
        $sql = "SELECT * FROM s WHERE replyTo=? OR id=? LIMIT ?";
        $s = $conn->prepare($sql);
        $s->bindParam(1, intval($_GET["thread"] ?? 0),	PDO::PARAM_INT);
        $s->bindParam(2, intval($_GET["thread"] ?? 0),	PDO::PARAM_INT);
        $s->bindParam(3, intval($_GET["num"] ?? 50),	PDO::PARAM_INT);
        $s->execute();
        $r = $s->fetchAll();
        $a = [];
        foreach ($r as $result) {
	        $a[] = [
	            "id"        	=> sha1($_SERVER['REMOTE_ADDR']),
	            "user_agent"    => $_SERVER['HTTP_USER_AGENT'],
	            "totalPosts"   	=> $r['COUNT(sha_id)'],
	        ];
    	}
        return json_encode($a);
	}

	function post($board, $thread=null) {
	   	global $servername;
    	global $dbname;
    	global $username;
    	global $password;
    	global $port;
	}
}