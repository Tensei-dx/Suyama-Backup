<?php

/**
 * <Update> 2020.05.18 TP Uddin     Modify URLs and Method names with respect to URL一覧.xlsx
 * <Update> 2021.08.19 TDN Okada    SPRINT_03 [TASK009]
 * <Update> 2021.08.25 TP Ivin      SPRINT_04 [Task125]
 * <Update> 2021.09.02 TP Jermaine  SPRINT_05 [Task114]
 * <Update> 2021.09.03 TDN Okada    SPIRNT_05 [Task131]
 * <Update> 2021.09.10 TP Jermaine  SPIRNT_05 [Task140]
 */


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Task Scheduler for GAE
 * This URI is excempted for authentication.
 */
Route::get('/scheduler', 'Scheduler')->middleware(['appengine.cron']);


Route::get('thankyou',            'PagesController@logout');

Route::get('/log/{message}', function ($message) {
    info("Hello my log, message: $message");
    return response()->noContent();
});

Route::get('/exception/{message}', function ($message) {
    throw new Exception("Intentional exception, message: $message");
});

// Change locale
Route::get('changeLocale/{locale}', 'SessionController@changeLocale');

// Application info
Route::get('/getAppVersion',    'ApplicationController@getAppVersion');

Route::middleware(['locale', 'authenticated'])->group(function () {

    Auth::routes();

    // Login
    Route::post('validateLogin',    'Auth\LoginController@validateLogin');

    // Pages
    Route::get('',                  'PagesController@dashboard');
    Route::get('device-monitoring', 'PagesController@monitoring');
    Route::get('floor-view',        'PagesController@displayview');
    Route::get('user-management',   'PagesController@users')->middleware('module');
    Route::get('gateway-management', 'PagesController@gateway')->middleware('module');
    Route::get('device-management', 'PagesController@device')->middleware('module');
    Route::get('binding-management', 'PagesController@binding')->middleware('module');
    Route::get('logs',              'PagesController@logs')->middleware('module');
    Route::get('notifications',     'PagesController@notifications');
    Route::get('floor-management',  'PagesController@floor')->middleware('module');
    Route::get('about',             'PagesController@about');
    Route::get('help',              'PagesController@help');
    Route::get('management',        'PagesController@management')->middleware('authenticatedAdmin'); //[TASK114]
    Route::get('guest',             'PagesController@guest');
    Route::get('welcome',           'PagesController@welcome');
    Route::get('terms',             'PagesController@showTerms');

    Route::get('roomselect',        'PagesController@roomselect');
    Route::get('remotelock',        'PagesController@remotelock');
    Route::get('remotelockGuest',   'PagesController@remotelockGuest');
    Route::get('remotelock/updateGuest/{id}',   'PagesController@showUpdateGuestAccount'); //+ SPRINT_05 [Task131]
    Route::get('message',           'PagesController@message');
    Route::get('cleaner',           'PagesController@cleaner');
    Route::get('restaurant',        'PagesController@restaurant');
    Route::get('restaurant-guest',  'PagesController@restaurant_guest');
    Route::get('guestinfo/{user}',         'PagesController@guestinfo');

    // Dashboard
    Route::post('getGateway',       'DashboardController@getGateway');
    Route::post('getGatewayStatus', 'DashboardController@getGatewayStatus');
    Route::post('getDeviceStatus',  'DashboardController@getDeviceStatus');
    Route::post('getDevice',        'DashboardController@getDevice');
    Route::post('getStatus',        'DashboardController@getStatus');
    Route::post('getModules',       'DashboardController@getModules');
    Route::get('getUniqueDevices',  'DashboardController@getUniqueDevices');
    Route::post('getProcessedData', 'DashboardController@getProcessedData');

    //Management
    Route::get('getAllRooms',               'ManagementController@getAllRooms');
    Route::get('getDeviceType',             'ManagementController@getDeviceType');
    Route::get('getPeopleCounterCameras',   'ManagementController@getPeopleCounterCameras');
    Route::post('peopleCounterData',        'ManagementController@peopleCounterData');
    Route::get('getDevice',                 'ManagementController@getDevice');
    Route::post('updateRoomStatus',         'ManagementController@updateRoomStatus');
    // Route::post('updateTemp',               'ManagementController@updateTemp');
    Route::get('getDevice',                 'ManagementController@getDevice');

    // Client/Guest
    Route::get('client/gateway',                'ClientController@getRoomGateway');
    Route::get('client/devices/{id}',           'ClientController@getRoomDevices');
    Route::get('client/appliances/{id}',        'ClientController@getRoomAppliances');
    Route::get('client/data',                   'ClientController@getDeviceData');
    Route::post('client/instruct',              'ClientController@sendInstruction');
    Route::get('client/device/{id}',            'ClientController@getDeviceData');
    Route::get('client/room/{id}',              'ClientController@getRoom');
    Route::post('client/room/message/update',   'ClientController@updateRoomMessage');
    Route::get('client/notifications',     'ClientController@getUserNotifications');
    Route::post('client/data/latest',           'ClientController@getLatestData');
    Route::post('client/updateNotification',   'ClientController@updateNotification');
    Route::post('client/room/status/update',   'ClientController@updateRoomStatus');
    Route::post('client/createCheckInNotification', 'ClientController@createCheckInNotification');
    Route::post('client/createCheckOutNotification', 'ClientController@createCheckOutNotification');
    // + Sprint04 Task125
    Route::post('client/lateCheckoutNotifications', 'ClientController@lateCheckoutNotifications');
    // + Sprint04 Task125
    Route::post('client/errorCheckoutNotifications', 'ClientController@errorCheckoutNotifications');

    // 【Task015】
    // Cleaning
    // Route::get('getAllCleaningLogs',            'CleaningController@getAllCleaningLogs');
    // Route::get('getRoomInformation',            'CleaningController@getRoomInformation');
    // Route::get('getCleaningTask',               'CleaningController@getCleaningTask');
    // Route::post('updateCleaningLog',             'CleaningController@updateCleaningLog');
    // Route::post('updateCleaningStatus',          'CleaningController@updateCleaningStatus');
    // 【Task015】

    // Remote Lock
    Route::get('getApiInfo',                        'RemoteLockController@getApiInfo');
    // Route::post('remoteLockApi',                 'RemoteLockController@remoteLockApi');
    Route::post('createRemoteLockGuestAccount',     'RemoteLockController@createRemoteLockGuestAccount');
    Route::post('createRemoteLockUserAccount',      'RemoteLockController@createRemoteLockUserAccount');
    // Route::post('deleteRemoteLockGuestAccount',  'RemoteLockController@deleteRemoteLockGuestAccount');
    Route::post('sendRemoteLockAlertEmail',         'RemoteLockController@sendRemoteLockAlertEmail');
    Route::post('unlockRemoteLockState',            'RemoteLockController@unlockRemoteLockState');
    Route::get('getRemoteLockPinSettings',          'RemoteLockController@getRemoteLockPinSettings');
    Route::get('getAccountInfo',                    'RemoteLockController@getAccountInfo');
    // Route::post('updateUserAccount',                'RemoteLockController@updateUserAccount');       [Task 001] Remove
    // Route::post('updateGuestAccount',               'RemoteLockController@updateGuestAccount');      [Task 001] Remove
    // Route::post('deleteAccount',                    'RemoteLockController@deleteAccount');
    // Route::post('duplicationPinCheck',              'RemoteLockController@duplicationPinCheck');
    // Route::post('createBookAccount',                'RemoteLockController@createBookAccount');
    Route::post('sendBookCancellationEmail',        'RemoteLockController@sendBookCancellationEmail');
    Route::get('getBookDetails',                    'RemoteLockController@getBookDetails');
    // Route::get('getAvailableRooms',                 'RemoteLockController@getAvailableRooms');
    Route::get('getRemoteLockDevicesRoom',          'RemoteLockController@getRemoteLockDevicesRoom');

    // User
    Route::prefix('users')->group(function () {
        Route::get('/',             'UserController@index');
        Route::post('/',            'UserController@store');
        Route::put('/{user}',       'UserController@update');
        Route::delete('/{user}',    'UserController@destroy');
    });
    Route::post('getUsers',             'UserController@getUsers');
    Route::post('getUser',              'UserController@getUser');
    Route::post('getInactiveUsers',     'UserController@getInactiveUsers');
    Route::post('getActiveUsers',       'UserController@getActiveUsers');
    Route::post('createUser',           'UserController@createUser');
    Route::post('disableUser',          'UserController@disableUser');
    Route::post('enableUser',           'UserController@enableUser');
    Route::post('changePasswordUser',   'UserController@changePasswordUser');
    Route::post('updateUserProfile',    'UserController@updateUserProfile');
    Route::post('updateUserDesignation', 'UserController@updateUserDesignation');

    // Manufacturer
    Route::get('getManufacturerAll',    'ManufacturerController@getManufacturerAll');

    // Floor
    Route::post('getFloorAll',           'FloorController@getFloorAll');
    Route::post('getFloor/{id}',         'FloorController@getFloor');
    Route::post('getFloorRooms/{id}',   'FloorController@getFloorRooms');
    Route::get('getFloorGateways/{id}', 'FloorController@getFloorGateways');
    Route::get('getFloorDevices/{id}',  'FloorController@getFloorDevices');
    Route::post('createFloor',          'FloorController@createFloor');
    Route::post('updateFloor',          'FloorController@updateFloor');
    Route::post('deleteFloor',          'FloorController@deleteFloor');
    Route::post('getAuthFloor',         'FloorController@getAuthFloor');
    Route::get(
        'getFloorRoomRegisteredGateways/{id}',
        'FloorController@getFloorRoomRegisteredGateways'
    );

    // Module
    Route::post('getModuleAll',         'ModuleController@getModuleAll');

    // Room
    Route::get('rooms',             'RoomController@index');
    Route::put('rooms',             'RoomController@massUpdate');
    Route::get('rooms/{room}',      'RoomController@show');
    Route::put('rooms/{room}',      'RoomController@update');

    Route::get('getRoomAll',            'RoomController@getRoomAll');
    Route::get('getRoom/{id}',          'RoomController@getRoom');
    Route::get('getRoomFloor/{id}',      'RoomController@getRoomFloor');
    Route::get('getRoomGateways/{id}',  'RoomController@getRoomGateways');
    Route::get('getRoomDevices/{id}',   'RoomController@getRoomDevices');
    Route::post('createRoom',           'RoomController@createRoom');
    Route::post('updateRoom',           'RoomController@updateRoom');
    Route::post('deleteRoom',           'RoomController@deleteRoom');
    Route::get('getRoomIrWithAppliances/{id}', 'RoomController@getRoomIrWithAppliances');
    // + [TASK 127]
    Route::get('getHotelRoom',            'RoomController@getCo2DevicePerRoom');
    Route::get('getRoomNetvoxDevicesHotel/{id}', 'RoomController@getRoomNetvoxDevicesHotel');
    Route::post('updateRoomStatusForGuest', 'RoomController@updateRoomStatus');
    // + [TASK 127]

    // Gateway
    Route::get('scanGatewayAll',            'GatewayController@scanGatewayAll');
    Route::get('getGatewayAll',             'GatewayController@getGatewayAll');
    Route::get('getGateway/{id}',           'GatewayController@getGateway');
    Route::post('deleteGatewayForce',       'GatewayController@deleteGatewayForce');
    Route::get('getUnregisteredGateways',   'GatewayController@getUnregisteredGateways');
    Route::get('getRegisteredGateways',     'GatewayController@getRegisteredGateways');
    Route::get('getBlockedGateways',        'GatewayController@getBlockedGateways');
    Route::get('getDeletedGateways',        'GatewayController@getDeletedGateways');
    Route::get('getGatewayDevices/{id}',    'GatewayController@getGatewayDevices');
    Route::get('getGatewayFloor/{id}',      'GatewayController@getGatewayFloor');
    Route::get('getGatewayRoom/{id}',       'GatewayController@getGatewayRoom');
    Route::post('registerGateway',          'GatewayController@registerGateway');
    Route::post('updateGateway',            'GatewayController@updateGateway');
    Route::post('deleteGateway',            'GatewayController@deleteGateway');
    Route::post('deleteGatewayManual',      'GatewayController@deleteGatewayManual');
    Route::post('undeleteGateway',          'GatewayController@undeleteGateway');
    Route::post('blockGateway',             'GatewayController@blockGateway');
    Route::post('unblockGateway',           'GatewayController@unblockGateway');
    Route::post('onlineGateway',            'GatewayController@onlineGateway');
    Route::post('createGatewayModbus',      'GatewayController@createGatewayModbus');
    Route::post('enableJoinMC',             'GatewayController@enableJoinMC');
    Route::get('getAcsSystem',              'GatewayController@getAcsSystem');
    Route::post('registerCameraGateway',    'GatewayController@registerCameraGateway');
    Route::get('checkCameraGatewayInfo',    'GatewayController@checkCameraGatewayInfo');

    // Device
    Route::get('scanDeviceAll',                 'DeviceController@scanDeviceAll');
    Route::get('getDeviceAll',                  'DeviceController@getDeviceAll');
    Route::post('getDevice/{id}',               'DeviceController@getDevice');
    Route::get('getUnregisteredDevices',        'DeviceController@getUnregisteredDevices');
    Route::get('getRegisteredDevices',          'DeviceController@getRegisteredDevices');
    Route::get('getBlockedDevices',             'DeviceController@getBlockedDevices');
    Route::get('getDeletedDevices',             'DeviceController@getDeletedDevices');
    Route::get('getDeviceFloor/{id}',           'DeviceController@getDeviceFloor');
    Route::get('getDeviceRoom/{id}',            'DeviceController@getDeviceRoom');
    Route::get('getDeviceGateway/{id}',         'DeviceController@getDeviceGateway');
    Route::get('getDeviceProcessedData/{id}',   'DeviceController@getDeviceProcessedData');
    Route::get('getDeviceBindings/{id}',        'DeviceController@getDeviceBindings');
    Route::get('registerDevice',               'DeviceController@registerDevice');
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
    Route::post('createDevice',                 'DeviceController@createDevice');
    Route::post('deleteDeviceFromMC',           'DeviceController@deleteDeviceFromMC');
    Route::post('offlineDevice',                'DeviceController@offlineDevice');
    Route::get('getDevicesCustomQuery',         'DeviceController@getDevicesCustomQuery');

    // Bacnet Devices
    Route::get('getUnregisteredBacnetDevices',  'BacnetDevicesController@getUnregisteredBacnetDevices');
    Route::get('getRegisteredBacnetDevices',    'BacnetDevicesController@getRegisteredBacnetDevices');
    Route::get('getBacnetDeviceFloor/{id}',     'BacnetDevicesController@getBacnetDeviceFloor');
    Route::get('getBacnetDeviceRoom/{id}',      'BacnetDevicesController@getBacnetDeviceRoom');
    Route::get('getBacnetDeviceGateway/{id}',   'BacnetDevicesController@getBacnetDeviceGateway');
    Route::get('getDeviceList',                'BacnetDevicesController@getDeviceList');
    Route::get('getObjectList/{name}',          'BacnetDevicesController@getObjectList');
    Route::get('getDeviceObjects/{id}',         'BacnetDevicesController@getDeviceObjects');
    Route::post('validateBacnetObjects',        'BacnetDevicesController@validateBacnetObjects');
    Route::post('registerBacnetDevice',         'BacnetDevicesController@registerBacnetDevice');
    Route::post('unregisterBacnetDevice',       'BacnetDevicesController@unregisterBacnetDevice');
    Route::post('updateBacnetDevice',           'BacnetDevicesController@updateBacnetDevice');
    Route::post('deleteBacnetDevice',           'BacnetDevicesController@deleteBacnetDevice');
    Route::post('scanBacnetDevices',            'BacnetDevicesController@scanBacnetDevices');
    Route::post('getBacnetData',                'BacnetDevicesController@getBacnetData');

    // Binding
    Route::get('getBindingAll',         'BindingController@getBindingAll');
    Route::get('getBinding/{id}',       'BindingController@getBinding');
    Route::get('getCameraBindingDevices', 'BindingController@getCameraBindingDevices');
    Route::post('createBinding',        'BindingController@createBinding');
    Route::post('deleteBinding',        'BindingController@deleteBinding');
    Route::post('deleteSensorBinding',  'BindingController@deleteSensorBinding');
    Route::post('enableBinding',        'BindingController@enableBinding');
    Route::post('disableBinding',       'BindingController@disableBinding');
    Route::post('checkBindings',        'BindingController@checkBindings');
    Route::post('enableAllBinding',     'BindingController@enableAllBinding');
    Route::post('disableAllBinding',    'BindingController@disableAllBinding');
    Route::post('checkBindingCondition', 'BindingController@checkBindingCondition');
    Route::post('createCameraBinding',  'BindingController@createCameraBinding');
    Route::post('deleteCameraBinding',  'BindingController@deleteCameraBinding');
    Route::post('modifyCameraBindingStatus',  'BindingController@modifyCameraBindingStatus');

    // Binding List
    Route::get('getBindingListAll',                 'BindingListController@getBindingListAll');
    Route::get('getBindingList/{id}',               'BindingListController@getBindingList');
    Route::get('getBindingListBindings/{id}',       'BindingListController@getBindingListBindings');
    Route::get('getBindingListSourceDevices/{id}',  'BindingListController@getBindingListSourceDevices');
    Route::get('getBindingListTargetDevices/{id}',  'BindingListController@getBindingListTargetDevices');
    Route::get('getCamerasWithBindings',            'BindingController@getCamerasWithBindings');

    // Binding Alert
    Route::get('getAlertBinding',           'BindingAlertController@getAlertBinding');
    Route::post('createAlertBinding',       'BindingAlertController@createAlertBinding');
    Route::post('enableAllAlertBinding',       'BindingAlertController@enableAllAlertBinding');
    Route::post('disableAllAlertBinding',       'BindingAlertController@disableAllAlertBinding');
    Route::post('deleteAllAlertBinding',       'BindingAlertController@deleteAllAlertBinding');
    Route::post('deleteAlertBinding',       'BindingAlertController@deleteAlertBinding');

    // Instruction
    Route::post('sendInstruction',      'InstructionController@sendInstruction')
        ->middleware('high');
    Route::post('sendAlertSMS',      'InstructionController@sendAlertSMS');
    Route::post('sendAlertEmail',       'InstructionController@sendAlertEmail');

    // Notification Data
    Route::post('createNotification',   'NotificationController@createNotification');
    Route::get('getNotification/{id}',  'NotificationController@getNotification');
    Route::post('updateNotification',   'NotificationController@updateNotification');
    Route::post('notifications/room-emergency', 'NotificationController@broadcastRoomEmergency');

    // Logs Notification Data
    Route::prefix('logs-notification')
        ->group(function () {
            Route::get('/get/all',                     'LogsNotificationController@getAllLogsNotification');
            Route::get('/get/notification',            'LogsNotificationController@getLogsNotification');
            Route::post('/create/response',            'LogsNotificationController@createResponse');
            Route::post('/update/event-status',        'LogsNotificationController@updateLogsNotification');
        });

    // Appliance List
    Route::get('getAllAppliances',      'ApplianceController@getAllAppliances');
    Route::post('createAppliance',      'ApplianceController@createAppliance');
    Route::post('deleteAppliance',      'ApplianceController@deleteAppliance');

    // Logs
    Route::get('getAllLog',                 'LogController@getAllLog');
    Route::post('getSystemLogs',            'LogController@getSystemLogs');
    Route::post('getAuditLogs',             'LogController@getAuditLogs');
    Route::post('createSystemLogs',         'LogController@createSystemLogs');
    Route::post('createAuditLogs',          'LogController@createAuditLogs');
    Route::post('getUserLogs',              'LogController@getUserLogs');
    Route::post('downloadLog',              'LogController@downloadLog');
    Route::post('generateReport',           'LogController@generateReport');
    Route::post('generateSystemLogsFile',   'LogController@generateSystemLogsFile');
    Route::post('generateAuditLogsFile',    'LogController@generateAuditLogsFile');
    Route::post('deleteOldLogs',            'LogController@deleteOldLogs');
    Route::post('getAppLogs',            'LogController@getAppLogs');

    // Session
    Route::get('getSession',            'SessionController@getSession');
    Route::get('getUserID',             'SessionController@getUserId');
    Route::get('checkSession/{id}',    'SessionController@checkSession');

    // ProcessedData
    Route::post('createProcessedData',  'ProcessedDataController@createProcessedData');
    Route::post('createMcFunctionNotif', 'ProcessedDataController@createMcFunctionNotif');
    Route::post('createBroadcastNotif', 'ProcessedDataController@createBroadcastNotif');
    Route::post('createAlertInstruction', 'ProcessedDataController@createAlertInstruction');
    Route::post('checkAlert', 'ProcessedDataController@checkAlert');
    Route::get('getNetvoxProcessedDataHotel/{id}', 'ProcessedDataController@getNetvoxProcessedDataHotel');
    Route::get('getStartAndEndTimeHotel', 'ProcessedDataController@getStartAndEndTimeHotel');

    // Camera
    Route::post('scanAllCameras',       'CameraController@scanAllCameras');
    Route::post('registerCamera',       'CameraController@registerCamera');
    Route::post('updateCamera',         'CameraController@updateCamera');
    Route::post('deleteCamera',         'CameraController@deleteCamera');
    Route::get('getUnregisteredCameras', 'CameraController@getUnregisteredCameras');
    Route::get('getRegisteredCameras',  'CameraController@getRegisteredCameras');
    Route::post('storeCameraLogs',      'CameraController@storeCameraLogs');
    Route::post('postPcData',           'CameraController@postPcData');

    // 2021.05.05 TP Uddin -- for testing Nature Remo API
    Route::get('getNatureRemoAppliances', 'DeviceController@getNatureRemoAppliances');
    Route::get('getNatureRemoApplianceSignals', 'DeviceController@getNatureRemoApplianceSignals');
    Route::get('sendNatureRemoSignal', 'DeviceController@sendNatureRemoSignal');

    Route::post('scanNatureRemoDevices', 'NatureRemoController@scanNatureRemoDevices');
    Route::post('registerNatureRemoDevice', 'NatureRemoController@registerNatureRemoDevice');


    //Restaurant API
    Route::get('getRestaurantRoom', 'RestaurantController@getRestaurantRoom');
    Route::get('getRoomNetvoxDevices/{id}', 'RestaurantController@getRoomNetvoxDevices');
    Route::get('getNetvoxDevice/{id}', 'RestaurantController@getNetvoxDevice');
    Route::get('getNetvoxNotification', 'RestaurantController@getNetvoxNotification');
    Route::get('getNetvoxProcessedData/{id}', 'RestaurantController@getNetvoxProcessedData');
    Route::get('getStartAndEndTime', 'RestaurantController@getStartAndEndTime');

    Route::get('getNotificationTest/{id}', 'NotificationController@getNotificationTest');
    Route::get('getRestaurantRoomDevices', 'RestaurantController@getRestaurantRoomDevices');
    Route::get('getDevicesByDeviceTypeAndRoomType', 'RestaurantController@getDevicesByDeviceTypeAndRoomType');
    Route::get('getFloorDevicesByDeviceTypeAndRoomTypes', 'RestaurantController@getFloorDevicesByDeviceTypeAndRoomTypes');

    //Delete this after
    Route::get('getNotif/{id}', 'ProcessedDataController@getNotif');


    /*[TASK 001]
        Guest List Screen Change status
    */
    //BookingController
    Route::get('getBookingAccountInfo', 'BookingController@getBookingAccountInfo');
    Route::post('updateGuestAccount',   'BookingController@updateGuestAccount');
    Route::post('duplicationPinCheck',  'BookingController@duplicationPinCheck');
    Route::post('deleteGuestAccount',   'BookingController@deleteGuestAccount');
    Route::post('deleteAdminAccount',   'BookingController@deleteAdminAccount');
    Route::post('updateUserAccount',    'BookingController@updateUserAccount');
    Route::post('deleteUserAccount',    'BookingController@deleteUserAccount');
    Route::get('getAvailableRooms',     'BookingController@getAvailableRooms');
    Route::post('createBookAccount',    'BookingController@createBookAccount');
    Route::get('remotelock/updateGuest/{userid}',  'BookingController@getUpdateInfo'); // + SPRINT_05 [Task131]
    // + Sprint06 Task140
    Route::get('getCurrentLoginUserDetails',    'BookingController@getCurrentLoginUserDetails');
    // + Sprint06 Task140
    Route::get('getCurrentCheckInDetails',    'BookingController@getCurrentCheckInDetails');
    Route::get('getBookRoomDetails',    'BookingController@getBookRoomDetails');

    //[Task 001] 20210723
    Route::get('apiTester', 'RemoteLockController@apiTester');

    // 2021.06.04 TP Uddin -- for testing
    Route::get('room/{id}/devices',         'ClientController@getRoomDevices');
    Route::get('room/{id}/appliances',      'ClientController@getRoomAppliances');

    Route::post('scanNatureRemoDevices',            'NatureRemoDeviceController@scanNatureRemoDevices');
    Route::post('registerNatureRemoDevice',         'NatureRemoDeviceController@registerNatureRemoDevice');
    Route::get('getNatureRemoDevices',              'NatureRemoDeviceController@getNatureRemoDevices');
    Route::post('updateNatureRemoDeviceAppliances', 'NatureRemoDeviceController@updateNatureRemoDeviceAppliances');
    Route::get('getNatureRemoDevice',              'NatureRemoDeviceController@getNatureRemoDevice');

    // 2021.06.09 TP Uddin
    Route::get('getNatureRemoAppliances',       'NatureRemoApplianceController@getNatureRemoAppliances');
    Route::post('createNatureRemoAppliance',    'NatureRemoApplianceController@createNatureRemoAppliance');
    Route::post('deleteNatureRemoAppliance',    'NatureRemoApplianceController@deleteNatureRemoAppliance');
    Route::get('getNatureRemoApplianceDevices', 'NatureRemoApplianceController@getNatureRemoApplianceDevices');

    // 2021.06.09 TP Uddin
    Route::get('getNatureRemoSignals',          'NatureRemoSignalController@getNatureRemoSignals');
    Route::get('fetchNatureRemoSignalData',     'NatureRemoSignalController@fetchNatureRemoSignalData');
    Route::post('testNatureRemoSignalData',     'NatureRemoSignalController@testNatureRemoSignalData');
    Route::post('createNatureRemoSignal',       'NatureRemoSignalController@createNatureRemoSignal');
    Route::post('deleteNatureRemoSignal',       'NatureRemoSignalController@deleteNatureRemoSignal');
    Route::post('sendNatureRemoSignal',         'NatureRemoSignalController@sendNatureRemoSignal');

    // + [TASK 009]
    // Staysee API check-out
    Route::get('patchStayseeCheckOut/{bookid}', 'Staysee\StayseeReservationController@patchStayseeCheckOut');
    Route::patch('patchStayseeCheckOut/{bookid}', 'Staysee\StayseeReservationController@patchStayseeCheckOut');
    // + [TASK 009]

    // GuestCard
    Route::get('guestcard',         'GuestCardController@showGuestcard');
    Route::get('guestcard/{id}',    'GuestCardController@showReservationDetails');
    // Route::post('addGuestDetails',  'GuestCardController@addGuestDetails');
    // Route::post('imageStore',       'GuestCardController@imageStore');
    // Route::post('csvStore',         'GuestCardController@csvStore');
    // Route::post('urlStore',         'GuestCardController@urlStore');
    // Route::post('putCsv',           'GuestCardController@putCsv');

    Route::post('syncReservations', 'Staysee\StayseeReservationController@sync');

    // Remote Lock Device
    Route::prefix('remotelock_devices')
        ->group(function () {
            Route::get('/',                     'RemoteLock\RemoteLockDeviceController@index');
            Route::post('/scan',                'RemoteLock\RemoteLockDeviceController@scan');
            Route::put('/{id}',                 'RemoteLock\RemoteLockDeviceController@update');
            Route::delete('/{id}',              'RemoteLock\RemoteLockDeviceController@destroy');
            Route::put('/{id}/register',        'RemoteLock\RemoteLockDeviceController@register');
            Route::post('/{id}/unlock',         'RemoteLock\RemoteLockDeviceController@unlock');
            Route::post('/{id}/lock',         'RemoteLock\RemoteLockDeviceController@lock');
        });

    // Nature Remo Accounts
    Route::prefix('nature_remo_accounts')
        ->group(function () {
            Route::get('/',                     'NatureRemo\NatureRemoAccountController@index');
            Route::post('/',                    'NatureRemo\NatureRemoAccountController@store');
            Route::get('/{account}',            'NatureRemo\NatureRemoAccountController@show');
            Route::delete('/{account}',         'NatureRemo\NatureRemoAccountController@destroy');
        });

    // Nature Remo Devices
    Route::prefix('nature_remo_devices')
        ->group(function () {
            Route::get('/',                     'NatureRemo\NatureRemoDeviceController@index');
            Route::post('/scan',                'NatureRemo\NatureRemoDeviceController@scan');
            Route::get('/{device}',             'NatureRemo\NatureRemoDeviceController@show');
            Route::put('/{device}',             'NatureRemo\NatureRemoDeviceController@update');
            Route::put('/{device}/register',    'NatureRemo\NatureRemoDeviceController@register');
        });

    // Nature Remo Appliances
    Route::prefix('nature_remo_appliances')
        ->group(function () {
            Route::get('/',                     'NatureRemo\NatureRemoApplianceController@index');
            Route::post('/scan',                'NatureRemo\NatureRemoApplianceController@scan');
            Route::get('/{appliance}',          'NatureRemo\NatureRemoApplianceController@show');
            Route::get('/{appliance}/signals',  'NatureRemo\NatureRemoApplianceController@getSignals');
        });

    // Nature Remo Signals
    Route::prefix('nature_remo_signals')
        ->group(function () {
            Route::get('/',                     'NatureRemo\NatureRemoSignalController@index');
            Route::get('/{signal}',             'NatureRemo\NatureRemoSignalController@show');
            Route::post('/{signal}/send',       'NatureRemo\NatureRemoSignalController@send');
        });

    // Staysee
    Route::post('staysee_rooms/sync', 'Staysee\StayseeRoomController@sync');

    // Camera Capture
    Route::get('camera_capture',    'CameraCaptureController@create');
    Route::post('camera_capture',   'CameraCaptureController@store');

    //Room Message
    Route::get('room_messages/', 'RoomMessageController@index');

    // SpareKey resource
    Route::prefix('spare_keys')
        ->group(function () {
            Route::get('/',             'SpareKeyController@index');
            Route::post('/refresh',     'SpareKeyController@refresh');
        });

    // Task resource
    Route::prefix('tasks')
        ->group(function () {
            Route::get('/',         'TaskController@index');
            Route::get('/{task}',   'TaskController@show');
            Route::put('/{task}',   'TaskController@update');
        });

    Route::prefix('param-settings')
        ->group(function () {
            Route::post('/updateParamSettings',          'ParamSettingsController@updateParamSettings');
            Route::get('/getParamSettings',               'ParamSettingsController@getParamSettings');
        });
});
