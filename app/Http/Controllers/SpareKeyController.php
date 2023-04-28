<?php

namespace App\Http\Controllers;

use App\Events\SpareKeysUpdated;
use App\Models\Room;
use App\Models\SpareKey;
use App\Services\RemoteLockService;
use Illuminate\Http\Request;

class SpareKeyController extends Controller
{
    /****************************************************************/
    /* Processing Heirarchy                                         */
    /****************************************************************/
    // index        (1.0) Display a listing of the SpareKey resource
    // refresh      (2.0) Refresh all spare key PIN codes

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> index <br>
     * <Function> Show a listing of SpareKey resource <br>
     *            METHOD: GET
     *            URI: /spare_keys/
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->USER_TYPE !== 1) {
            return response('Unauthorized', 401);
        }

        $query = SpareKey::query();

        if ($request->boolean('show_room')) {
            $query->with('room');
        }

        if ($request->boolean('show_device')) {
            $query->with('device');
        }

        return response($query->get());
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> refresh <br>
     * <Function> Refresh all spare key PIN codes <br>
     *            METHOD: POST
     *            URI: /spare_keys/refresh
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        $service = new RemoteLockService();

        // get all the SpareKey entities
        $spareKeys = SpareKey::all();

        // deactivate the access guests
        foreach ($spareKeys as $spareKey) {
            $service->deactivateAccessPerson($spareKey->REMOTE_LOCK_USER_ID);
        }

        // delete the old access keys
        $spareKeys->each->delete();

        // get all the Room entities with the RemoteLockDevices
        $rooms = Room::has('remotelockDevices')
            ->with('remotelockDevices')
            ->get();

        try {
            // create SpareKey for each RemoteLock device
            foreach ($rooms as $room) {
                foreach ($room->remotelockDevices as $remoteLockDevice) {
                    $service->createSpareKey($remoteLockDevice, $room->ROOM_ID);
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        // delete expired spare keys
        $service->deleteExpiredSpareKeys();

        // emit an event when updating the spare keys are done
        broadcast(new SpareKeysUpdated());

        return response('success');
    }
}
