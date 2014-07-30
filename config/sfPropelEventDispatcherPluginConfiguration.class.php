<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class sfPropelEventDispatcherPluginConfiguration extends sfPluginConfiguration
{
    public function initialize()
    {
        // Load Services from services.yml
        $this->dispatcher->connect(
            'service_container.load_configuration',
            function (sfEvent $event) {
                $loader = new XmlFileLoader($event->getSubject(), new FileLocator(__DIR__));
                $loader->load('services.xml');
                $event->getSubject()->addCompilerPass(new RegisterEventListenersPass());
            }
        );

        // Load Services from services.yml
        $this->dispatcher->connect(
            'service_container.loaded',
            function (sfEvent $event) {
                $container = $event->getSubject();
                $container->get('propel_event_dispatcher.injector')->initializeModels();
            }
        );
    }
}


