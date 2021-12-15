<?php

namespace Acilia\Bundle\TranslationBundle\Event;

use Acilia\Bundle\TranslationBundle\Event\ResourceEvent;
use Symfony\Contracts\EventDispatcher\Event;

class ResourcesEvent extends Event implements \Countable
{
    protected array $resources;

    public function __construct()
    {
        $this->resources = [];
    }

    public function count(): int
    {
        return count($this->resources);
    }

    public function addResource(ResourceEvent $resourceEvent): self
    {
        $this->resources[] = $resourceEvent;

        return $this;
    }

    public function getResources(): array
    {
        return $this->resources;
    }
}
