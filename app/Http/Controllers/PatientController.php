<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\StatusCovid;
use App\Models\Patient;
use App\Models\RecordPatient;

class PatientController extends Controller
{
	public function __construct()
	{
		$this->now = time();
	}

	private function format_patient($record, $patient, $status_covid)
	{
		return [
			'id' => $record->id,
			'patient_name' => $patient->name,
			'patient_phone' => $patient->phone,
			'patient_address' => $patient->address,
			'status_covid' => $status_covid->name,
			'in_date_at' => $record->in_date_at,
			'out_date_at' => $record->out_date_at,
			'created_at' => $record->created_at,
			'updated_at' => $record->updated_at,
		];
	}

	public function index()
	{
		$apiRes = [];
        try {
			$data = RecordPatient::with(['status_covid','patient'])->get();

			if (count($data) < 1) {
				$apiRes['meta'] = [
					'code' => '200',
					'type' => 'success',
					'message' => 'data patients is empty'
				];
				$apiRes['data'] = [];
				
				return new Response($apiRes, 200);	
			}

			$apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success get patients'
            ];
            $apiRes['data'] = [];

			foreach ($data as $row) {
				$apiRes['data'][] = $this->format_patient($row, $row->patient, $row->status_covid);
			}
			
            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'server error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
            return new Response($apiRes, 500);
        }
	}

	public function store(Request $req)
	{
		$apiRes = [];
		DB::beginTransaction();
        try {
			$dataIns = [
                'name' => $req->name,
                'phone' => $req->phone,
                'address' => $req->address,
                'status_covid' => $req->status_covid,
                'in_date_at' => $req->in_date_at,
                'out_date_at' => $req->out_date_at,
                'created_at' => date('Y-m-d H:i:s', $this->now),
            ];
            $rulesDataIns = [
                'name' => 'required|string|max:255',
                'phone' => 'required|digits_between:8,14',
                'address' => 'required|string',
                'status_covid' => 'required|string|max:255',
                'in_date_at' => 'required|date_format:Y-m-d',
                'out_date_at' => 'required|date_format:Y-m-d',
            ];

			// validasi input
			$isValidDataIns = Validator::make($dataIns, $rulesDataIns);
			if (!$isValidDataIns->passes()) {
                $apiRes['meta'] = [
                    'code' => '400',
                    'type' => 'fail',
                    'message' => $isValidDataIns->messages()->first(),
                ];

				DB::rollBack();
                return new Response($apiRes, 400);
            }

			// check string status_covid
			$checkStatusCovid = StatusCovid::where('name', strtolower($dataIns['status_covid']))->first();
			if ($checkStatusCovid == null) {
				$apiRes['meta'] = [
                    'code' => '400',
                    'type' => 'fail',
                    'message' => 'status covid tidak valid',
                ];

				DB::rollBack();
                return new Response($apiRes, 400);
			}

            $patient = new Patient();
            $patient->name = $dataIns['name'];
            $patient->phone = $dataIns['phone'];
            $patient->address = $dataIns['address'];
            $patient->created_at = date('Y-m-d H:i:s', $this->now);
            $patient->save();

            /**
             * karena langsung mendapatkan $eloquent->id adalah return null
             * jadi mau tidak mau pakai DB::getPdo()->lastInsertId()
             */
            $patient->id = DB::getPdo()->lastInsertId();

			$recordPatient = new RecordPatient();
			$recordPatient->patient_id = $patient->id;
			$recordPatient->status_covid_id = $checkStatusCovid->id;
			$recordPatient->in_date_at = $dataIns['in_date_at'];
			$recordPatient->out_date_at = $dataIns['out_date_at'];
			$recordPatient->created_at = date('Y-m-d H:i:s', $this->now);
			$recordPatient->save();

			/**
			 * karena langsung mendapatkan $eloquent->id adalah return null
			 * jadi mau tidak mau pakai DB::getPdo()->lastInsertId()
			 */
			$recordPatient->id = DB::getPdo()->lastInsertId();

            $apiRes['meta'] = [
                'code' => '201',
                'type' => 'success',
                'message' => 'success store patient'
            ];
            $apiRes['data'] = $this->format_patient($recordPatient, $patient, $checkStatusCovid);

			DB::commit();
            return new Response($apiRes, 201);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'server error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();

			DB::rollBack();
            return new Response($apiRes, 500);
        }
	}

	public function show($id)
	{
		$apiRes = [];
        try {
            $data = RecordPatient::with(['status_covid','patient'])->find($id);

			if ($data == null) {
				$apiRes['meta'] = [
					'code' => '404',
					'type' => 'fail',
					'message' => 'patient not found'
				];
	
				return new Response($apiRes, 404);
			}

			$apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success get detail patient'
            ];
            $apiRes['data'] = $this->format_patient($data, $data->patient, $data->status_covid);

            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'server error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
            return new Response($apiRes, 500);
        }
	}

	public function update(Request $req, $id)
	{
		$apiRes = [];
		DB::beginTransaction();
        try {
			$dataUpdtRecordPatient = [
                'updated_at' => date('Y-m-d H:i:s', $this->now),
            ];
			$dataUpdtPatient = [
                'updated_at' => date('Y-m-d H:i:s', $this->now),
            ];
            $rulesDataUpdtRecordPatient = [];
            $rulesDataUpdtPatient = [];

			// only updated data if input not empty
			if ($req->name != null & trim($req->name) != '') {
                $dataUpdtPatient['name'] = $req->name;
                $rulesDataUpdtRecordPatient['name'] = 'required|string|max:255';
            }
			if ($req->phone != null & trim($req->phone) != '') {
                $dataUpdtPatient['phone'] = $req->phone;
                $rulesDataUpdtRecordPatient['phone'] = 'required|digits_between:8,14';
            }
			if ($req->address != null & trim($req->address) != '') {
                $dataUpdtPatient['address'] = $req->address;
                $rulesDataUpdtRecordPatient['address'] = 'required|string';
            }
			if ($req->status_covid != null & trim($req->status_covid) != '') {
                $dataUpdtRecordPatient['status_covid'] = $req->status_covid;
                $rulesDataUpdtPatient['status_covid'] = 'required|string|max:255';
            }
			if ($req->in_date_at != null & trim($req->in_date_at) != '') {
                $dataUpdtRecordPatient['in_date_at'] = $req->in_date_at;
                $rulesDataUpdtPatient['in_date_at'] = 'required|date_format:Y-m-d';
            }
			if ($req->out_date_at != null & trim($req->out_date_at) != '') {
                $dataUpdtRecordPatient['out_date_at'] = $req->out_date_at;
                $rulesDataUpdtPatient['out_date_at'] = 'required|date_format:Y-m-d';
            }

			// validasi input
			$dataUpdt = array_merge($dataUpdtRecordPatient, $dataUpdtPatient);
			$rulesDataUpdt = array_merge($rulesDataUpdtRecordPatient, $rulesDataUpdtPatient);
			$isValidDataIns = Validator::make($dataUpdt, $rulesDataUpdt);
			if (!$isValidDataIns->passes()) {
                $apiRes['meta'] = [
                    'code' => '400',
                    'type' => 'fail',
                    'message' => $isValidDataIns->messages()->first(),
                ];

				DB::rollBack();
                return new Response($apiRes, 400);
            }

			// check resource
			$data = RecordPatient::with(['status_covid','patient'])->find($id);
			if ($data == null) {
				$apiRes['meta'] = [
					'code' => '404',
					'type' => 'fail',
					'message' => 'patient not found'
				];
	
				DB::rollBack();
				return new Response($apiRes, 404);
			}

			// check string status_covid
			if (isset($dataUpdt['status_covid'])) {
				$checkStatusCovid = StatusCovid::where('name', strtolower($dataUpdt['status_covid']))->first();
				if ($checkStatusCovid == null) {
					$apiRes['meta'] = [
						'code' => '400',
						'type' => 'fail',
						'message' => 'status covid tidak valid',
					];
	
					DB::rollBack();
					return new Response($apiRes, 400);
				}

				unset($dataUpdt['status_covid']);
				unset($dataUpdtRecordPatient['status_covid']);
				$dataUpdt['status_covid_id'] = $checkStatusCovid->id;
				$dataUpdtRecordPatient['status_covid_id'] = $checkStatusCovid->id;
			}

			// check uniq phone
			if (isset($dataUpdt['phone'])) {
				$checkPhone = Patient::where('phone', $dataUpdt['phone'])->first();
				if ($checkPhone != null) {
					if ($checkPhone->phone != $dataUpdt['phone']) {
						$apiRes['meta'] = [
							'code' => '400',
							'type' => 'fail',
							'message' => 'nomor telepon telah digunakan',
						];
		
						DB::rollBack();
						return new Response($apiRes, 400);
					}
				}
			}

			// update data
			$data->update($dataUpdtRecordPatient);
			$data->patient->update($dataUpdtPatient);

			// get updated data
			$data = RecordPatient::with(['status_covid','patient'])->find($id);

            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success update patient'
            ];
            $apiRes['data'] = $this->format_patient($data, $data->patient, $data->status_covid);

			DB::commit();
            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'server error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();

			DB::rollBack();
            return new Response($apiRes, 500);
        }
	}

	public function destroy($id)
	{
		$apiRes = [];
		DB::beginTransaction();
        try {
			$data = RecordPatient::with(['patient','status_covid'])->find($id);

			if ($data == null) {
				$apiRes['meta'] = [
					'code' => '404',
					'type' => 'fail',
					'message' => 'patient not found1'
				];
	
				DB::rollBack();
				return new Response($apiRes, 404);
			}

			$data->delete();
			$data->patient->delete();

            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success delete patient'
            ];

			DB::commit();
            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'server error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();

			DB::rollBack();
            return new Response($apiRes, 500);
        }
	}

	public function search($name)
	{
		$apiRes = [];
        try {
            $data = RecordPatient::with(['status_covid','patient'])
            ->whereHas('patient', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->get();

			if (count($data) < 1) {
				$apiRes['meta'] = [
					'code' => '404',
					'type' => 'fail',
					'message' => 'patient not found'
				];
	
				return new Response($apiRes, 404);
			}

            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success search patients'
            ];
            $apiRes['data'] = [];

			foreach ($data as $row) {
				$apiRes['data'][] = $this->format_patient($row, $row->patient, $row->status_covid);
			}

            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'server error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
            return new Response($apiRes, 500);
        }
	}

	public function negative()
	{
		$apiRes = [];
        try {
            $data = RecordPatient::with(['status_covid','patient'])
            ->whereHas('status_covid', function ($query) {
                $query->where('name', '=', StatusCovid::TEXT_NEGATIVE);
            })
            ->get();

            $cdata = count($data);

            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success get negative patients'
            ];
            $apiRes['total'] = $cdata;
            $apiRes['data'] = [];

			foreach ($data as $row) {
				$apiRes['data'][] = $this->format_patient($row, $row->patient, $row->status_covid);
			}

            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'server error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
            return new Response($apiRes, 500);
        }
	}

	public function positive()
	{
		$apiRes = [];
        try {
            $data = RecordPatient::with(['status_covid','patient'])
            ->whereHas('status_covid', function ($query) {
                $query->where('name', '=', StatusCovid::TEXT_POSITIVE);
            })
            ->get();

            $cdata = count($data);

            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success get positive patients'
            ];
            $apiRes['total'] = $cdata;
            $apiRes['data'] = [];

			foreach ($data as $row) {
				$apiRes['data'][] = $this->format_patient($row, $row->patient, $row->status_covid);
			}

            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'server error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
            return new Response($apiRes, 500);
        }
	}

	public function recovered()
	{
		$apiRes = [];
        try {
            $data = RecordPatient::with(['status_covid','patient'])
            ->whereHas('status_covid', function ($query) {
                $query->where('name', '=', StatusCovid::TEXT_RECOVERED);
            })
            ->get();

            $cdata = count($data);

            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success get recovered patients'
            ];
            $apiRes['total'] = $cdata;
            $apiRes['data'] = [];

			foreach ($data as $row) {
				$apiRes['data'][] = $this->format_patient($row, $row->patient, $row->status_covid);
			}

            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'server error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
            return new Response($apiRes, 500);
        }
	}

	public function dead()
	{
		$apiRes = [];
        try {
            $data = RecordPatient::with(['status_covid','patient'])
            ->whereHas('status_covid', function ($query) {
                $query->where('name', '=', StatusCovid::TEXT_DEAD);
            })
            ->get();

            $cdata = count($data);

            $apiRes['meta'] = [
                'code' => '200',
                'type' => 'success',
                'message' => 'success get dead patients'
            ];
            $apiRes['total'] = $cdata;
            $apiRes['data'] = [];

			foreach ($data as $row) {
				$apiRes['data'][] = $this->format_patient($row, $row->patient, $row->status_covid);
			}

            return new Response($apiRes, 200);
        } catch (\Exception $e) {
            $apiRes['meta'] = [
                'code' => '500',
                'type' => 'error',
                'message' => 'server error'
            ];
            if (env('APP_DEBUG')) $apiRes['meta']['message'] = $e->getMessage();
            return new Response($apiRes, 500);
        }
	}
}
