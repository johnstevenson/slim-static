<?php
namespace Statical\SlimStatic;

class Config extends SlimSugar
{
	public static function get($key)
	{
		return static::$slim->config($key);
	}

	public static function set($key, $value)
	{
		return static::$slim->config($key, $value);
	}
}
