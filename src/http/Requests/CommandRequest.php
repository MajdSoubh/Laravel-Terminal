<?php

namespace Maso\WebShell\http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommandRequest extends FormRequest
{
    /**
     * The list of commands that are not allowed to be run.
     *
     * @var array<string>
     */
    private array $interactiveCommands = [
        'php artisan tinker',
        'php artisan serve',
        'php artisan queue:work',
        'php artisan queue:listen',
        'php artisan schedule:work',
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'command' => ['required', Rule::notIn($this->interactiveCommands)]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422));
    }
}
