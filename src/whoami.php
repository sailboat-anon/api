<?php
namespace sailboats;
use \PDO;

$db_config  = parse_ini_file('../conf/db.conf');
$servername = $db_config["servername"];
$dbname     = $db_config["dbname"];
$username   = $db_config["username"];
$password   = $db_config["password"];
$port       = $db_config["port"];

class whoami {
	function get() {
	    echo(json_encode($this->save('whoami')));
	}

	function save($board, $thread=null) {
	   	global $servername;
    	global $dbname;
    	global $username;
    	global $password;
    	global $port;

        $conn = new PDO("mysql:host={$servername};port={$port};dbname={$dbname}", $username, $password);
        $sql = "INSERT INTO users (sha_id, user_agent, board, thread) VALUES (?,?,?,?)";
        $s = $conn->prepare($sql);
        $s->bindParam(1, sha1($_SERVER['REMOTE_ADDR']),	PDO::PARAM_STR);
        $s->bindParam(2, $_SERVER['HTTP_USER_AGENT'],	PDO::PARAM_STR);
        $s->bindParam(3, $board,						PDO::PARAM_STR);
        $s->bindParam(4, $thread,						PDO::PARAM_INT);
        $s->execute();

        $sql = "SELECT COUNT(sha_id) FROM users WHERE sha_id = ?";
        $s = $conn->prepare($sql);
        $s->bindParam(1, sha1($_SERVER['REMOTE_ADDR']),	PDO::PARAM_STR);
        $s->execute();
        $r = $s->fetch();
        foreach ($r as $result) {
	        $a[0] = [
	            "id"        	=> sha1($_SERVER['REMOTE_ADDR']),
	            "user_agent"    => $_SERVER['HTTP_USER_AGENT'],
	            "totalPosts"   	=> $r['COUNT(sha_id)'],
	        ];
    	}
        return $a;
	}
}