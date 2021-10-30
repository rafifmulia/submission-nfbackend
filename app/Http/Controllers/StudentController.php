<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Student;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->now = time();
        $this->student = new Student();
    }

    public function index()
    {
        $apiRes = [];
        try {
            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success get students'
            ];
            $apiRes['data'] = $this->student->getStudents(); // Student::all();
            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => $e->getMessage()
            ];
            return new Response($apiRes, 500);
        }
    }

    public function store(Request $req)
    {
        $apiRes = [];
        try {
            $dataIns = [
                'nama' => $req->nama,
                'nim' => $req->nim,
                'email' => $req->email,
                'jurusan' => $req->jurusan,
                'created_at' => date('Y-m-d H:i:s', $this->now),
            ];
            $insStudent = $this->student->addStudent($dataIns); // Student::create($dataIns);
            $apiRes['meta'] = [
                'code' => '201',
                'type' => 'success',
                'message' => 'success insert new student'
            ];
            $apiRes['data'] = $insStudent;
            return new Response($apiRes, 201);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => $e->getMessage()
            ];
            return new Response($apiRes, 500);
        }
    }

    public function update(Request $req, $id)
    {
        $apiRes = [];
        try {
            $dataUpdt = [
                'id' => $id,
                'nama' => $req->nama,
                'nim' => $req->nim,
                'email' => $req->email,
                'jurusan' => $req->jurusan,
                'updated_at' => date('Y-m-d H:i:s', $this->now),
            ];
            // $updtStudent = Student::where('id', $id)->update($dataUpdt);
            $updtStudent = $this->student->updateStudent($dataUpdt);
            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success update student'
            ];
            $apiRes['data'] = $updtStudent;
            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => $e->getMessage()
            ];
            return new Response($apiRes, 500);
        }
    }

    public function destroy($id)
    {
        $apiRes = [];
        try {
            $dataDel = [
                'id' => $id,
            ];
            $delStudent = $this->student->deleteStudent($dataDel);
            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success delete student'
            ];
            // $apiRes['data'] = $delStudent;
            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => $e->getMessage()
            ];
            return new Response($apiRes, 500);
        }
    }
}
