<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Attributes;

use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPUnit\Framework\TestCase;

final class AttributesTest extends TestCase
{

	/** @var PhpDocNode */
	private $phpDocNode;

	/**
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();
		$lexer = new Lexer();
		$constExprParser = new ConstExprParser();
		$phpDocParser = new PhpDocParser(new TypeParser($constExprParser), $constExprParser);

		$input = '/** @var string */';
		$tokens = new TokenIterator($lexer->tokenize($input));
		$this->phpDocNode = $phpDocParser->parse($tokens);
	}

	/**
	 * @return void
	 */
	public function testGetAttribute()
	{
		$unKnownValue = $this->phpDocNode->getAttribute('unknown');
		$this->assertNull($unKnownValue);
	}

	/**
	 * @return void
	 */
	public function testSetAttribute()
	{
		$this->phpDocNode->setAttribute('key', 'value');

		$attributeValue = $this->phpDocNode->getAttribute('key');
		$this->assertSame('value', $attributeValue);
	}

}
