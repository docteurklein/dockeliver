FROM debian:7
RUN apt-get update && \
    apt-get -y install amqp-tools curl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
ADD http://stedolan.github.io/jq/download/linux64/jq /usr/bin/jq
RUN chmod a+x /usr/bin/jq
ADD . /code
VOLUME ["/code"]
WORKDIR /code
