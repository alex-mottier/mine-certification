<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\SecurityScheme;
use OpenApi\Attributes\Tag;

#[Info(
    version: '1.0.0',
    title: 'mine-certification'
)]
#[SecurityScheme(
    securityScheme: 'apiToken',
    type: 'apiKey',
    name: 'Authentication',
    in: 'header',
    bearerFormat: 'Bearer'
)]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
