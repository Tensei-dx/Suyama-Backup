<?php

namespace App\Services;

use App\Models\Api;
use App\Models\Book_Room;
use App\Models\Device;
use App\Models\ParamSettings;
use App\Models\SpareKey;
use App\Traits\CommonFunctions;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RemoteLockService
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // getToken                 (1.0) Used for retrieving valid access token
    // generatePin              (2.0) Generates a unique PIN
    // createAccessGuest        (3.0) Create a new access guest
    // grantLockAccess          (4.0) Grants the access guest access to remote lock device
    // updateAccessGuest        (5.0) Update the access guest
    // deactivateAccessGuest    (6.0) Deactivate an access guest
    // processNewGuest          (6.0) Perform complete process of creating a Remote Lock guest access
    //                                with access to the Remote Lock device in the reserved room
    // scanDevices              (7.0) Get all the remote lock devices using the Remote Lock Cloud API
    // unlockDevice             (8.0) Unlock the remote lock device using the Remote Lock API
    // createAccessUser         (9.0) Create an access user account
    // updateAccessUser `       (10.0) Update an access user account
    // processNewUser           (11.0) Perform complete process of creating a Remote Lock guest access with
    //                                  access to the Remote Lock device in the reserved room
    // createSpareKey           (12.0) Create spare key for each Remote Lock device in each room
    // deleteExpiredSpareKeys   (13.0) The logic of pruning the SpareKey entities

    use CommonFunctions;

    /**
     * Hostname of the Remote Lock API
     *
     * @var string
     */
    const HOSTNAME = 'https://api.remotelock.jp';

    /**
     * Required headers for the Remote Lock API
     *
     * @var array
     */
    const HEADERS = [
        'Accept' => 'application/vnd.lockstate+json; version=1',
        'Content-Type' => 'application/json'
    ];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> getToken
     * <Function> Used for retrieving valid access token
     *
     * @return string
     */
    protected function getToken(): string
    {
        $tokenInfo = Api::firstWhere('API_NAME', 'remote_lock_cc');

        return $this->getAccessToken($tokenInfo->API_ID);
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> generatePin
     * <Function> Generates a unique PIN
     *
     * @return string
     */
    public function generatePin(): string
    {
        $length = ParamSettings::value('RL_NUM_PIN');
        $pin = '';

        do {
            $number = (string) rand(0, 10 ** $length - 1);
            $pin = Str::padLeft($number, $length, '0');
        } while (Book_Room::all()->pluck('PIN')->contains($pin));

        return $pin;
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> getDoorGroups
     * <Function> Get the door group list
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function getDoorGroups()
    {
        return Http::withToken($this->getToken())
            ->withHeaders(self::HEADERS)
            ->get(self::HOSTNAME . "/groups");
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> createAccessUser <br>
     * <Function> Create an access user account <br>
     *
     * @param  string  $username
     * @param  string  $email
     * @return \Illuminate\Http\Client\Response
     */
    public function createAccessUser(string $username, string $email = null)
    {
        $attributes['name'] = $username;
        $attributes['pin'] = $this->generatePin();
        if ($email) $attributes['email'] = $email;

        return Http::withToken($this->getToken())
            ->withHeaders(self::HEADERS)
            ->baseUrl(self::HOSTNAME)
            ->post("access_persons", [
                'type' => 'access_user',
                'attributes' => $attributes
            ]);
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> updateAccessUser<br>
     * <Function> Update an access user account <br>
     *
     * @param  string  $userUUID
     * @param  string  $username
     * @param  string  $email
     * @param  bool  $updatePinFlag
     * @return \Illuminate\Http\Client\Response
     */
    public function updateAccessUser(
        string $userUUID,
        string $username,
        string $email,
        bool $updatePinFlag = false
    ) {
        $attributes['name'] = $username;
        $attributes['email'] = $email;
        if ($updatePinFlag) $attributes['pin'] = $this->generatePin();

        return Http::withToken($this->getToken())
            ->withHeaders(self::HEADERS)
            ->put(self::HOSTNAME . "/access_persons/" . $userUUID, [
                'attributes' => $attributes
            ]);
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> createAccessGuest
     * <Function> Create a new access guest
     *
     * @param  string  $username
     * @param  string  $email
     * @param  string  $checkInDateTime
     * @param  string  $checkOutDateTime
     * @param  ?string  $pin
     * @return \Illuminate\Http\Client\Response
     */
    public function createAccessGuest(
        string $username,
        string $email,
        string $checkInDateTime,
        string $checkOutDateTime,
        string $pin = null
    ) {
        return Http::withToken($this->getToken())
            ->withHeaders(self::HEADERS)
            ->post(self::HOSTNAME . "/access_persons", [
                'type' => 'access_guest',
                'attributes' => [
                    'name' => $username,
                    'email' => $email,
                    'pin' => $pin ?? $this->generatePin(),
                    'starts_at' => $checkInDateTime,
                    'ends_at' => $checkOutDateTime
                ]
            ]);
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> updateAccessGuest
     * <Function> Update the access guest
     *
     * @param  string  $guestUUID
     * @param  string  $email
     * @param  string  $checkInDateTime
     * @param  string  $checkOutDateTime
     * @return \Illuminate\Http\Client\Response
     */
    public function updateAccessGuest(
        string $guestUUID,
        string $email,
        string $checkInDateTime,
        string $checkOutDateTime
    ) {
        return Http::withToken($this->getToken())
            ->withHeaders(self::HEADERS)
            ->put(self::HOSTNAME . "/access_persons/" . $guestUUID, [
                'attributes' => [
                    'email' => $email,
                    'starts_at' => $checkInDateTime,
                    'ends_at' => $checkOutDateTime
                ]
            ]);
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> grantLockAccess
     * <Function> Grants the access guest access to remote lock device
     *
     * @param  string  $guestUUID
     * @param  string  $remoteLockDeviceUUID
     * @return \Illuminate\Http\Client\Response
     */
    public function grantLockAccess(string $guestUUID, string $remoteLockDeviceUUID)
    {
        $attributes['accessible_type'] = 'lock';
        $attributes['accessible_id'] = $remoteLockDeviceUUID;

        return Http::withToken($this->getToken())
            ->withHeaders(self::HEADERS)
            ->post(self::HOSTNAME . "/access_persons/" . $guestUUID . "/accesses", [
                'attributes' => $attributes
            ]);
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> grantDoorGroupAccess
     * <Function> Grants the access person to a door group
     *
     * @param  string  $userUUID
     * @param  string  $doorGroupID
     * @return \Illuminate\Http\Response
     */
    public function grantDoorGroupAccess(string $userUUID, string $doorGroupID)
    {
        $attributes['accessible_type'] = 'door_group';
        $attributes['accessible_id'] = $doorGroupID;

        return Http::withToken($this->getToken())
            ->withHeaders(self::HEADERS)
            ->post(self::HOSTNAME . "/access_persons/" . $userUUID . "/accesses", [
                'attributes' => $attributes
            ]);
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> deactivateAccessPerson
     * <Function> Deactivates an access person
     *
     * @param  string  $userUUID
     * @return \Illuminate\Http\Client\Response
     */
    public function deactivateAccessPerson(string $userUUID)
    {
        return Http::withToken($this->getToken())
            ->withHeaders(self::HEADERS)
            ->baseUrl(self::HOSTNAME)
            ->put("/access_persons/$userUUID/deactivate");
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> processNewGuest
     * <Function> Perform complete process of creating a Remote Lock guest access with
     *            access to the Remote Lock device in the reserved room <br>
     *
     * @param  string  $remoteLockDeviceUUID
     * @param  string  $username
     * @param  string  $email
     * @param  string  $checkInDateTime
     * @param  string  $checkOutDateTime
     * @param  ?string  $pin
     * @return array
     */
    public function processNewGuest(
        string $remoteLockDeviceUUID,
        string $username,
        string $email,
        string $checkInDateTime,
        string $checkOutDateTime,
        string $pin = null
    ) {
        // create new Remote Lock "Guest Access"
        $createGuestResponse = $this->createAccessGuest(
            $username,
            $email,
            $checkInDateTime,
            $checkOutDateTime,
            $pin
        );
        if ($createGuestResponse->failed()) {
            throw new \Exception('failed creating new access guest', 400);
        }

        // get the contents of the API response
        $guestData = $createGuestResponse->json();

        // Get the Guest Access UUID
        $guestUUID = $guestData['data']['id'];

        // grant the "Guest Access" access to the Remote Lock device
        $grantAccessResponse = $this->grantLockAccess(
            $guestUUID,
            $remoteLockDeviceUUID
        );

        // if the grantLockAccess fails, deactivate the account
        if ($grantAccessResponse->failed()) {

            $deactivateGuestResponse = $this->deactivateAccessPerson($guestUUID);

            if ($deactivateGuestResponse->failed()) {
                throw new \Exception('failed deactivating access guest', 400);
            }

            throw new \Exception('failed creating guest account', 400);
        }

        // return the "Guest Access" data
        return $guestData;
    }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> scanDevices
     * <Function> Get all the remote lock devices using the Remote Lock Cloud API
     *
     * @return \Illuminate\Support\Collection
     */
    public function scanDevices()
    {
        $response = Http::withToken($this->getToken())
            ->withHeaders(self::HEADERS)
            ->get(self::HOSTNAME . "/devices", [
                'type' => 'lock'
            ]);

        return collect($response['data']);
    }

    /**
     * <Layer number> (13.0)
     *
     * <Processing name> unlockDevice <br>
     * <Function> Unlock the remote lock device using the Remote Lock API <br>
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Client\Response
     */
    public function unlockDevice(string $uuid)
    {
        return Http::withToken($this->getToken())
            ->withHeaders(self::HEADERS)
            ->put(self::HOSTNAME . "/devices/" . $uuid . "/unlock");
    }

    /**
     * <Layer number> (14.0)
     *
     * <Processing name> lockDevice <br>
     * <Function> Lock the remote lock device using the Remote Lock API <br>
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Client\Response
     */
    public function lockDevice(string $uuid)
    {
        return Http::withToken($this->getToken())
            ->withHeaders(self::HEADERS)
            ->put(self::HOSTNAME . "/devices/" . $uuid . "/lock");
    }

    /**
     * <Layer number> (15.0)
     *
     * <Processing name> processNewUser <br>
     * <Function> Perform complete process of creating a Remote Lock user access with
     *            access to the Remote Lock device in the reserved room <br>
     *
     * @param  string  $remoteLockDeviceUUID
     * @param  int  $roomID
     * @return array
     */
    public function processNewUser(string $remoteLockDeviceUUID, int $roomID)
    {
        $username = "Spare Key: $roomID";

        // create new Remote Lock "Access User"
        $createUserResponse = $this->createAccessUser($roomID, $username);

        // if the API fails, return 400s
        if ($createUserResponse->failed()) {
            abort(400, 'failed creating new access user');
        }

        // get the contents of the API response
        $userData = $createUserResponse->json();

        // get the access user UUID
        $userUUID = $userData['data']['id'];

        // grant the "Access User" access to the Remote Lock device
        $grantAccessResponse = $this->grantLockAccess(
            $userUUID,
            $remoteLockDeviceUUID
        );

        // if the grantLockAccess fails, deactivate the account
        if ($grantAccessResponse->failed()) {

            $deactivateGuestResponse = $this->deactivateAccessPerson($userUUID);

            if ($deactivateGuestResponse->failed()) {
                abort(400, 'failed deactivating access user');
            }

            abort(400, 'failed creating user account');
        }

        // return the "Access User" data
        return $userData;
    }

    /**
     * <Layer number> (16.0)
     *
     * <Processing name> createSpareKey <br>
     * <Function> Create spare key for each Remote Lock device in each room <br>
     *
     * @param  \App\Device  $device
     * @param  int  $roomID
     * @return \Illuminate\Http\Response
     */
    public function createSpareKey(Device $device, int $roomID)
    {
        $deviceUUID = $device->DATA['remote_lock_id'];

        $deviceID = $device->DEVICE_ID;

        $today = CarbonImmutable::today();

        // Flag to determine if today's Spare Key is available
        $spareKeyFlag1 = SpareKey::where('ROOM_ID', $roomID)
            ->where('REMOTE_LOCK_DEVICE_ID', $deviceID)
            ->where('STARTS_AT', $today->toDateTimeLocalString())
            ->exists();

        if (!$spareKeyFlag1) {
            // create spare key that expires today
            $guestData1 = $this->processNewGuest(
                $deviceUUID,
                "Spare Key 1: Room No. $roomID",
                '',
                $today->toDateTimeLocalString(),
                $today->addDays(2)->subSecond()->toDateTimeLocalString()
            );

            // create spare key entity in iBMS
            SpareKey::create([
                'ROOM_ID' => $roomID,
                'REMOTE_LOCK_DEVICE_ID' => $deviceID,
                'REMOTE_LOCK_USER_ID' => $guestData1['data']['id'],
                'PIN_CODE' => $guestData1['data']['attributes']['pin'],
                'STARTS_AT' => $guestData1['data']['attributes']['starts_at'],
                'ENDS_AT' => $guestData1['data']['attributes']['ends_at']
            ]);
        }

        // Flag to determine if next day's Spare Key is available
        $spareKeyFlag2 = SpareKey::where('ROOM_ID', $roomID)
            ->where('REMOTE_LOCK_DEVICE_ID', $deviceID)
            ->where('STARTS_AT', $today->addDay()->toDateTimeLocalString())
            ->exists();

        if (!$spareKeyFlag2) {
            // create spare key that expires after 2 days
            $guestData2 = $this->processNewGuest(
                $deviceUUID,
                "Spare Key 2: Room No. $roomID",
                '',
                $today->addDay(1)->toDateTimeLocalString(),
                $today->addDays(3)->subSecond()->toDateTimeLocalString()
            );

            // create spare key entity in iBMS
            SpareKey::create([
                'ROOM_ID' => $roomID,
                'REMOTE_LOCK_DEVICE_ID' => $deviceID,
                'REMOTE_LOCK_USER_ID' => $guestData2['data']['id'],
                'PIN_CODE' => $guestData2['data']['attributes']['pin'],
                'STARTS_AT' => $guestData2['data']['attributes']['starts_at'],
                'ENDS_AT' => $guestData2['data']['attributes']['ends_at']
            ]);
        }

        return response('success');
    }

    /**
     * <Layer number> (17.0)
     *
     * <Processing name> deleteExpiredSpareKeys <br>
     * <Function> The logic of pruning the SpareKey entities <br>
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteExpiredSpareKeys()
    {
        $now = Carbon::now();

        $expiredSpareKeys = SpareKey::where('ENDS_AT', '<=', $now)
            ->get();

        $expiredSpareKeys->each->delete();

        return response('success');
    }
}
