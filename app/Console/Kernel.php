<?php

namespace App\Console;

use App\Dialog;
use App\Distribution;
use App\Http\Controllers\telegramController;
use App\Http\Controllers\vkController;
use App\Image;
use App\Jobs\sendToTlgrmJob;
use App\Jobs\sendToVKJob;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $distributions = Distribution::where('run_date','<=',Carbon::now())->get();
            foreach ($distributions as $distribution){
                $services=DB::table('distribution_services')->where('distribution_id','=',$distribution->id)->get();
                $image_path=(\DB::table('images')->join('image_distributions','image_distributions.image_id','=','images.id')
                    ->where('image_distributions.distribution_id','=',$distribution->id)->get())[0]->path;
                foreach ($services as $service){
                    switch ($service->id){
                        case 2:
                            $points=Dialog::where('service_id','=',2)->get();
                            foreach ($points as $point){
                                sendToVKJob::dispatch($point->chat_id, $distribution->text, $image_path, vkController::makeKeyboardVK($point->dialog_stage_id,$point->chat_id,2),['next_stage'=>$point->dialog_stage_id,'pre_stage'=>$point->pre_stage,'spec_info'=>$point->spec_info]);
                            }
                            break;
                        case 3:
                            $points=Dialog::where('service_id','=',3)->get();
                            foreach ($points as $point){
                                sendToTlgrmJob::dispatch($point->chat_id, $distribution->text, $image_path, telegramController::makeKeyboardTlgrm($point->dialog_stage_id,$point->chat_id,3),['next_stage'=>$point->dialog_stage_id,'pre_stage'=>$point->pre_stage,'spec_info'=>$point->spec_info]);
                            }
                            break;
                    }
                }
            }
        })->everyThirtyMinutes();
    }

}
