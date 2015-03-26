
import smtplib
from email.mime.text import MIMEText
import pika
import json
from os import environ, path
from jinja2 import Environment, FileSystemLoader

env = Environment(loader=FileSystemLoader(path.dirname(path.realpath(__file__))))

def run(ch, method, properties, body):
    message = json.loads(body)
    print(message)

    html = env.get_template('mail.html').render(
        repo=message['repo'],
        commit_url=message['commit_url'],
        commit=message['commit'],
        repo=message['repo'],
        urls=message['urls'].iteritems(),
        logs=message['logs'].iteritems(),
        host=message['host'],
        project_name=message['project_name']
    )

    msg = MIMEText(html, 'html')
    msg['Subject'] = '[%s] deployed version %s' % (message['repo'], message['commit'])
    msg['From'] = 'florian.klein@free.fr'
    msg['To'] = ', '.join([message['email']])

    s = smtplib.SMTP_SSL(environ.get('SMTP_HOST'))
    s.login(environ.get('SMTP_USER'), environ.get('SMTP_PASS'))
    s.sendmail(msg['From'], [message['email']], msg.as_string())
    s.quit()
    print(msg.as_string())

def on_open(connection):
    connection.channel(on_channel_open)

def on_channel_open(channel):
    channel.basic_consume(run, queue='notify', no_ack=True)

connection = pika.SelectConnection(pika.ConnectionParameters(host='rabbitmq'), on_open_callback=on_open)

connection.ioloop.start()
