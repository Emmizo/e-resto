<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="Error",
 *     type="object",
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="error"
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         example="Error message"
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="Success",
 *     type="object",
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="success"
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         example="Success message"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="object"
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="john@example.com"
 *     ),
 *     @OA\Property(
 *         property="phone_number",
 *         type="string",
 *         example="+1234567890"
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         example="123 Main St"
 *     ),
 *     @OA\Property(
 *         property="role",
 *         type="string",
 *         enum={"admin", "restaurant_owner", "customer"},
 *         example="customer"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time"
 *     )
 * )
 */
class BaseSchemas {}
