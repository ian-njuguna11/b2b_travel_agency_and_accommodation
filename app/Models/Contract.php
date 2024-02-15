<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function travelAgent()
    {
        return $this->belongsTo(User::class);
    }

    public function getMapping()
    {
        return [
            'id' => $this->id,
            'contract_rates' => $this->contract_rates,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'accommodation_id' => $this->accommodation_id,
            'travel_agent_id' => $this->travel_agent_id ,
            'travel_agent_email' => $this->travelAgent->email ,
            'accommodation_name' => $this->accommodation->name,
            'travel_agent' => $this->travelAgent
        ];
    }

}
