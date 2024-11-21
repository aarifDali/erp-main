<?php

namespace Workdo\Holidayz\Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();


        // email notification
        $notifications = [
            'New Hotel','New Hotel Customer','New Room Booking Invoice','New Room Booking Invoice Payment','Room Booking Invoice Status Updated','New Room Booking By Hotel Customer'
        ];
        $permissions = [
            'holidayz manage',
            'hotel customer manage',
            'rooms booking manage',
            'rooms booking manage',
            'rooms booking manage',
            'rooms booking manage'

        ];
        foreach($notifications as $key=>$n){
            $ntfy = Notification::where('action',$n)->where('type','mail')->where('module','Holidayz')->count();
            if($ntfy == 0){
                $new = new Notification();
                $new->action = $n;
                $new->status = 'on';
                $new->permissions = $permissions[$key];
                $new->module = 'Holidayz';
                $new->type = 'mail';
                $new->save();
            }
        }
    }
}
