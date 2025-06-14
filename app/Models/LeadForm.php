<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'workspace_id',
        'source_id',
        'stage_id',
        'assigned_to',
        'slug'
    ];

    public function leadFormField(){

        return $this->hasMany(LeadFormField::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function leadSource(){
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function leadStage(){
        return $this->belongsTo(LeadStage::class, 'stage_id');
    }


}
