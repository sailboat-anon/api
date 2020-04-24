<?php
require_once __DIR__ . '/../src/ratelimit.php';
if (in_array($_SERVER["REMOTE_ADDR"], file("blacklist", FILE_IGNORE_NEW_LINES))) { header("HTTP/1.1 403 Forbidden", TRUE, 403); exit; }
include __DIR__ . '/../src/route.php';
include __DIR__ . '/../src/whoami.php';
include __DIR__ . '/../src/s.php';
include __DIR__ . '/../src/boards.php';
include __DIR__ . '/../src/frontend_index.php';
include __DIR__ . '/../src/login.php';
include __DIR__ . '/../src/treasure.php';

use sailboats\route; // https://github.com/steampixel/simplePHPRouter
use sailboats\whoami;
use sailboats\sharedBoard; // https://github.com/sailboat-anon/sailboatland
use sailboats\boards; // https://github.com/cyberland-digital/cyberland-protocol/blob/master/protocol.md
use sailboats\frontend;
use sailboats\login_obj; // https://github.com/firebase/php-jwt
use sailboats\treasure;
  
$rl = new ratelimit();
$st = $rl->getSleepTime($_SERVER["REMOTE_ADDR"]);

route::add('/', function() {
  $obj = new frontend();
  $obj->get();
});

route::add('/api/v1/treasure', function () {
  $obj = new treasure();
  $obj->get();
});

route::add('/api/v1/auth', function() {
  $obj = new login_obj();
  $obj->get($_POST['username'], $_POST['password']);
}, 'post');

route::add('/boards', function() {
  $obj = new boards();
  $obj->get();
});

route::add('/api/v1/whoami', function() {
  $obj = new whoami();
  $obj->get();
});

route::add('/s', function() {
  $sb = new sharedBoard();
  $sb->get();
});

route::add('/s', function() {
  global $st;
  if ($st > 0) { header("HTTP/1.1 429 Too Many Requests", TRUE, 429);  exit; }
  $sb = new sharedBoard();
  $sb->post();
}, 'post');

route::pathNotFound(function() {
  header('HTTP/1.1 404 Not Found', TRUE, 404);
});

route::methodNotAllowed(function() {
  header('HTTP/1.1 405 Method Not Allowed', TRUE, 405);
});

route::run('/');
