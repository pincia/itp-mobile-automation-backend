<?php

namespace App\Listeners;

use App\Events\NewDrumData;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewDrumData
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
       }

    /**
     * Handle the event.
     *
     * @param  NewDrumData  $event
     * @return void
     */
    public function handle(NewDrumData $event)
    {
        \Log::error('LISTENER HANDLED');
    }
}
