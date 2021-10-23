<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AnimalController extends Controller
{

    protected $animals = [
        [
            'id' => 1,
            'name' => 'kucing',
        ],
        [
            'id' => 2,
            'name' => 'ayam',
        ],
        [
            'id' => 3,
            'name' => 'ikan',
        ],
    ];

    public function index()
    {
        $apiRes = [];
        try {
            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success get animals data'
            ];
            $apiRes['data'] = $this->animals;
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
            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success insert new animals'
            ];
            $insertId = array_key_last($this->animals)+2;
            $newAnimals = [
                'id' => $insertId,
                'name' => $req->name,
            ];
            array_push($this->animals, $newAnimals);
            $apiRes['data'] = $this->animals;
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

    public function update(Request $req, $id)
    {
        $apiRes = [];
        try {
            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success update animals data'
            ];
            foreach ($this->animals as $key => $animal) {
                if ($animal['id'] == (int) $id) {
                    $this->animals[$key]['name'] = $req->name;
                }
            }
            $apiRes['data'] = $this->animals;
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
            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success delete animals data'
            ];
            foreach ($this->animals as $key => $animal) {
                if ($animal['id'] == (int) $id) {
                    // array_splice($this->animals, $key, 1);
                    unset($this->animals[$key]);
                }
            }
            $apiRes['data'] = $this->animals;
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
