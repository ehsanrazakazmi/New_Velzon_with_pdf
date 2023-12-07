<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }
//-----------------------------------------------------------------------------------------------------------------------------



    public function index()
    {
        $products = Product::latest()->paginate(5);     // gets products by latest arrangement
        return view('Product-Management.Products.list',compact('products'))->with('i', (request()->input('page', 1) - 1) * 5);
    }


    public function store(Request $request)
    {
        // validates the name and detail
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'detail' => 'required',
            'price' => 'required|numeric',
            'date' => 'nullable|date',
            'quantity' => 'nullable|integer',
        ]);
        // prints the follwing statement if validation fails
        if ($validator->fails())
        {
            return redirect()->back()->with('warning', 'Cannot add duplicate product');
        }
        // it will store the validated data into the database
        Product::create($request->all());
        
        // redirect back to the list page after
        return redirect()->route('product.index')
                        ->with('success','Product created successfully.');
    }

    public function edit($id)
    {
        $id = decrypt($id);   // decrypts the id after encryption in the blade file
        $product = Product::find($id);  // feed the product variable into the blade file
        return view('Product-Management.Products.edit',compact('product'));
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
            'price' => 'required|numeric',
            'date' => 'nullable|date',
            'quantity' => 'nullable|integer',

        ]);
        Product::find($id)->update(
            [
                'name' => $request->name,
                'detail'=>$request->detail,
                'price'=>$request->price,
                'date'=>$request->date,
                'quantity'=>$request->quantity,
            ]);
        return redirect()->route('product.index')
                        ->with('success','Product updated successfully');
    }


    public function destroy($id)
    {
        $id = decrypt($id);
        Product::find($id)->delete();   // delete the several id through the method
        return redirect()->route('product.index')
                        ->with('success','Product deleted successfully');
    }

        public function exportproduct()
        {
            return Excel::download(new ProductExport, 'product.xlsx');
        }

    public function importproduct(Request $request)
    {
        // dd('import');
        
        Excel::import(new ProductImport, $request->file('file'));
        return redirect()->back();
    }

    public function downloadpdf()
    {
        $data = Product::all();
        $pdf = Pdf::loadView('pdf.pdf', ['data' => $data]);
        return $pdf->download('webappfix.pdf');
    }
}
