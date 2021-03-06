<?php

namespace STS\SnapThis;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;
use STS\SnapThis\Exceptions\SnapshotException;

class Client
{
    protected $endpoint = 'https://api.snapthis.io';

    protected $payload = [];

    protected static $defaults;

    public function __construct($apiKey)
    {
        $this->payload['apikey'] = $apiKey;
    }

    public static function setDefaults(Closure $closure)
    {
        static::$defaults = $closure;
    }

    public function applyDefaults()
    {
        if (static::$defaults) {
            call_user_func(static::$defaults, $this);
        }

        return $this;
    }

    public function url(string $url)
    {
        $this->payload['url'] = $url;

        return $this;
    }

    public function html(string $html)
    {
        $this->payload['html'] = $html;

        return $this;
    }

    public function view(string $view, $data = [], $mergeData = [])
    {
        return $this->html(
            view($view, $data, $mergeData)->render()
        );
    }

    public function contents($contents)
    {
        if ($contents instanceof View) {
            return $this->html($contents->render());
        }

        return Str::startsWith($contents, ['http://', 'https://'])
            ? $this->url($contents)
            : $this->html($contents);
    }

    public function output(string $output)
    {
        $this->payload['output'] = $output;

        return $this;
    }

    public function screenshot($contents = null)
    {
        return $this->snapshot($contents);
    }

    public function snapshot($contents = null)
    {
        $this->output('png');

        if (!is_null($contents)) {
            $this->contents($contents);
        }

        return $this->take();
    }

    public function pdf($contents = null)
    {
        $this->output('pdf');

        if (!is_null($contents)) {
            $this->contents($contents);
        }

        return $this->take();
    }

    public function take()
    {
        $response = Http::post($this->endpoint, $this->payload);

        if ($response->status() == 200) {
            return (new Snapshot($response->json()))->verifyPayload();
        }

        if ($response->status() == 500 && $response->json('message', false)) {
            throw new SnapshotException($response->json('message'));
        }

        throw new SnapshotException("Unknown failure");
    }

    public function name(string $name)
    {
        $this->payload['filename'] = $name;

        return $this;
    }

    public function background(bool $background)
    {
        $this->payload['background'] = $background;

        return $this;
    }

    public function showBackground()
    {
        $this->payload['background'] = true;

        return $this;
    }

    public function hideBackground()
    {
        $this->payload['background'] = false;

        return $this;
    }

    public function windowSize(int $width, int $height)
    {
        $this->payload['windowSize'] = [$width, $height];

        return $this;
    }

    public function deviceScale(int $scale)
    {
        $this->payload['deviceScale'] = $scale;

        return $this;
    }

    public function pageSize(string $pageSize)
    {
        $this->payload['pageSize'] = $pageSize;

        return $this;
    }

    public function fullPage()
    {
        $this->payload['fullpage'] = true;

        return $this;
    }

    public function layout(string $layout)
    {
        $this->payload['layout'] = $layout;

        return $this;
    }

    public function landscape()
    {
        return $this->layout('landscape');
    }

    public function wait()
    {
        $this->payload['wait'] = true;

        return $this;
    }

    public function margins(float $top, float $right, float $bottom, float $left, string $unit = 'in')
    {
        $unit = in_array($unit, ['mm', 'cm', 'in', 'px']) ? $unit : 'in';

        $this->payload['margins'] = [$top, $right, $bottom, $left, $unit];

        return $this;
    }

    public function header(string $html)
    {
        $this->payload['header'] = $html;

        return $this;
    }

    public function footer(string $html)
    {
        $this->payload['footer'] = $html;

        return $this;
    }

    public function media(string $media)
    {
        $this->payload['media'] = $media;

        return $this;
    }

    public function headers(array $headers)
    {
        $this->payload['headers'] = $headers;

        return $this;
    }

    public function expiration(Carbon $expiration)
    {
        $this->payload['expiration'] = $expiration->toISOString();

        return $this;
    }

    public function lifetime(int $lifetime)
    {
        $this->payload['lifetime'] = $lifetime;

        return $this;
    }

    public function private()
    {
        $this->payload['private'] = true;

        return $this;
    }

    public function inline()
    {
        $this->payload['inline'] = true;

        return $this;
    }

    public function resizeTo(int $width, int $height, $method = 'contain')
    {
        $method = in_array($method, ['contain', 'crop']) ? $method : 'contain';

        $this->payload['resize'] = [$width, $height, $method];

        return $this;
    }
}