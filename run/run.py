
from compose.project import Project
from compose import config
from docker import Client
from os import environ
import pika
import json

def env_vars_from_file(filename):
    try:
        return config.env_vars_from_file(filename)
    except:
        return {}

config.env_vars_from_file = env_vars_from_file

def run(ch, method, properties, body):
    message = json.loads(body)
    print(message)

    name = '%s_%s' % (message['repo'].replace('/', '_'), message['commit'])
    name = name.replace('_', '').lower()
    message['project_name'] = name

    client = Client(base_url=message['socket'])
    message['urls'] = {}
    dicts = sanitize(config.load(config.find(message['dir'], message['path'])), message)
    project = Project.from_dicts(name, dicts, client=client)

    project.build()
    project.up()


    message['logs'] = {}
    message['logs'] = {}
    for container in project.containers(stopped=True):
        message['logs'][container.name_without_project] = container.logs(stream=False)

    ch.basic_publish(exchange='run', routing_key='', body=json.dumps(message))

def sanitize(dicts, message):
    services = []
    for service in dicts:
        if 'ports' in service:
            ports = []
            for port in service['ports']:
                port = str(port)
                if ':' in port:
                    port = port.split(':')[-1]

                ports.append(port)

            service['ports'] = ports

        for key in ['privileged', 'security_opt', 'mem_limit', 'net', 'log_driver', 'pid', 'external_links', 'devices', 'cap_add', 'cap_drop', 'cpu_shares', 'cpuset', 'dns', 'dns_search']:
            service.pop(key, None)

        host = environ.get('HOST')
        service['environment'] = service.get('environment', {})
        service['environment']['VIRTUAL_HOST'] = '%s.%s.%s.%s' % (
            service['name'],
            message['commit'][:7],
            message['repo'].replace('/', '.'),
            host
        )
        message['host'] = host
        message['urls'][service['name']] = service['environment']['VIRTUAL_HOST']

        services.append(service)

    return services

def on_open(connection):
    connection.channel(on_channel_open)

def on_channel_open(channel):
    channel.basic_consume(run, queue='run', no_ack=True)

connection = pika.SelectConnection(pika.ConnectionParameters(host='rabbitmq'), on_open_callback=on_open)

connection.ioloop.start()
