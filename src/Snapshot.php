<?php

namespace STS\SnapThis;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use STS\Record\Record;
use STS\SnapThis\Exceptions\SnapshotException;

class Snapshot extends Record implements Responsable
{
    public function verifyPayload()
    {
        if($this->has('name') &&  ($this->has('url') || $this->has('url'))) {
            return $this;
        }

        throw new SnapshotException("Invalid snapshot payload");
    }

    public function getExpiresAtAttribute()
    {
        return new Carbon($this->expires);
    }

    public function canRedirect()
    {
        return $this->has('inline_url');
    }

    public function redirect()
    {
        return response()->redirectTo($this->inline_url);
    }

    public function download()
    {
        if($this->has('download_url')) {
            return response()->redirectTo($this->download_url);
        }

        if($this->has('contents')) {
            return response()->download($this->contents(), $this->name);
        }
    }

    public function inline()
    {
        return response()->file($this->contents());
    }

    public function contents()
    {
        return $this->has('contents')
            ? base64_decode($this->contents)
            : file_get_contents($this->url);
    }

    public function toResponse($request)
    {
        return $this->canRedirect()
            ? $this->redirect()
            : $this->inline();
    }
}