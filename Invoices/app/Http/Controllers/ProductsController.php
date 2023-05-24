<?php

namespace App\Http\Controllers;

use App\products;
use App\sections;

use Illuminate\Http\Request;

class ProductsController extends Controller
{

function __construct()
{

    $this->middleware('permission:عرض صلاحية', ['only' => ['index']]);
    $this->middleware('permission:اضافة صلاحية', ['only' => ['create','store']]);
    $this->middleware('permission:تعديل صلاحية', ['only' => ['edit','update']]);
    $this->middleware('permission:حذف صلاحية', ['only' => ['destroy']]);

}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = sections::all();
        $products = products::all();
        return view('products.products', compact('sections', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'Product_name' => 'required|unique:products|max:255',
            'description' => 'required',
            'section_id' => 'required',

        ],[
            'Product_name.required' => 'يرجى ادخال اسم المنتج ',
            'Product_name.unique' => 'اسم المنتج مسجل مسبقا ',
            'description.required' => 'يرجى ادخال البيان  ',
            'section_id.required' => 'يرجى اختيار عنصر من القائمة   ',

        ]);

            products::create([
            'Product_name' => $request->Product_name,
            'description' => $request->description,
            'section_id' => $request->section_id,

        ]);
        session()->flash('Add', 'تم اضافة المنتج بنجاح ');
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $id = sections::where('section_name', $request->section_name)->first()->id; // بتاعة  id وتجبلي ال  section_name وتدور علا  sections معنتة بيروج علا جدول ال
        $Products = Products::findOrFail($request->pro_id); // pro_id ويخذة بس الي جيلك من ال  Productsالي جو جدول ال  id وتبحثلي علا ال  Products معنتة بيروح علا جدول ال

        $Products->update([
        'Product_name' => $request->Product_name,
        'description' => $request->description,
        'section_id' => $id,
        ]);

        session()->flash('Edit', 'تم تعديل المنتج بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
            $Products = Products::findOrFail($request->pro_id); // pro_id الي جيلك من ال  id روح ابحثلي علا ال  Products معنتة روح علا جدول ال
            $Products->delete(); // احذفة لي
            session()->flash('delete', 'تم حذف المنتج بنجاح');
            return back();
    }
}
