<?php

namespace App\Domain;

use Github\Client;

class Repositories
{
    private $client;
    private $host;

    public function __construct(Client $client, $token, $host)
    {
        $this->client = $client;
        $this->client->authenticate($token, null, Client::AUTH_HTTP_TOKEN);
        $this->host = $host;
    }

    public function all()
    {
        return $this->client->api('current_user')->repositories();
    }

    public function hook($username, $repository)
    {
        return $this->client->api('repo')->hooks()
            ->create($username, $repository, [
                'name'   => 'web',
                'active' => true,
                'events' => ['push',],
                'config' => [
                    'url' => "http://hook.dockeliver.{$this->host}",
                    'content_type' => 'json'
                ]
            ])
        ;
    }
}
