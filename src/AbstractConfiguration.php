<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractConfiguration implements \Countable, \IteratorAggregate, \ArrayAccess, ResolverInterface
{
    private array $configs;

    public function __construct(array $configs = [])
    {
        $this->configs = $this->resolve($configs);
    }

    public function resolve(array $options = []): array
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        foreach ($resolver->getDefinedOptions() as $option) {
            if ($resolver->hasDefault($option)) {
                continue;
            }

            $resolver->setDefault($option, null);
            $resolver->addAllowedTypes($option, 'null');
        }

        return $resolver->resolve($options);
    }

    public function extend(ResolverExtensionInterface $extension): void
    {
        throw new \BadMethodCallException(sprintf('The method %s::%s doesn\'t supported.', static::class, __FUNCTION__));
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset): bool
    {
        return isset($this->configs[$offset]) || \array_key_exists($offset, $this->configs);
    }

    /**
     * @param string $offset
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new UndefinedOptionsException(sprintf('The option "%s" does not exist.', $offset));
        }

        return $this->configs[$offset];
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value): void
    {
        throw new \BadMethodCallException(sprintf('The method %s::%s doesn\'t supported.', static::class, __FUNCTION__));
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset): void
    {
        throw new \BadMethodCallException(sprintf('The method %s::%s doesn\'t supported.', static::class, __FUNCTION__));
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

    public function toArray(): array
    {
        return $this->configs;
    }

    abstract protected function configureOptions(OptionsResolver $resolver): void;
}
