<?php

namespace App\Http\Controllers\Api\Landing;

use App\Http\Controllers\Api\BaseController;
use App\Jobs\NewTicketJob;
use App\Mail\TicketMail;
use App\Models\Category;
use App\Models\Design;
use App\Models\DesignChild;
use App\Models\Education;
use App\Models\Product;
use App\Models\Testimoni;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;

class LandingController extends BaseController
{
    public function getCategory()
    {
        $data = Category::orderBy('name','ASC')
            ->get();

        return $this->sendResponse($data, 'Category List.');
    }

    public function getSupplier()
    {
        $data = User::role('Supplier')
            ->orderBy('name','ASC')
            ->get();

        return $this->sendResponse($data, 'Supplier List.');
    }

    public function getSupplierDetail($id)
    {
        $data = User::role('Supplier')
            ->where('id','=',$id)
            ->first();

        return $this->sendResponse($data, 'Supplier Detail.');
    }

    public function getSupplierByCategory($category)
    {
        $data = User::role('Supplier')
            ->where('category','=',$category)
            ->get();

        return $this->sendResponse($data, 'Supplier Category.');
    }

    public function getSupplierProduct($id)
    {
        $data = Product::with('productVariant')
            ->where('supplier_id','=',$id)
            ->where('productStock','>',1)
            ->where('status','=','Active')
            ->get();

        return $this->sendResponse($data, 'Product Supplier List.');
    }

    public function getDesign()
    {
        $data = Design::orderBy('designName','ASC')->get();
        return $this->sendResponse($data,'List Design.');
    }

    public function getDesignBySupplier($supplier)
    {
        $data = Design::where('supplier_id','=',$supplier)
            ->where('shop_id','=',null)
            ->orderBy('created_at','ASC')
            ->get();
        return $this->sendResponse($data,'List Design.');
    }

    public function getSubDesign($id)
    {
        $data = DesignChild::where('design_id','=',$id)->get();
        return $this->sendResponse($data,'Sub Design');
    }

    public function getProduct(Request $request)
    {
        $limit = $request->query('limit');
        $sortBy = $request->query('sortBy'); //popular|newest|mostBuy|highPrice|lowPrice|search

        if($sortBy == "popular")
        {
            $data = Product::with([
                'productCategory'
            ])
                ->withCount('productSold')
                ->where('status','=','Active')
                ->orderBy('product_sold_count','DESC')
                ->limit($limit)
                ->get();
        }else {
            $data = Product::with([
                'productCategory'
            ])
                ->withCount('productSold')
//                ->orderBy('productName','ASC')
                ->limit($limit)
                ->get();
        }
        return $this->sendResponse($data,'Success');
    }

    public function getDetailProduct($id)
    {
        $data = Product::with([
            'productCategory','productVariant'
        ])
            ->where('id','=',$id)->first();

        return $this->sendResponse($data,'Success');
    }

    public function getTestimoni()
    {
        $data = Testimoni::orderBy('created_at','DESC')->get();
        return $this->sendResponse($data,'List testimoni.');
    }

    public function sendTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'pesan' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(),'Validation Error.',400);
        }

        $store = new Ticket();
        $store->name = $request['name'];
        $store->email = $request['email'];
        $store->pesan = $request['pesan'];
        $store->save();

//        $this->dispatch(new NewTicketJob($store));

        Mail::to('support@pvotdigital.com')->send(new TicketMail($store));

        return $this->sendResponse($store,'Success');
    }

    public function getEducation($group)
    {
        $data = Education::where('group','=',$group)
            ->orderBy('title','ASC')
            ->get();
        return $this->sendResponse($data,'Success');
    }
}
