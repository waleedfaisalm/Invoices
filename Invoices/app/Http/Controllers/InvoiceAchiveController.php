<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoices;
class InvoiceAchiveController extends Controller
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
        $invoices = invoices::onlyTrashed()->get(); // وجبلي البيانات الي تم حذفها  invoices معنتة روح علا جدول ال
        return view('Invoices.Archive_Invoices',compact('invoices'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * withTrashed(): معنتة الحاجة الي اتعمل لها ارشفة ورحعها لي
     * restore(): null معنتة استعادها بيتشيل التاريخ والوقت من حقل الارشفة وتخلة
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->invoice_id;
        $flight = Invoices::withTrashed()->where('id', $id)->restore(); // وشوف العمدة الي مترشفه وفعلها استعدة  Invoices معنتة بروح علا جدول ال
        session()->flash('restore_invoice');
        return redirect('/Invoices');
    }

    /**
     * withTrashed(): معنتة دورلي علا الحاجة المترشفة
     *
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoices = invoices::withTrashed()->where('id',$request->invoice_id)->first(); // الي في الفروم  id يسوي ال  id ودورلي علا حاجة المترشفة لما يكون ال  invoices معنتة روح علا جدول ال
        $invoices->forceDelete(); // معنتة احذف نهاية
        session()->flash('delete_invoice');
        return redirect('/Archive');
    }
}
