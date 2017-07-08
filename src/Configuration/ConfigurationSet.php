<?php namespace Radar\Adr\Configuration;
use Auryn\Injector;

/**
 * @author george
 */
class ConfigurationSet implements Configuration
{
    /**
     * @var Configuration[]
     */
    private $configurations;

    public function __construct(array $configurations)
    {
        $this->configurations = $configurations;
    }

    public function configure(Injector $di)
    {
        foreach ($this->configurations as $configuration) {
            $configuration->configure($di);
        }
    }
}