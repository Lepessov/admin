<?php

namespace App\Http\Controllers;

use App\Contracts\APIMessageEntity;
use App\Exceptions\UserDoesNotExistsException;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\RefreshTokenRepository;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends AccessTokenController
{
    use ApiResponse;

    /**
     * @OA\Post (
     *     path="/api/login",
     *     summary = "Login",
     *     operationId="auth.login",
     *     tags={"Авторизация"},
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *           @OA\Property(
     *             property="email",
     *             description="Email",
     *             type="string",
     *             example="admin@admin.com",
     *           ),
     *          @OA\Property(
     *             property="password",
     *             description="Password",
     *             type="string",
     *             example="admin",
     *           ),
     *         ),
     *       ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Users list",
     *     )
     * )
     *
     * @param ServerRequestInterface $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function login(ServerRequestInterface $request): JsonResponse
    {
        $inputParams   = $request->getParsedBody();
        $inputEmail    = $inputParams['email'];
        $inputPassword = $inputParams['password'];
        $user          = User::getUserByParams(['email' => $inputEmail]);

        throw_if(is_null($user), UserDoesNotExistsException::class);

        if (!Hash::check($inputPassword, $user->password)) {
            return $this->errorResponse(
                null,
                Response::HTTP_UNAUTHORIZED,
                APIMessageEntity::INVALID_CREDENTIALS
            );
        }

        $request = $request->withParsedBody([
            'grant_type'    => 'password',
            'client_id'     => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'username'      => $inputEmail,
            'password'      => $inputPassword,
            'scope'         => '*',
        ]);

        $response = json_decode($this->issueToken($request)->getContent(), true);

        return $this->successResponse(
            $response,
            Response::HTTP_OK,
            APIMessageEntity::AUTHORIZED
        );
    }

    /**
     * @OA\Post (
     *     path="/api/logout",
     *     summary = "Logout",
     *     operationId="auth.logout",
     *     tags={"Авторизация"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response="200",
     *         description="Users list",
     *     )
     * )
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = auth('api')->user();

        if(!$user) {
            return $this->errorResponse(null, Response::HTTP_UNAUTHORIZED, APIMessageEntity::UNAUTHORIZED);
        }

        $refreshTokenRepository = app(RefreshTokenRepository::class);

        $token = $request->user('api')->token();

        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
        $token->revoke();

        return $this->successResponse(null, Response::HTTP_OK, 'Успешный выход из системы');
    }

    /**
     * @OA\Post (
     *     path="/api/refresh-token",
     *     summary = "Refresh",
     *     operationId="auth.refresh",
     *     tags={"Авторизация"},
     *     @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *           @OA\Property(
     *             property="refresh_token",
     *             description="Refresh Token",
     *             type="string",
     *           ),
     *         ),
     *       ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Users list",
     *     )
     * )
     *
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function refresh(ServerRequestInterface $request): JsonResponse
    {
        $request     = $request->withParsedBody([
            'grant_type'    => 'refresh_token',
            'client_id'     => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'refresh_token' => request()->refresh_token,
            'scope'         => '*',
        ]);

        $response    = json_decode($this->issueToken($request)->getContent(), true);

        return $this->successResponse($response);
    }

    /**
     * @OA\Post (
     *     path="/api/register",
     *     summary = "Register",
     *     operationId="auth.register",
     *     tags={"Авторизация"},
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *           @OA\Property(
     *             property="name",
     *             description="Name",
     *             type="string",
     *             example="John Doe",
     *           ),
     *          @OA\Property(
     *             property="email",
     *             description="Email",
     *             type="string",
     *             example="john@example.com",
     *           ),
     *          @OA\Property(
     *             property="role_id",
     *             description="Id of Role",
     *             example="1",
     *             @OA\Schema(enum={1,2,3})
     *           ),
     *          @OA\Property(
     *             property="password",
     *             description="Password",
     *             type="string",
     *             example="password123",
     *           ),
     *         ),
     *       ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User registered successfully",
     *     )
     * )
     *
     * @param UserRegisterRequest $request
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $validateData = $request->validated();

        $user = User::query()->create([
            'name' => $validateData['name'],
            'role_id' => $validateData['role_id'],
            'email' => $validateData['email'],
            'password' => bcrypt($validateData['password']),
        ]);

        return $this->successResponse(
            ['user' => $user],
            Response::HTTP_CREATED,
            'User registered successfully'
        );
    }
}
