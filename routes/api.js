const StudentController = require('../controllers/StudentController');

exports.use = function (app) {

  app.get('/students', StudentController.index);
  app.post('/students', StudentController.store);
  app.put('/students/:id', StudentController.update);
  app.delete('/students/:id', StudentController.destroy);

}