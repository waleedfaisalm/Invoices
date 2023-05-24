<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sections extends Model
{
    // كود يسمحلي افعل لي اضافة او تعديل لقاعدة البيانات
    protected $fillable = [
        'section_name',
        'description',
        'created_by'
    ];
}

// created_by: معنتة مين الي ضاف القسم
