<?php
namespace sailboats;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/resource.php';

use sailboats\secretResource;

class treasure {
	function get() {
		$auth = new secretResource();

		if ($auth->validateToken()) {
			echo("
				you found the treasure!  you've successfully passed-back the JWT and you're accessing a protected endpoint.  fair winds!

				                  .
				                .'|     .8
				               .  |    .8:
				              .   |   .8;:        .8
				             .    |  .8;;:    |  .8;
				            .     n .8;;;:    | .8;;;
				           .      M.8;;;;;:   |,8;;;;;
				          .    .,\"n8;;;;;;:   |8;;;;;;
				         .   .',  n;;;;;;;:   M;;;;;;;;
				        .  ,' ,   n;;;;;;;;:  n;;;;;;;;;
				       . ,'  ,    N;;;;;;;;:  n;;;;;;;;;
				      . '   ,     N;;;;;;;;;: N;;;;;;;;;;
				     .,'   .      N;;;;;;;;;: N;;;;;;;;;;
				    ..    ,       N6666666666 N6666666666
				    I    ,        M           M
				   ---nnnnn_______M___________M______mmnnn
				         \"-.                          /
				  __________\"-_______________________/_________
			    ");
		}
	}
}