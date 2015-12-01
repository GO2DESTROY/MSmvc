<?php

namespace system;

use system\pipelines\MS_pipeline;

class MS_bundle
{
	private static $scriptSet;
	private static $styleSet;

	public static function returnStyleCollection() {
		return self::$styleSet;
	}

	public static function returnScriptCollection() {
		return self::$scriptSet;
	}

	public static function javascript($path, $masterViewSet = ['*']) {
		foreach($masterViewSet as $value) {
			self::$scriptSet[$value][] = MS_pipeline::$root.$path;
		}
	}

	public static function stylesheet($path, $masterViewSet = ['*']) {
		foreach($masterViewSet as $value) {
			self::$styleSet[$value][] = MS_pipeline::$root.$path;
		}
	}
}