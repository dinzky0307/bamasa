<?php

namespace App\Http\Middleware;

use App\Models\Business;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BusinessWizardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Only apply to logged-in business owners
        if ($user && $user->role === 'business') {

            $business = Business::where('user_id', $user->id)->first();

            // If business exists but wizard not completed, force them into /owner/wizard/*
            if ($business && ! $business->wizard_completed) {

                // Allow any URL under /owner/wizard/*
                if (! $request->is('owner/wizard/*')) {
                    return redirect('/owner/wizard/step1');
                }
            }
        }

        return $next($request);
    }
}
