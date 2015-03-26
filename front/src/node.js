
import {Resource} from './resource/resource';

export class Node {

  node = null;

  activate(node) {
    if (!node) {
      return this.resource = null;
    }
    this.resource = new Resource(node);
  }
}

export class LogValueConverter {
  toView(value) {
    return console.log(value);
  }
}
