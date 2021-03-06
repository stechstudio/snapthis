<?php

namespace STS\SnapThis;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Testing\MimeType;
use STS\Record\Record;
use STS\SnapThis\Exceptions\SnapshotException;

class Snapshot extends Record implements Responsable
{
    public function verifyPayload()
    {
        if($this->has('name') &&  ($this->has('url') || $this->has('contents'))) {
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

    public function download($filename = null)
    {
        if($this->has('download_url') && ($filename == null || $filename == $this->filename)) {
            return response()->redirectTo($this->download_url);
        }

        return response()->make($this->contents(), 200, [
            'Content-Type' => $this->contentType(),
            'Content-Disposition' => 'attachment; filename="' . ($filename ?? $this->filename) . '"'
        ]);
    }

    public function inline()
    {
        return response()->make($this->contents(), 200, ['Content-Type' => $this->contentType()]);
    }

    public function contentType()
    {
        return MimeType::get($this->type);
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