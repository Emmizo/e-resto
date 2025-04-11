<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="role", type="string", example="customer"),
 *     @OA\Property(property="restaurant_id", type="integer", format="int64", example=1, nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Order",
 *     required={"user_id", "restaurant_id", "total_amount", "status", "payment_status"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="restaurant_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="total_amount", type="number", format="float", example=99.99),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="payment_status", type="string", example="unpaid"),
 *     @OA\Property(property="delivery_address", type="string", example="123 Main St"),
 *     @OA\Property(property="special_instructions", type="string", example="No onions please"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="items",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/OrderItem")
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="OrderItem",
 *     required={"order_id", "menu_item_id", "quantity", "price"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="order_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="menu_item_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="quantity", type="integer", example=2),
 *     @OA\Property(property="price", type="number", format="float", example=9.99),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="menu_item",
 *         ref="#/components/schemas/MenuItem"
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="MenuItem",
 *     required={"menu_id", "name", "price"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="menu_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Cheeseburger"),
 *     @OA\Property(property="description", type="string", example="Juicy beef patty with cheese"),
 *     @OA\Property(property="price", type="number", format="float", example=9.99),
 *     @OA\Property(property="category", type="string", example="Main Course"),
 *     @OA\Property(property="dietary_info", type="string", example="Contains dairy"),
 *     @OA\Property(property="is_available", type="boolean", example=true),
 *     @OA\Property(property="image", type="string", example="burgers/cheeseburger.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
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
 *         example="Operation completed successfully"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Restaurant Name"),
 *             @OA\Property(property="description", type="string", example="Restaurant Description"),
 *             @OA\Property(property="address", type="string", example="123 Main St"),
 *             @OA\Property(property="phone", type="string", example="+1234567890"),
 *             @OA\Property(property="email", type="string", example="restaurant@example.com"),
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(
 *                 property="menus",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Main Menu"),
 *                     @OA\Property(property="is_active", type="boolean", example=true),
 *                     @OA\Property(
 *                         property="menu_items",
 *                         type="array",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(property="id", type="integer", example=1),
 *                             @OA\Property(property="name", type="string", example="Burger"),
 *                             @OA\Property(property="description", type="string", example="Delicious burger"),
 *                             @OA\Property(property="price", type="number", format="float", example=9.99),
 *                             @OA\Property(property="is_available", type="boolean", example=true)
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="Restaurant",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Delicious Restaurant"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         example="A fine dining experience"
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         example="456 Food St, City, Country"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         example="+1234567890"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="info@delicious.com"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"active", "inactive"},
 *         example="active"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-03-20T07:48:04.000000Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-03-20T07:48:04.000000Z"
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="MenuItem",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="restaurant_id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Spaghetti Carbonara"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         example="Classic Italian pasta dish with eggs, cheese, pancetta, and black pepper"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         example=15.99
 *     ),
 *     @OA\Property(
 *         property="category",
 *         type="string",
 *         example="Main Course"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"available", "unavailable"},
 *         example="available"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-03-20T07:48:04.000000Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-03-20T07:48:04.000000Z"
 *     )
 * )
 */
class Schemas {}
