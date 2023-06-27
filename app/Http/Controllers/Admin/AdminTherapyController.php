<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\AdminController;
use App\Http\Requests\Admin\AdminPostDoctorRequest;
use App\Http\Requests\Admin\AdminPostAppointmentRequest;
use App\Http\Requests\Admin\AdminPostTherapyRequest;
use App\Http\Requests\Base\JsonRequestEx;
use App\Models\Doctor;
use App\Models\Therapy;
use App\Models\TherapyPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTherapyController extends AdminController {


    public function __construct()
    {
        parent::__construct();
        $this->middleware(self::SANCTUM_AUTH_MIDDLE);
    }

    public function getTherapies(Request $request): JsonResponse {
        $size = intval( $request->query('size', 10));
        $res = Therapy::orderBy('id', 'desc')->paginate($size);
        return response()->json($res);
    }

    public function getTherapyById(int $id): JsonResponse {
        $res = Therapy::findOrFail($id);
        return response()->json($res);
    }



    public function addTherapy(AdminPostTherapyRequest $request): JsonResponse {
        $input = $request->except('photos');
        $photos = $request->only('photos')['photos'];
        $therapy = Therapy::create($input);
        if(!empty($photos)) {
            $data = array();
            foreach ($photos as $path) {
                $item = new TherapyPhoto(['path' => $path]);
                $data[] = $item;
            }

            $therapy->photos()->saveMany($data);
        }
        $therapy->load(['photos', 'doctor']);
        $therapy->refresh();
        return response()->json($therapy);
    }

    public function getPopulars(): JsonResponse {
        $total = Therapy::whereRaw("1")->count();
        $therapies = Therapy::withCount('appointments')->orderBy('appointments_count', 'desc')->limit(6)->get();
        return response()->json([
            'total' => $total,
            'therapies' => $therapies
        ]);
    }



}
