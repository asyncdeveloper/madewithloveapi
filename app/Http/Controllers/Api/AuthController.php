<?php
namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{

    /**
     * @SWG\Post(
     *   tags={"Auth"},
     *   path="/auth/login",
     *   summary="Login user",
     *  @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/User"),
     *   ),
     *   @SWG\Response(response=200, description="Login successful"),
     *   @SWG\Response(response=400, description="Invalid email/password supplied"),
     *   @SWG\Response(response=401, description="Incorrect email/password supplied")
     * )
     */
    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);

        $validator = Validator::make($data, [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        if (!Auth::attempt($data)) {
            return response()->json([
                'message' => 'Invalid login credentials',
            ], 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addMonths(3);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
        ]);

    }

    /**
     * @SWG\Get(
     *   tags={"Auth"},
     *   path="/auth/logout",
     *   summary="Logout user",
     *   @SWG\Response(response=200, description="Logout successful"),
     *   @SWG\Response(response=401, description="Unauthenticated"),
     *   security={
     *    { "Bearer":{} }
     *   },
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * @SWG\Post(
     *   tags={"Auth"},
     *   path="/auth/register",
     *   summary="Create user",
     *  @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/User"),
     *   ),
     *   @SWG\Response(response=201, description="User created successfully"),
     *   @SWG\Response(response=400, description="Invalid email/password/name supplied")
     * )
     */
    public function register(Request $request)
    {
        $data = $request->only(['email', 'password', 'name' ]);

        $validator = Validator::make($data, [
            'name' => 'required|string|min:3|max:191',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:3|max:191'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }


        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return response()->json([
            'message' => 'Successfully created user',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ], 201);
    }

}
