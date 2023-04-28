<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//This is all the function from MC
//Note: No user information provided.

//MC to OPS Functions
Route::post('newDevice',        'DeviceController@createDevice');
Route::post('delete/Device/MC', 'DeviceController@deleteDeviceFromMC');
Route::post('offlineDevice',    'DeviceController@offlineDevice');
Route::post('newData',          'ProcessedDataController@createProcessedData');

//Other Functions of MC to OPS
Route::post('onlineGateway',    'ProcessedDataController@createMcFunctionNotif');
Route::post('offlineGateway',   'ProcessedDataController@createMcFunctionNotif');
Route::post('zigbeeSetup',      'ProcessedDataController@createMcFunctionNotif');
Route::post('setupDevice',      'ProcessedDataController@createMcFunctionNotif');
Route::post('alarmDevice',      'ProcessedDataController@createMcFunctionNotif');
Route::post('logMessage',       'ProcessedDataController@createMcFunctionNotif');

//Notification from other OPS
Route::post('createBroadcastNotif', 'ProcessedDataController@createBroadcastNotif');

//This is all the function from OPS to MC
Route::post('sendInstruction',      'InstructionController@sendInstruction');
Route::post('enableJoinMC',         'GatewayController@enableJoinMC');

//User for adding Floor
Route::post('createFloor',          'FloorController@createFloor');

// Notification Data
Route::post('createNotification',   'NotificationController@createNotification');
Route::get('getNotification/{id}',  'NotificationController@getNotification');
Route::post('updateNotification',   'NotificationController@updateNotification');

//Delete this on future only for testing
Route::post('deleteGatewayForce',   'GatewayController@deleteGatewayForce');
Route::post('deleteGateway',        'GatewayController@deleteGateway');
Route::post('deleteGatewayManual',  'GatewayController@deleteGatewayManual');
Route::post('getUniqueDevices',     'DashboardController@getUniqueDevices');

// Device
Route::get('scanDeviceAll',                 'DeviceController@scanDeviceAll');
Route::get('getDeviceAll',                  'DeviceController@getDeviceAll');
Route::get('getDevice/{id}',                'DeviceController@getDevice');
Route::get('getUnregisteredDevices',        'DeviceController@getUnregisteredDevices');
Route::get('getRegisteredDevices',          'DeviceController@getRegisteredDevices');
Route::get('getBlockedDevices',             'DeviceController@getBlockedDevices');
Route::get('getDeletedDevices',             'DeviceController@getDeletedDevices');
Route::get('getDeviceFloor/{id}',           'DeviceController@getDeviceFloor');
Route::get('getDeviceRoom/{id}',            'DeviceController@getDeviceRoom');
Route::get('getDeviceGateway/{id}',         'DeviceController@getDeviceGateway');
Route::get('getDeviceProcessedData/{id}',   'DeviceController@getDeviceProcessedData');
Route::get('getDeviceBindings/{id}',        'DeviceController@getDeviceBindings');
Route::post('registerDevice',               'DeviceController@registerDevice');
Route::post('updateDevice',                 'DeviceController@updateDevice');
Route::post('deleteDevice',                 'DeviceController@deleteDevice');
Route::post('blockDevice',                  'DeviceController@blockDevice');
Route::post('unblockDevice',                'DeviceController@unblockDevice');
Route::get('getDevicesWithBindings',        'DeviceController@getDevicesWithBindings');
Route::get('getDeviceBindingList/{id}',     'DeviceController@getDeviceBindingList');
Route::get(
    'getDeviceBindingListCondition/{id}',
    'DeviceController@getDeviceBindingListCondition'
);
Route::get(
    'getDeviceBindingListDevices/{id}/{condition}/{devicetypeid}',
    'DeviceController@getDeviceBindingListDevices'
);
Route::get(
    'getMultiDeviceBindingListDevices/{id}/{devicetype}/{condition}/{devicetypeid}',
    'DeviceController@getMultiDeviceBindingListDevices'
);

Route::get('checkNextActivity',     'BindingController@checkNextActivity');

Route::post('storeCameraLogs',          'CameraController@storeCameraLogs');

// Camera
Route::get('getAcsCameraList',          'CameraController@getAcsCameraList');
Route::get('getAcsServerConfiguration', 'CameraController@getAcsServerConfiguration');
Route::post('scanAllCameras',           'CameraController@scanAllCameras');

// Camera Gateway
Route::get('getAcsSystem',              'GatewayController@getAcsSystem');

// Camera
Route::get('vmdAlert',                  'CameraController@vmdAlert');
Route::post('darkFeedAlert',             'CameraController@darkFeedAlert');

// Archibus
Route::get('sendPeopleCounterDataToArchibus', 'CameraController@sendPeopleCounterDataToArchibus');


Route::get('getRLAToken',     'RemoteLockController@getRLAToken');
Route::get('sendEmailx',     'RemoteLockController@sendEmailx');

Route::get('triggerCameraBinding', 'BindingController@triggerCameraBinding');

Route::get('checkNextActivity', 'BindingController@checkNextActivity');

Route::get('getAllCleaningLogs',      'CleaningController@getAllCleaningLogs');
Route::get('getRoomInformation',          'CleaningController@getRoomInformation');
Route::get('getCleaningTask',          'CleaningController@getCleaningTask');
Route::get('updateCleaningLog',          'CleaningController@updateCleaningLog');

Route::get('getAllManufacturerDevices',          'ManufacturerController@getAllManufacturerDevices');

Route::get('getProcessedDataDevice',          'ProcessedDataController@getProcessedDataDevice');

Route::post('sendClientMail', 'MailController@sendClientMail');
// + [Sprint04] [Task142]
Route::post('accessDeniedNotification', 'RemoteLockController@accessDeniedNotification');
// + [Sprint04] [Task142]

Route::post('opsToCloud', 'ProcessedDataController@newTXData');

Route::post('dataTesting/logsNotifs',   'GenerateTestingDataController@createLogsNotifsData');
Route::post('dataTesting/processData',  'GenerateTestingDataController@createProcessData');
Route::post('dataTesting/userData',     'GenerateTestingDataController@createUserData');
Route::post('dataTesting/guestData',     'GenerateTestingDataController@createGuestData');

Route::post('axis/people_counts', 'Api\PeopleCounterController@store');
Route::get('axis/people_counts/{device}', 'Api\PeopleCounterController@show');
