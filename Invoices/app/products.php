<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    // كود يسمحلي افعل لي اضافة او تعديل لقاعدة البيانات
    protected $fillable = [
        'Product_name',
        'description',
        'section_id',

    ];

    // belongsTo: sections الي جو جدول ال  id  معنتة هتلي الحاجة الي هيا ماتتكررش او هيا الاب الي هو ال  one to one تستخدم مع علاقة
    public function section()
    {
        return $this->belongsTo('App\sections');
    }


}


//  protected $guarded = []; fillable كود يسمحلي افعل لي اضافة او تعديل لقاعدة البيانات نفس فكرة ال
