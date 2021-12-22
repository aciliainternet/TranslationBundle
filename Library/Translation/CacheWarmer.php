<?php
namespace Acilia\Bundle\TranslationBundle\Library\Translation;

use Acilia\Bundle\TranslationBundle\Event\ResourceEvent;
use Acilia\Bundle\TranslationBundle\Event\ResourcesEvent;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CacheWarmer implements CacheWarmerInterface
{
    protected $eventDispatcher;
    protected $loader;

    public function __construct(EventDispatcherInterface $eventDispatcher, Loader $loader)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->loader = $loader;
    }

    public function warmUp(string $cacheDir): void
    {
        $resourcesEvent = new ResourcesEvent();
        $this->eventDispatcher->dispatch(ResourceEvent::EVENT_WARMUP, $resourcesEvent);

        foreach ($resourcesEvent->getResources() as $resourceEvent) {
            $this->loader->load(
                $resourceEvent->getResource(),
                $resourceEvent->getCulture(),
                $resourceEvent->getVersion()
            );
        }
    }

    public function isOptional(): bool
    {
        return true;
    }
}
