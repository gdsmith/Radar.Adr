<?php
/**
 *
 * This file is part of Radar for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Radar\Adr\Configuration;

use Aura\Router\Map;
use Aura\Router\Matcher;
use Aura\Router\RouterContainer;
use Aura\Router\Rule\RuleIterator;
use Auryn\Injector;
use Radar\Adr\Route;

/**
 *
 * DI container configuration for Radar classes.
 *
 * @package radar/adr
 *
 */
class RadarConfiguration implements Configuration
{
    /**
     * @var Injector
     */
    private $di;
    /**
     *
     * Defines params, setters, values, etc. in the Container.
     *
     * @param Container $di The DI container.
     *
     */
    public function configure(Injector $di)
    {
        $this->di = $di;
        /**
         * Aura\Router\Container
         */
        $di->prepare(RouterContainer::class, [$this, 'prepareRouterContainer'])
            ->share(RouterContainer::class);

        /**
         * Radar\Adr\Adr
         */
        $di->delegate(Map::class, [$this, 'delegateMap']);
        $di->delegate(RuleIterator::class, [$this, 'delegateRuleIterator']);

        /**
         * Radar\Adr\Handler\RoutingHandler
         */
        $di->delegate(Matcher::class, [$this, 'delegateMatcher']);
    }

    /**
     * @param Injector $di
     * @return RuleIterator
     */
    public function delegateRuleIterator(Injector $di)
    {
        return $di->make(RouterContainer::class)->getRuleIterator();
    }

    /**
     * @param Injector $di
     * @return Map
     */
    public function delegateMap(Injector $di)
    {
        return $di->make(RouterContainer::class)->getMap();
    }

    /**
     * @param Injector $di
     * @return \Aura\Router\RuleIterator
     */
    public function delegateMatcher(Injector $di)
    {
        return $di->make(RouterContainer::class)->getMatcher();
    }

    /**
     * @param Injector $di
     * @return \Aura\Router\RuleIterator
     */
    public function prepareRouterContainer(RouterContainer $routerContainer, Injector $di)
    {
        $routerContainer->setRouteFactory([$this, 'makeRoute']);
    }

    /**
     * @param Injector $di
     */
    public function makeRoute()
    {
        $$this->di->make(Route::class);
    }
}
