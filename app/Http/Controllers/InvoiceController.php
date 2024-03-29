<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Counter;
use App\Models\InvoiceItem;
class InvoiceController extends Controller
{
    public function get_all_invoices()
    {
        $invoices = Invoice::with('Customer')->orderBy('id','DESC')->get();
        return response()->json([
            'invoices' => $invoices
        ],200);
    }
    public function search_invoice(Request $request)
    {
              $search = $request->get('s');

              if($search != null){
                $invoices = Invoice::with('Customer')
                ->where('total','LIKE',"%$search%")
                ->get();
                return response()->json([
                    'invoices' => $invoices
                ],200);
              }
              else {
                return $this->get_all_invoices();
              }
    }
    public function create_invoice(Request $request)
    {
       $counter = Counter::where('key','INVOICE')->first();

       $random = Counter::where('key','invoice')->first();
       $invoice = Invoice::orderBy('id','DESC')->first();
       
       if($invoice){
         $invoice = $invoice->id+1;
         $counters = $counter->value + $invoice;
        }
        else {
          $counter = $counter->value;
        }
        
   
       $formData =[
        'number' => $counter->prefix.$counters,
        'customer_id' => null,
        'customer' => null,
        'date'   =>  date('Y-m-d'),
        'due_date' => null,
        'reference' => null,
        'discount'  => 0,
        'terms_and_conditions' => 'deafult terms and Conditions',
        'items'  => [
            [
                'product_id' =>null,
                'products'    => null,
                'unit_rice'   => 0,
                'quantity'    => 1
            ]
            ]
        ];
       return response()->json($formData);
    }
    public function add_invoice(Request $request)
    {
      $invoiceitem = $request->input("invoice_item");
   
      $invoicedata['sub_total'] = $request->input("subtotal");
      $invoicedata['total']    = $request->input("total");
      $invoicedata['cutomer_id'] = $request->input("customer_id");
      $invoicedata['number']    = $request->input("number"); 
      $invoicedata['date'] = $request->input("date");
      $invoicedata['due_date']    = $request->input("due_date");
      $invoicedata['discount'] = $request->input("discount");
      $invoicedata['reference']    = $request->input("reference"); 
      $invoicedata['terms_and_conditions'] = $request->input("terms_and_conditions");
      $invoice = Invoice ::create($invoicedata);
      foreach(json_decode($invoiceitem) as $item){
        $itemdata['products_id'] = $item->id;
        $itemdata['invoice_id'] = $invoice->id;
        $itemdata['quantity']   = $item->quantity;
        $itemdata['unit_price'] = $item->unit_price;
        Invoiceitem::create($itemdata);
      }
    }
    public function show_invoice($id){
       $invoice = Invoice::with(['customer','invoice_items.product'])->find($id);
       return response()->json([
           'invoice' => $invoice,
       ],200);

    }
    public function edit_invoice($id){
      $invoices = Invoice::with(['customer','invoice_items.product'])->find($id);
      return response()->json([
          'invoices' => $invoices,
      ],200);

   }
   public function delete_invoice_items($id){
      $invoices = InvoiceItem::findOrFail($id);
      $invoices->delete();
  }
    
  public function update_invoice_items(Request $request, $id){
      

      $invoice = Invoice::where('id',$id)->first();
      // dd($request->all());
      $invoice->sub_total = $request->subtotal;
      $invoice->total = $request->total;
      $invoice->cutomer_id = $request->customer_id;
      $invoice->number = $request->number;
      $invoice->date = $request->date;
      $invoice->due_date = $request->due_date;
      $invoice->discount = $request->discount;
      $invoice->reference = $request->reference;
      $invoice->terms_and_conditions = $request->terms_and_conditions;

      $invoice->update($request->all());

      $invoiceitem = $request->input("invoice_item");
      $invoice->invoice_items()->delete();
    //  dd($invoiceitem );
      foreach(json_decode($invoiceitem) as $item)
      {
        $itemdata['products_id'] = $item->products_id;
        $itemdata['invoice_id'] = $invoice->id;
        $itemdata['quantity']   = $item->quantity;
        $itemdata['unit_price'] = $item->unit_price;
        Invoiceitem::create($itemdata);
      }
  
  }
  public function delete_invoice($id)
  {
       $invoice = Invoice::findOrFail($id);
       $invoice->invoice_items()->delete();
       $invoice->delete();
  }
}
