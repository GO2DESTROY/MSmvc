<?php

namespace system\helpers;

class MS_utilization
{
	public static function returnMemoryOfPHP()
	{
		$unit=array('b','kb','mb','gb','tb','pb');
		return @round(memory_get_usage(true)/pow(1024,($i=floor(log(memory_get_usage(true),1024)))),2).' '.$unit[$i];
	}
}