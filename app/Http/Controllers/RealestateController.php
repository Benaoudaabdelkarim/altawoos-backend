<?php

namespace App\Http\Controllers;

use App\Models\Realestate;
use App\Http\Requests\StoreRealestateRequest;
use App\Http\Requests\UpdateRealestateRequest;
use App\Http\Resources\RealestateResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RealestateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RealestateResource::collection(Realestate::orderBy('created_at', 'DESC')
            ->paginate(30));
    }
    public function getRealestateForGuest()
    {
        return RealestateResource::collection(Realestate::orderBy('created_at', 'DESC')
            ->paginate(30));
    }
    public function getFeatered()
    {
        return RealestateResource::collection(Realestate::where("featred", 1)->get());
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRealestateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRealestateRequest $request)
    {
        $data = $request->validated();

        //return $data;

        $images = array();

        // Check if image was given and save on local file system
        if (isset($data['images']))
            foreach ($data['images'] as $image)
                $images[] =  $image->store('public/images');

        // if (isset($data['images']))
        //     foreach ($data['images'] as $image)
        //         $images[] = $this->saveImage($image);

        $data['images'] = $images;

        $realestate = Realestate::create($data);

        return new RealestateResource($realestate);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Realestate  $realestate
     * @return \Illuminate\Http\Response
     */
    public function show(Realestate $realestate)
    {
        return new RealestateResource($realestate);
    }

    public function showForGuest(Realestate $realestate)
    {
        return new RealestateResource($realestate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Realestate  $realestate
     * @return \Illuminate\Http\Response
     */
    public function edit(Realestate $realestate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRealestateRequest  $request
     * @param  \App\Models\Realestate  $realestate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRealestateRequest $request, Realestate $realestate)
    {
        $data = $request->validated();

        // Check if image was given and save on local file system
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;

            // If there is an old image, delete it
            if ($realestate->image) {
                $absolutePath = public_path($realestate->image);
                File::delete($absolutePath);
            }
        }

        // Update realestate in the database
        $realestate->update($data);

        return new RealestateResource($realestate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Realestate  $realestate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //return $realestate;
        $realestate = Realestate::find($id);
        $realestate->delete();
        return response('deleted', 204);
    }
}
