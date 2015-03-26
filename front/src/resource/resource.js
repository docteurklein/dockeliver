
export class Resource {

  resource = null;

  constructor(resource) {
    this.resource = resource;
  }

  props() {
    return Object.keys(this.resource.props || {}).map(prop => {
      return {
        name: prop,
        value: this.resource.props[prop]
      };
    });
  }

  links() {
    return Object.keys(this.resource.links || {}).map(rel => {
      var links = this.resource.links[rel];
      if (!links.length) {
        links = [links];
      }
      return {
        rel: rel,
        links: links
      };
    });
  }

  embeds() {
    return Object.keys(this.resource.embedded || {}).map(name => {
      var embeds = this.resource.embedded[name];
      if (!embeds.length) {
        embeds = [embeds];
      }
      return {
        name: name,
        embeds: embeds
      };
    });
  }
}
