<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Estuaire Emploie API Documentation",
 *     description="Documentation de l'API REST pour l'application mobile Estuaire Emploie - Plateforme de recherche d'emploi au Cameroun",
 *     @OA\Contact(
 *         email="contact@estuaire-emploie.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Serveur API Principal"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Authentification via Laravel Sanctum - Utiliser le token obtenu lors du login/register"
 * )
 */
abstract class Controller
{
    //
}
