<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApplyUserAppearance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply to authenticated users and HTML responses
        if (!Auth::check() || 
            !$response->headers->get('content-type') || 
            !str_contains($response->headers->get('content-type'), 'text/html')) {
            return $response;
        }

        $user = Auth::user();
        $appearance = $user->appearance ?? 'system';

        // Skip processing if appearance is system (let JavaScript handle it)
        if ($appearance === 'system') {
            return $response;
        }

        $content = $response->getContent();
        
        // Apply the appearance class to the HTML element
        $htmlClass = $appearance;
        $content = preg_replace('/<html[^>]*class="[^"]*"/', '<html class="' . $htmlClass . '"', $content);
        
        $response->setContent($content);

        return $response;
    }
}
