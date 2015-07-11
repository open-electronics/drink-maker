<?php namespace App\Http\Middleware;

use App\Settings;
use Closure;

class SettingsExistMiddleware{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
            if (Settings::exists()){
                return $next($request);
            }else {
                return redirect('configure');
            }
    }

}
