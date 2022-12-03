<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = User::all()->sortByDesc('created_at');
        $title = 'القنوات';
        return view('channels.index', compact('channels', 'title'));
    }

    //search inside channel
    public function search(Request $request)
    {
        $channels = User::where('name', 'like', '%' . $request->term . '%')->get();
        $title = 'عرض نتائج البحث عن' . $request->term;
        return view('channels.index', compact('channels', 'title'));
    }


    public function adminIndex()
    {
        $users = User::all()->sortByDesc('created_at');
        $title = 'القنوات';
        return view('admin.channels.index', compact('users', 'title'));
    }
}
