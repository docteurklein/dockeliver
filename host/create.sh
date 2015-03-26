#!/bin/bash

set -x
set -e

message=$(tee)

repo=$(echo $message | jq '.repo' | tr -cd [:alnum:])

#docker-machine create --driver virtualbox $repo || true
#eval "$(docker-machine env $repo)"

DOCKER_HOST='unix:///var/run/docker.sock' # @TODO
#DOCKER_HOST='tcp://127.0.0.1:2375' # @TODO

message=$(echo $message | jq ". | . + { socket: \"$DOCKER_HOST\" }" )
amqp-publish -s rabbitmq:5672 -e host -b "$message"
