<?php

$connection = new AMQPConnection([
    'host' => 'rabbitmq',
    'login' => 'guest',
    'password' => 'guest',
]);
$connection->connect();
$ex = new AMQPExchange(new AMQPChannel($connection));
$ex->setName('hook');
$ex->setType(AMQP_EX_TYPE_FANOUT);

$request = new http\Env\Request;
$commit = $request->getQuery('commit', null, $request->getForm()->getObject('head_commit')->id);
$token = $request->getQuery('token');
$repo = $request->getForm()->getObject('repository')->full_name;
$archive_url = str_replace(
    ['{archive_format}', '{/ref}'],
    ['tarball/', $commit],
    $request->getForm()->getObject('repository')->archive_url
);
$dir = "/builds/$repo@$commit";
$path = $request->getQuery('path', null, 'docker-compose.yml');
$author = $request->getForm()->getObject('head_commit')->author['email'];
$owner = $request->getForm()->getObject('repository')->owner['email'];
$email = $request->getQuery('email', null, $author);

$message = [
    'commit' => $commit,
    'commit_url' => $request->getForm()->getObject('head_commit')->url,
    'repo' => $repo,
    'archive_url' => $archive_url,
    'token' => $token,
    'dir' => $dir,
    'path' => $path,
    'author' => $author,
    'owner' => $owner,
    'email' => $email,
];

$status = $ex->publish(json_encode($message));
var_dump($status);
