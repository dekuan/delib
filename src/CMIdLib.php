<?php

namespace dekuan\delib;

use dekuan\dedid\CDId;


class CMIdLib
{
	/**
	 *	check if a mid is valid
	 *	@param $vMId	mixed
	 *	@return	boolean
	 */
	static function isValidMId( $vMId )
	{
		$bRet	= false;

		if ( is_array( $vMId ) && count( $vMId ) > 0 )
		{
			$bRet	= true;
			foreach ( $vMId as $vItem )
			{
				if ( ! CDId::getInstance()->isValidId( $vItem ) )
				{
					$bRet = false;
					break;
				}
			}
		}
		else
		{
			$bRet = CDId::getInstance()->isValidId( intval( $vMId ) );	
		}

		return $bRet;
	}

	/**
	 *	create a new mid
	 *	@param $nCenter	int
	 *	@param $nNode	int
	 *	@param $sSource	string
	 *	@param $arrData array
	 *	@return	int 
	 */
	static function createMId( $nCenter = 0, $nNode = 0, $sSource = null, & $arrData = null )
	{
		return CDId::getInstance()->createId( $nCenter, $nNode, $sSource, $arrData );
	}
	

}