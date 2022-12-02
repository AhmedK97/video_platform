<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function main()
    {
        $date = Carbon::today()->subDays(7);
        $title = 'الفيديوهات الاكثر مشاهدة هذا الاسبوع';

        $videos = Video::join('views', 'videos.id', 'views.video_id')
            ->where('videos.created_at', '>=', $date)
            ->orderBy('views.created_at', 'Desc')
            ->take(10)
            ->get();

        return view('main.index', compact('videos', 'title'));
    }

    public function ChannelsVideo(User $channel)
    {
        $videos = Video::where('user_id', $channel->id)->get();
        $title = 'الفيديوهات المرفوعة من قبل ' . $channel->name;
        return view('main.index', compact('videos', 'title'));
    }
}
