<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoForStreaming;
use App\Models\Convertedvideo;
use App\Models\Like;
use App\Models\Video;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;

class videoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'addView']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = auth()->user()->videos->sortByDesc('created_at');
        $title = 'اخر الفديوهات المرفوعه';
        return view('videos.my-videos', compact('videos', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('videos.uploader');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'image' => 'image|required',
            'video' => 'required'
        ]);
        $randomPath = str::random(16);
        $videoPath = $randomPath . '.' . $request->video->getClientOriginalExtension();
        // dd($videoPath);
        $imagePath = $randomPath . '.' . $request->image->getClientOriginalExtension();
        $image = Image::make($request->image)->resize(320, 180);

        $path = Storage::put($imagePath, $image->stream());

        $request->video->storeAs('/', $videoPath, 'public');

        $video = Video::create([
            'disk' => 'public',
            'video_path' => $videoPath,
            'image_path' => $imagePath,
            'title' => $request->title,
            'user_id' => auth()->id(),
        ]);

        $view = View::create([
            'video_id' => $video->id,
            'user_id' => auth()->id(),
            'views_number' => 0
        ]);

        ConvertVideoForStreaming::dispatch($video);

        return redirect()->back()->with('success', 'سيكون مقطع الفيديو متوفر عند الانتهاء من المعالجه');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(video $video)
    {
        $countLike = Like::where('video_id', $video->id)->where('like', '1')->count();
        $countDisLike = Like::where('video_id', $video->id)->where('like', '0')->count();

        if (Auth::check()) {
            $userLike = Auth::user()->likes()->where('video_id', $video->id)->first();
            // dd($userLike);
        } else {
            $userLike = 0;
        }

        // video user history
        if (Auth::check()) {
            auth()->user()->videoInHistory()->attach($video->id);
        }

        //show comment sortByDesc(created_at)
        $comments = $video->comments->sortByDesc('created_at');

        return view('videos.show', compact('video', 'countLike', 'countDisLike', 'userLike', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video = Video::where('id', $id)->first();
        return view('videos.edit-video', compact('video'));
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
        $this->validate($request, [
            'title' => 'required',
        ]);

        $video = Video::where('id', $id)->first();

        if ($request->has('image')) {

            $randomPath = Str::random(16);
            $newPath =  $randomPath . '.' . $request->image->getClientOriginalExtension();

            Storage::delete($video->image_path);

            $image = Image::make($request->image)->resize(320, 180);
            //Store with stream();
            $path = Storage::put($newPath, $image->stream());

            $video->image_path = $newPath;
        }

        $video->title = $request->title;

        $video->save();

        return redirect('/videos')->with(
            'success',
            'تم تعديل معلومات الفيديو بنجاح'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $video = Video::where('id', $id)->first();
        $convertedVideos = Convertedvideo::where('video_id', $id)->get();

        foreach ($convertedVideos as $covertedVideo) {
            Storage::delete([
                $covertedVideo->mp4_Format_240,
                $covertedVideo->mp4_Format_360,
                $covertedVideo->mp4_Format_480,
                $covertedVideo->mp4_Format_720,
                $covertedVideo->mp4_Format_1080,
                $covertedVideo->webm_Format_240,
                $covertedVideo->webm_Format_360,
                $covertedVideo->webm_Format_480,
                $covertedVideo->webm_Format_720,
                $covertedVideo->webm_Format_1080,
                $video->image_path
            ]);
        }
        $video->delete();
        return back()->with('success', 'تم حذف الفيديو بنجاح');
    }


    public function search(Request $request)
    {
        $videos = Video::where('title', 'like', "%{$request->term}%")->paginate(12);
        $title = ' عرض نتائج البحث عن: ' . $request->term;
        return view('videos.my-videos', compact('videos', 'title'));
    }

    //add view video
    public function addView(Request $request)
    {
        $views = View::where('video_id', $request->videoId)->first();

        $views->views_number++;

        $views->save();

        $viewsNumbers = $views->views_number;
        return response()->json(['viewsNumbers' => $viewsNumbers]);
    }

    public function mostView()
    {
        $mostViewedVideos = View::orderBy('views_number', 'desc')->take(10)->whereHas('video', function ($query) {
            $query->where('processed', 1);
        })->get([
            'video_id',
            'views_number',
            'user_id'
        ]);
        $videoNames = [];
        $videoViews = [];
        foreach ($mostViewedVideos as $view) {
            $videoNames[] = video::find($view->video_id)->title;
            $videoViews[] = $view->views_number;
        }

        return view('admin.most-view', compact('mostViewedVideos'))
            ->with('videoNames', json_encode($videoNames, JSON_NUMERIC_CHECK))
            ->with('videoViews', json_encode($videoViews, JSON_NUMERIC_CHECK));
    }
}
