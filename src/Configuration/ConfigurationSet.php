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

    /**
     * ConfigurationSet constructor.
     * @param array $configurations
     */
    public function __construct(array $configurations)
    {
        $this->configurations = $configurations;
    }

    /**
     * @inheritdoc
     */
    public function apply(Injector $di)
    {
        foreach ($this->configurations as $configuration) {
            if (is_string($configuration) AND class_exists($configuration))
            {
                $configuration = $di->make($configuration);
            }
            $configuration->apply($di);
        }
    }
}