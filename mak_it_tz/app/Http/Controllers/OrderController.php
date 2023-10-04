<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Get a list of orders",
     *     operationId="order.index",
     *     tags={"Заказы"},
     *     security={{"bearer": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of orders",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Missing or invalid token",
     *     ),
     * )
     */
    public function index(): JsonResponse
    {
        $user = auth('api')->user();

        $orders = Order::with(['user', 'product'])->where('user_id', $user->id)->get();

        return $this->successResponse($orders, ResponseAlias::HTTP_OK, 'ready');
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     summary="Get information about an order",
     *     operationId="order.show",
     *     tags={"Заказы"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order information",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Missing or invalid token",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *     ),
     * )
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::with(['user', 'product'])->find($id);

        if (!$order) {
            return $this->errorResponse($order, ResponseAlias::HTTP_NOT_FOUND, 'This item not found!');
        }

        return $this->successResponse($order, ResponseAlias::HTTP_OK, 'ready');
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Create a new order",
     *     operationId="order.store",
     *     tags={"Заказы"},
     *     security={{"bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Order data",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"user_id", "product_id", "quantity", "total_price"},
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     description="ID of the user placing the order",
     *                 ),
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="integer",
     *                     description="ID of the product being ordered",
     *                 ),
     *                 @OA\Property(
     *                     property="quantity",
     *                     type="integer",
     *                     description="Quantity of the product",
     *                 ),
     *                 @OA\Property(
     *                     property="total_price",
     *                     type="number",
     *                     description="Total price of the order",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request - Invalid input data",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Missing or invalid token",
     *     ),
     * )
     */
    public function create(OrderRequest $request): JsonResponse
    {
        $user = auth('api')->user();

        $validatedData = $request->validated();
        $validatedData['user_id'] = $user->id;

        $order = Order::query()->create($validatedData);

        return $this->successResponse($order, ResponseAlias::HTTP_CREATED, 'created');
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{id}",
     *     summary="Update an existing order",
     *     operationId="order.update",
     *     tags={"Заказы"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order to update",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated order data",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"user_id", "product_id", "quantity", "total_price"},
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     description="ID of the user placing the order",
     *                 ),
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="integer",
     *                     description="ID of the product being ordered",
     *                 ),
     *                 @OA\Property(
     *                     property="quantity",
     *                     type="integer",
     *                     description="Quantity of the product",
     *                 ),
     *                 @OA\Property(
     *                     property="total_price",
     *                     type="number",
     *                     description="Total price of the order",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request - Invalid input data",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Missing or invalid token",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *     ),
     * )
     */
    public function update(OrderRequest $request, int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->errorResponse($order, ResponseAlias::HTTP_NOT_FOUND, 'This item is not found!');
        }

        $order->update($request->validated());

        return $this->successResponse($order, ResponseAlias::HTTP_ACCEPTED, 'updated');
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     summary="Delete an existing order",
     *     operationId="order.destroy",
     *     tags={"Заказы"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order to delete",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Order successfully deleted",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Missing or invalid token",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *     ),
     * )
     */
    public function delete(int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->errorResponse($order, ResponseAlias::HTTP_NOT_FOUND, 'This item is not found!');
        }

        $order->delete();

        return $this->successResponse($order, ResponseAlias::HTTP_ACCEPTED, 'updated');
    }
}
