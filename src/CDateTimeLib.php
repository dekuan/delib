<?php

namespace App\Http\Lib;

use dekuan\delib\CLib;


/**
 *	Class CDateTimeLib
 *	@package App\Http\Lib
 */
class CDateTimeLib
{
	//
	//	0 (for Sunday) through 6 (for Saturday)
	//
	const WEEK_DAY_INDEX_SUNDAY		= 0;
	const WEEK_DAY_INDEX_MONDAY		= 1;
	const WEEK_DAY_INDEX_TUESDAY		= 2;
	const WEEK_DAY_INDEX_WEDNESDAY		= 3;
	const WEEK_DAY_INDEX_THURSDAY		= 4;
	const WEEK_DAY_INDEX_FRIDAY		= 5;
	const WEEK_DAY_INDEX_SATURDAY		= 6;
	const WEEK_DAY_INDEX_MIN		= self::WEEK_DAY_INDEX_SUNDAY;
	const WEEK_DAY_INDEX_MAX		= self::WEEK_DAY_INDEX_SATURDAY;
	const WEEK_DAY_INDEX_COUNT		= self::WEEK_DAY_INDEX_MAX + 1;

	//
	//	1 (for Sunday) through 64 (for Saturday)
	//		1, 2, 4, 8, 16, 32, 64
	//
	const WEEK_DAY_VALUE_SUNDAY		= ( 1 << self::WEEK_DAY_INDEX_SUNDAY );
	const WEEK_DAY_VALUE_MONDAY		= ( 1 << self::WEEK_DAY_INDEX_MONDAY );
	const WEEK_DAY_VALUE_TUESDAY		= ( 1 << self::WEEK_DAY_INDEX_TUESDAY );
	const WEEK_DAY_VALUE_WEDNESDAY		= ( 1 << self::WEEK_DAY_INDEX_WEDNESDAY );
	const WEEK_DAY_VALUE_THURSDAY		= ( 1 << self::WEEK_DAY_INDEX_THURSDAY );
	const WEEK_DAY_VALUE_FRIDAY		= ( 1 << self::WEEK_DAY_INDEX_FRIDAY );
	const WEEK_DAY_VALUE_SATURDAY		= ( 1 << self::WEEK_DAY_INDEX_SATURDAY );


	
	static function getCurrentWeekDayIndex()
	{
		return intval( date( "w" ) );
	}
	static function getCurrentWeekDayValue()
	{
		return self::getWeekDayValueByIndex( self::getCurrentWeekDayIndex() );
	}
	static function isMatchedWeekDayIndex( $nWeekDayIndex, $nConfig )
	{
		return self::isMatchedWeekDayValue( self::getWeekDayValueByIndex( $nWeekDayIndex ), $nConfig );
	}
	static function isMatchedWeekDayValue( $nWeekDayValue, $nConfig )
	{
		$bRet	= false;

		if ( self::isValidWeekDayValue( $nWeekDayValue ) && is_numeric( $nConfig ) )
		{
			$nWeekDayValue	= intval( $nWeekDayValue );
			$nConfig	= intval( $nConfig );
			
			//	...
			$bRet = ( $nWeekDayValue == ( $nWeekDayValue & $nConfig ) );
		}

		return $bRet;
	}
	static function getCorrectWeekDay( $nWeekDay )
	{
		return ( intval( $nWeekDay ) % 7 );
	}
	static function getWeekDayValueByIndex( $nWeekDayIndex )
	{
		return self::isValidWeekDayIndex( $nWeekDayIndex ) ? ( 1 << $nWeekDayIndex ) : -1;
	}
	static function isValidWeekDayIndex( $nWeekDayIndex )
	{
		return ( is_numeric( $nWeekDayIndex ) &&
			$nWeekDayIndex >= self::WEEK_DAY_INDEX_SUNDAY && $nWeekDayIndex <= self::WEEK_DAY_INDEX_SATURDAY );
	}
	static function isValidWeekDayValue( $nWeekDayValue )
	{
		$bRet	= false;

		if ( is_numeric( $nWeekDayValue ) )
		{
			$nWeekDayValue	= intval( $nWeekDayValue );
			for ( $i = self::WEEK_DAY_INDEX_MIN; $i <= self::WEEK_DAY_INDEX_MAX; $i ++ )
			{
				if ( $nWeekDayValue === ( 1 << $i ) )
				{
					$bRet = true;
					break;
				}
			}
		}

		return $bRet;
	}
	static function isValidWeekDayRepeatValue( $nWeekDayRepeatValue )
	{
		$bRet	= false;

		if ( is_numeric( $nWeekDayRepeatValue ) && $nWeekDayRepeatValue > 0 )
		{
			$nWeekDayRepeatValue	= intval( $nWeekDayRepeatValue );
			for ( $i = self::WEEK_DAY_INDEX_MIN; $i <= self::WEEK_DAY_INDEX_MAX; $i ++ )
			{
				$nWeekDayValue	= ( 1 << $i );
				if ( $nWeekDayValue === ( $nWeekDayValue & $nWeekDayRepeatValue ) )
				{
					$bRet = true;
					break;
				}
			}
		}

		return $bRet;
	}
	
