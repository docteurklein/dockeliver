
import * as hyperagent from 'hyperagent';
import q from 'q';
import _ from 'underscore';
import * as urijs from 'urijs';

export class Agent {

  url = null;
  factory = null;

  constructor(url) {

    this.url = url;

    hyperagent.default.configure('ajax', options => {
      options.url = this.url + options.url;

      return jQuery.ajax(options);
    });
    hyperagent.default.configure('defer', q.defer);
    hyperagent.default.configure('_', _);

    this.factory = hyperagent.default.Resource;
  }

  fetch(path) {
    var api = new this.factory({
      url: path,
      headers: {
        'X-Requested-With': 'Hyperagent'
      }
    });

    return api.fetch();
  }
}
