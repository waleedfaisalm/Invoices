<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * unsignedBigInteger: معنتة افعلي ارقم صحيحة مش بي السالب
     * onDelete('cascade'): معنتة في حالة احذف قسم مين يحذف كل المنتجات الي تخصة
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('Product_name', length: 999);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('section_id'); // بتاع القسم حقي  id معنتة بيحفظ ال
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade'); // sections بتاع جدول الاقسام  id و  section_id فعنا علاقة بين ال
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
