<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Tariffs",
 *     description="Operations about tariffs"
 * )
 */
/**
 * @OA\Schema(
 *     schema="Tariff",
 *     type="object",
 *     required={"id", "name", "price_per_user"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Basic Plan"),
 *     @OA\Property(property="price_per_user", type="string", example="10.00"),
 * )
 */
class TariffController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tariffs",
     *     tags={"Tariffs"},
     *     summary="Get all tariffs",
     *     description="Returns a list of tariffs",
     *     @OA\Response(
     *         response=200,
     *         description="A list of tariffs",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Tariff")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal Server Error"),
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Tariff::all());
    }
}


