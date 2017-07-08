<?php namespace Radar\Adr\Configuration;
use Auryn\Injector;

/**
 * @author george
 */
interface Configuration
{
    public function configure(Injector $di);
}