<?php

namespace Common\InputFilter;

use Traversable;
use Laminas\Stdlib\ArrayUtils;
use Laminas\InputFilter\InputInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\InputFilter\InputFilter as LaminasInputFilter;
use Laminas\InputFilter\Exception\InvalidArgumentException;

class InputFilter extends LaminasInputFilter
{
    protected ?array $user = null;
    protected ?object $request = null;
    protected ?array $collectionNames = [];

    public function setRequest(object $request): void
    {
        $this->request = $request;
    }

    public function getRequest(): ?object
    {
        return $this->request;
    }

    public function setUser(array $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?array
    {
        return $this->user;
    }

    public function getCollectionNames(): array
    {
        return $this->collectionNames;
    }

    public function setCollectionNames(array $names): void
    {
        $this->collectionNames = $names;
    }

    /**
     * Returns the input data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data ?? [];
    }

    /**
     * Add an input to the input filter
     *
     * @param array|Traversable|InputInterface|InputFilterInterface $input
     * @param null|string $name
     * @return self
     */
    public function add($input, $name = null): self
    {
        if (!is_array($input) && !$input instanceof Traversable && !$input instanceof InputInterface && !$input instanceof InputFilterInterface) {
            throw new InvalidArgumentException("Invalid input type.");
        }

        parent::add($input, $name);
        return $this;
    }
}
