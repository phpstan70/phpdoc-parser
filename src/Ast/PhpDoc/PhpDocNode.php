<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function array_column;
use function array_filter;
use function array_map;
use function implode;

class PhpDocNode implements Node
{

	use NodeAttributes;

	/** @var PhpDocChildNode[] */
	public $children;

	/**
	 * @param PhpDocChildNode[] $children
	 */
	public function __construct(array $children)
	{
		$this->children = $children;
	}


	/**
	 * @return PhpDocTagNode[]
	 */
	public function getTags(): array
	{
		return array_filter($this->children, static function (PhpDocChildNode $child): bool {
			return $child instanceof PhpDocTagNode;
		});
	}


	/**
	 * @return PhpDocTagNode[]
	 * @param string $tagName
	 */
	public function getTagsByName($tagName): array
	{
		return array_filter($this->getTags(), static function (PhpDocTagNode $tag) use ($tagName): bool {
			return $tag->name === $tagName;
		});
	}


	/**
	 * @return VarTagValueNode[]
	 * @param string $tagName
	 */
	public function getVarTagValues($tagName = '@var'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof VarTagValueNode;
			}
		);
	}


	/**
	 * @return ParamTagValueNode[]
	 * @param string $tagName
	 */
	public function getParamTagValues($tagName = '@param'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof ParamTagValueNode;
			}
		);
	}


	/**
	 * @return TemplateTagValueNode[]
	 * @param string $tagName
	 */
	public function getTemplateTagValues($tagName = '@template'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof TemplateTagValueNode;
			}
		);
	}


	/**
	 * @return ExtendsTagValueNode[]
	 * @param string $tagName
	 */
	public function getExtendsTagValues($tagName = '@extends'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof ExtendsTagValueNode;
			}
		);
	}


	/**
	 * @return ImplementsTagValueNode[]
	 * @param string $tagName
	 */
	public function getImplementsTagValues($tagName = '@implements'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof ImplementsTagValueNode;
			}
		);
	}


	/**
	 * @return UsesTagValueNode[]
	 * @param string $tagName
	 */
	public function getUsesTagValues($tagName = '@use'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof UsesTagValueNode;
			}
		);
	}


	/**
	 * @return ReturnTagValueNode[]
	 * @param string $tagName
	 */
	public function getReturnTagValues($tagName = '@return'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof ReturnTagValueNode;
			}
		);
	}


	/**
	 * @return ThrowsTagValueNode[]
	 * @param string $tagName
	 */
	public function getThrowsTagValues($tagName = '@throws'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof ThrowsTagValueNode;
			}
		);
	}


	/**
	 * @return MixinTagValueNode[]
	 * @param string $tagName
	 */
	public function getMixinTagValues($tagName = '@mixin'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof MixinTagValueNode;
			}
		);
	}


	/**
	 * @return DeprecatedTagValueNode[]
	 */
	public function getDeprecatedTagValues(): array
	{
		return array_filter(
			array_column($this->getTagsByName('@deprecated'), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof DeprecatedTagValueNode;
			}
		);
	}


	/**
	 * @return PropertyTagValueNode[]
	 * @param string $tagName
	 */
	public function getPropertyTagValues($tagName = '@property'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof PropertyTagValueNode;
			}
		);
	}


	/**
	 * @return PropertyTagValueNode[]
	 * @param string $tagName
	 */
	public function getPropertyReadTagValues($tagName = '@property-read'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof PropertyTagValueNode;
			}
		);
	}


	/**
	 * @return PropertyTagValueNode[]
	 * @param string $tagName
	 */
	public function getPropertyWriteTagValues($tagName = '@property-write'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof PropertyTagValueNode;
			}
		);
	}


	/**
	 * @return MethodTagValueNode[]
	 * @param string $tagName
	 */
	public function getMethodTagValues($tagName = '@method'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof MethodTagValueNode;
			}
		);
	}


	/**
	 * @return TypeAliasTagValueNode[]
	 * @param string $tagName
	 */
	public function getTypeAliasTagValues($tagName = '@phpstan-type'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof TypeAliasTagValueNode;
			}
		);
	}


	/**
	 * @return TypeAliasImportTagValueNode[]
	 * @param string $tagName
	 */
	public function getTypeAliasImportTagValues($tagName = '@phpstan-import-type'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static function (PhpDocTagValueNode $value): bool {
				return $value instanceof TypeAliasImportTagValueNode;
			}
		);
	}


	public function __toString(): string
	{
		$children = array_map(
			static function (PhpDocChildNode $child): string {
				$s = (string) $child;
				return $s === '' ? '' : ' ' . $s;
			},
			$this->children
		);
		return "/**\n *" . implode("\n *", $children) . "\n */";
	}

}
