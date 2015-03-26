
import {Agent} from './agent';

export class Resource {

  resource = null;
  agent = null;

  constructor() {
    this.agent = new Agent('https://unicorn-cors-proxy.herokuapp.com/http://haltalk.herokuapp.com');
  }

  activate(params) {

    this.agent.fetch(params.path).then(resource => {

      this.resource = resource;

    }).catch(console.error.bind(console));
  }
}

export class LogValueConverter {
  toView(value) {
    return console.log(value);
  }
}
