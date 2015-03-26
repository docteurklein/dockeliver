# dockeliver

## What ?

dockeliver is a [docker-compose](https://docs.docker.com/compose/) based, fast, easy, continuous, deployment service.

Entirely relying on docker-compose, it reduces the gap between dev and staging to 0%.

## Why ?

No more custom, non-reproductible install recipes (travis-ci debug anyone?).  
No more extra, ppa-only services (travis-ci debug anyone?)  
No more extra, server-specific version (travis-ci debug anyone?).  
No more change-commit-push-test-repeat (travis-ci debug anyone?).

What you use to develop, use it to deploy.


dockeliver's goal is **not** to help you deploy on prod,  
but to deploy a staging version as soon as a change is detected in your git repository.

You can deploy different versions in parallel. The deploy url contains the git sha.

*Release early, release often* they say.

## How ?

    docker-machine create --driver virtualbox dockeliver
    eval $(docker-machine env dockeliver)
    echo "HOST=$(docker-machine ip dockeliver).xip.io" > env
    docker-compose build
    docker-compose up -d

Tadaaa!

There is now a github hook webserver listening at:

    hook.dockeliver.$(docker-machine ip dockeliver).xip.io

Currently, it'll try to deploy all the commits he receives.

You can simulate a push event using:

    curl -v \
        -X POST \
        -H 'Content-Type: application/json' \
        "http://hook.dockeliver.$(docker-machine ip dockeliver).xip.io?path=example/docker-compose.yml&commit=2815173" \
        -d @hook/pushEvent.json

## And ?

[Declare this hook](https://developer.github.com/webhooks/) for each repository you want to auto-deploy.

As stated, each github repository needs a docker-compose.yml file.
A few importants things to know:

 - it will run **all** the services.
 - it uses the github tarball to download the sources.
 - host volumes won't work.

Concerning the host volumes, it's in fact a much better idea to [`COPY`](https://docs.docker.com/reference/builder/#copy) the needed files (src, vendors, …), than to actually try to mount them using [`volumes`](https://docs.docker.com/compose/yml/#volumes). 

In case you really want to keep volumes locally during your dev, you can still use a dev-specific [extends](https://docs.docker.com/compose/extends/).


