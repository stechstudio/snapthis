<?php

namespace STS\SnapThis;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Arr;

class Snapshot implements Responsable
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function url()
    {
        return Arr::get($this->response, 'url');
    }

    public function downloadUrl()
    {
        return Arr::get($this->response, 'download');
    }

    public function inlineUrl()
    {
        return Arr::get($this->response, 'inline');
    }

    public function size()
    {
        return Arr::get($this->response, 'size');
    }

    public function redirect()
    {
        return response()->redirectTo($this->inlineUrl());
    }

    public function download()
    {
        return response()->redirectTo($this->downloadUrl());
    }

    public function contents()
    {
        return file_get_contents($this->url());
    }

    public function toResponse($request)
    {
        return $this->redirect();
    }
}