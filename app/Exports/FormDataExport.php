<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class FormDataExport implements FromArray
{
    // Holds the form data
    protected $formData;

    // Constructor to initialize form data
    public function __construct(array $formData)
    {
        $this->formData = $formData;
    }

    // This method returns the data in array format for the export
    public function array(): array
    {
        return [
            [
                'Name'  => $this->formData['name'],   // 'name' from the submitted form
                'Email' => $this->formData['email'],  // 'email' from the submitted form
                'Phone' => $this->formData['phone'],  // 'phone' from the submitted form
            ]
        ];
    }
}
