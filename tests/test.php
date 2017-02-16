<?php

require_once dirname( __DIR__ ) . '/src/CEnv.php';
require_once dirname( __DIR__ ) . '/src/CLib.php';

use dekuan\delib;


/**
 * Created by PhpStorm.
 * User: xing
 * Date: 17/02/2017
 * Time: 12:49 AM
 */
class test extends PHPUnit_Framework_TestCase
{
	public function testGetClientIP()
	{
		$arrVarList	=
		[
			'HTTP_VDATA_FORWARDED_FOR'	=> '106.39.200.1,106.39.200.2',
			'REMOTE_ADDR'			=> '106.39.200.3',
			'X-Real-IP'			=> '106.39.200.4',
			'HTTP_X_FORWARDED_FOR'		=> '106.39.200.5',
		];
		$arrStatus	=
		[
			[ true, true ],
			[ true, false ],
			[ false, true ],
			[ false, false ],
		];

		foreach ( $arrVarList as $sKey => $sVal )
		{
			$_SERVER	=
			[
				$sKey	=> $sVal,
			];

			echo "\r\n";
			echo "+ KEY:" . $sKey . "\r\n\tVALUE:\t" . $sVal . "\r\n";

			foreach ( $arrStatus as $arrSt )
			{
				echo "\t+ MustBePublic=" . ( $arrSt[ 0 ] ? "true" : "false" ) . ", ";
				echo "PlayWithProxy=" . ( $arrSt[ 1 ] ? "true" : "false" ) . "\r\n";
				$sClientIP	= delib\CLib::GetClientIP( $arrSt[ 0 ], $arrSt[ 1 ] );
				echo "\t\tRESULT:\t" . $sClientIP . "\r\n";
			}
		}

		//	...
		$_SERVER	= $arrVarList;
		$sClientIP	= delib\CLib::GetClientIP( false, true );
		echo "\r\n+ Play With Proxy=true\r\n";
		print_r( $_SERVER );
		echo "\tRESULT:\t" . $sClientIP . "\r\n";

		//	...
		$_SERVER	= $arrVarList;
		$sClientIP	= delib\CLib::GetClientIP( false, false );
		echo "\r\n+ Play With Proxy=false\r\n";
		print_r( $_SERVER );
		echo "\tRESULT:\t" . $sClientIP . "\r\n";
	}
}
