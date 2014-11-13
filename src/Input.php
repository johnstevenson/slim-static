<?php
namespace Statical\SlimStatic;

class Input extends SlimBase
{
	public static function file($name)
	{
		return isset($_FILES[$name]) && $_FILES[$name]['size'] ? $_FILES[$name] : null;
	}
}
