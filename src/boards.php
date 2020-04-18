<?php
// https://github.com/cyberland-digital/cyberland-protocol/blob/master/protocol.md

namespace sailboats;
use \PDO;

$db_config  = parse_ini_file('../conf/db.conf');
$servername = $db_config["servername"];
$dbname     = $db_config["dbname"];
$username   = $db_config["username"];
$password   = $db_config["password"];
$port       = $db_config["port"];

class boards {
	function get() { 
        global $servername, $dbname, $username, $password, $port;
        $conn = new PDO("mysql:host={$servername};port={$port};dbname={$dbname}", $username, $password);
        $sql = "SELECT COUNT(id) FROM s";
        $s = $conn->prepare($sql);
        $s->execute();
        $r = $s->fetch();
        foreach ($r as $result) {
            $post_count = $r['COUNT(id)'];
        }
        echo(json_encode(array(
            'slug'      => '/s/',
            'name'      => 'shared',
            'charLimit' => 5000,
            'posts'     => $post_count,
        )));
	}
}