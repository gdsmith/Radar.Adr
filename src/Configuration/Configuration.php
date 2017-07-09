<?php namespace Radar\Adr\Configuration;
use Auryn\Injector;

/**
 * @author george
 */
interface Configuration
{
    /**
     * @param Injector $di
     * @return null
     */
    public function apply(Injector $di);
}