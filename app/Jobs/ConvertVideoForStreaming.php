<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use FFMpeg;
// use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Str;
use App\Models\Video;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $video;
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $lowBitFormat = (new X264('aac', 'libx264'))->setKiloBitrate(500);
        $low2BitFormat = (new X264('aac', 'libx264'))->setKiloBitrate(900);
        $MediumBitFormat = (new X264('aac', 'libx264'))->setKiloBitrate(1500);
        $hightBitFormat = (new X264('aac', 'libx264'))->setKiloBitrate(3000);


        $convertedName = '240-' . $this->video->video_path;
        $convertedName_360 = '360-' . $this->video->video_path;
        $convertedName_480 = '480-' . $this->video->video_path;
        $convertedName_720 = '720-' . $this->video->video_path;


        FFMpeg::fromDisk('public')
            ->open($this->video->video_path)
            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(426, 240));
            })->export()
            ->toDisk('public')
            ->inFormat($lowBitFormat)
            ->save($convertedName)

            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(640, 360));
            })->export()
            ->toDisk('public')
            ->inFormat($low2BitFormat)
            ->save($convertedName_360)

            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(854, 480));
            })->export()
            ->toDisk('public')
            ->inFormat($MediumBitFormat)
            ->save($convertedName_480)

            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(1280, 720));
            })->export()
            ->toDisk('public')
            ->inFormat($hightBitFormat)
            ->save($convertedName_720);
    }
}
