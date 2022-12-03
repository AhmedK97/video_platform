<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $numberOfVideos = Video::all()->count();
        $numberOfUsers = User::all()->count();
        //most users video views

        $mostViews = View::select('user_id', DB::raw('sum(views.views_number) as total'))
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->take(5)->get();

        $names = [];
        $totalViews = [];
        foreach ($mostViews as $mostView) {
            $names[] = User::find($mostView->user_id)->name;
            $totalViews[] = $mostView->total;
        };
        return view('admin.index', compact('numberOfVideos', 'numberOfUsers'))
            ->with('names', json_encode($names, JSON_NUMERIC_CHECK))
            ->with('totalViews', json_encode($totalViews, JSON_NUMERIC_CHECK));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
