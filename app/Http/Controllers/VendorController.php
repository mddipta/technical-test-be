<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterVendorRequest;
use App\Models\Vendor;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function register(RegisterVendorRequest $request)
    {
        $req = $request->validated();
        $this->validateVendorIsExists();

        $userId = Auth::user()->id;

        Vendor::create([
            'user_id' => $userId,
            'name' => $req['name'],
            'description' => $req['description'],
            'address' => $req['address'],
        ]);

        return $this->success(null, 'Register vendor success');
    }

    public function validateVendorIsExists()
    {
        $userId = Auth::user()->id;
        $vendor = Vendor::where('user_id', $userId)->first();
        if ($vendor) {
            throw new HttpResponseException($this->error('Vendor already exists', 400));
        }
    }
}
