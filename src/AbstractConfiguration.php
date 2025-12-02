<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template TConfigs of array<string, mixed>
 *
 * @implements \IteratorAggregate<key-of<TConfigs>, value-of<TConfigs>>
 * @implements \ArrayAccess<key-of<TConfigs>, value-of<TConfigs>>
 */
abstract class AbstractConfiguration implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var TConfigs
     */
    private readonly array $configs;

    final public function __construct(array $configs = [])
    {
        $resolver = new OptionsResolver();
        static::configureOptions($resolver);

        /** @var TConfigs */
        $resolved = $resolver->resolve($configs);

        $this->configs = $resolved;
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset): bool
    {
        return isset($this->configs[$offset]) || \array_key_exists($offset, $this->configs);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset): mixed
    {
        if (!$this->offsetExists($offset)) {
            throw new UndefinedOptionsException(\sprintf('The option "%s" does not exist.', $offset));
        }

        return $this->configs[$offset];
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value): void
    {
        throw new \BadMethodCallException(\sprintf('The method %s::%s doesn\'t supported.', static::class, __FUNCTION__));
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset): void
    {
        throw new \BadMethodCallException(\sprintf('The method %s::%s doesn\'t supported.', static::class, __FUNCTION__));
    }

    #[\ReturnTypeWillChange]
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->configs);
    }

    #[\ReturnTypeWillChange]
    public function count(): int
    {
        return \count($this->configs);
    }

    /**
     * @return TConfigs
     */
    public function toArray(): array
    {
        return $this->configs;
    }

    abstract public static function configureOptions(OptionsResolver $resolver): void;
}
