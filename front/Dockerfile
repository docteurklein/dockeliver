FROM node:0.12-onbuild
RUN node_modules/.bin/jspm config registries.github.timeouts.lookup 60 && \
 node_modules/.bin/jspm config registries.github.timeouts.build 120 && \
 node_modules/.bin/jspm config registries.github.remote https://github.jspm.io && \
 node_modules/.bin/jspm config registries.github.auth ZG9jdGV1cmtsZWluOmFhNTQ2ZTMwZDg2Nzg1YjNhMzU1ZjI5MzYwYjI3NmM2NGFjM2JlZTc= && \
 node_modules/.bin/jspm config registries.github.maxRepoSize 0 && \
 node_modules/.bin/jspm config registries.github.handler jspm-github
RUN node_modules/.bin/jspm install -y
