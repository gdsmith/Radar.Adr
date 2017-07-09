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
use Psr\Log\LoggerInterface;
use Radar\Adr\Handler\ActionHandler;
use Radar\Adr\Resolver;
use Radar\Adr\Route;
use Relay\RelayBuilder;
use Relay\ResolverInterface;

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
    protected $di;
    /**
     * @inheritdoc
     */
    public function apply(Injector $di)
    {
        $this->di = $di;
        if( ! count($di->inspect(LoggerInterface::class, Injector::I_ALIASES)[Injector::I_ALIASES]))
        {
            $di->alias(LoggerInterface::class, NullLogger::class);
        }

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
         * Relay\RelayBuilder
         */
        $di->alias(ResolverInterface::class, Resolver::class);
        $di->share(ResolverInterface::class);
        $di->defineParam('resolver', $di->make(ResolverInterface::class));

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
     * @return Matcher
     */
    public function delegateMatcher(Injector $di)
    {
        return $di->make(RouterContainer::class)->getMatcher();
    }

    /**
     * @param Injector $di
     */
    public function prepareRouterContainer(RouterContainer $routerContainer, Injector $di)
    {
        $routerContainer->setRouteFactory($di->buildExecutable([$this, 'makeRoute']));
        $routerContainer->setLoggerFactory($di->buildExecutable([$this, 'makeLogger']));
//        $routerContainer->setMapFactory($di->buildExecutable([$this, 'makeMap']));
    }

    /**
     * @param Injector $di
     */
    public function makeRoute()
    {
        return $this->di->make(Route::class);
    }

    /**
     * @param Injector $di
     */
    public function makeLogger()
    {
        return $this->di->make(LoggerInterface::class);
    }

    /**
     * @param Injector $di
     */
    public function makeMap()
    {
        return $this->di->make(Map::class);
    }
}
