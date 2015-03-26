#!/bin/bash

set -x
set -e

message=$(tee)

url=$(echo $message | jq '.archive_url' | tr -d '"')
token=$(echo $message | jq '.token' | tr -d '"')
dir=$(echo $message | jq '.dir' | tr -d '"')

mkdir -p $dir
curl -H "Authorization: token $token" -kL $url | tar xz -C $dir --strip-components=1

amqp-publish -s rabbitmq:5672 -e download -b "$message"
