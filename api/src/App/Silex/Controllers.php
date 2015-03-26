<?php

namespace App\Silex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Controllers
{
    public function __construct(Application $app)
    {
        $app->get('/', function (Request $request, $user) {
            return (new Response($user->getUsername()));
        });

        $app->get('/{username}/{repo}/{commit}/logs/{service}', function (Request $request, $username, $repo, $commit, $service) use($app) {
            $logs = $app['logs']->get(
                $username, $repo, $commit, $service,
                $request->query->get('from', 0),
                $request->query->get('size', 100)
            );

            return (new JsonResponse($logs));
        });

        $app->get('/repositories', function(Request $request) use ($app) {
            return (new JsonResponse($app['repositories']->all()));
        });

        $app->get('/hook/{username}/{repository}', function(Request $request, $username, $repository) use ($app) {
            return (new JsonResponse($app['repositories']->hook($username, $repository)));
        });

        $app->after(function(Request $request, Response $response) {
            if ($response instanceof JsonResponse) {
                $response->setEncodingOptions(JSON_PRETTY_PRINT);
            }
        });
    }
}
