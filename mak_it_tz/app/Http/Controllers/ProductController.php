<?php

namespace App\Http\Controllers;

use App\CONST\AdminRolesCons;
use App\Helpers\Services\ProductService;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    use ApiResponse, ProductService;

    /**
     * @OA\Get (
     *     path="/api/product",
     *     summary = "Получить список продуктов",
     *     operationId="product.list",
     *     tags={"Продукты"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response="200",
     *         description="Список продуктов",
     *     )
     * )
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = auth('api')->user();
        $products = Product::query()->get();

        if (is_null($user)) {
            return $this->errorResponse(message:'Unauthorized!');
        }

        return $this->successResponse($products);
    }

    /**
     * @OA\Post (
     *     path="/api/product",
     *     summary = "Создать новый продукт",
     *     operationId="product.create",
     *     tags={"Продукты"},
     *     security={ {"bearer": {} }},
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *           @OA\Property(
     *             property="name",
     *             description="name",
     *             type="string",
     *           ),
     *          @OA\Property(
     *             property="description",
     *             description="description",
     *             type="string",
     *           ),
     *          @OA\Property(
     *             property="photo",
     *             description="photo",
     *             type="string",
     *           ),
     *          @OA\Property(
     *             property="price",
     *             description="price",
     *             type="integer",
     *           ),
     *         ),
     *       ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Продукт успешно создан",
     *     )
     * )
     *
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function create(ProductRequest $request): JsonResponse
    {
        $user = auth('api')->user();

        if (is_null($user)) {
            return $this->errorResponse(message:'Unauthorized!');
        }

        if ($user->role_id == AdminRolesCons::USER) {
            return $this->errorResponse(null, ResponseAlias::HTTP_METHOD_NOT_ALLOWED, 'Разрешение откланено');
        }

        $validateData = $request->validated();
        $validateData['user_id'] = $user->id;

        $product = Product::query()->create($validateData)->save();

        return $this->successResponse($product);
    }

    /**
     * @OA\Get (
     *     path="/api/product/{id}",
     *     summary = "Получить информацию о продукте",
     *     operationId="product.show",
     *     tags={"Продукты"},
     *     security={ {"bearer": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID продукта",
     *         required=true,
     *         @OA\Schema(type="integer",format="int64")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Информация о продукте",
     *     )
     * )
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::query()->find($id);

        if (!$product) {
            return $this->errorResponse(null, ResponseAlias::HTTP_NOT_FOUND, 'Продукт не найден');
        }

        return $this->successResponse($product);
    }

    /**
     * @OA\Put (
     *     path="/api/product/{id}/edit",
     *     summary = "Показать форму для редактирования продукта",
     *     operationId="product.edit",
     *     tags={"Продукты"},
     *     security={ {"bearer": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID продукта",
     *         required=true,
     *         @OA\Schema(type="integer",format="int64")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Форма редактирования продукта",
     *     )
     * )
     *
     * @param ProductRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function edit(ProductRequest $request, int $id): JsonResponse
    {
        $product = Product::query()->where(['id' => $id, 'user_id' => 1]);

        if (!$product) {
            return $this->errorResponse(null, ResponseAlias::HTTP_NOT_FOUND, 'Продукт не найден');
        }

        $product->update($request->validated());

        return $this->successResponse($product);
    }

    /**
     * @OA\Delete (
     *     path="/api/product/{id}",
     *     summary = "Удалить продукт",
     *     operationId="product.destroy",
     *     tags={"Продукты"},
     *     security={ {"bearer": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID продукта",
     *         required=true,
     *         @OA\Schema(type="integer",format="int64")
     *     ),
     *     @OA\Response(
     *         response="202",
     *         description="Продукт успешно удален",
     *     )
     * )
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $product = Product::query()->find($id);

        if (!$product) {
            return $this->errorResponse(null, ResponseAlias::HTTP_NOT_FOUND, 'Продукт не найден');
        }

        $product->delete();

        return $this->successResponse(null, ResponseAlias::HTTP_ACCEPTED, 'Продукт успешно удален');
    }
}
