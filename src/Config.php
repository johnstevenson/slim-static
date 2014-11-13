<?php
namespace Statical\SlimStatic;

class Config extends SlimBase
{
	public static function get($key)
	{
		return static::$slim->config($key);
	}

	public static function set($key, $value)
	{
		return static::$slim->config($key, $value);
	}

    public static function __callStatic($name, $args)
    {
        // Enforce above methods only
        throw new \BadMethodCallException($name.' method not available');
    }
}
