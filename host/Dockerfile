FROM debian:7
RUN apt-get update && \
    apt-get -y install amqp-tools curl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
ADD http://stedolan.github.io/jq/download/linux64/jq /usr/bin/jq
RUN chmod a+x /usr/bin/jq
#ADD https://github.com/docker/machine/releases/download/v0.3.0/docker-machine_linux-amd64 /usr/bin/docker-machine
#RUN chmod +x /usr/bin/docker-machine
ADD . /code
VOLUME ["/code"]
WORKDIR /code
