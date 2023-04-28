<?php

namespace App\Services;

use App\Events\NatureRemoApplianceStateUpdated;
use App\Models\NatureRemoAppliance;
use App\Models\NatureRemoSignal;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Http;

class NatureRemoService
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // fetchDeviceData              (1.0) Fetch device data through Nature Remo Cloud API
    // fetchApplianceData           (2.0) Fetch appliance data through Nature Remo Cloud API
    // storeInfraredSignals         (3.0) Store available signals extracted from an IR appliance
    // storeTelevisionSignals       (4.0) Store available signals extracted from a TV appliance
    // storeLightSignals            (5.0) Store available signals extracted from a LIGHT appliance
    // storeAirConditionerSignals   (6.0) Store available signals extracted from an AC appliance
    // sendInfraredSignal           (7.0) Request to send a signal of an IR appliance
    // sendTelevisionSignal         (8.0) Request to send a signal of a TV appliance
    // sendLightSignal              (9.0) Request to send a signal of a LIGHT appliance
    // sendAirConditionerSignals    (10.0) Request to send a signal of an AC appliance
    // processCommand               (11.0) Prepare and process the requested signal to be sent

    use CommonFunctions;

    /**
     * The common hostname for Nature Remo Cloud API.
     *
     * @var  string  $hostname
     */
    private string $hostname = 'https://api.nature.global';

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> fetchDeviceData <br>
     * <Function> Fetch device data through Nature Remo Cloud API <br>
     *
     * @param  string  $token
     * @return \Illuminate\Http\Client\Response
     */
    public function fetchDeviceData(string $token)
    {
        return Http::withToken($token)
            ->acceptJson()
            ->get("{$this->hostname}/1/devices");
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> fetchApplianceData <br>
     * <Function> Fetch appliance data through Nature Remo Cloud API <br>
     *
     * @param  \App\NatureRemoAccount  $account
     * @return \Illuminate\Http\Client\Response
     */
    public function fetchApplianceData(string $token)
    {
        return Http::withToken($token)
            ->acceptJson()
            ->get("{$this->hostname}/1/appliances");
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> storeInfraredSignals <br>
     * <Function> Store available signals extracted from an IR appliance <br>
     *
     * @param  \App\NatureRemoAppliance  $appliance
     * @param  array  $item
     * @return \Illuminate\Http\Response
     */
    public function storeInfraredSignals(NatureRemoAppliance $appliance, array $item)
    {
        // Only retrieve required attributes and transform the collection to array
        $signals = collect($item['signals'])->map(fn ($signal) => [
            'SIGNAL_UUID' => $signal['id'],
            'SIGNAL_NAME' => $signal['name'],
            'SIGNAL_LABEL' => $signal['name'],
            'SIGNAL_GROUP' => null,
            'SIGNAL_DATA' => null
        ])->toArray();

        $appliance->natureRemoSignals()->createMany($signals);

        return response()->noContent();
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> storeTelevisionSignals <br>
     * <Function> Store available signals extracted from a TV appliance <br>
     *
     * @param  \App\NatureRemoAppliance  $appliance
     * @param  array  $item
     * @return \Illuminate\Http\Response
     */
    public function storeTelevisionSignals(NatureRemoAppliance $appliance, array $item)
    {
        $signals = collect($item['tv']['buttons'])->map(fn ($signal) => [
            'SIGNAL_UUID' => null,
            'SIGNAL_NAME' => $signal['name'],
            'SIGNAL_LABEL' => $signal['label'],
            'SIGNAL_GROUP' => null,
            'SIGNAL_DATA' => $signal['name']
        ])->toArray();

        $appliance->natureRemoSignals()->createMany($signals);

        return response()->noContent();
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> storeLightSignals <br>
     * <Function> Store available signals extracted from a LIGHT appliance <br>
     *
     * @param  \App\NatureRemoAppliance  $appliance
     * @param  array  $item
     * @return \Illuminate\Http\Response
     */
    public function storeLightSignals(NatureRemoAppliance $appliance, array $item)
    {
        $signals = collect($item['light']['buttons'])->map(fn ($signal) => [
            'SIGNAL_UUID' => null,
            'SIGNAL_NAME' => $signal['name'],
            'SIGNAL_LABEL' => $signal['label'],
            'SIGNAL_GROUP' => null,
            'SIGNAL_DATA' => $signal['name']
        ])->toArray();

        $appliance->natureRemoSignals()->createMany($signals);

        return response()->noContent();
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> storeAirConditionerSignals <br>
     * <Function> Store available signals extracted from an AC appliance <br>
     *
     * @param  \App\NatureRemoAppliance  $appliance
     * @param  array  $item
     * @return \Illuminate\Http\Response
     */
    public function storeAirConditionerSignals(NatureRemoAppliance $appliance, array $item)
    {
        $buttons = collect($item['aircon']['range']['fixedButtons'])->map(fn ($button) => [
            'SIGNAL_UUID' => null,
            'SIGNAL_NAME' => 'button',
            'SIGNAL_LABEL' => "{$button} button",
            'SIGNAL_GROUP' => null,
            'SIGNAL_DATA' => $button
        ])->toArray();

        $appliance->natureRemoSignals()->createMany($buttons);

        // register each operation mode
        foreach ($item['aircon']['range']['modes'] as $mode => $modeSetting) {
            $appliance->natureRemoSignals()->create([
                'SIGNAL_UUID' => null,
                'SIGNAL_NAME' => 'operation_mode',
                'SIGNAL_LABEL' => "{$mode} mode",
                'SIGNAL_GROUP' => null,
                'SIGNAL_DATA' => $mode
            ]);

            // register available temperature range for the current operation mode
            $temps = collect($modeSetting['temp'])->map(fn ($temp) => [
                'SIGNAL_UUID' => null,
                'SIGNAL_NAME' => 'temperature',
                'SIGNAL_LABEL' => $temp === '' ? 'temp auto' : "temp {$temp}",
                'SIGNAL_GROUP' => $mode,
                'SIGNAL_DATA' => $temp
            ])->toArray();

            $appliance->natureRemoSignals()->createMany($temps);

            // register available air volume range for the current operation mode
            $vols = collect($modeSetting['vol'])->map(fn ($vol) => [
                'SIGNAL_UUID' => null,
                'SIGNAL_NAME' => 'air_volume',
                'SIGNAL_LABEL' => $vol === '' ? 'air volume auto' : "air volume {$vol}",
                'SIGNAL_GROUP' => $mode,
                'SIGNAL_DATA' => $vol
            ])->toArray();

            $appliance->natureRemoSignals()->createMany($vols);

            // register avaiable air direction range for the current operation mode
            $dirs = collect($modeSetting['dir'])->map(fn ($dir) => [
                'SIGNAL_UUID' => null,
                'SIGNAL_NAME' => 'air_direction',
                'SIGNAL_LABEL' => $dir === '' ? 'air direction auto' : "air direction {$dir}",
                'SIGNAL_GROUP' => $mode,
                'SIGNAL_DATA' => $dir
            ])->toArray();

            $appliance->natureRemoSignals()->createMany($dirs);
        }

        return response()->noContent();
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> sendInfraredSignal <br>
     * <Function> Request to send a signal of an IR appliance <br>
     *
     * @access private
     * @param  string  $token
     * @param  string  $signalUuid
     * @return \Illuminate\Http\Client\Response
     */
    private function sendInfraredSignal(string $token, string $signalUuid)
    {
        return Http::withToken($token)
            ->acceptJson()
            ->post("{$this->hostname}/1/signals/$signalUuid/send");
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> sendTelevisionSignal <br>
     * <Function> Request to send a signal of a TV appliance <br>
     *
     * @access private
     * @param  string  $token
     * @param  string  $applianceUuid
     * @param  string  $signalName
     * @return \Illuminate\Http\Client\Response
     */
    private function sendTelevisionSignal(string $token, string $applianceUuid, string $signalName)
    {
        return Http::withToken($token)
            ->acceptJson()
            ->asForm()
            ->post("{$this->hostname}/1/appliances/$applianceUuid/tv", [
                'button' => $signalName
            ]);
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> sendLightSignal <br>
     * <Function> Request to send a signal of a LIGHT appliance <br>
     *
     * @access private
     * @param  string  $token
     * @param  string  $applianceUuid
     * @param  string  $signalName
     * @return \Illuminate\Http\Client\Response
     */
    private function sendLightSignal(string $token, string $applianceUuid, string $signalName)
    {
        return Http::withToken($token)
            ->acceptJson()
            ->asForm()
            ->post("{$this->hostname}/1/appliances/$applianceUuid/light", [
                'button' => $signalName
            ]);
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> sendAirConditionerSignal <br>
     * <Function> Request to send a signal of an AC appliance <br>
     *
     * @access private
     * @param  string  $token
     * @param  string  $applianceUuid
     * @param  string  $signalName
     * @param  string  $signalData
     * @return \Illuminate\Http\Client\Response
     */
    private function sendAirConditionerSignal(string $token, string $applianceUuid, string $signalName, string $signalData)
    {
        return Http::withToken($token)
            ->acceptJson()
            ->asForm()
            ->post("{$this->hostname}/1/appliances/$applianceUuid/aircon_settings", [
                $signalName => $signalData
            ]);
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> processCommand <br>
     * <Function> Prepare and process the requested signal to be sent <br>
     *
     * @param  \App\NatureRemoSignal  $signal
     */
    public function processCommand(NatureRemoSignal $signal)
    {
        try {
            // lazy load relationship to retrieve correct access token
            $signal->load('natureRemoAppliance.natureRemoDevice.natureRemoAccount');
            $roomID = $signal->natureRemoAppliance->natureRemoDevice->ROOM_ID;
            $token = $signal->natureRemoAppliance->natureRemoDevice->natureRemoAccount->ACCESS_TOKEN;

            switch ($signal->natureRemoAppliance->APPLIANCE_TYPE) {
                case 'IR':
                    $response = $this->sendInfraredSignal(
                        $token,
                        $signal->SIGNAL_UUID
                    );
                    break;

                case 'TV':
                    $response = $this->sendTelevisionSignal(
                        $token,
                        $signal->natureRemoAppliance->APPLIANCE_UUID,
                        $signal->SIGNAL_NAME
                    );
                    break;

                case 'LIGHT':
                    $response = $this->sendLightSignal(
                        $token,
                        $signal->natureRemoAppliance->APPLIANCE_UUID,
                        $signal->SIGNAL_NAME
                    );
                    break;

                case 'AC':
                    $response = $this->sendAirConditionerSignal(
                        $token,
                        $signal->natureRemoAppliance->APPLIANCE_UUID,
                        $signal->SIGNAL_NAME,
                        $signal->SIGNAL_DATA
                    );
                    // Notification for Nature Remo Operation
                    $notif = app()->make('App\Http\Controllers\NotificationController');
                    $notif->natureRemoDeviceNotification($signal->SIGNAL_LABEL, $roomID);
                    break;

                default:
                    throw new \Exception("Error Processing Request: Invalid appliance type");
                    break;
            }

            if ($response->successful()) {
                /**
                 * If the signal is sent successfully,
                 * update the state of the appliance
                 */
                $signal->natureRemoAppliance->update([
                    'APPLIANCE_SETTINGS' => $response->json()
                ]);

                /**
                 * broadcast the appliance that has been updated
                 */
                broadcast(new NatureRemoApplianceStateUpdated($signal->natureRemoAppliance));
            }

            return $response->json();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
