<?php

/*
 * This file is a part of boxlight/php-opencloud-service-provider
 */

namespace Boxlight\Silex\Provider\OpenCloud;

use Silex\Application;
use Silex\ServiceProviderInterface;

use OpenCloud;

/**
 * OpenCloud Service Provider.
 *
 * @author Robert Cambridge <robert@boxlightmedia.com>
 */
class DoctrineOrmServiceProvider implements ServiceProviderInterface
{
    public function boot(Application $app)
    {

    }

    public function register(Application $app)
    {
        $app['opencloud'] = $app->share(function () use ($app) {
            $variant = isset($app['opencloud.variant']) ? $app['opencloud.variant'] : 'OpenStack';

            $fqcn = sprintf('OpenCloud\%s', $variant);

            return new $fqcn($app['opencloud.endpoint'], $app['opencloud.credentials'], $app['opencloud.options']);
        });
    }
}
