<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCatalogRequest;
use App\Http\Requests\UpdateCatalogRequest;
use App\Http\Resources\CatalogResource;
use App\Models\Catalog;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendorId = Auth::user()->vendor->id;
        $catalogs = Catalog::with('vendor')->where('vendor_id', $vendorId)->paginate(10);
        
        return $this->successWithPaginatedResource($catalogs, CatalogResource::class, 'Success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCatalogRequest $request)
    {
        $req = $request->validated();
        $this->validateCodeIsNotExist($req['code']);

        $vendor = getVendor()->id;


        if (!$vendor) {
            return $this->error('Vendor not found', 404);
        }

        $slug = Str::of($req['name'])->slug('-');

        Catalog::create([
            'vendor_id' => $vendor,
            'code' => $req['code'],
            'name' => $req['name'],
            'slug' => $slug,
            'description' => $req['description'],
        ]);
        return $this->success(null, 'Create catalog success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->validateIdIsExist($id);
        $catalog = Catalog::with('vendor')->find($id);
        $data = CatalogResource::make($catalog);
        return $this->success($data, 'Success get catalog');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCatalogRequest $request, string $id)
    {
        $req = $request->validated();
        $this->validateIdIsExist($id);

        $catalog = Catalog::with('vendor')->find($id);
        $catalog->update([
            'name' => $req['name'],
            'description' => $req['description'],
        ]);
        return $this->success(null, 'Update catalog success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $catalog = Catalog::find($id);
        if (!$catalog) {
            return $this->error('Catalog not found', 404);
        }
        $catalog->delete();
        return $this->success(null, 'Delete catalog success');
    }

    public function validateIdIsExist(string $id)
    {
        $catalog = Catalog::with('vendor')->find($id);
        if (!$catalog) {
            throw new HttpResponseException(
                $this->error('Catalog not found', 404)
            );
        }
    }

    public function validateCodeIsNotExist(string $code)
    {
        $catalog = Catalog::where('code', $code)->first();
        if ($catalog) {
            throw new HttpResponseException(
                $this->error('Catalog code is exist', 400)
            );
        }
    }
}
