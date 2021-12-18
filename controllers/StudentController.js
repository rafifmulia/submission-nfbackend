// TODO 3: Import data students dari folder data/students.js
const students = require('../data/students');

class StudentController {
  index(req, res) {
    // TODO 4: Tampilkan data students
    const data = {
      message: "Menampilkan semua students",
      data: students,
    };

    res.status(200).json(data);
  }

  store(req, res) {
    const { nama } = req.body;

    // check jika input nama belum diisi
    if (typeof nama == 'undefined') {
      const data = {
        message: `Bad request. field nama is required`,
      };
  
      return res.status(400).json(data);  
    }

    // TODO 5: Tambahkan data students
    students.push({
      nama,
    });
    
    const data = {
      message: `Menambahkan data student: ${nama}`,
      data: students,
    };

    res.status(201).json(data);
  }

  update(req, res) {
    const { id } = req.params;
    const { nama } = req.body;

    // check jika id students tidak ditemukan
    if (typeof students[id] == 'undefined') {
      const data = {
        message: `Student dengan id ${id} tidak ditemukan`,
      };
  
      return res.status(404).json(data);  
    }

    // check jika input nama belum diisi
    if (typeof nama == 'undefined') {
      const data = {
        message: `Bad request. field nama is required`,
      };
  
      return res.status(400).json(data);  
    }

    // TODO 6: Update data students
    students[id].nama = nama
    
    const data = {
      message: `Mengedit student id ${id}, nama ${nama}`,
      data: students,
    };

    res.status(200).json(data);
  }

  destroy(req, res) {
    const { id } = req.params;

    // check jika id students tidak ditemukan
    if (typeof students[id] == 'undefined') {
      const data = {
        message: `Student dengan id ${id} tidak ditemukan`,
      };
  
      return res.status(404).json(data);  
    }

    // TODO 7: Hapus data students
    students.splice(id, 1)

    const data = {
      message: `Menghapus student id ${id}`,
      data: students,
    };

    res.status(200).json(data);
  }
}

const object = new StudentController();

module.exports = object;