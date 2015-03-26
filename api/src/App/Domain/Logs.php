<?php

namespace App\Domain;

use Elasticsearch\Client;

class Logs
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get($username, $repo, $commit, $service, $from = 0, $size = 100)
    {
        $messages = $this->client->search([
            'index' => 'logstash-'.date('Y.m.d'),
            'body' => [
                'from' => $from,
                'size' => $size,
                'query' => [
                    'match' => [
                        'docker.name' => "/{$username}{$repo}{$commit}_{$service}"
                    ]
                ],
                '_source' => ['message']
            ]
        ]);

        return array_map(function($hit) {
            return $hit['_source']['message'];
        }, $messages['hits']['hits']);
    }
}

