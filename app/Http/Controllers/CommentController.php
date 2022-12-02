<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function saveComment(Request $request)
    {

        $comment = new Comment();
        $comment->user_id = Auth::user()->id;
        $comment->video_id = $request->videoId;
        $comment->body = $request->comment;
        $comment->save();


        $userName = auth()->user()->name;
        $userImage = auth()->user()->profile_photo_url;
        $commentDate = Carbon::now()->diffForHumans();

        return response()->json([
            'userName' => $userName,
            'userImage' => $userImage,
            'commentDate' => $commentDate,
            'commentId' => $comment->id,
        ]);
    }

    //destroy comment
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    //edit comment
    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        return view('comments.edit', compact('comment'));
    }

    //update comment
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'comment' => 'required',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->body = $request->comment;
        $comment->save();
        return redirect()->route('videos.show', $comment->video_id)->with('success', 'تم التعديل بنجاح');
    }
}
