<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Services\EmployeeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SendAttendanceToServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:attendance-to-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send the attendance to the liver server';

    /**
     * Execute the console command.
     */
    public function handle(EmployeeService $employeeService)
    {
        $attendances = Attendance::where('is_sent',false)->get();
        foreach ($attendances as $attendance)
        {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$employeeService->storeAccessTokenToSession()
            ])->post(config('api.main_app_url').'/api/store-attendance',[
                'type' => $attendance->type,
                'timestamp' => $attendance->timestamp,
                'id' => $attendance->id
            ])->json();

            if($response['success'] === true)
            {
                $this->markAttendanceAsSent($attendance->uid);
                $this->info($attendance->type .' success');
            }
            else{
                $this->info($attendance->type .' failed');
            }
        }
    }

    private function markAttendanceAsSent($uid): void
    {
        DB::table('attendances')->where('uid',$uid)->update(['is_sent' => true]);
    }

}
