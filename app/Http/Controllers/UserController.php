<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Mail\AdminUserCreated;
use App\Mail\AdminUserUpdated;
use App\Models\AuthLocation;
use App\Models\AuthModule;
use App\Models\User;
use App\Services\RemoteLockService;
use App\Traits\CommonFunctions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * <Class Name> UserController
 *
 * <Function Name> User Management and Processing<br>
 * Create : 2018.08.31 TP Harvey<br>
 * Update : 2018.11.21 TP Raymond   Added code for modules
 *          2019.07.11 TP Ivin/Pat  Add try Catch functions
 *          2019.12.06 TP Ivin      Added duplicate user function error
 *          2020.05.18 TP Uddin     Modify URL and method names according to the URL list<br>
 *          2020.08.25 TP Russell   Add $user->CONTACT_NUMBER = $request->CONTACT_NUMBER;
 *                                  Add $user->ALLOW_ALERT_NOTIFICATION = json_decode($request->ALLOW_ALERT_NOTIFICATION,true);
 *                                  Add $useralert = $request->USERALERT;
 *                                  Add $usercontact = $request->USERCONTACT;
 *                                  Add $users->CONTACT_NUMBER = $usercontact;
 *                                  Add $users->ALLOW_ALERT_NOTIFICATION = json_decode($useralert,true);
 *          2021.03.02 TP Uddin     Update method comment documentation
 *
 * <Overview> This controller manages users and user's information and user's floor, room and module privileges
 * @package Controller
 * @author TP Russell <r-russell@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class UserController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // getUsers                         (1.0) Retrieve all users according to the specified user type
    // getActiveUsers                   (2.0) Retrieve registered users according to the specified user type
    // getInactiveUsers                 (3.0) Retrieve unregistered users according to the specified user type
    // getUser                          (4.0) Retrieve specific user information with its assigned floor and modules
    // createUser                       (5.0) Create new user and manages the user avatar and its associated modules
    // disableUser                      (6.0) Change user's register flag to 0
    // enableUser                       (7.0) Change user's register flag to 1
    // changePasswordUser               (8.0) Validate user and change user's password
    // updateUserProfile                (9.0) Update user avatar and information
    // updateUserDesignation            (10.0) Change user's floor, room and module privilege

    use AuthenticatesUsers;
    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> All users are acquired<br>
     * <Function> Retrieve all users according to the specified user type<br>
     *            URL: http://localhost/getUsers<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return User[]|string $user | $e->getMessage()
     * @throws Throwable $e When an exception occurs in this process
     */
    public function getUsers(Request $request)
    {
        $user = [];
        // Check if what type of user
        if ($request->USER_TYPE == 1) {
            try {
                $user = User::with('authUserFloor', 'authModules')
                    ->get();
            } catch (\Throwable $e) {
                // Insert to new logs
                $uri = $request->getUri();
                $this->processError($uri, $e);
            }
        } elseif ($request->USER_TYPE == 2) {
            try {
                $user = User::where('USER_TYPE', 2)
                    ->with('authUserFloor', 'authModules')
                    ->get();
            } catch (\Throwable $e) {
                // Insert to new logs
                $uri = $request->getUri();
                $this->processError($uri, $e);
            }
        }
        // Return all registered users
        return $user;
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Get registered users<br>
     * <Function> Retrieve registered users according to the specified user type<br>
     *            URL: http://localhost/getActiveUsers<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return User[]|string $user | $e->getMessage()
     * @throws Throwable $e When an exception occurs in this process
     */
    public function getActiveUsers(Request $request)
    {
        $user = [];
        // Check if what type of user
        if ($request->USER_TYPE == 1) {
            try {
                $user = User::where('REG_FLAG', 1)
                    ->with('authUserFloor', 'authModules')
                    ->get();
            } catch (\Throwable $e) {
                // Insert to new logs
                $uri = $request->getUri();
                $this->processError($uri, $e);
            }
        } elseif ($request->USER_TYPE == 2) {
            try {
                $user = User::where('REG_FLAG', 1)
                    ->where('USER_TYPE', 3)
                    ->with('authUserFloor', 'authModules')
                    ->get();
            } catch (\Throwable $e) {
                // Insert to new logs
                $uri = $request->getUri();
                $this->processError($uri, $e);
            }
        }
        // Return all active users
        return $user;
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Get unregistered users<br>
     * <Function> Retrieve unregistered users according to the specified user type<br>
     *            URL: http://localhost/getInactiveUsers<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return User[]|string $user | $e->getMessage()
     * @throws Throwable $e When an exception occurs in this process
     */
    public function getInactiveUsers(Request $request)
    {
        $user = [];
        // Check if what type of user
        if ($request->USER_TYPE == 1) {
            try {
                $user = User::where('REG_FLAG', 0)
                    ->with('authUserFloor', 'authModules')
                    ->get();
            } catch (\Throwable $e) {
                // Insert to new logs
                $uri = $request->getUri();
                $this->processError($uri, $e);
            }
        } elseif ($request->USER_TYPE == 2) {
            try {
                $user = User::where('REG_FLAG', 0)
                    ->where('USER_TYPE', 3)
                    ->with('authUserFloor', 'authModules')
                    ->get();
            } catch (\Throwable $e) {
                // Insert to new logs
                $uri = $request->getUri();
                $this->processError($uri, $e);
            }
        }
        // Return all inactive users
        return $user;
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Get user information<br>
     * <Function> Retrieve specific user information with its assigned floor and modules<br>
     *            URL: http://localhost/getUser<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return User|string $user | $e->getMessage()
     * @throws Throwable $e When an exception occurs in this process
     */
    public function getUser(Request $request)
    {
        // Check if what type of user
        try {
            $userid = $request->USER_ID;
            $user = User::with('authUserFloor', 'authModules')
                ->findOrFail($userid);
            return $user;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> Create new user<br>
     * <Function> Create new user and manages the user avatar and its associated modules<br>
     *            URL: http://localhost/createUser<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success" | "duplicate" | $e->getMessage()
     * @throws Throwable $e When an exception occurs in this process
     */
    public function createUser(Request $request)
    {
        // Handle File Upload
        $userLogoPath = "public/imgs/users";
        $file = $request->file;
        $fileName = $request->fileName;
        $filePath = "/$userLogoPath/$fileName";
        try {
            $dbuser = User::where('USERNAME', $request->USERNAME)
                ->get();
            if (count($dbuser) > 0) {
                return 'duplicate';
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        try {
            // Password of the new user
            $password = Hash::make($request->PASSWORD);
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        try {
            // Details for creating new user
            $user = new User();
            $user->USER_TYPE = (int) $request->USER_TYPE;
            $user->USERNAME = $request->USERNAME;
            $user->PASSWORD = $password;
            $user->FIRST_NAME = isset($request->FIRST_NAME) ? $request->FIRST_NAME : "";
            $user->LAST_NAME =  isset($request->LAST_NAME)  ? $request->LAST_NAME : "";
            $user->CONTACT_NUMBER = $request->CONTACT_NUMBER;
            $user->ALLOW_ALERT_NOTIFICATION = json_decode($request->ALLOW_ALERT_NOTIFICATION, true);
            $user->EMAIL = $request->EMAIL;
            $user->REG_FLAG = 1;
            if ($request->hasFile == 1) {
                $file->move(public_path($userLogoPath), $fileName);
                $user->USER_LOGO = $filePath;
            }
            $user->save();
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        // Save to app logs
        $user = auth()->user();
        $user_id = $user->USER_ID;
        // $this->appLog($user_id,'Admin Account ('.$request->USERNAME.')','User Created');
        return 'success';
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> Disable a user<br>
     * <Function> Change user's register flag to 0<br>
     *            URL: http://localhost/users/disableUser<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success" | $e->getMessage()
     * @throws Throwable $e When an exception occurs in this process
     */
    public function disableUser(Request $request)
    {
        try {
            // Function to disable the user
            $user = User::where('USER_ID', $request->USERID)
                ->first();
            $user->REG_FLAG = 0;
            $user->save();
            // Insert Audit logs
            $user = "";
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'User Management';
            $instruction = 'Disabled a User';
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> Enable a user<br>
     * <Function> Change user's register flag to 1<br>
     *            URL: http://localhost/users/enableUser<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success" | $e->getMessage()
     * @throws Throwable $e When an exception occurs in this process
     */
    public function enableUser(Request $request)
    {
        try {
            // Function to enable the user
            $user = User::where('USER_ID', $request->USERID)
                ->first();
            $user->REG_FLAG = 1;
            $user->save();
            // Insert Audit Logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'User Management';
            $instruction = 'Enabled a User';
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> Update user password<br>
     * <Function> Validate user and change user's password<br>
     *            URL: http://localhost/changePasswordUser<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success" | $e->getMessage()
     * @throws Throwable $e When an exception occurs in this process
     */
    public function changePasswordUser(Request $request)
    {
        try {
            // For audit logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'User Management';
            $instruction = 'Changed User Password';
            $this->auditLogs($ip, $host, $module, $instruction);
            // Function for creating new password
            $username = auth()->user()->USERNAME;
            $password = Hash::make($request->PASSWORD);
            $user = User::where('USER_ID', $request->USERID)
                ->first();
            $user->PASSWORD = $password;
            $user->save();
            return 'success';
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> Update user profile<br>
     * <Function> Update user avatar and information<br>
     *            URL: http://localhost/users/updateUserProfile<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success" | $e->getMessage()
     * @throws Throwable $e When an exception occurs in this process
     */
    public function updateUserProfile(Request $request)
    {
        try {
            // Handle File Upload
            $userLogoPath = "public/imgs/users";
            $file = $request->file;
            $fileName = $request->fileName;
            $filePath = "/$userLogoPath/$fileName";
            // Requesting field for updating profile
            $userid = $request->USERID;
            $username = $request->USERNAME;
            $useremail = $request->USEREMAIL;
            $usercontact = $request->USERCONTACT;
            $useralert = $request->USERALERT;
            $users = User::findOrFail($userid);
            // Handle the Username and Email of the User
            $users->USERNAME = $username;
            $users->EMAIL = $useremail;
            $users->CONTACT_NUMBER = $usercontact;
            $users->ALLOW_ALERT_NOTIFICATION = json_decode($useralert, true);
            if ($request->hasFile == 1) {
                $file->move(public_path() . "/" . $userLogoPath, $fileName);
                $users->USER_LOGO = $filePath;
            }
            // For audit logs
            $ip = $request->ip();
            $authUser = auth()->user();
            $host = $authUser->USERNAME;
            $module = 'User Management';
            $instruction = 'Updated a Profile';
            $this->auditLogs($ip, $host, $module, $instruction);
            // Save to DB
            $users->save();
            return 'success';
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> Update user designation<br>
     * <Function> Change user's floor, room and module privilege<br>
     *            URL: http://localhost/updateUserDesignation<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success" | $e->getMessage()
     * @throws Throwable $e When an exception occurs in this process
     */
    public function updateUserDesignation(Request $request)
    {
        // Requesting field for updating user designation
        try {
            $userid = $request->USERID;
            $floors = $request->FLOORS;
            $modules = $request->MODULES;
            $users = User::where('USER_ID', $userid)
                ->first();
            // Location
            $users = AuthLocation::where('USER_ID', $userid)
                ->get();
            // Delete the existing designation of the user
            foreach ($users as $user) {
                $user->delete();
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        try {
            // Set the new designation floor of the user
            foreach ($floors as $floor) {
                $authLocation = new AuthLocation();
                $authLocation->USER_ID = $userid;
                $authLocation->FLOOR_ID = $floor['FLOOR_ID'];
                $authLocation->save();
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        // Modules
        try {
            $userid = $request->USERID;
            $floors = $request->FLOORS;
            $modules = $request->MODULES;
            $users = AuthModule::where('USER_ID', $userid)
                ->get();
            // Delete the existing module of the user
            foreach ($users as $user) {
                $user->delete();
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        try {
            // Set the new designation module of the user
            // For audit logs
            $ip = $request->ip();
            $authUser = auth()->user();
            $host = $authUser->USERNAME;
            $module = 'User Management';
            $instruction = 'Updated User Designation';
            $this->auditLogs($ip, $host, $module, $instruction);
            foreach ($modules as $module) {
                $authModule = new AuthModule();
                $authModule->USER_ID = $userid;
                $authModule->MODULE_ID = $module['MODULE_ID'];
                $authModule->save();
            }
            return 'success';
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> index <br>
     * <Function> Display a listing of the resource <br>
     *          METHOD: GET
     *          URI: /users/
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = User::query();

        // if "user_type" query parameter is available, filter the query
        if (!!request()->input('user_type')) {
            $query->ofType(request()->input('user_type'));
        }

        return response($query->get());
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> store <br>
     * <Function> Create new user with a remotelock access user <br>
     *          METHOD: POST
     *          URI: /users/
     *
     * @param  \App\Http\Requests\User\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        // validate request data
        $validated = $request->validated();

        // initialize service class
        $remoteLockService = new RemoteLockService();

        // get all door groups
        $response1 = $remoteLockService->getDoorGroups();
        abort_if($response1->failed(), 400, 'Failed getting the door groups.');

        // find the door group for all device
        $doorGroup = collect($response1->json()['data'])->firstWhere('attributes.name', 'SUYAMA Rooms');
        abort_if(empty($doorGroup), 400, 'No door group found.');

        // create an access user
        $response2 = $remoteLockService->createAccessUser($validated['username'], $validated['email']);
        abort_if($response2->failed(), 400, 'Failed creating remote lock access user.');

        // grant 'access user' access to the door group
        $accessUser = collect($response2->json()['data']);
        $response3 = $remoteLockService->grantDoorGroupAccess($accessUser['id'], $doorGroup['id']);

        // deactivate access user if granting access fails
        if ($response3->failed()) {
            $remoteLockService->deactivateAccessPerson($accessUser['id']);
            abort(400, 'Failed granting user access to the door group.');
        }

        try {
            // create user in iBMS
            $user = User::create([
                'USERNAME' => $validated['username'],
                'FIRST_NAME' => $validated['name'],
                'EMAIL' => $validated['email'],
                'CONTACT_NUMBER' => $validated['phone_number'],
                'PASSWORD' => Hash::make($accessUser['attributes']['pin']),
                'USER_TYPE' => 1,
                'REG_FLAG' => 1,
            ]);

            // create access person entity
            $user->accessPerson()->create([
                'ACCESS_PERSON_ID' => $accessUser['id'],
                'USER_ID' => $user->USER_ID,
                'ACCESS_TYPE' => $accessUser['type']
            ]);
        } catch (\Exception $th) {
            return response($th->getMessage(), 400);
        }

        Mail::to($user->EMAIL)->send(new AdminUserCreated($user, $accessUser));

        return response('success', 201);
    }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> update <br>
     * <Function> Update user and Remote Lock access user <br>
     *          METHOD: PUT
     *          URI: /users/{user}
     *
     * @param  \App\Http\Requests\User\UpdateRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        $validated = $request->validated();

        // load the access person relation
        $user->load('accessPerson');

        // initialize service class
        $remoteLockService = new RemoteLockService();

        // update the access user
        $response1 = $remoteLockService->updateAccessUser(
            $user->accessPerson->ACCESS_PERSON_ID,
            $validated['username'],
            $validated['email'],
            $validated['update_pin_flag']
        );
        abort_if($response1->failed(), 400, 'Failed updating remote lock access user');

        $accessUser = collect($response1->json()['data']);

        try {
            // update iBMS user
            $user->update([
                'USERNAME' => $validated['username'],
                'FIRST_NAME' => $validated['name'],
                'EMAIL' => $validated['email'],
                'CONTACT_NUMBER' => $validated['phone_number'],
                'PASSWORD' => Hash::make($accessUser['attributes']['pin'])
            ]);

            // update access person entity
            $user->accessPerson->update([
                'ACCESS_TYPE' => $accessUser['type']
            ]);
        } catch (\Exception $th) {
            return response($th->getMessage(), 400);
        }

        Mail::to($user->EMAIL)->send(new AdminUserUpdated($user, $accessUser));

        // return status code 204
        return response()->noContent();
    }

    /**
     * <Layer number> (13.0)
     *
     * <Processing name> destroy <br>
     * <Function> Delete the user and the remote lock access user <br>
     *          METHOD: DELETE
     *          URI: /users/{user}
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // load the accessPerson relation
        $user->load('accessPerson');

        // initialize service class
        $remoteLockService = new RemoteLockService();

        // delete the access user
        $response1 = $remoteLockService->deactivateAccessPerson($user->accessPerson->ACCESS_PERSON_ID);
        abort_if($response1->failed(), 400, 'Failed deactivating remote lock access user.');

        // delete the access person
        $user->accessPerson->delete();

        // delete the iBMS user
        $user->delete();

        // return status code 204
        return response()->noContent();
    }
}
