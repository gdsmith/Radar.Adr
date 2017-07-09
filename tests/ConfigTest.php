<?php
namespace Radar\Adr;

use Aura\Router\RouterContainer;
use Auryn\Injector;
use Radar\Adr\Configuration\RadarConfiguration;
use Radar\Adr\Handler\ActionHandler;
use Radar\Adr\Handler\RoutingHandler;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Injector
     */
    private $di;

    protected function setUp()
    {
        $this->di = new Injector;

    }

    public function testConfiguration()
    {
        $configuration = new RadarConfiguration();
        $configuration->apply($this->di);
        return $this->checkConfiguration([
            Adr::class,
            Resolver::class,
            RouterContainer::class,
            ActionHandler::class,
            RoutingHandler::class,
        ]);
    }

    private function checkConfiguration(array $classes)
    {
        foreach ($classes as $class) {
            $this->assertInstanceOf($class, $this->di->make($class));
        }
    }
}
