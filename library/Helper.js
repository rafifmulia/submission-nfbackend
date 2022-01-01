require("dotenv").config();

class Helper {
  static checkErrMsg(err) {
    if (process.env.APP_ENV == 'development') {
      if (typeof err.message != 'undefined') return err.message;
      return err;
    }
    return '';
  }
}

module.exports = Helper