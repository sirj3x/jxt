<?php

namespace Sirj3x\Jxt;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Sirj3x\Jxt\Console\JXTGenerateSecretCommand;

class JxtServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/jxt.php' => config_path('jxt.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                JXTGenerateSecretCommand::class,
            ]);
        }

        // register auth driver
        Auth::viaRequest('jxt', function (Request $request) {
            if (!$request->hasHeader('Authorization')) return null;
            $tokenData = JxtToken::decode($request->bearerToken());
            if (count($tokenData) == 0) return null;
            $guards = JxtHelper::getAppliedGuards($request->route()->getAction());
            if (!in_array($tokenData["guard"], $guards)) return null;
            try {
                $guardProvider = config('auth.guards')[$tokenData["guard"]]["provider"];
                $guardModel = config('auth.providers')["$guardProvider"]["model"];
            }
            catch (\Exception $exception) {
                return null;
            }
            $query = new $guardModel;
            return $query->find($tokenData["user_id"]);
        });
    }
}
