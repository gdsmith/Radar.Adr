<?php
/**
 *
 * This file is part of Radar for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Radar\Adr;

use Auryn\Injector;
use Radar\Adr\Configuration\ConfigurationSet;
use Radar\Adr\Configuration\RadarConfiguration;

/**
 *
 * Bootstraps the ADR instance with DI container configuration.
 *
 * @package radar/adr
 *
 */
class Boot
{
    /**
     *
     * A path to a serialized container cache.
     *
     * @var string
     *
     */
    protected $containerCache;

    /**
     *
     * Constructor.
     *
     * @param string $containerCache A path to a serialized container cache.
     *
     */
    public function __construct($containerCache = null)
    {
        $this->containerCache = $containerCache;
    }

    /**
     *
     * Returns a new ADR instance using container configurations.
     *
     * @param array $config An array of container configuration class names
     * and/or instances.
     *
     * @param bool $autoResolve Use the auto-resolving DI container?
     *
     * @return Adr
     *
     */
    public function adr(array $config = [], $autoResolve = false)
    {
        if ($this->containerCache) {
            $di = $this->cachedContainer($config, $autoResolve);
        } else {
            $di = $this->newContainer($config, $autoResolve);
        }

        return $di->make(Adr::class);
    }

    /**
     *
     * Builds and returns a container using the container cache.
     *
     * @param array $config An array of container configuration class names
     * and/or instances.
     *
     * @param bool $autoResolve Use the auto-resolving DI container?
     *
     * @return Container
     *
     */
    protected function cachedContainer(array $config, $autoResolve = false)
    {
        if (file_exists($this->containerCache)) {
            return unserialize(file_get_contents($this->containerCache));
        }

        $di = $this->newContainer($config, $autoResolve);
        file_put_contents($this->containerCache, serialize($di));
        return $di;
    }

    /**
     *
     * Builds and returns a new, uncached container.
     *
     * @param array $config An array of container configuration class names
     * and/or instances.
     *
     * @param bool $autoResolve Use the auto-resolving DI container?
     *
     * @return Injector
     *
     */
    protected function newContainer(array $config, $autoResolve = false)
    {
        $di = new Injector();
        (new ConfigurationSet(array_merge([new RadarConfiguration], $config)))->configure($di);
        return $di;
    }
}
