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

    public function adminUpdate(Request $request, User $user)
    {
        $user->administration_level = $request->administration_level;
        $user->save();
        return redirect()->route('channels.index')->with('success', 'تم تعديل الصلاحيات بنجاح');
    }

    public function adminDistroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('channels.index')->with('success', 'تم حذف القناة بنجاح');
    }

    public function adminBlock($id)
    {
        $user = User::find($id);
        $user->block = 1;
        $user->save();
        return redirect()->route('channels.index')->with('success', 'تم حظر القناة بنجاح');
    }

    public function userUnblock($id)
    {
        $user = User::find($id);
        $user->block = 0;
        $user->save();
        return redirect()->route('channels.block')->with('success', 'تم إلغاء حظر القناة بنجاح');
    }
    public function blockedChannels()
    {
        $users = User::where('block', 1)->get();
        $title = 'القنوات المحظورة';

        return view('admin.channels.blocked', compact('users', 'title'));
    }

    public function Channels()
    {
        $users = User::all()->sortByDesc('created_at');
        $title = 'جميع القنوات';

        return view('admin.channels.channels', compact('users', 'title'));
    }
}
