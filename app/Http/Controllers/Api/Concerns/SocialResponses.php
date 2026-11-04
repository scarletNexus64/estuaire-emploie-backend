<?php

namespace App\Http\Controllers\Api\Concerns;

use App\Services\Social\SocialException;
use App\Services\Social\SocialTarget;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Réponses communes aux contrôleurs sociaux :
 * `{ "success": false, "message": "..." }` (traduit) en cas d'erreur.
 */
trait SocialResponses
{
    /** Exécute l'action et convertit les erreurs métier/validation en JSON. */
    protected function socialAction(Closure $action): JsonResponse
    {
        try {
            return $action();
        } catch (SocialException $e) {
            return $this->socialError($e->translatedMessage(), $e->status);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
                'errors' => $e->errors(),
            ], 422);
        }
    }

    protected function socialError(string $message, int $status): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $message], $status);
    }

    protected function assertSocialType(string $type): void
    {
        if (! SocialTarget::isValidType($type)) {
            throw SocialException::notFound();
        }
    }

    /** Valide le corps d'un commentaire (1..1500 caractères, après trim). */
    protected function validateCommentBody(array $input, bool $withParent = false): array
    {
        if (isset($input['body']) && is_string($input['body'])) {
            $input['body'] = trim($input['body']);
        }

        $rules = ['body' => ['required', 'string', 'min:1', 'max:1500']];
        if ($withParent) {
            $rules['parent_id'] = ['nullable', 'integer', 'min:1'];
        }

        return Validator::make($input, $rules, [
            'body.required' => __('social.validation.body_required'),
            'body.string' => __('social.validation.body_required'),
            'body.min' => __('social.validation.body_required'),
            'body.max' => __('social.validation.body_max', ['max' => 1500]),
            'parent_id.integer' => __('social.validation.parent_invalid'),
            'parent_id.min' => __('social.validation.parent_invalid'),
        ])->validate();
    }
}
