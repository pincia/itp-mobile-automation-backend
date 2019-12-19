<?php

namespace App\Http\Middleware;


use Closure;
use Asm89\Stack\CorsService;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\Request;
use Illuminate\Http\Response as LaravelResponse;
use Symfony\Component\HttpFoundation\Response;
class Cors



{
    /** @var CorsService $cors */
    protected $cors;
    /** @var Dispatcher $events */
    protected $events;
    public function __construct(CorsService $cors, Dispatcher $events)
    {
        $this->cors = $cors;
        $this->events = $events;
    }
    /**
     * Handle an incoming request. Based on Asm89\Stack\Cors by asm89
     * @see https://github.com/asm89/stack-cors/blob/master/src/Asm89/Stack/Cors.php
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
    return $next($request)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization');
}
}