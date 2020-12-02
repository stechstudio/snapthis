<?php

namespace STS\SnapThis;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use STS\Record\Record;

class Snapshot extends Record implements Responsable
{
    public function getExpiresAtAttribute()
    {
        return new Carbon($this->expires);
    }

    public function redirect()
    {
        return response()->redirectTo($this->inline_url);
    }

    public function download()
    {
        return response()->redirectTo($this->download_url);
    }

    public function contents()
    {
        return file_get_contents($this->url);
    }

    public function toResponse($request)
    {
        return $this->redirect();
    }
}