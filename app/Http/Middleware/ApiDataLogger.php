<?php

namespace App\Http\Middleware;
use Tymon\JWTAuth\JWTAuth;
use App\GdprLog;
use \DateTime;
use \DateTimeZone;
use App\User;


use Closure;

class ApiDataLogger
{
    protected $jwt;

    public function __construct(JWTAuth $jwt) {
        // var_dump(1);        
        $this->jwt = $jwt;
        // var_dump(2);die();
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->startTime = microtime(true);
        return $next($request);
    }
    public function terminate($request, $response)
    {   

        $date = new DateTime("now", new DateTimeZone('Europe/Belgrade'));
        $date->format('Y-m-d H:i:s');

        $gdpr_sensitive = 'jmbg, email, broj licne karte';
        $user = User::findOrFail($request->id);

        if($user->jmbg != null && $user->personal_id == null) {
            // var_dump($user,'asdasd');die();
            GdprLog::create([
                'user' => $this->jwt->user(),
                'date_and_time' => $date,
                'gdpr_sensitive_data' => 'JMBG',
                'ip_adress' => $request->ip(),
                'requested_user_id' => $user->id
            ]);
        } else if($user->personal_id != null && $user->personal_id == null) {
            GdprLog::create([
                'user' => $this->jwt->user(),
                'date_and_time' => $date,
                'gdpr_sensitive_data' => 'Broj licne karte',
                'ip_adress' => $request->ip(),
                'requested_user_id' => $user->id
            ]);
        } else if($user->jmbg != null && $user->personal_id != null) {
            GdprLog::create([
                'user' => $this->jwt->user(),
                'date_and_time' => $date,
                'gdpr_sensitive_data' => 'JMBG i broj licne karte',
                'ip_adress' => $request->ip(),
                'requested_user_id' => $user->id
            ]);
        }

        if ( env('API_DATALOGGER', true) ) {
            $endTime = microtime(true);
            $filename = 'api_datalogger_' . $date->format('Y-m-d') . '.log';
            $dataToLog  = 'Time: '   . $date->format('H:i:s') . "\n";
            $dataToLog .= 'Duration: ' . number_format($endTime - LARAVEL_START, 3) . "\n";
            $dataToLog .= 'IP Address: ' . $request->ip() . "\n";
            $dataToLog .= 'URL: '    . $request->fullUrl() . "\n";
            $dataToLog .= 'Method: ' . $request->method() . "\n";
            $dataToLog .= 'Input: '  . $request->getContent() . "\n";
            $dataToLog .= 'User: '   . $this->jwt->user() . "\n";
            $dataToLog .= 'Requested user' . $user->name . ' ' . $user->surname . "\n";
        }

        \File::append( storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 20) . "\n\n");
        
    }
}
