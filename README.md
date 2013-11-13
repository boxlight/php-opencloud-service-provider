php-opencloud-service-provider
==============================

A basic service provider for [rackspace/php-opencloud](https://github.com/rackspace/php-opencloud). Knowledge of the rackspace/php-opencloud library is required.

Installation
------------

```json
"repositories": [
    { "type": "vcs", "url": "git@github.com:boxlight/php-opencloud-service-provider.git" }
],
"require": {
    "boxlight/php-opencloud-service-provider": "dev-master"
}
```

Initialise
----------

```php
<?php

    // bootstrap.php
    $app->register(new Boxlight\Silex\Provider\OpenCloud\OpenCloudServiceProvider(), array(

        // change to 'OpenStack' for non-Rackspace
        // see https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/authentication.md#authenticating-against-openstack-clouds
        'opencloud.variant' => 'Rackspace',
        'opencloud.endpoint' => OpenCloud\Rackspace::UK_IDENTITY_ENDPOINT,
        'opencloud.secret' => array(
            'username' => 'foo',
            'apiKey' => 'bar'
        ),

        // import/export is sometimes required
        // "some deployments will limit the frequency with which you can authenticate."
        // see https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/authentication.md#credential-caching
        'opencloud.importCredentials' => $app->protect(function() use ($app) {
            return load_credentials_from_cache();
        }),

        // if this isn't callable, the credentials will become stored here
        // you can access $app['opencloud.exportCredentials'] once they have
        'opencloud.exportCredentials' => $app->protect(function($credentials) use ($app) {
            store_credentials_in_cache($credentials);
        })

    ));
```

Use
---

```php
<?php

    // helloworld.php
    $store = $app['opencloud']->objectStoreService('cloudFiles', 'LON', 'publicURL');
    $container = $store->getContainer('my container');

    do_something_useful($container->getObjectCount());
```