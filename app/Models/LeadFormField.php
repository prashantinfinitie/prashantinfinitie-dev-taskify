<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFormField extends Model
{
    use HasFactory;


    protected $fillable = [

            'form_id',
            'label',
            'name',
            'type',
            'is_required',
            'is_mapped',
            'options'
    ];

    public function leadForm(){
        return $this->belongsTo(LeadForm::class, 'form_id');
    }

}