	static function getISODateString( $nTimestamp = null )
	{
		if ( is_numeric( $nTimestamp ) && $nTimestamp > 0 )
		{
			//	YYYY-mm-dd HH:ii:ss by user specified
			return date( "Y-m-d H:i:s", $nTimestamp );
		}
		else
		{
			//	YYYY-mm-dd HH:ii:ss by current OS
			return date( "Y-m-d H:i:s" );
		}
	}

	static function getTimeWithNowTime( $nTimestamp = null )
	{
		return strtotime( self::getISODateTimeString( $nTimestamp ) );
	}
	static function getISODateTimeString( $nTimestamp = null )
	{
		if ( is_numeric( $nTimestamp ) && $nTimestamp > 0 )
		{
			$nTimeNew = mktime
			(
				date( "H" ),
				date( "i" ),
				date( "s" ),
				date( "n", $nTimestamp ),
				date( "j", $nTimestamp ),
				date( "Y", $nTimestamp )
			);
			return date( "Y-m-d H:i:s", $nTimeNew );
		}
		else
		{
			return date( "Y-m-d H:i:s" );
		}
	}

	static function isToday( $sDate )
	{
		if ( ! self::isValidISODateString( $sDate ) &&
			! self::isValidISODateTimeString( $sDate ) )
		{
			return false;	
		}

		$sCheckDate = date( "Y-m-d", strtotime( $sDate ) );
		return ( 0 == strcasecmp( $sCheckDate, date( "Y-m-d" ) ) );
	}
	static function isFuture( $sDate )
	{
		if ( ! self::isValidISODateString( $sDate ) &&
			! self::isValidISODateTimeString( $sDate ) )
		{
			return false;
		}

		$sCheckDate = date( "Y-m-d", strtotime( $sDate ) );
		return ( DateTimeLib::getISODatesDiff( date( "Y-m-d" ), $sCheckDate ) > 0 );
	}
	static function isPast( $sDate )
	{
		if ( ! self::isValidISODateString( $sDate ) &&
			! self::isValidISODateTimeString( $sDate ) )
		{
			return false;
		}

		$sCheckDate = date( "Y-m-d", strtotime( $sDate ) );
		return ( DateTimeLib::getISODatesDiff( date( "Y-m-d" ), $sCheckDate ) < 0 );
	}


	/**
	 *	https://en.wikipedia.org/wiki/ISO_8601
	 * 
	 *	@param	string	$sDate
	 *	@return bool
	 */
	static function isValidISODateString( $sDate )
	{
		if ( ! CLib::IsExistingString( $sDate ) )
		{
			return false;
		}

		$nTime	= strtotime( $sDate );
		return ( false !== $nTime &&
			0 == strcasecmp( $sDate, date( "Y-m-d", $nTime ) ) );
	}
	static function isValidISODateTimeString( $sDateTime )
	{
		if ( ! CLib::IsExistingString( $sDateTime ) )
		{
			return false;
		}

		$nTime	= strtotime( $sDateTime );
		return ( false !== $nTime &&
			0 == strcasecmp( $sDateTime, date( "Y-m-d H:i:s", $nTime ) ) );
	}
	static function getISODatesDiff( $sDate1, $sDate2 )
	{
		//
		//	sDate1	- yyyy-mm-dd or yyyy-mm-dd hh:ii:ss
		//	sDate2	- yyyy-mm-dd or yyyy-mm-dd hh:ii:ss
		//	RETURN	- timestamp in seconds by sDate2 - sDate1
		//
		$nRet	= 0;

		if ( ! self::isValidISODateString( $sDate1 ) &&
			! self::isValidISODateTimeString( $sDate1 ) )
		{
			return 0;
		}
		if ( ! self::isValidISODateString( $sDate2 ) &&
			! self::isValidISODateTimeString( $sDate2 ) )
		{
			return 0;
		}
		
		//	...
		$nTime1	= strtotime( $sDate1 );
		$nTime2	= strtotime( $sDate2 );
		$nRet	= $nTime2 - $nTime1;

		//	...
		return $nRet;
	}
	
	
	
	static function getDaySectionNameByFloat( $fHour )
	{
		if ( ! is_numeric( $fHour ) )
		{
			return '未知';
		}

		$fHour	= floatval( $fHour );
		if ( $fHour > 0 && $fHour < 12 )
		{
			return '上午';
		}
		else if ( $fHour < 18 )
		{
			return '下午';
		}
		else
		{
			return '晚上';
		}
	}

