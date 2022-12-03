@extends('layouts.main')

@section('content')
    <div class="row latest-download">
        <div class="container">
            <div class="row justify-content-center">
                <form class="form-inline col-md-6 justify-content-center" action="{{ route('channel.search') }}"
                    method="GET">
                    <input type="text" class="mb-2 form-control mx-sm-3" name="term">
                    <button type="submit" class="mb-2 btn btn-secondary">ابحث</button>
                </form>
            </div>
            <hr>
            <br>
            <p class="my-4">{{ $title }}</p>
            <div class="row">
                @forelse($channels as $channel)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="m-4">
                            <img class="mx-auto rounded-full" width="120px" src="{{ $channel->profile_photo_url }}">

                            <div class="p-0 card-body">
                                <p class="mt-4 text-center">{{ $channel->name }}</p>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('main.channels.video', $channel->id) }}"
                                    class="btn btn-secondary btn-lg">تصفح القناة</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="mx-auto">
                        <p>لا يوجد قنوات</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
