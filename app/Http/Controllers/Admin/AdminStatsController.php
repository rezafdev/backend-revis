<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\AdminController;
use App\Http\Requests\Admin\AdminPostDoctorRequest;
use App\Http\Requests\Admin\AdminPostAppointmentRequest;
use App\Http\Requests\Base\JsonRequestEx;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Therapy;
use App\Models\TherapyPhoto;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminStatsController extends AdminController {


    public function __construct()
    {
        parent::__construct();
        $this->middleware(self::SANCTUM_AUTH_MIDDLE);
    }

    public function getStatistics(Request $request): JsonResponse {
        $endOfToday = Carbon::today()->endOfDay();
        $startOfMonth = Carbon::today()->startOfMonth();
        $startOfLastMonth = Carbon::today()->subMonth()->startOfMonth();

        $date1 = $endOfToday->copy()->subDays(7)->endOfDay();
        $date2 = $date1->copy()->subDays(7)->endOfDay();

        $totalPatients = Patient::whereRaw("1")->count();
        $lastTotalPatients = Patient::where('created_at', '<=', $date1->toDateTimeString())->count();
        $totalPatientsChangePercent = $lastTotalPatients <= 0 ? 0 : round(($totalPatients-$lastTotalPatients) * 100 / $lastTotalPatients);

        $newPatients = Patient::where('created_at', '>', $date1->toDateTimeString())->count();
        $lastNewPatients = Patient::where('created_at', '>', $date2->toDateTimeString())->where('created_at', '<=', $date1->toDateTimeString())->count();
        $newPatientsChangePercent = $lastNewPatients <= 0 ? 0 : round(($newPatients-$lastNewPatients) * 100 / $lastNewPatients);

        $monthlyPatients = Patient::where('created_at', '>=', $startOfMonth->toDateTimeString())->count();
        $lastMonthlyPatients = Patient::where('created_at', '>', $startOfLastMonth->toDateTimeString())->where('created_at', '<', $startOfMonth->toDateTimeString())->count();
        $monthlyPatientsChangePercent = $lastMonthlyPatients <= 0 ? 0 : round(($monthlyPatients-$lastMonthlyPatients) * 100 / $lastMonthlyPatients);

        $appVisits = 53;
        $lastAppVisits = 43;
        $appVisitsChange = $appVisits - $lastAppVisits;

        $therapySessions = Appointment::where('beginDate', '>', $date1->toDateString())->where('beginDate', '<=', $endOfToday->toDateString())->count();
        $lastTherapySessions = Appointment::where('beginDate', '>', $date2->toDateString())->where('beginDate', '<=', $date1->toDateString())->count();
        $therapySessionsChangePercent = $lastTherapySessions <= 0 ? 0 : round(($therapySessions-$lastTherapySessions) * 100 / $lastTherapySessions);

        return response()->json([
            'appVisits' => [
                'value' => $appVisits,
                'last' => $lastAppVisits,
                'change' => $appVisitsChange,
                'changeSymbol' => '',
            ],
            'therapySessions' => [
                'value' => $therapySessions,
                'last' => $lastTherapySessions,
                'change' => $therapySessionsChangePercent,
                'changeSymbol' => '%',
            ],
            'totalPatients' => [
                'value' => $totalPatients,
                'last' => $lastTotalPatients,
                'change' => $totalPatientsChangePercent,
                'changeSymbol' => '%',
            ],
            'newPatients' => [
                'value' => $newPatients,
                'last' => $lastNewPatients,
                'change' => $newPatientsChangePercent,
                'changeSymbol' => '%',
            ],
            'monthlyPatients' => [
                'value' => $monthlyPatients,
                'last' => $lastMonthlyPatients,
                'change' => $monthlyPatientsChangePercent,
                'changeSymbol' => '%',
            ],
        ]);
    }





}
