<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Services\UserService;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;


/**
 * @group User management
 *
 * APIs for managing users
 */
class UserController extends Controller
{
    protected $userService;

    /**
     * Constructs a new UserController object
     */
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }
    
    /**
     * GET api/token
     * 
     * Return a json array containing a new auth token for a new GuestUser
     * @unauthenticated
     * 
     * @return Illuminate\Http\JsonResponse the access token
     */
    public function token(): JsonResponse
    {
        return response()->json(['token' => $this->userService->getToken()->plainTextToken]);
    }


    /**
     * POST api/user/login
     * 
     * Return a json array containing a new auth token for registered user.
     * Checks if the email belongs to a existing domain 
     * 
     * @bodyParam email string required valid email from existing domain. Example: codechallenge@gmail.com
     * 
     * @response scenario=success { "token": "34|0JJwyg6oCkEBnlAGFVOlq5f42SY9u476JSCBVUwT"}
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * @response status=422 scenario="invalid parameters" {"message": "The password field is required."}
     * @response status=403 scenario="wrong credentials" {"message": "Login error"}
     * 
     * @return Illuminate\Http\JsonResponse status 200 on success. status 400-500 on error
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $loggedIn = $this->userService->login($request->email, $request->password);

        return $loggedIn ? 
            response()->json(['token' => $this->userService->getToken()->plainTextToken], 200) :
            response()->json(['message' => 'login error'], 403);
    }

    /**
     * POST api/user/register
     * 
     * Process a registration request.
     * Checks if the email belongs to a existing domain 
     * 
     * @bodyParam email string required valid email from existing domain. Example: codechallenge@gmail.com
     * 
     * @response scenario=success
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * @response status=422 scenario="invalid parameters" {"message": "The password field is required."}
     * @response status=403 scenario="register failed" {"message": "Register error"}
     * 
     * 
     * @return Illuminate\Http\JsonResponse status 200 on success. status 400-500 on error
     */
    public function register(UserRegisterRequest $request) : JsonResponse
    {
        
        $registered = $this->userService->register($request->email, $request->password, $request->name, $request->address);

        return $registered ? 
            response()->json(['message' => 'Registered'], 200) :
            response()->json(['message' => 'Register error'], 403);
        
    }

    /**
     * GET api/user/orders
     * 
     * Return a Json array containing the orders of the user
     * 
    * @response scenario=success {
    *  "orders": [
    *    {
    *        "id": 6,
    *        "user_id": 104,
    *        "address": "Mi dirección",
    *        "total": 35,
    *        "created_at": "2023-04-10T03:31:10.000000Z",
    *        "updated_at": "2023-04-10T03:31:10.000000Z"
    *    }
    *  ]
    * }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * 
     * @return Illuminate\Http\JsonResponse orders of the user. status 400-500 on error
     */
    public function orders(): JsonResponse
    {
        return response()->json([
            'orders' => $this->userService->getOrders(),
        ]);
    }

    /**
     * GET api/user
     * 
     * Return a Json array containing the information of the user
     * 
     * @response scenario=success {
     *  "name": "Javier",
     *  "email": "javier@smartidea.es",
     *  "address": "Mi dirección",
     *  "registered": true
     * }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * 
     * @return Illuminate\Http\JsonResponse user information: name, email, address, bool registered. status 400-500 on error
     */
    public function get() : JsonResponse
    {
        $user = $this->userService->getUser();
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'registered' => $user->registered(),
        ]);
    }
}
