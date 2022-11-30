@extends('layouts.main')
@section('content')
    {{-- To Do Add Videojs  (videojs.com)  --}}
    <div class="container">

        <div class="row">
            <div class="mx-auto col-9">
                <input id="videoId" type="hidden" value="{{ $video->id }}">

                <div class='vidcontainer'>
                    @foreach ($video->convertedvideos as $video_converted)
                        <video id="videoPlayer" controls
                            style='{{ $video->Longitudinal == '0' ? 'width: 100%; height: 90%;' : 'width: 900px; height: 510px;' }}'>
                            @if ($video->quality == 1080)
                                <source id="webm_source" src="{{ Storage::url($video_converted->webm_Format_1080) }}"
                                    type="video/webm">
                                <source id="mp4_source" src="{{ Storage::url($video_converted->mp4_Format_1080) }}"
                                    type="video/mp4">
                            @elseif($video->quality == 720)
                                <source id="webm_source" src="{{ Storage::url($video_converted->webm_Format_720) }}"
                                    type="video/webm">
                                <source id="mp4_source" src="{{ Storage::url($video_converted->mp4_Format_720) }}"
                                    type="video/mp4">
                            @elseif($video->quality == 480)
                                <source id="webm_source" src="{{ Storage::url($video_converted->webm_Format_480) }}"
                                    type="video/webm">
                                <source id="mp4_source" src="{{ Storage::url($video_converted->mp4_Format_480) }}"
                                    type="video/mp4">
                            @elseif($video->quality == 360)
                                <source id="webm_source" src="{{ Storage::url($video_converted->webm_Format_360) }}"
                                    type="video/webm">
                                <source id="mp4_source" src="{{ Storage::url($video_converted->mp4_Format_360) }}"
                                    type="video/mp4">
                            @else
                                <source id="webm_source" src="{{ Storage::url($video_converted->webm_Format_240) }}"
                                    type="video/webm">
                                <source id="mp4_source" src="{{ Storage::url($video_converted->mp4_Format_240) }}"
                                    type="video/mp4">
                            @endif
                        </video>
                    @endforeach
                </div>
                <select id='qualityPick'>
                    <option value="1080" {{ $video->quality == 1080 ? 'selected' : '' }}
                        {{ $video->quality < 1080 ? 'hidden' : '' }}>1080p</option>
                    <option value="720" {{ $video->quality == 720 ? 'selected' : '' }}
                        {{ $video->quality < 720 ? 'hidden' : '' }}>720p</option>
                    <option value="480" {{ $video->quality == 480 ? 'selected' : '' }}
                        {{ $video->quality < 480 ? 'hidden' : '' }}>480p</option>
                    <option value="360" {{ $video->quality == 360 ? 'selected' : '' }}
                        {{ $video->quality < 360 ? 'hidden' : '' }}>360p</option>
                    <option value="240" {{ $video->quality == 240 ? 'selected' : '' }}>240p</option>
                </select>
                <div class="mt-3 title">
                    <h5>
                        {{ $video->title }}
                    </h5>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script>
            document.getElementById("qualityPick").onchange = function() {
                changeQulity()
            };

            function changeQulity() {
                var video = document.getElementById("videoPlayer");
                var curTime = video.currentTime;
                var selected = document.getElementById("qualityPick").value;

                if (selected == '1080') {
                    source = document.getElementById("webm_source").src =
                        "{{ Storage::url($video_converted->webm_Format_1080) }}";
                    source = document.getElementById("mp4_source").src =
                        "{{ Storage::url($video_converted->mp4_Format_1080) }}";
                } else if (selected == '720') {
                    source = document.getElementById("webm_source").src =
                        "{{ Storage::url($video_converted->webm_Format_720) }}";
                    source = document.getElementById("mp4_source").src =
                        "{{ Storage::url($video_converted->mp4_Format_720) }}";
                } else if (selected == '480') {
                    source = document.getElementById("webm_source").src =
                        "{{ Storage::url($video_converted->webm_Format_480) }}";
                    source = document.getElementById("mp4_source").src =
                        "{{ Storage::url($video_converted->mp4_Format_480) }}";
                } else if (selected == '360') {
                    source = document.getElementById("webm_source").src =
                        "{{ Storage::url($video_converted->webm_Format_360) }}";
                    source = document.getElementById("mp4_source").src =
                        "{{ Storage::url($video_converted->mp4_Format_360) }}";
                } else if (selected == '240') {
                    source = document.getElementById("webm_source").src =
                        "{{ Storage::url($video_converted->webm_Format_240) }}";
                    source = document.getElementById("mp4_source").src =
                        "{{ Storage::url($video_converted->mp4_Format_240) }}";
                }

                video.load();
                video.play();
                video.currentTime = curTime;

            }
        </script>
    @endsection
