<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
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
                'message' => 'Server Error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
            return new Response($apiRes, 500);
        }
    }

    public function show($id)
    {
        $apiRes = [];
        try {
            $dataGet = [
                'id' => $id,
            ];
            $rulesDataGet = [
                'id' => 'required|numeric',
            ];
            $isValidDataGet = Validator::make($dataGet, $rulesDataGet);
            
            // if ($isValidDataGet->fails()) {
            if (!$isValidDataGet->passes()) {
                // dd($isValidDataGet->errors()); // ->first()
                // dd($isValidDataGet->messages()->get('*')); // ->first()

                $apiRes['meta'] = [
                    'code' => '400',
                    'type' => 'fail',
                    'message' => $isValidDataGet->messages()->first(),
                ];
                return new Response($apiRes, 400);
            }

            $getStudent = $this->student->getStudentById($dataGet['id']);
            if (count($getStudent) != 1) {
                $apiRes['meta'] = [
                    'code' => '404',
                    'type' => 'fail',
                    'message' => 'student not found'
                ];
                return new Response($apiRes, 404);
            }

            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success show students'
            ];
            $apiRes['data'] = $getStudent[0]; // Student::all();
            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'Server Error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
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
            $rulesDataIns = [
                'nama' => 'required|max:255',
                'nim' => 'required|digits:10',
                'email' => 'required|email',
                'jurusan' => 'required|max:255',
            ];

            // jika custom messages set directly
            // $errMsgDataIns = [
            //     'required' => 'Data :attribute dibutuhkan',
            //     'digits' => 'Data :attribute harus :digits digit',
            //     'email' => 'Data email tidak valid',
            //     'max' => 'Data :attribute maksimal :max karakter',
            // ];
            // $isValidDataIns = Validator::make($dataIns, $rulesDataIns, $errMsgDataIns);
            $isValidDataIns = Validator::make($dataIns, $rulesDataIns);
            
            // if ($isValidDataIns->fails()) {
            if (!$isValidDataIns->passes()) {
                // dd($isValidDataIns->errors()); // ->first()
                // dd($isValidDataIns->messages()->get('*')); // ->first()

                $apiRes['meta'] = [
                    'code' => '400',
                    'type' => 'fail',
                    'message' => $isValidDataIns->messages()->first(),
                ];
                return new Response($apiRes, 400);
            }

            $checkNim = $this->student->checkStudentByNim($dataIns['nim']);
            if (count($checkNim) > 0) {
                $apiRes['meta'] = [
                    'code' => '200_1',
                    'type' => 'fail',
                    'message' => 'nim telah digunakan'
                ];
                return new Response($apiRes, 200);
            }

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
                'message' => 'Server Error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
            return new Response($apiRes, 500);
        }
    }

    public function update(Request $req, $id)
    {
        $apiRes = [];
        try {
            $dataUpdt = [
                'id' => $id,
                'updated_at' => date('Y-m-d H:i:s', $this->now),
            ];
            $rulesDataUpdt = [
                'id' => 'required|numeric',
            ];

            if ($req->nama != null & trim($req->nama) != '') {
                $dataUpdt['nama'] = $req->nama;
                $rulesDataUpdt['nama'] = 'max:255';
            }
            if ($req->nim != null & trim($req->nim) != '') {
                $dataUpdt['nim'] = $req->nim;
                $rulesDataUpdt['nim'] = 'digits:10';
            }
            if ($req->email != null & trim($req->email) != '') {
                $dataUpdt['email'] = $req->email;
                $rulesDataUpdt['email'] = 'email';
            }
            if ($req->jurusan != null & trim($req->jurusan) != '') {
                $dataUpdt['jurusan'] = $req->jurusan;
                $rulesDataUpdt['jurusan'] = 'max:255';
            }

            $isValidDataUpdt = Validator::make($dataUpdt, $rulesDataUpdt);
            
            // if ($isValidDataUpdt->fails()) {
            if (!$isValidDataUpdt->passes()) {
                // dd($isValidDataUpdt->errors()); // ->first()
                // dd($isValidDataUpdt->messages()->get('*')); // ->first()

                $apiRes['meta'] = [
                    'code' => '400',
                    'type' => 'fail',
                    'message' => $isValidDataUpdt->messages()->first(),
                ];
                return new Response($apiRes, 400);
            }

            $checkStudent = $this->student->checkStudentById($dataUpdt['id']);
            if (count($checkStudent) != 1) {
                $apiRes['meta'] = [
                    'code' => '404',
                    'type' => 'fail',
                    'message' => 'student not found'
                ];
                return new Response($apiRes, 404);
            }

            if (isset($dataUpdt['nim'])) {
                $checkNim = $this->student->checkStudentByNim($dataUpdt['nim']);
                if (count($checkNim) > 0) {
                    foreach ($checkNim as $row) {
                        if ($row->id != $dataUpdt['id']) {
                            $apiRes['meta'] = [
                                'code' => '200_1',
                                'type' => 'fail',
                                'message' => 'nim telah digunakan'
                            ];
                            return new Response($apiRes, 200);
                        }
                    }
                }
            }

            // $updtStudent = Student::where('id', $id)->update($dataUpdt);
            $updtStudent = $this->student->updateStudent($dataUpdt);
            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success update student'
            ];
            $apiRes['data'] = $this->student->getStudentById($dataUpdt['id']);
            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'Server Error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
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
            $rulesDataDel = [
                'id' => 'required|numeric',
            ];
            $isValidDataDel = Validator::make($dataDel, $rulesDataDel);
            
            // if ($isValidDataDel->fails()) {
            if (!$isValidDataDel->passes()) {
                // dd($isValidDataDel->errors()); // ->first()
                // dd($isValidDataDel->messages()->get('*')); // ->first()

                $apiRes['meta'] = [
                    'code' => '400',
                    'type' => 'fail',
                    'message' => $isValidDataDel->messages()->first(),
                ];
                return new Response($apiRes, 400);
            }

            $checkStudent = $this->student->checkStudentById($dataDel['id']);
            if (count($checkStudent) != 1) {
                $apiRes['meta'] = [
                    'code' => '404',
                    'type' => 'fail',
                    'message' => 'student not found'
                ];
                return new Response($apiRes, 404);
            }

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
                'message' => 'Server Error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
            return new Response($apiRes, 500);
        }
    }
}
