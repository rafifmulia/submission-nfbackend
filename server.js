const express = require('express');
const router = require('./routes/api');
const app = express();

// parsing application/json
app.use(express.json({ limit: '10mb' }));
// parsing application/x-www-form-urlencoded
app.use(express.urlencoded({ extended: true }));

app.use(async function (req, res, next) {
  // set control allowed headers
  res.header('Access-Control-Allow-Origin', '*');
  res.header('Access-Control-Allow-Credentials', true);
  res.header('Access-Control-Allow-Methods', 'OPTIONS,GET,HEAD,PUT,PATCH,POST,DELETE');
  res.header('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type, Range, X-Requested-With, x-forwarded-for');
  next();
})

// api
router.use(app);

app.listen(3005);