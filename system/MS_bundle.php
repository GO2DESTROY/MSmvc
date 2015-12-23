<?php

namespace system;

use system\pipelines\MS_pipeline;

class MS_bundle
{
	private static $scriptSet;
	private static $styleSet;

	public static function returnStyleCollection($masterview) {
		if(isset(self::$styleSet['*']) && isset(self::$styleSet[$masterview])) {
			return array_merge(self::$styleSet['*'], self::$styleSet[$masterview]);
		}
		elseif(isset(self::$styleSet['*'])) {
			return self::$styleSet['*'];
		}
		elseif(isset(self::$styleSet[$masterview])) {
			return self::$styleSet[$masterview];
		}
		else {
			return NULL;
		}
	}

	public static function returnScriptCollection($masterview) {
		if(isset(self::$scriptSet['*']) && isset(self::$scriptSet[$masterview])) {
			return array_merge(self::$scriptSet['*'], self::$scriptSet[$masterview]);
		}
		elseif(isset(self::$scriptSet['*'])) {
			return self::$scriptSet['*'];
		}
		elseif(isset(self::$scriptSet[$masterview])) {
			return self::$scriptSet[$masterview];
		}
		else {
			return NULL;
		}
	}

	public static function javascript($path, $masterViewSet = ['*'], $relative = TRUE) {
		$prefix = $relative === TRUE ? MS_pipeline::$root : '';
		foreach($masterViewSet as $value) {
			self::$scriptSet[$value][] = $prefix . $path;
		}
	}

	public static function stylesheet($path, $masterViewSet = ['*'], $relative = TRUE) {
		$prefix = $relative === TRUE ? MS_pipeline::$root : '';
		foreach($masterViewSet as $value) {
			self::$styleSet[$value][] = $prefix . $path;
		}
	}
}