<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     produces={"application/json"},
 * 	   consumes={"application/json"},
 *     host=L5_SWAGGER_CONST_HOST,
 *     basePath="/api",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="This is my cool API for ecommerce platform",
 *         description="Backend api for ecommerce web application",
 *         @SWG\Contact(
 *             email="contact@asyncdeveloper.com"
 *         )
 *     ),
 *     @SWG\SecurityScheme(
 *       securityDefinition="Bearer",
 *       type="apiKey",
 *       in="header",
 *       name="Authorization"
 *   )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
