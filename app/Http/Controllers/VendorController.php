<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterVendorRequest;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function register(RegisterVendorRequest $request)
    {
        $req = $request->validated();

        $userId = Auth::user()->id;

        Vendor::create([
            'user_id' => $userId,
            'name' => $req['name'],
            'description' => $req['description'],
            'address' => $req['address'],
        ]);

        return $this->success(null, 'Register vendor success');
    }
}
