<?php

/**
 * Update : 2021.09.02 TP Jermaine SPRINT_05 TASK114
 **/

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\HttpsProtocol::class,
        // \App\Http\Middleware\LogsHigh::class
        // \App\Http\Middleware\AuditTrail::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            //\App\Http\Middleware\AbortIfUnauthenticated::class,
        ],

        'api' => [
            // \App\Http\Middleware\AuthenticateApi::class,
            // 'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'auth.admin' => \App\Http\Middleware\AuthenticateIfAdmin::class,
        'module' => \App\Http\Middleware\ModuleMiddleware::class,
        'high' => \App\Http\Middleware\LogsHigh::class,
        'expire' => \App\Http\Middleware\SessionExpire::class,
        'timeout' => \App\Http\Middleware\SessionTimeout::class,
        // + SPRINT_05 [TASK114]
        'authenticatedAdmin' => \App\Http\Middleware\ManagementMiddleware::class,
        // + SPRINT_05 [TASK114]
        'locale' => \App\Http\Middleware\DetectLocale::class,
        'appengine.cron' => \App\Http\Middleware\AppEngineCronRequest::class,
        'authenticated' => \App\Http\Middleware\AbortIfUnauthenticated::class
    ];
}
