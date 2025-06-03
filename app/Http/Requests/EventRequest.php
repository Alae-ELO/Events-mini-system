<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'category_id' => 'required|exists:categories,id',
            'place_id' => 'required|exists:places,id',
            'organizer_id' => 'required|exists:users,id',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The event title is required.',
            'description.required' => 'The event description is required.',
            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'end_date.required' => 'The end date is required.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after' => 'The end date must be after the start date.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'place_id.required' => 'Please select a place.',
            'place_id.exists' => 'The selected place is invalid.',
            'organizer_id.required' => 'Please select an organizer.',
            'organizer_id.exists' => 'The selected organizer is invalid.',
            'picture.image' => 'The file must be an image.',
            'picture.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'picture.max' => 'The image may not be greater than 2MB.'
        ];
    }
} 