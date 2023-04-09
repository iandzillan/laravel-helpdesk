<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                switch (Auth::user()->role) {
                    case 'Admin':
                        return redirect()->route('admin.dashboard');
                        break;

                    case 'Approver1':
                        return redirect()->route('dept.dashboard');
                        break;
                    
                    case 'Approver2':
                        return redirect()->route('subdept.dashboard');
                        break;
                    
                    case 'User':
                        return redirect()->route('user.dashboard');
                        break;
                    
                    case 'Technician':
                        return redirect()->route('technician.dashboard');
                        break;
                    
                    default:
                        abort(404);
                        break;
                }
                // return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
