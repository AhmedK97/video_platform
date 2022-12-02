@extends('layouts.main')

@section('content')
    <div class="mx-4">

        @if ($videos->count() > 0)
            <div class="row justify-content-center">
                <form method="POST" action="{{ route('history.destroyAll') }}"
                    class="form-inline col-md-6 justify-content-center"
                    onsubmit="return confirm('هل انت متاكد من حذف السجل باكمله')">
                    @csrf
                    @method('Post')
                    <button type="submit" class="btn btn-danger">حذف السجل</button>
                </form>
            </div>
        @endif
        <br>
        <p class="my-4">{{ $title }}</p>
        <div class="row">
            @forelse($videos as $video)
                @if ($video->processed)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="p-1 mb-4 card">
                            <div class="card-icons">
                                @php
                                    $hours_add_zero = sprintf('%02d', $video->hours);
                                    $minutes_add_zero = sprintf('%02d', $video->minutes);
                                    $seconds_add_zero = sprintf('%02d', $video->seconds);
                                @endphp
                                <a href="/videos/{{ $video->id }}">
                                    <img src="{{ Storage::url($video->image_path) }}" class="card-img-top" alt="...">
                                    <time>{{ $video->hours > 0 ? $hours_add_zero . ':' : '' }}{{ $minutes_add_zero }}:{{ $seconds_add_zero }}</time>
                                    <i class="fas fa-play fa-2x"></i>
                                </a>
                            </div>
                            <a href="/videos/{{ $video->id }}">
                                <div class="p-0 card-body">
                                    <p class="card-title">{{ Str::limit($video->title, 60) }}</p>
                                </div>
                            </a>
                            <div class="card-footer">
                                <small class="text-muted">
                                    @foreach ($video->views as $view)
                                        <span class="d-block"><i class="fas fa-eye"></i> {{ $view->views_number }}
                                            مشاهدة</span>
                                    @endforeach
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $video->pivot->created_at->diffForHumans() }}</span>
                                    @auth
                                        @if ($video->user_id == auth()->user()->id || auth()->user()->administration_level > 0)
                                            @if (!auth()->user()->block)
                                                <form method="POST" action="{{ route('history.destroy', $video->pivot->id) }}"
                                                    onsubmit="return confirm('هل أنت متأكد أنك تريد حذف مقطع الفيديو هذا؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="float-left"><i
                                                            class="far fa-trash-alt text-danger fa-lg"></i></button>
                                                </form>
                                            @endif
                                        @endif
                                    @endauth
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="mx-auto col-8">
                    <div class="text-center alert alert-primary" role="alert">
                        لا يوجد فيديوهات
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
