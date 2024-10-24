<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 *     schema="Subscription",
 *     type="object",
 *     required={"status", "tariff_id", "user_count", "payment_frequency"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(property="tariff_id", type="integer", example=1),
 *     @OA\Property(property="tariff_name", type="string", example=""),
 *     @OA\Property(property="user_count", type="integer", example=7),
 *     @OA\Property(property="total_cost", type="number", format="float", example=28.00),
 *     @OA\Property(property="payment_frequency", type="string", example="monthly"),
 *     @OA\Property(property="valid_until", type="string", format="date", example="20/10/2024")
 * )
 */
class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @OA\Get(
     *     path="/api/subscriptions",
     *     summary="Get all subscriptions",
     *     tags={"Subscriptions"},
     *     @OA\Response(
     *         response=200,
     *         description="List of subscriptions",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Subscription"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $currentSubscription = $this->subscriptionService->getCurrentSubscription();
        $lastSubscription = $this->subscriptionService->getLastSubscription();

        return response()->json([
            'currentSubscription' => $currentSubscription,
            'nextSubscription' => $lastSubscription,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/subscriptions/{id}",
     *     summary="Get a specific subscription",
     *     tags={"Subscriptions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subscription details",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(response=404, description="Subscription not found")
     * )
     */
    public function show($id): JsonResponse
    {
        $subscription = $this->subscriptionService->getById($id);

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        return response()->json($subscription);
    }

    /**
     * @OA\Post(
     *     path="/api/subscriptions",
     *     summary="Create a new subscription",
     *     tags={"Subscriptions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Subscription created",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(SubscriptionRequest $request): JsonResponse
    {
        $subscription = $this->subscriptionService->create($request->validated());
        return response()->json($subscription, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/subscriptions/{id}",
     *     summary="Update a subscription",
     *     tags={"Subscriptions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subscription updated",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(response=404, description="Subscription not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(SubscriptionRequest $request, $id): JsonResponse
    {
        $subscription = $this->subscriptionService->getById($id);

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        $updatedSubscription = $this->subscriptionService->update($subscription, $request->validated());

        return response()->json($updatedSubscription);
    }

    /**
     * @OA\Delete(
     *     path="/api/subscriptions/{id}",
     *     summary="Delete a subscription",
     *     tags={"Subscriptions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subscription deleted",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(response=404, description="Subscription not found")
     * )
     */
    public function destroy($id): JsonResponse
    {
        $subscription = $this->subscriptionService->getById($id);

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        $this->subscriptionService->delete($subscription);

        return response()->json(['message' => 'Subscription deleted successfully']);
    }
}
