<?php

namespace dekuan\delib;
use dekuan\vdata\CConst;


/**
 *	Created by PhpStorm.
 *	User: xing
 *	Date: 12:49 AM, February 17, 2017
 */
class testCLib extends \PHPUnit\Framework\TestCase
{
	public function testGetClientIP()
	{
		$arrVarList	=
		[
			[ true,	true,	'HTTP_VDATA_FORWARDED_FOR', '106.39.200.1,106.39.200.2', '106.39.200.1' ],
			[ true,	false,	'HTTP_VDATA_FORWARDED_FOR', '106.39.200.1,106.39.200.2', '' ],
			[ false, true,	'HTTP_VDATA_FORWARDED_FOR', '106.39.200.1,106.39.200.2', '106.39.200.1' ],
			[ false, false,	'HTTP_VDATA_FORWARDED_FOR', '106.39.200.1,106.39.200.2', '' ],

			[ true,	true,	'REMOTE_ADDR', '106.39.200.3', '106.39.200.3' ],
			[ true,	false,	'REMOTE_ADDR', '106.39.200.3', '106.39.200.3' ],
			[ false, true,	'REMOTE_ADDR', '106.39.200.3', '106.39.200.3' ],
			[ false, false,	'REMOTE_ADDR', '106.39.200.3', '106.39.200.3' ],

			[ true,	true,	'HTTP_X_TRUE_IP', '45.34.23.101, 115.238.232.96', '45.34.23.101' ],
			[ true,	false,	'HTTP_X_TRUE_IP', '45.34.23.101, 115.238.232.96', '45.34.23.101' ],
			[ false, true,	'HTTP_X_TRUE_IP', '45.34.23.101, 115.238.232.96', '45.34.23.101' ],
			[ false, false,	'HTTP_X_TRUE_IP', '45.34.23.101, 115.238.232.96', '45.34.23.101' ],

			[ true,	true,	'HTTP_X_FORWARDED_FOR', '106.39.200.5,106.39.200.6', '106.39.200.5' ],
			[ true,	false,	'HTTP_X_FORWARDED_FOR', '106.39.200.5,106.39.200.6', '' ],
			[ false, true,	'HTTP_X_FORWARDED_FOR', '106.39.200.5,106.39.200.6', '106.39.200.5' ],
			[ false, false,	'HTTP_X_FORWARDED_FOR', '106.39.200.5,106.39.200.6', '' ],
		];

		foreach ( $arrVarList as $arrData )
		{
			$bMustBePublic	= $arrData[ 0 ];
			$bPlayWithProxy	= $arrData[ 1 ];
			$sKey		= $arrData[ 2 ];
			$sValue		= $arrData[ 3 ];
			$sExpect	= $arrData[ 4 ];

			$_SERVER	=
			[
				$sKey	=> $sValue,
			];

			echo "\r\n";
			echo "+ KEY:    " . $sKey . "\r\n  VALUE:  " . $sValue . "\r\n";

			echo "  PARAM:  MustBePublic=" . ( $bMustBePublic ? "true" : "false" ) . ", ";
			echo "PlayWithProxy=" . ( $bPlayWithProxy ? "true" : "false" ) . "\r\n";
			$sClientIP	= CLib::GetClientIP( $bMustBePublic, $bPlayWithProxy );
			echo "  GOT IP: \"" . $sClientIP . "\"\r\n  EXPECT: \"" . $sExpect . "\"\r\n";

			//	...
			new CAssertResult
			(
				__CLASS__, __FUNCTION__, 'CLib::GetClientIP', strcasecmp( $sExpect, $sClientIP )
			);
		}

		//	...
		$_SERVER	= [];

		foreach ( $arrVarList as $arrData )
		{
			$sKey		= $arrData[ 2 ];
			$sValue		= $arrData[ 3 ];

			//	...
			$_SERVER[ $sKey ] = $sValue;
		}
		unset( $_SERVER[ 'HTTP_VDATA_FORWARDED_FOR' ] );

		//	...
		$sClientIP	= CLib::GetClientIP( false, true );
		echo "\r\n+ Play With Proxy=true\r\n";
		echo "\tRESULT:\t" . $sClientIP . "\r\n";

		//	...
		new CAssertResult
		(
			__CLASS__,
			__FUNCTION__,
			'CLib::GetClientIP',
			strcasecmp( '45.34.23.101', $sClientIP )
		);
	}
	
	public function testIsValidMobile()
	{
		echo( __FUNCTION__ . "\r\n" );

		//
		//	static function IsValidMobile( $sStr, $bTrim = false )
		//
		$arrVarList	=
		[
			[ true,	false,	'13011070903' ],
			[ true,	false,	'13111070903' ],
			[ true,	false,	'13211070903' ],
			[ true,	false,	'13311070903' ],
			[ true,	false,	'13411070903' ],
			[ true,	false,	'13511070903' ],
			[ true,	false,	'13611070903' ],
			[ true,	false,	'13711070903' ],
			[ true,	false,	'13811070903' ],
			[ true,	false,	'13911070903' ],

			[ true,	true,	' 13011070903 ' ],
			[ true,	true,	' 13111070903 ' ],
			[ true,	true,	' 13211070903 ' ],
			[ true,	true,	' 13311070903 ' ],
			[ true,	true,	' 13411070903 ' ],
			[ true,	true,	' 13511070903 ' ],
			[ true,	true,	' 13611070903 ' ],
			[ true,	true,	' 13711070903 ' ],
			[ true,	true,	' 13811070903 ' ],
			[ true,	true,	' 13911070903 ' ],

			[ false, false,	' 13911070903 ' ],

			[ false, true,	'13911070903' ],
			[ false, true,	'139110709' ],
			[ false, true,	'13911070' ],
			[ false, true,	'1391107' ],
			[ false, true,	'139110' ],
			[ false, true,	'13911' ],
			[ false, true,	'1391' ],
			[ false, true,	'139' ],
			[ false, true,	'13' ],
			[ false, true,	'1' ],
			[ false, true,	'' ],
			[ false, true,	null ],
			[ false, true,	[] ],
		];

		foreach ( $arrVarList as $arrData )
		{
			$bExpect	= $arrData[ 0 ];
			$bTrim		= $arrData[ 1 ];
			$sValue		= $arrData[ 2 ];

			$bValid		= CLib::IsValidMobile( $sValue, $bTrim );

			//	...
			new CAssertResult
			(
				__CLASS__,
				__FUNCTION__,
				'CLib::IsValidMobile',
				( $bValid == $bExpect ? CConst::ERROR_SUCCESS : CConst::ERROR_FAILED )
			);
		}
		
	}
	
	
	
	
}
