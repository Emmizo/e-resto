<?php

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="E-Resto API Documentation",
 *     description="API documentation for E-Resto application",
 *     @OA\Contact(
 *         email="support@eresto.com"
 *     )
 * )
 */

/**
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST . "/api/v1",
 *     description="API Server"
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

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
 *         example={"field": {"Error message"}}
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
