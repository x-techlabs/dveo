<?php namespace App\Controllers\Api;

use BaseController as Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;

class TvappPlatformsController extends Controller {

    /**
     * Display a listing of $COLLECTION$
     *
     * @return Response | JsonResponse
     */
    public function index($channel_id)
    {
        return Response::json(\TvappPlatform::all());
    }

    /**
     * Show the form for creating a new $RESOURCE$
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created $RESOURCE$ in storage.
     *
     * @return Response
     */
    public function store()
    {

    }

    /**
     * Display the specified $RESOURCE$.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($channel_id, $id)
    {
        $platform = \TvappPlatform::find($id);
        if(is_null($platform)) {
            App::abort(404);
        }
        return Response::json($platform);
    }

    /**
     * Show the form for editing the specified $RESOURCE$.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified $RESOURCE$ in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($channel_id, $id)
    {

    }

    /**
     * Remove the specified $RESOURCE$ from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($channel_id, $id)
    {

    }
}