<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\AdminController;
use App\Http\Requests\Admin\AdminPostDoctorRequest;
use App\Http\Requests\Admin\AdminPostAppointmentRequest;
use App\Http\Requests\Admin\AdminPostPatientRequest;
use App\Http\Requests\Base\JsonRequestEx;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Therapy;
use App\Models\TherapyPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPatientController extends AdminController {


    public function __construct()
    {
        parent::__construct();
        $this->middleware(self::SANCTUM_AUTH_MIDDLE);
    }

    public function getPatients(Request $request): JsonResponse {
        $size = intval( $request->query('size', 10));
        $res = Patient::with(['nearestAppointment'])->orderBy('id', 'desc')->paginate($size);
        return response()->json($res);
    }

    public function getPatientById(int $id): JsonResponse {
        $res = Patient::with(['nearestAppointment'])->with('appointments')->findOrFail($id);
        return response()->json($res);
    }



    public function addPatient(AdminPostPatientRequest $request): JsonResponse {
        $input = $request->validated();
        $patient = Patient::create($input);
        return response()->json($patient);
    }




}
