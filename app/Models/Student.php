<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $guarded = [];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function getStudents()
    {
        return DB::select('SELECT * FROM students;');
    }

    public function getStudentById($sid)
    {
        return DB::select('SELECT * FROM students WHERE id = ? ;', [$sid]);
    }

    public function addStudent($data)
    {
        $sid = DB::table('students')->insertGetId($data);
        return $this->getStudentById($sid);
    }

    public function updateStudent($data)
    {
        $sid = DB::table('students')->where('id', $data['id'])->update($data);
        return $this->getStudentById($sid);
    }

    public function deleteStudent($data)
    {
        return DB::table('students')->where('id', $data['id'])->delete();
    }
}
