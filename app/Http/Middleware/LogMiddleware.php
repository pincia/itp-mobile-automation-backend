<?php

namespace App\Http\Middleware;

use Closure;

class LogMiddleware 
{

      public function handle($request, Closure $next)
    {

         \Log::info("PLUTO");
        $request->start = microtime(true);

        return $next($request);
    }

    public function terminate($request, $response)
    {
        $request->end = microtime(true);

        $this->log($request,$response);
    }

    protected function log($request,$response)
    {
        $duration = $request->end - $request->start;
        $url = $request->fullUrl();
        $method = $request->getMethod();
        $ip = $request->getClientIp();

        $log = "{$ip}: {$method}@{$url} - {$duration}ms \n".
        "Request : {[$request->all()]} \n".
        "Response : {$response->getContent()} \n";

       \Log::info($log);
    }
}