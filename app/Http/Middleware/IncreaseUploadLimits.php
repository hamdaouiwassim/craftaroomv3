<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncreaseUploadLimits
{
    /**
     * Handle an incoming request.
     * Increase PHP upload limits for large file uploads (videos/reels)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Note: upload_max_filesize and post_max_size cannot be changed via ini_set
        // They must be set in php.ini, .htaccess, or .user.ini
        // But we can set other limits that are changeable
        
        // These can be changed at runtime
        ini_set('max_execution_time', '300');
        ini_set('max_input_time', '300');
        ini_set('memory_limit', '256M');
        
        // Log current PHP limits for debugging
        \Log::info('Upload limits check', [
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
        ]);

        return $next($request);
    }
}
