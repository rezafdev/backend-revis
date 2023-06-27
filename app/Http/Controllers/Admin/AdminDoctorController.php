<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\AdminController;
use App\Http\Requests\Admin\AdminPostDoctorRequest;
use App\Http\Requests\Base\JsonRequestEx;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDoctorController extends AdminController {


    public function __construct()
    {
        parent::__construct();
        $this->middleware(self::SANCTUM_AUTH_MIDDLE);
    }



    public function getDoctors(): JsonResponse {
        $records = Doctor::all();
        return response()->json($records);
    }

    public function addDoctor(AdminPostDoctorRequest $request): JsonResponse {
        $input = $request->validated();
        $record = Doctor::create($input);
        return response()->json($record);
    }




}
