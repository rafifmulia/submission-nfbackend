const db = require("../config/dbMysql");

class Student {
  static async all() {
    return new Promise((resolve, reject) => {
      const query = "SELECT * FROM students;";
      db.query(query, (err, results) => {
				if (err) {
					reject(err);
					return;
				}
        resolve(results);
      });
    });
  }

	static async show(id) {
    return new Promise((resolve, reject) => {
      const query = "SELECT * FROM students WHERE id = ? LIMIT 1;";
      db.query(query, [id], (err, results) => {
				if (err) {
					reject(err);
					return;
				}
        resolve(results);
      });
    });
  }

  /**
   * TODO 1: Buat fungsi untuk insert data.
   * Method menerima parameter data yang akan diinsert.
   * Method mengembalikan data student yang baru diinsert.
   */
  static async create(data) {
    return new Promise((resolve, reject) => {
      const query = "INSERT INTO students SET ?;";
      db.query(query, data, (err, results) => {
				if (err) {
					reject(err);
					return;
				}
        resolve(results);
      });
    });
  }

	static async update(data, id) {
    return new Promise((resolve, reject) => {
      const query = "UPDATE students SET ? WHERE id = ?;";
      db.query(query, [data, id], (err, results) => {
				if (err) {
					reject(err);
					return;
				}
        resolve(results);
      });
    });
  }

	static async delete(id) {
    return new Promise((resolve, reject) => {
      const query = "DELETE FROM students WHERE id = ?;";
      db.query(query, [id], (err, results) => {
				if (err) {
					reject(err);
					return;
				}
        resolve(results);
      });
    });
  }
}

module.exports = Student;