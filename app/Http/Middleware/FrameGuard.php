<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;

    class FrameGuard
    {
        /**
         * Handle an incoming request.
         *
         * @param \Illuminate\Http\Request $request
         * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
         * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
         */
        public function handle($request, Closure $next)
        {
            $response = $next($request);

            // Add Content-Security-Policy header
            $response->header('Access-Control-Allow-Origin', '*');
            $response->header('Access-Control-Allow-Methods', '*');
            $response->header('Access-Control-Allow-Headers',' Origin, Content-Type, Accept, Authorization, X-Request-With');
            $response->header('Access-Control-Allow-Credentials',' true');
      
            return $response;
        }
    }
