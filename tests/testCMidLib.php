<?php

namespace dekuan\delib;

use dekuan\vdata\CConst;


/**
 *	Created by PhpStorm.
 *	User: xing
 *	Date: 16:09, December 25, 2017
 */
class testCMidLib extends \PHPUnit\Framework\TestCase
{
	public function testIsValidMId()
	{
		$arrVarList	=
		[
			[ true,		148034115453586888 ],
			[ true,		148034115453586889 ],
			[ true,		148034115453586886 ],
			[ false,	0 ],
			[ false,	null ],
			[ false,	[] ],
		];

		foreach ( $arrVarList as $arrData )
		{
			$bExpect	= $arrData[ 0 ];
			$vValue		= $arrData[ 1 ];

			$bReturnValue	= CMIdLib::isValidMId( $vValue );
			$sDumpString	= "" .
			"+ VALUE:  " . ( is_array( $vValue ) ? "[]" : strval( $vValue ) ) . "\r\n" .
			"  RETURN: \"" . $bReturnValue . "\"\r\n  EXPECT: \"" . ( $bExpect ? "true" : "false" ) . "\"\r\n";

			//	...
			new CAssertResult
			(
				__CLASS__,
				__FUNCTION__,
				'CLib::isValidMId',
				( $bReturnValue === $bExpect ? CConst::ERROR_SUCCESS : CConst::ERROR_FAILED ),
				$sDumpString
			);
		}
	}

	
	
	
	
}
