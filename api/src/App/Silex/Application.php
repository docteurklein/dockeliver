<?php

namespace App\Silex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\SessionServiceProvider;
use Silex;
use Elasticsearch\ClientBuilder;
use App\Domain\Logs;
use Symfony\Component\Debug\Debug;
use App\Domain\Repositories;

final class Application extends Silex\Application
{
    public function __construct($debug = false)
    {
        parent::__construct($_ENV);
        $this['debug'] = $debug;

        $this->register(new SessionServiceProvider);
        new OAuth($this);
        new Controllers($this);

        $this['elasticsearch'] = $this->share(function() {
            return ClientBuilder::create()
                ->setHosts(['elk:9200'])
                ->build()
            ;
        });

        $this['logs'] = $this->share(function() {
            return new Logs($this['elasticsearch']);
        });

        $this['github'] = $this->share(function() {
            return new \Github\Client;
        });

        $this['repositories'] = $this->share(function() {
            return new Repositories(
                $this['github'],
                $this['session']->get('user')->getToken(),
                $this['HOST']
            );
        });
    }

    public static function debug()
    {
        Debug::enable();
        return new self(true);
    }

    public static function nodebug()
    {
        return new self(false);
    }
}
