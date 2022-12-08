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

        // Check if image was given and save on local file system
        if (isset($data['image'])) {
            $relativePath  = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }

        //$path = $data['image']->store('images/products');

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
        $user = $request->user();
        if ($user->id !== $realestate->user_id) {
            return abort(403, 'Unauthorized action.');
        }

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
    public function destroy(Realestate $realestate)
    {
        $user = $request->user();
        if ($user->id !== $realestate->user_id) {
            return abort(403, 'Unauthorized action.');
        }

        $realestate->delete();

        // If there is an old image, delete it
        if ($realestate->image) {
            $absolutePath = public_path($realestate->image);
            File::delete($absolutePath);
        }

        return response('', 204);
    }

    private function saveImage($image)
    {
        // Check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1);
            // Get file extension
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'images/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);

        return $relativePath;
    }
}
