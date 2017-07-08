<?php
namespace Radar\Adr;

use Aura\Di\ContainerBuilder;
use Aura\Router\Rule\RuleIterator;
use Auryn\Injector;
use Radar\Adr\Fake\FakeMiddleware;
use Radar\Adr\Route;
use Relay\RelayBuilder;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class AdrTest extends \PHPUnit_Framework_TestCase
{
    protected $adr;
    protected $fakeMap;
    protected $fakeRules;
    protected $relayBuilder;

    public function setUp()
    {
        $resolver = new Resolver(new Injector());

        $this->fakeMap = new Fake\FakeMap($resolver(Route::class));
        $this->fakeRules = new RuleIterator();
        $this->relayBuilder = new RelayBuilder($resolver);

        $this->adr = new Adr(
            $this->fakeMap,
            $this->fakeRules,
            $this->relayBuilder
        );
    }

    public function testRules()
    {
        $this->assertSame($this->adr->rules(), $this->fakeRules);
    }

    public function testProxyToMap()
    {
        $expect = 'Radar\Adr\Fake\FakeMap::fakeMapMethod';
        $actual = $this->adr->fakeMapMethod();
        $this->assertSame($expect, $actual);
    }

    public function testRun()
    {
        FakeMiddleware::$count = 0;

        $this->adr->middle('Radar\Adr\Fake\FakeMiddleware');
        $this->adr->middle('Radar\Adr\Fake\FakeMiddleware');
        $this->adr->middle('Radar\Adr\Fake\FakeMiddleware');

        $response = $this->adr->run(
            ServerRequestFactory::fromGlobals(),
            new Response()
        );

        $actual = (string) $response->getBody();
        $this->assertSame('123456', $actual);
    }
}
