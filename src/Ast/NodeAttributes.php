<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast;

use function array_key_exists;

trait NodeAttributes
{

	/** @var array<string, mixed> */
	private $attributes = [];

	/**
	 * @param mixed $value
	 *
	 * @return void
	 * @param string $key
	 */
	public function setAttribute($key, $value)
	{
		$this->attributes[$key] = $value;
	}

	/**
	 * @param string $key
	 */
	public function hasAttribute($key): bool
	{
		return array_key_exists($key, $this->attributes);
	}

	/**
	 * @return mixed
	 * @param string $key
	 */
	public function getAttribute($key)
	{
		if ($this->hasAttribute($key)) {
			return $this->attributes[$key];
		}

		return null;
	}

}
