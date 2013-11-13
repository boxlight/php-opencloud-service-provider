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
class OpenCloudServiceProvider implements ServiceProviderInterface
{
    public function boot(Application $app)
    {

    }

    public function register(Application $app)
    {
        $app['opencloud'] = $app->share(function () use ($app) {
            $variant = isset($app['opencloud.variant']) ? $app['opencloud.variant'] : 'OpenStack';

            $fqcn = sprintf('OpenCloud\%s', $variant);

            $opencloud = new $fqcn($app['opencloud.endpoint'], $app['opencloud.secret'], isset($app['opencloud.options']) ? $app['opencloud.options'] : array());

            if (isset($app['opencloud.importCredentials'])) {

                if (is_callable($app['opencloud.importCredentials'])) {
                    $credentials = call_user_func($app['opencloud.importCredentials']);
                } else {
                    $credentials = $app['opencloud.importCredentials'];
                }

                // the result may still be falsy because the callable may return null
                // take that to mean that there are no credentials cached
                if ($credentials) {
                    $opencloud->importCredentials($credentials);
                }
            }

            $opencloud->authenticate();

            $credentials = $opencloud->exportCredentials();

            if (isset($app['opencloud.exportCredentials'])) {
                if (is_callable($app['opencloud.exportCredentials'])) {
                    call_user_func($app['opencloud.exportCredentials'], $credentials);
                }
            } else {
                $app['opencloud.exportCredentials'] = $credentials;
            }

            return $opencloud;
        });
    }
}
