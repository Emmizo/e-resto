<?php

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
