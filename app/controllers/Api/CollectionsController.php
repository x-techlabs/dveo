<?php namespace App\Controllers\Api;

use BaseController as Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CollectionsController extends Controller {

    /**
     * Display a listing of $COLLECTION$
     *
     * @return JsonResponse
     */
    public function index($channel_id)
    {
        $collections = \Collections::with([])
            ->where("channel_id", "=", $channel_id)
            ->orderBy('title', 'asc')
            ->get();
        return $collections;
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
     * @return Response
     */
    public function show($id)
    {

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
    public function update($id)
    {

    }

    /**
     * Remove the specified $RESOURCE$ from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {

    }

}