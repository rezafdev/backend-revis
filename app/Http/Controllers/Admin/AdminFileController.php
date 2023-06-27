<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\AdminController;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AdminFileController extends AdminController {

    const KEY_FILE = 'file';
    const KEY_PATH = 'path';

    public function uploadFile(Request $request) : JsonResponse {
        $userId = $this->authUserId();
        $res = [];
        if(!$request->hasFile(self::KEY_FILE)) {
            throw new BadRequestHttpException("File Not Sent");
        }
        $res[self::KEY_PATH] = self::handleUploadedFile($request->file(self::KEY_FILE), $userId, "storage/admin/upload/files");
        return response()->json($res);
    }

    public static function handleUploadedFile(UploadedFile $uploadedFile, $userId, $subDir = "storage/upload") : string {
        $dir = public_path($subDir);
        if(!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $fileName = self::getFileName($dir, $userId, $uploadedFile->extension(), "");
        $target = $uploadedFile->move($dir, $fileName);
        return asset( $subDir . "/" . $target->getFilename() ) ;
    }

    private static function getFileName($dir, $userId, $ext, $prefix = "") : string {
        $time = Carbon::now()->getTimestamp();
        do {
            $str = sprintf("%04X-%s-%s.%s", mt_rand(0, 65535), $userId, $time , $ext);
        } while (file_exists("$dir/$str"));
        return $str;
    }


}
