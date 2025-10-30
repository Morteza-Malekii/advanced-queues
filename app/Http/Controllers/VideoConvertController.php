<?php

namespace App\Http\Controllers;

use App\Jobs\VideoConvertorJob;
use Illuminate\Http\Request;

class VideoConvertController extends Controller
{
    public function convert()
    {
        $path = '/storage/12.mp4';
        VideoConvertorJob::dispatch($path);
    }
}
