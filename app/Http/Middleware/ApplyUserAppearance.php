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

        // Only apply to authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            $appearance = $user->appearance ?? 'system';

            // Apply the appearance class to the HTML element
            if ($response->headers->get('content-type') && str_contains($response->headers->get('content-type'), 'text/html')) {
                $content = $response->getContent();
                
                // For system preference, we'll let JavaScript handle it
                // For light/dark, apply directly
                $htmlClass = $appearance === 'system' ? 'system' : $appearance;
                
                // Replace the default class with the user's preference
                $content = preg_replace('/<html[^>]*class="[^"]*"/', '<html class="' . $htmlClass . '"', $content);
                
                $response->setContent($content);
            }
        }

        return $response;
    }
}
