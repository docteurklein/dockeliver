<?php

namespace App\Silex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Domain\User;

class OAuth
{
    public function __construct(Application $app)
    {
        $app['oauth'] = $app->share(function() use($app) {
            return new \League\OAuth2\Client\Provider\Github([
                'clientId'     => $app['GH_CLIENT_ID'],
                'clientSecret' => $app['GH_SECRET_ID'],
                'redirectUri'  => "http://api.{$app['HOST']}/oauth/callback",
            ]);
        });

        $app->before(function (Request $request, Application $app) {
            $user = $app['session']->get('user');
            if ($user instanceof User) {
                $request->attributes->set('user', $user);
                return;
            }

            if ($request->attributes->get('_route') === 'GET_oauth_callback') {
                return;
            }

            return new RedirectResponse($app['oauth']->getAuthorizationUrl([
                'scope' => ['user', 'write:repo_hook'],
            ]));
        });

        $app->get('/oauth/callback', function (Request $request) use($app) {
            $token = $app['oauth']->getAccessToken('authorization_code', [
                'code' => $request->query->get('code'),
            ]);
            $user = $app['oauth']->getResourceOwner($token);
            $app['session']->set('user', new User($user->toArray(), $token));
            $app['session']->save();

            return new RedirectResponse('/');
        });

        $app->get('/oauth/logout', function (Request $request) use($app) {
            $app['session']->remove('user');
            $app['session']->save();

            return new RedirectResponse('/');
        });
    }
}
