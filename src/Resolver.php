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
use Relay\ResolverInterface;

/**
 *
 * Resolves object specifications using the DI container.
 *
 * @package radar/adr
 *
 */
class Resolver implements ResolverInterface
{
    /**
     *
     * The injection factory from the DI container.
     *
     * @var InjectionFactory
     *
     */
    protected $di;

    /**
     *
     * Constructor.
     *
     * @param InjectionFactory $injectionFactory The injection factory from the
     * DI container.
     *
     */
    public function __construct(Injector $di)
    {
        $this->di = $di;
    }

    /**
     *
     * Resolves an object specification.
     *
     * @param mixed $spec The object specification.
     *
     * @return mixed
     *
     */
    public function __invoke($spec)
    {
        if (is_string($spec)) {
            return $this->di->make($spec);
        }

        if (is_array($spec) && is_string($spec[0])) {
            $spec[0] = $this->di->make($spec[0]);
        }

        return $spec;
    }
}
