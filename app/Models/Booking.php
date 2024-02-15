<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    function getMapping()
    {
        return [
            'contract_id' => $this->contract_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'contract' => $this->contract,
            'accomodation' => $this->contract->accommodation
        ];
    }
}
