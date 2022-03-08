<?php

namespace App\Http\Controllers;

use App\Models\MasterClass;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminController extends Controller
{
    public function createClass()
    {
        $user=request()->user();
        if(!$user->hasRole('admin'))
        {
            return ResponseFormatter::error(null,'Anda tidak punya kewenangan', 403);
        }
        $validator=Validator::make(request()->all(),[
            'name' => 'required|max:255|string',
            'status' => 'required|in:AKTIF,NONAKTIF',
        ]);
        if($validator->fails()){
            return ResponseFormatter::error($validator, $validator->messages(), 400);
        }
        $masterClass = MasterClass::create([
            'name' => request()->name,
            'slug' => Str::slug(request()->name),
            'status' => request()->status,
        ]);
        return ResponseFormatter::success($masterClass,'Data master class berhasil ditambahkan');
    }

    public function showAllClasses()
    {
        $user=request()->user();
        if(!$user->hasRole('admin'))
        {
            return ResponseFormatter::error(null,'Anda tidak punya kewenangan', 403);
        }
        $masterClasses = MasterClass::all();
        return ResponseFormatter::success($masterClasses,'Data semua kelas berhasil didapatkan');
    }

    public function showClass($masterClassID)
    {
        $user=request()->user();
        if(!$user->hasRole('admin'))
        {
            return ResponseFormatter::error(null,'Anda tidak punya kewenangan', 403);
        }
        $masterClass=MasterClass::find($masterClassID);
        if(!$masterClass)
        {
            return ResponseFormatter::error(null,'Data kelas tidak ditemukan', 404);
        }
        return ResponseFormatter::success($masterClass,'Data kelas berhasil didapatkan');
    }

    public function updateClass($masterClassID)
    {
        $user=request()->user();
        if(!$user->hasRole('admin'))
        {
            return ResponseFormatter::error(null,'Anda tidak punya kewenangan', 403);
        }
        $validator=Validator::make(request()->all(),[
            'name' => 'required|max:255|string',
            'status' => 'required|in:AKTIF,NONAKTIF',
        ]);
        if($validator->fails()){
            return ResponseFormatter::error($validator, $validator->messages(), 400);
        }

        $masterClass=MasterClass::find($masterClassID);
        if(!$masterClass)
        {
            return ResponseFormatter::error(null,'Data kelas tidak ditemukan', 404);
        }

        $masterClass->update([
            'name' => request()->name,
            'slug' => Str::slug(request()->name),
            'status' => request()->status,
        ]);
        return ResponseFormatter::success($masterClass,'Data kelas berhasil diupdate');
    }

    public function deleteClass($masterClassID)
    {
        $user=request()->user();
        if(!$user->hasRole('admin'))
        {
            return ResponseFormatter::error(null,'Anda tidak punya kewenangan', 403);
        }
        $masterClass=MasterClass::find($masterClassID);
        if(!$masterClass)
        {
            return ResponseFormatter::error(null,'Data kelas tidak ditemukan', 404);
        }
        MasterClass::destroy($masterClassID);
        return ResponseFormatter::success(null,'Data kelas berhasil dihapus');
    }

    public function generate()
    {
        $qrcode = QrCode::size(400)->generate("Koe kok iso gay iku pie dik?");
        return $qrcode;
    }
}
