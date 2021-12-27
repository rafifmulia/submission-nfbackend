const Student = require('../models/Student');

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
        message: "err: " + err.message,
      })
    }
  }

  async show(req, res) {
    try {
      const { id } = req.params;
      const detailStudent = await Student.show(id);

      // check jika id students tidak ditemukan
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
        message: "err: " + err.message,
      })
    }
  }

  async store(req, res) {
    try {
      const { name, nim, prodi, address } = req.body;

      // check jika input name belum diisi
      if (typeof name == 'undefined') {
        const data = {
          message: `Bad request. field name is required`,
        };
        return res.status(400).json(data);  
      }
      // check jika input nim belum diisi
      if (typeof nim == 'undefined') {
        const data = {
          message: `Bad request. field nim is required`,
        };
        return res.status(400).json(data);  
      }
      // check jika input prodi belum diisi
      if (typeof prodi == 'undefined') {
        const data = {
          message: `Bad request. field prodi is required`,
        };
        return res.status(400).json(data);  
      }
      // check jika input address belum diisi
      if (typeof address == 'undefined') {
        const data = {
          message: `Bad request. field address is required`,
        };
        return res.status(400).json(data);  
      }

      const dataStudent = {
        name,
        nim,
        prodi,
        address,
      };
      const insertStudent = await Student.create(dataStudent);
      const detailDataStudent = await Student.show(insertStudent.insertId);

      const data = {
        message: `Menambahkan data student`,
        data: detailDataStudent[0],
      };

      return res.status(201).json(data);
    } catch (err) {
      return res.status(500).json({
        message: "err: " + err.message,
      })
    }
  }

  async update(req, res) {
    const { id } = req.params;
    const { name, nim, prodi, address } = req.body;

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
  }

  async destroy(req, res) {
    const { id } = req.params;

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
  }
}

const object = new StudentController();

module.exports = object;