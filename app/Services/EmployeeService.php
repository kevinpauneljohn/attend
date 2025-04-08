<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Jmrashed\Zkteco\Lib\ZKTeco;

class EmployeeService
{

    public mixed $owner_id;

    public function __construct()
    {
        $this->owner_id = config('api.owner_id');
    }

    public function zkTecoInitialize(): ZKTeco
    {
        return new ZKTeco(config('biometrics.ipAddress'));
    }
    public function getEmployeesAttendance(): array
    {
        $zkTeco = $this->zkTecoInitialize();
        $zkTeco->connect();
        $zkTeco->disableDevice();
        $users = $zkTeco->getUser();
        $zkTeco->enableDevice();
        $zkTeco->disableDevice();
        $attendance = $zkTeco->getAttendance();
        $zkTeco->enableDevice();

        return collect($attendance)->map(function ($item, $key) use ($users){
            $employee = collect($users)->where('userid',$item['id'])->first();
            $item['timestamp'] = Carbon::parse($item['timestamp'])->format('Y-m-d h:i:s a');
            $item['type'] = $this->attendanceType($item['type']);
            return collect($item)->merge(['name' => $employee['name']]);
        })->all();
    }

    private function attendanceType($type): string
    {
        if($type === 10)
        {
            return 'Check In';
        }
        elseif($type === 11)
        {
            return 'Check Out';
        }
        elseif($type === 4)
        {
            return 'Break In';
        }
        else{
            return 'Break Out';
        }
    }

    public function clearAttendance()
    {
        $zkTeco = $this->zkTecoInitialize();
        $zkTeco->connect();
        $zkTeco->disableDevice();
        $clearAttendance = $zkTeco->clearAttendance();
        $zkTeco->enableDevice();
        return $clearAttendance;
    }

    public function getAccessToken()
    {
        $response = Http::asForm()->post(config('api.main_app_url').'/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('api.client_id'),
            'client_secret' => config('api.client_secret'),
        ]);

        return $response->json()['access_token'];
    }

    public function storeAccessTokenToSession()
    {
        if(is_null(session('access_token')))
        {
            session()->put('access_token',$this->getAccessToken());
        }
        return session('access_token');
    }

    public function getEmployeesFromMainApp()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->storeAccessTokenToSession(),
        ])->get(config('api.main_app_url').'/api/get-all-employees/'.$this->owner_id);

        return $response->json();
    }

    public function getBiometricUsers(): array
    {
        $zkTeco = $this->zkTecoInitialize();
        $zkTeco->connect();
        $zkTeco->disableDevice();
        $users = $zkTeco->getUser();
        $zkTeco->enableDevice();
        return $users;
    }

    public function connectEmployeeToBiometrics($employee_biometrics_userid, $employee_id)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->storeAccessTokenToSession(),
        ])->post(config('api.main_app_url').'/api/add-employee-to-biometrics/'.$employee_id,[
            'biometric_users' => $employee_biometrics_userid
        ]);

        return $response->json();
    }
}
