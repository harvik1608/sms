<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class ContactsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Contact([
            'name' => $row['name'] ?? '',
            'email' => $row['email'] ?? '',
            'phone' => $row["whatsapp_no"] ?? '',
            'created_by' => Auth::user()->id,
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }

    public function headingRow(): int
    {
        return 1; // Header is on the first line of your CSV
    }
}
