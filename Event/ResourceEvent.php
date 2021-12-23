<?php

namespace Acilia\Bundle\TranslationBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ResourceEvent extends Event
{
    const EVENT_WARMUP = 'translation.warmup';
    const EVENT_LOAD = 'translation.load';

    protected $resource;
    protected $culture;
    protected $version;

    public function setResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function setCulture(string $culture): self
    {
        $this->culture = $culture;

        return $this;
    }

    public function getCulture(): string
    {
        return $this->culture;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
