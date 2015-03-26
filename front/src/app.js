import 'bootstrap';
import 'bootstrap/css/bootstrap.css!';

export class App {
  configureRouter(config, router){
    router.title = 'dockeliver';

    config.mapUnknownRoutes(instruction => {
      instruction.config.moduleId = './resource';
    });

    this.router = router;
  }
}
