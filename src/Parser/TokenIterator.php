<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Lexer\Lexer;
use function array_pop;
use function assert;
use function count;
use function in_array;
use function strlen;

class TokenIterator
{

	/** @var mixed[][] */
	private $tokens;

	/** @var int */
	private $index;

	/** @var int[] */
	private $savePoints = [];

	public function __construct(array $tokens, int $index = 0)
	{
		$this->tokens = $tokens;
		$this->index = $index;

		if ($this->tokens[$this->index][Lexer::TYPE_OFFSET] !== Lexer::TOKEN_HORIZONTAL_WS) {
			return;
		}

		$this->index++;
	}


	public function currentTokenValue(): string
	{
		return $this->tokens[$this->index][Lexer::VALUE_OFFSET];
	}


	public function currentTokenType(): int
	{
		return $this->tokens[$this->index][Lexer::TYPE_OFFSET];
	}


	public function currentTokenOffset(): int
	{
		$offset = 0;
		for ($i = 0; $i < $this->index; $i++) {
			$offset += strlen($this->tokens[$i][Lexer::VALUE_OFFSET]);
		}

		return $offset;
	}


	/**
	 * @param string $tokenValue
	 */
	public function isCurrentTokenValue($tokenValue): bool
	{
		return $this->tokens[$this->index][Lexer::VALUE_OFFSET] === $tokenValue;
	}


	/**
	 * @param int $tokenType
	 */
	public function isCurrentTokenType($tokenType): bool
	{
		return $this->tokens[$this->index][Lexer::TYPE_OFFSET] === $tokenType;
	}


	public function isPrecededByHorizontalWhitespace(): bool
	{
		return ($this->tokens[$this->index - 1][Lexer::TYPE_OFFSET] ?? -1) === Lexer::TOKEN_HORIZONTAL_WS;
	}


	/**
	 * @throws ParserException
	 *
	 * @return void
	 * @param int $tokenType
	 */
	public function consumeTokenType($tokenType)
	{
		if ($this->tokens[$this->index][Lexer::TYPE_OFFSET] !== $tokenType) {
			$this->throwError($tokenType);
		}

		$this->index++;

		if (($this->tokens[$this->index][Lexer::TYPE_OFFSET] ?? -1) !== Lexer::TOKEN_HORIZONTAL_WS) {
			return;
		}

		$this->index++;
	}


	/**
	 * @throws ParserException
	 *
	 * @return void
	 * @param int $tokenType
	 * @param string $tokenValue
	 */
	public function consumeTokenValue($tokenType, $tokenValue)
	{
		if ($this->tokens[$this->index][Lexer::TYPE_OFFSET] !== $tokenType || $this->tokens[$this->index][Lexer::VALUE_OFFSET] !== $tokenValue) {
			$this->throwError($tokenType, $tokenValue);
		}

		$this->index++;

		if (($this->tokens[$this->index][Lexer::TYPE_OFFSET] ?? -1) !== Lexer::TOKEN_HORIZONTAL_WS) {
			return;
		}

		$this->index++;
	}


	/** @phpstan-impure
	 * @param string $tokenValue */
	public function tryConsumeTokenValue($tokenValue): bool
	{
		if ($this->tokens[$this->index][Lexer::VALUE_OFFSET] !== $tokenValue) {
			return false;
		}

		$this->index++;

		if ($this->tokens[$this->index][Lexer::TYPE_OFFSET] === Lexer::TOKEN_HORIZONTAL_WS) {
			$this->index++;
		}

		return true;
	}


	/** @phpstan-impure
	 * @param int $tokenType */
	public function tryConsumeTokenType($tokenType): bool
	{
		if ($this->tokens[$this->index][Lexer::TYPE_OFFSET] !== $tokenType) {
			return false;
		}

		$this->index++;

		if ($this->tokens[$this->index][Lexer::TYPE_OFFSET] === Lexer::TOKEN_HORIZONTAL_WS) {
			$this->index++;
		}

		return true;
	}


	public function getSkippedHorizontalWhiteSpaceIfAny(): string
	{
		if ($this->index > 0 && $this->tokens[$this->index - 1][Lexer::TYPE_OFFSET] === Lexer::TOKEN_HORIZONTAL_WS) {
			return $this->tokens[$this->index - 1][Lexer::VALUE_OFFSET];
		}

		return '';
	}


	/** @phpstan-impure
	 * @param int ...$tokenType */
	public function joinUntil(...$tokenType): string
	{
		$s = '';
		while (!in_array($this->tokens[$this->index][Lexer::TYPE_OFFSET], $tokenType, true)) {
			$s .= $this->tokens[$this->index++][Lexer::VALUE_OFFSET];
		}
		return $s;
	}

	/**
	 * @return void
	 */
	public function next()
	{
		$this->index++;

		if ($this->tokens[$this->index][Lexer::TYPE_OFFSET] !== Lexer::TOKEN_HORIZONTAL_WS) {
			return;
		}

		$this->index++;
	}

	/**
	 * @phpstan-impure
	 * @return void
	 */
	public function forwardToTheEnd()
	{
		$lastToken = count($this->tokens) - 1;
		$this->index = $lastToken;
	}

	/**
	 * @return void
	 */
	public function pushSavePoint()
	{
		$this->savePoints[] = $this->index;
	}

	/**
	 * @return void
	 */
	public function dropSavePoint()
	{
		array_pop($this->savePoints);
	}

	/**
	 * @return void
	 */
	public function rollback()
	{
		$index = array_pop($this->savePoints);
		assert($index !== null);
		$this->index = $index;
	}


	/**
	 * @throws ParserException
	 *
	 * @return void
	 */
	private function throwError(int $expectedTokenType, string $expectedTokenValue = null)
	{
		throw new ParserException(
			$this->currentTokenValue(),
			$this->currentTokenType(),
			$this->currentTokenOffset(),
			$expectedTokenType,
			$expectedTokenValue
		);
	}

}
