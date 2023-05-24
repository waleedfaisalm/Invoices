<?php

namespace App\Http\Controllers;

use App\invoices_details;
use App\Invoices;
use App\invoice_attachments;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Http\Request;

class InvoicesDetailsController extends Controller
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
        //
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
     * @param  \App\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function show(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *get(): invoices_Details عشان يجبلي كل المعلومات من جدول ال  foreach نستخدما عندما نريد نفعل
     * first(): first يعني مشتي افعل لوب استخدم ال  foreach نستخدمة في حال اجيب صف وحد فقط وكمان في حال ماشتي استخدم
     * @param  \App\invoices_details  $invoices_details معنتة تفاصيل الفاتورة
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = invoices::where('id',$id)->first(); // first ويجيب كل المعلومات الصفحة رقم واحد   من خلال دلة ال  invoices معنتة بيروح علا جدول ال

        $details  = invoices_Details::where('id_Invoice',$id)->get(); // الي جيلي حيبلي التفاصيل بتاعها   $id بيسوي ال  id_Invoice لما يكون ال  invoices_Details معنتة ادخل علا جدول ال
        $attachments  = invoice_attachments::where('invoice_id',$id)->get(); // جيبلي كل المرفقات الي تخص الفاتورة رقم 3 مثلا وجيبها لي    $id يسوي  invoice_id لما يكون ال  invoice_attachments معنتة ادخل علا جدول ال

        return view('invoices.details_invoice',compact('invoices','details','attachments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoices = invoice_attachments::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }
    // دلة عشان يقدر يحمل الملف
    public function get_file($invoice_number,$file_name)
    {
        $contents= Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
        return response()->download( $contents);
    }


    // دلة عشان يقدر يعرض الملف
    public function open_file($invoice_number,$file_name)
    {
        $files = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
        return response()->file($files);
    }
}
