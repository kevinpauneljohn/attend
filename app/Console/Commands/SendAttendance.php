<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Jmrashed\Zkteco\Lib\ZKTeco;

class SendAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the biometrics attendance to the local server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->saveAttendance();
    }

    private function getBiometricsAttendance(): array
    {
        $zkTeco = new ZKTeco(config('biometrics.ipAddress'));
        $zkTeco->connect();
        $zkTeco->disableDevice();
        $attendance = $zkTeco->getAttendance();
        $zkTeco->enableDevice();
        return collect($attendance)->map(function ($item, $key) {
            return collect($item)->merge(['is_sent' => false]);
        })->toArray();
    }

    private function saveAttendance(): void
    {
        foreach ($this->getBiometricsAttendance() as $attendance) {
          $attendanceCount = Attendance::where('uid',$attendance['uid'])->count();
            if($attendanceCount === 0)
            {
                $this->info(json_encode($attendance));
                DB::table('attendances')->insert($attendance);
            }else{
                $this->info('no new attendance');
            }
        }
    }
}
