<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoices extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'invoice_number',
        'invoice_Date',
        'Due_date',
        'product',
        'section_id',
        'Amount_collection',
        'Amount_Commission',
        'Discount',
        'Value_VAT',
        'Rate_VAT',
        'Total',
        'Status',
        'Value_Status',
        'note',
        'Payment_Date',
    ];

    protected $dates = ['deleted_at'];

        // belongsTo: sections الي جو جدول ال  id  معنتة هتلي الحاجة الي هيا ماتتكررش او هيا الاب الي هو ال  one to one تستخدم مع علاقة
        public function section()
        {
            return $this->belongsTo('App\sections');
        }

}
