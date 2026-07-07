<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Offline Threshold
    |--------------------------------------------------------------------------
    |
    | Number of seconds of inactivity (no heartbeat / online call) after
    | which a user is considered offline by the presence sweep command.
    | This should be at least 2-3x the heartbeat interval used by the
    | mobile client to tolerate missed pings / brief connectivity drops.
    |
    */

    'offline_threshold_seconds' => env('PRESENCE_OFFLINE_THRESHOLD', 90),

];
