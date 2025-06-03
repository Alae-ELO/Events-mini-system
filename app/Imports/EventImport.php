<?php

namespace App\Imports;

use App\Models\Event;
use App\Models\Category;
use App\Models\Place;
use App\Models\Organizer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Str;

class EventImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Find or create category
        $category = Category::firstOrCreate(
            ['name' => $row['category']],
            ['slug' => Str::slug($row['category'])]
        );

        // Find or create place
        $place = Place::firstOrCreate(
            ['name' => $row['place']],
            ['slug' => Str::slug($row['place'])]
        );

        // Find or create organizer
        $organizer = Organizer::firstOrCreate(
            ['name' => $row['organizer']],
            ['slug' => Str::slug($row['organizer'])]
        );

        return new Event([
            'title' => $row['title'],
            'description' => $row['description'],
            'start_date' => $row['start_date'],
            'end_date' => $row['end_date'],
            'price' => $row['price'],
            'capacity' => $row['capacity'],
            'category_id' => $category->id,
            'place_id' => $place->id,
            'organizer_id' => $organizer->id,
        ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
            'place' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'title.required' => 'The title field is required.',
            'description.required' => 'The description field is required.',
            'start_date.required' => 'The start date field is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'end_date.required' => 'The end date field is required.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after' => 'The end date must be after the start date.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 0.',
            'capacity.required' => 'The capacity field is required.',
            'capacity.integer' => 'The capacity must be an integer.',
            'capacity.min' => 'The capacity must be at least 1.',
            'category.required' => 'The category field is required.',
            'place.required' => 'The place field is required.',
            'organizer.required' => 'The organizer field is required.',
        ];
    }

    public function headingRow(): int
    {
        return 5;
    }
}
