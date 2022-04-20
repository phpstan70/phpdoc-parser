<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast;

interface Node
{

	public function __toString(): string;

	/**
	 * @param mixed $value
	 *
	 * @return void
	 * @param string $key
	 */
	public function setAttribute($key, $value);

	/**
	 * @param string $key
	 */
	public function hasAttribute($key): bool;

	/**
	 * @return mixed
	 * @param string $key
	 */
	public function getAttribute($key);

}
