const Validator = require('validatorjs')
const Helper = require('../library/Helper');
const Student = require('../models/Student');

Validator.useLang('id');

class StudentController {
  async index(req, res) {
    try {
      const data = {
        message: "Menampilkan semua students",
        data: await Student.all(),
      };
  
      return res.status(200).json(data);
    } catch (err) {
      return res.status(500).json({
        message: "err: " + Helper.checkErrMsg(err),
      })
    }
  }

  async show(req, res) {
    try {
      const { id } = req.params;

      const inputStudent = {
        id,
      };
      const rulesInput = {
        id: 'required|digits_between:1,11',
      }
      const isInputValid = new Validator(inputStudent, rulesInput)
      if (isInputValid.fails()) {
        const data = {
          message: Object.values(isInputValid.errors.all())[0][0],
        };
        return res.status(400).json(data);
      }
      
      // check jika id students tidak ditemukan
      const detailStudent = await Student.show(id);
      if (detailStudent.length < 1) {
        const data = {
          message: "Student tidak ditemukan",
        };
        return res.status(404).json(data);
      }

      const data = {
        message: "Menampilkan detail student",
        data: detailStudent[0],
      };
      return res.status(200).json(data);
    } catch (err) {
      return res.status(500).json({
        message: "err: " + Helper.checkErrMsg(err),
      })
    }
  }

  async store(req, res) {
    try {
      const { name, nim, prodi, address } = req.body;

      const inputStudent = {
        name,
        nim,
        prodi,
        address,
      };
      const rulesInput = {
        name: 'required|string|max:245',
        nim: 'required|digits:10',
        prodi: 'required|string|max:2',
        address: 'required|string|max:245',
      }
      const isInputValid = new Validator(inputStudent, rulesInput)
      if (isInputValid.fails()) {
        const data = {
          message: Object.values(isInputValid.errors.all())[0][0],
        };
        return res.status(400).json(data);
      }

      const insertStudent = await Student.create(inputStudent);
      const detailDataStudent = await Student.show(insertStudent.insertId);

      const data = {
        message: `Menambahkan data student`,
        data: detailDataStudent[0],
      };

      return res.status(201).json(data);
    } catch (err) {
      return res.status(500).json({
        message: "err: " + Helper.checkErrMsg(err),
      })
    }
  }

  async update(req, res) {
    try {
      const { id } = req.params;
      const { name, nim, prodi, address } = req.body;

      const inputStudent = {
        id,
        name,
        nim,
        prodi,
        address,
      };
      const rulesInput = {
        id: 'required|digits_between:1,11',
        name: 'required|string|max:245',
        nim: 'required|digits:10',
        prodi: 'required|string|max:2',
        address: 'required|string|max:245',
      }
      const isInputValid = new Validator(inputStudent, rulesInput)
      if (isInputValid.fails()) {
        const data = {
          message: Object.values(isInputValid.errors.all())[0][0],
        };
        return res.status(400).json(data);
      }
  
      // check jika id students tidak ditemukan
      const detailStudent = await Student.show(id);
      if (detailStudent.length < 1) {
        const data = {
          message: "Student tidak ditemukan",
        };
        return res.status(404).json(data);
      }
  
      // set data yang ingin diupdate
      const dataStudent = {};
  
      // check jika input name diisi
      if (typeof name != 'undefined') {
        dataStudent.name = name;
      }
      // check jika input nim diisi
      if (typeof nim != 'undefined') {
        dataStudent.nim = nim;
      }
      // check jika input prodi diisi
      if (typeof prodi != 'undefined') {
        dataStudent.prodi = prodi;
      }
      // check jika input address diisi
      if (typeof address != 'undefined') {
        dataStudent.address = address;
      }
  
      // check jika semua field tidak diisi
      if (Object.keys(dataStudent).length === 0) {
        const data = {
          message: "Minimal ada salah 1 input yang akan diupdate",
        };
        return res.status(400).json(data);
      }
  
      await Student.update(dataStudent, id);
      const detailDataStudent = await Student.show(id);
      
      const data = {
        message: `Mengedit student id`,
        data: detailDataStudent[0],
      };
  
      return res.status(200).json(data);
    } catch (err) {
      return res.status(500).json({
        message: "err: " + Helper.checkErrMsg(err),
      })
    }
  }

  async destroy(req, res) {
    try {
      const { id } = req.params;

      const inputStudent = {
        id,
      };
      const rulesInput = {
        id: 'required|digits_between:1,11',
      }
      const isInputValid = new Validator(inputStudent, rulesInput)
      if (isInputValid.fails()) {
        const data = {
          message: Object.values(isInputValid.errors.all())[0][0],
        };
        return res.status(400).json(data);
      }
      
      // check jika id students tidak ditemukan
      const detailStudent = await Student.show(id);
      if (detailStudent.length < 1) {
        const data = {
          message: "Student tidak ditemukan",
        };
        return res.status(404).json(data);
      }

      // delete student
      await Student.delete(id);

      const data = {
        message: `Menghapus student id:${id}`,
      };

      return res.status(200).json(data); 
    } catch (err) {
      return res.status(500).json({
        message: "err: " + Helper.checkErrMsg(err),
      })
    }
  }
}

const object = new StudentController();

module.exports = object;