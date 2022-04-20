<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use Iterator;
use PHPStan\PhpDocParser\Ast\Node;
use PHPUnit\Framework\TestCase;

final class NodePrintTest extends TestCase
{

	/**
	 * @dataProvider providePhpDocData
	 *
	 * @return void
	 * @param \PHPStan\PhpDocParser\Ast\Node $node
	 * @param string $expectedPrinted
	 */
	public function testPrintMultiline($node, $expectedPrinted)
	{
		$this->assertSame($expectedPrinted, (string) $node);
	}


	public function providePhpDocData(): Iterator
	{
		yield [
			new PhpDocNode([
				new PhpDocTextNode('It works'),
			]),
			'/**
 * It works
 */',
		];

		yield [
			new PhpDocNode([
				new PhpDocTextNode('It works'),
				new PhpDocTextNode(''),
				new PhpDocTextNode('with empty lines'),
			]),
			'/**
 * It works
 *
 * with empty lines
 */',
		];
	}

}
