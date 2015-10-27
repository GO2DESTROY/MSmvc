<?php

namespace system;
class MS_utilization{
	public function returnMemoryOfPHP()
	{
		$unit=array('b','kb','mb','gb','tb','pb');
		return @round(memory_get_usage(true)/pow(1024,($i=floor(log(memory_get_usage(true),1024)))),2).' '.$unit[$i];
	}
}