	static function getFloatHour( $nHour, $nMinute )
	{
		return ( floatval( $nHour ) + ( floatval( $nMinute ) / 100 ) );
	}
	static function getTimeByFloat( $fHour )
	{
		$arrRet	= [ 'h' => 0, 'm' => 0 ];

		if ( is_numeric( $fHour ) )
		{
			$sStr	= strval( $fHour );
			$arrT	= explode( '.', $sStr );

			if ( is_array( $arrT ) && count( $arrT ) >= 1 )
			{
				$sHour		= $arrT[ 0 ];
				$sMinute	= count( $arrT ) >= 2 ? substr( $arrT[ 1 ], 0, 2 ) : "00";

				if ( 1 == strlen( $sMinute ) )
				{
					$sMinute = ( $sMinute . "0" );
				}

				$arrRet	= [
					'h'	=> intval( $sHour ),
					'm'	=> intval( $sMinute )
				];
			}
		}

		return $arrRet;
	}
	static function getFormatedTimeByFloat( $fHour )
	{
		if ( ! is_numeric( $fHour ) )
		{
			return '';
		}

		//	....
		$sRet		= '';
		$arrTime	= self::getTimeByFloat( $fHour );
		if ( CLib::IsArrayWithKeys( $arrTime, [ 'h', 'm' ] ) )
		{
			$sRet	= sprintf( "%02d:%02d", $arrTime[ 'h' ], $arrTime[ 'm' ] );
		}

		return $sRet;
	}
	static function getFormatedTimeWithSecondsByFloat( $fHour, $nSeconds = null )
	{
		$sRet	= self::getFormatedTimeByFloat( $fHour );
		if ( CLib::IsExistingString( $sRet ) )
		{
			$sRet = sprintf
			(
				"%s:%s",
				$sRet,
				( is_numeric( $nSeconds ) && $nSeconds >= 0 && $nSeconds <= 60 )
					? sprintf( "%02d", $nSeconds ) : "00"
			);
		}
		
		return $sRet;
	}

	static function getCorrectHour( $fHour )
	{
		return ( floatval( $fHour ) % 24.0 );
	}

	static function getHoursDiff( $fHour1, $fHour2 )
	{
		$fRet	= 0.0;

		if ( is_numeric( $fHour1 ) && is_numeric( $fHour2 ) )
		{
			//	...
			$fHour1		= floatval( $fHour1 );
			$fHour2		= floatval( $fHour2 );
			$fMax		= max( $fHour1, $fHour2 );
			$fMin		= min( $fHour1, $fHour2 );

			$nMaxIntegral	= intval( $fMax );
			$fMaxDecimal	= $fMax - floatval( $nMaxIntegral );

			$nMinIntegral	= intval( $fMin );
			$fMinDecimal	= $fMin - floatval( $nMinIntegral );

			if ( $fMaxDecimal < $fMinDecimal )
			{
				$nMaxIntegral --;
				$fMaxDecimal += 0.60;
			}

			//	...
			$fRet = floatval( $nMaxIntegral - $nMinIntegral ) + ( $fMaxDecimal - $fMinDecimal );
		}

		return $fRet;
	}

	static function isValidTimeValue( $fTime )
	{
		return ( is_numeric( $fTime ) && floatval( $fTime ) >= 0.0 && floatval( $fTime ) < 24.0 );
	}

	static function isDateRangeOverlap( $sStart1, $sEnd1, $sStart2, $sEnd2 )
	{
		if ( ! self::isValidISODateString( $sStart1 ) )
		{
			return false;
		}
		if ( ! self::isValidISODateString( $sEnd1 ) )
		{
			return false;
		}
		if ( ! self::isValidISODateString( $sStart2 ) )
		{
			return false;
		}
		if ( ! self::isValidISODateString( $sEnd2 ) )
		{
			return false;
		}

		//	...
		$nTimeStart1	= strtotime( $sStart1 );
		$nTimeEnd1	= strtotime( $sEnd1 );
		$nTimeStart2	= strtotime( $sStart2 );
		$nTimeEnd2	= strtotime( $sEnd2 );

		return ( $nTimeStart1 <= $nTimeEnd2 && $nTimeStart2 <= $nTimeEnd1 );
	}


	//
	//	计算两个时间段是否有交集
	//	边界重叠也算交集
	//
	static function isTimeRangeOverlap( $fStartTime1, $fEndTime1, $fStartTime2, $fEndTime2, $bCalcBorderOverlap = false )
	{
		if ( ! is_numeric( $fStartTime1 ) )
		{
			return false;
		}
		if ( ! is_numeric( $fEndTime1 ) )
		{
			return false;
		}
		if ( ! is_numeric( $fStartTime2 ) )
		{
			return false;
		}
		if ( ! is_numeric( $fEndTime2 ) )
		{
			return false;
		}

		if ( $bCalcBorderOverlap )
		{
			if ( $fEndTime1 == $fStartTime2 || $fEndTime2 == $fStartTime1 )
			{
				//
				//	two time range were connected by their heads and tails
				//
				return true;
			}
		}

		//	...
		$fDiff1 = $fStartTime2 - $fStartTime1;

		if ( $fDiff1 > 0 )
		{
			$fDiff2 = $fStartTime2 - $fEndTime1;
			if ( $fDiff2 >= 0 )
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			$fDiff2 = $fEndTime2 - $fStartTime1;
			if ( $fDiff2 > 0 )
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
	static function isDateTimeInService( $sDateTime )
	{
		return ( ( self::isValidISODateTimeString( $sDateTime ) || self::isValidISODateString( $sDateTime ) )
			&&
			( @ strtotime( $sDateTime ) - time() ) > 0 );
	}
}