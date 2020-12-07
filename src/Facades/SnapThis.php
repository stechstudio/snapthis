<?php

namespace STS\SnapThis\Facades;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Facade;
use STS\SnapThis\Client;
use STS\SnapThis\Snapshot;

/**
 * @see \STS\SnapThis\Client
 * @method static self url(string $url)
 * @method static self html(string $html)
 * @method static self name(string $name)
 * @method static self view($view, $data = [], $mergeData = [])
 * @method static self contents(mixed $contents)
 * @method static self output(string $output)
 * @method static self inline()
 * @method static self private()
 * @method static Snapshot screenshot(mixed $contents)
 * @method static Snapshot snapshot(mixed $contents)
 * @method static Snapshot pdf(mixed $contents)
 * @method static self background(bool $background)
 * @method static self showBackground()
 * @method static self hideBackground()
 * @method static self windowSize(int $width, int $height)
 * @method static self pageSize(string $pageSize)
 * @method static self fullPage()
 * @method static self layout(string $layout)
 * @method static self landscape()
 * @method static self wait()
 * @method static self margins($top, $right, $bottom, $left, $unit = 'mm')
 * @method static self header(string $html)
 * @method static self footer(string $html)
 * @method static self media(string $media)
 * @method static self headers(array $headers)
 * @method static self expiration(Carbon $expiration)
 * @method static self lifetime(int $lifetime)
 */
class SnapThis extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }

    public static function setDefaults(Closure $closure)
    {
        Client::setDefaults($closure);
    }
}
