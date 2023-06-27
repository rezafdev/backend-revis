<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\AdminController;
use App\Http\Requests\Admin\AdminPostDoctorRequest;
use App\Http\Requests\Admin\AdminPostAppointmentRequest;
use App\Http\Requests\Base\JsonRequestEx;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Therapy;
use App\Models\TherapyPhoto;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAppointmentController extends AdminController {


    public function __construct()
    {
        parent::__construct();
        $this->middleware(self::SANCTUM_AUTH_MIDDLE);
    }

    public function getAppointments(Request $request): JsonResponse {
        $size = intval( $request->query('size', 10));
        $res = Appointment::with('patient')->orderBy('id', 'desc')->paginate($size);
        return response()->json($res);
    }

    public function getAppointmentsByDate(string $date): JsonResponse {
        $res = Appointment::with('patient')->where('beginDate', $date)->orderBy('beginTime', 'asc')->get();
        return response()->json($res);
    }

    public function getAppointmentsForRange(string $startDate, string $endDate): JsonResponse {
        $res = Appointment::with('patient')
            ->where('beginDate', '>=', $startDate)
            ->where('beginDate', '<=', $endDate)
            ->orderBy('beginAt', 'asc')->get();
        return response()->json($res);
    }

    public function getAppointmentById(int $id): JsonResponse {
        $res = Appointment::with('patient')->findOrFail($id);
        return response()->json($res);
    }



    public function addAppointment(AdminPostAppointmentRequest $request): JsonResponse {
        $input = $request->validated();
        $appointment = Appointment::updateOrCreate([
            'patientId' => $input['patientId'],
            'therapyId' => $input['therapyId'],
            'beginDate' => $input['beginDate'],
        ],
            ['beginTime' => $input['beginTime'] ?? '08:00:00']
        );
        $appointment->load([ 'patient', 'therapy']);
        return response()->json($appointment);
    }

    public function lastAppointments(Request $request): JsonResponse {
        $limit = intval( $request->query('limit', 6));
        $appointments = Appointment::with('patient')
            ->orderBy('beginAt', 'desc')->limit($limit)->get();
        return response()->json($appointments);
    }




}
