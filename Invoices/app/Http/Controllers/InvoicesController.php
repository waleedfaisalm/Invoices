<?php

namespace App\Http\Controllers;

use App\Invoices;
use App\sections;
use App\User;
use Illuminate\Support\Facades\Notification;
use App\invoices_details;
use App\invoice_attachments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class InvoicesController extends Controller
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
        $invoices = Invoices::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = sections::all(); // معنتة بيرجعل كل الاقسام من قاعدة البيانات
        return view('invoices.add_invoice',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // invoices اضافة البيانات لجدول ال
        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

         // invoices_details اضافة البيانات لجدول ال
        $invoice_id = invoices::latest()->first()->id; //بتاعها  id هتلي ال  invoices اخر حاجة تم في جدول ال  invoices معنتة بتروح علا جدول ال
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);


        // شرط لو في صور او مرفقات نفذ الكود هذا لو مافيش مش بينفذ حاجة

        if ($request->hasFile('pic')) {

            $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic'); // file  جيلك من ال  $request
            $file_name = $image->getClientOriginalName(); // معنتة يجبلي لسم الصورة او الملف
            $invoice_number = $request->invoice_number; // الي جيلك من الفاتورة  $request معنتة رقم الفاتورة بيجلك من ال

            $attachments = new invoice_attachments(); // الي هو المودل بتاعي عشان يحفظ البيانات  invoice_attachments
            $attachments->file_name = $file_name; // معنتة عشان يجبلي اسم الملف يحفضة لي بي الجدول
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();  // عشان يحفظ اسم المرفق بي قاعدة البيانات لاكن المرفق نفسة بيحفضة علا السيرفر يعني بي مجلد دخل المشروع
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName); // معنتة بينشئ مجلد بي رقم الفاتورة بعد كذا بيخذ اسم بي تاع الفاتورة
        }

        // $user = User::first();
        // Notification::send($user, new AddInvoice($invoice_id));

        $user = User::get();
        $invoices = invoices::latest()->first();
        Notification::send($user, new \App\Notifications\Add_invoice_new($invoices));



        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoices = invoices::where('id', $id)->first();
        return view('invoices.status_update', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = invoices::where('id', $id)->first();
        $sections = sections::all();
        return view('invoices.edit_invoice', compact('sections', 'invoices'));
    }

    /**
     * Update the specified resource in storage.
     *update: دلة جو لارفير تفعل تعديل
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $invoices = invoices::findOrFail($request->invoice_id); // الي جيلك من الفاتورة  id بناء علا ال  invoices الي دخل جدول ال  id وبحثلي علا ال  invoices معنتة ادخل علا جدول الفاتورة ال
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * forceDelete(): دلة تحذف العنصر نهاين من الوجة ومن قاعدة البيانات
     * delete(): دلة تحذف العنصر لاكن بيبقئ موجود في قاعدة البيانات ويمكن تروح للرشفة في حال استعدها
     * deleteDirectory(): دلة لحذف الملف والمجلد
     * @param  \App\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id; // request الي جيلي من ال  id بيرجعلي ال
        $invoices = invoices::where('id', $id)->first();
        $Details = invoice_attachments::where('invoice_id', $id)->first();

        $id_page =$request->id_page;


        // بيحذفة نهاية من قاعدة البيانات  forceDelete مش بيسوي 2 يعني بينفذ كود ال  id_page شرط معنتة
        if (!$id_page==2) {

            // كود حذف المرفقات بتاع الفاتوة
        if (!empty($Details->invoice_number)) {

            Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
        }

        $invoices->forceDelete(); // معنتة بيحذف الفاتورة من قاعدة البيانات مع المرفق بتاع الفاتورة
        session()->flash('delete_invoice');
        return redirect('/Invoices');


    }
    // يعني بيحذفة لاكن بينقلة للارشفة  delete بيسوي 2 بيفعل  id_page مالم لو لقيت
    else {

            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/Archive');
        }
    }

    public function getproducts($id)
    {
        // pluck("Product_name", "id"): id وال  Product_name جبلي ال  products معنتة لما تكون بي جدول ال
        // الي جيلي من الصفحة بتاعي  $id يسوي ال  section_id ولما يكون  products معنتة بتروح علا جدول ال
        $products = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }

    public function Status_Update($id, Request $request)
    {
        $invoices = invoices::findOrFail($id);   // هذا وجبلي الصف بتاعتة  id معنتة روح علا قاعدة البيانات جبلي
        if ($request->Status === 'مدفوعة') {     // مدفوعة بيسوي مدفوعة ادخل افعل تحديث  Status معنتة لو الحالة

            $invoices->update([
                'Value_Status' => 1, // واحد يعني مدفوعة
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/Invoices');

    }

    public function Invoice_Paid()
    {
        $invoices = Invoices::where('Value_Status', 1)->get(); // يسوي 1 يعني مدفوعة رجعها لي  Value_Status لما يكون ال ال  invoices معنتة ادخل علا جدول ال
        return view('invoices.invoices_paid',compact('invoices'));
    }

    public function Invoice_unPaid()
    {
        $invoices = Invoices::where('Value_Status',2)->get();  // يسوي 2 يعني غير مدفوعة رجعها لي  Value_Status لما يكون ال ال  invoices معنتة ادخل علا جدول ال
        return view('invoices.invoices_unpaid',compact('invoices'));
    }

    public function Invoice_Partial()
    {
        $invoices = Invoices::where('Value_Status',3)->get(); // يسوي 3 يعني مدفوعة جزئية  رجعها لي  Value_Status لما يكون ال ال  invoices معنتة ادخل علا جدول ال
        return view('invoices.invoices_Partial',compact('invoices'));
    }

    public function Print_invoice($id)
    {
        $invoices = invoices::where('id', $id)->first();
        return view('invoices.Print_invoice',compact('invoices'));
    }

    public function MarkAsRead_all (Request $request)
    {

        $userUnreadNotification= auth()->user()->unreadNotifications;

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }


    }

}
