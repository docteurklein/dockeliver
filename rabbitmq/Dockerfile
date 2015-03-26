FROM rabbitmq
COPY definitions.json /definitions.json
COPY rabbitmq.config /etc/rabbitmq/rabbitmq.config
RUN rabbitmq-plugins enable \
        rabbitmq_management \
        rabbitmq_management_visualiser \
        rabbitmq_tracing
