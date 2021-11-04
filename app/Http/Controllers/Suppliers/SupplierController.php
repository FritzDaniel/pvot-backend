<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPicture;
use App\Models\ProductVariant;
use App\Models\Settings;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function myProduct(Request $request)
    {
        $user = $request->user();
        $data = Product::with(['userDetail', 'productPhoto', 'productVariant'])
            ->where('user_id', '=', $user->id)
            ->orderBy('created_at', 'DESC')
            ->get();
        return $this->sendResponse($data, 'My product.', 200);
    }

    public function detailProduct(Request $request, $id)
    {
        $user = $request->user();
        $data = Product::with(['userDetail', 'productPhoto', 'productVariant'])
            ->where('user_id', '=', $user->id)
            ->where('id', '=', $id)
            ->first();
        return $this->sendResponse($data, 'Success');
    }

    public function storeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productName' => 'required',
            'productDesc' => 'required',
            'qty' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Error', 400);
        }
        // Product Revenue
        $settings = Settings::where('name', '=', 'Product Markup')->first();
        $productRevenue = $request['price'] * ($settings->value / 100);

        $store = new Product();
        $store->user_id = $request->user()->id;
        $store->productName = $request['productName'];
        $store->productDesc = $request['productDesc'];
        $store->productQty = $request['qty'];
        $store->productPrice = $request['price'];
        $store->productRevenue = ceil($productRevenue);
        $store->showPrice = $request['price'] + ceil($productRevenue);
        $store->save();

        if ($request['productCategory']) {
            foreach ($request['productCategory'] as $cat) {
                $storeCategory = [
                    'product_id' => $store->id,
                    'category_id' => $cat,
                ];
                ProductCategory::create($storeCategory);
            }
        }

        if ($request['productVariant']) {
            foreach ($request['productVariant'] as $var) {
                $data = new ProductVariant();
                $data->product_id = $store->id;
                $data->tipe = $var['tipe'];
                $data->variantName = $var['variantName'];
                $data->save();
            }
        }

        if ($request->hasFile('productPhoto')) {
            foreach ($request->file('productPhoto') as $image) {
                $name = str_replace(' ', '', $request['productName']) . Carbon::now()->timestamp . '.' . $image->getClientOriginalName();
                $store_path = 'public/fotoProduct';
                $image->storeAs($store_path, $name);

                $storePhoto = [
                    'product_id' => $store->id,
                    'productPicture' => isset($name) ? "/storage/fotoProduct/" . $name : '/storage/img/dummy.jpg',
                ];
                ProductPicture::create($storePhoto);
            }
        } else {
            $storePhoto = [
                'product_id' => $store->id,
                'productPicture' => '/storage/img/dummy.jpg',
            ];
            ProductPicture::create($storePhoto);
        }
        return $this->sendResponse($store, 'Add Product Success.', 201);
    }

    public function updateProduct(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'productName' => 'required',
            'productDesc' => 'required',
            'qty' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Error', 400);
        }

        $store = Product::where('id', '=', $id)->first();
        if ($request['productName']) {
            $store->productName = $request['productName'];
        }
        if ($request['productDesc']) {
            $store->productDesc = $request['productDesc'];
        }
        if ($request['qty']) {
            $store->productQty = $request['qty'];
        }
        if ($request['price']) {
            $store->productPrice = $request['price'];
            // Product Revenue
            $settings = Settings::where('name', '=', 'Product Markup')->first();
            $productRevenue = $request['price'] * ($settings->value / 100);
            $store->productRevenue = ceil($productRevenue);
            $store->showPrice = $request['price'] + ceil($productRevenue);
        }
        $store->update();

        if ($request['productCategory']) {
            $category = ProductCategory::where('product_id', '=', $id)->get();

            if ($category->count() > 0) {
                foreach ($category as $ct) {
                    $ct->delete();
                }
            }

            foreach ($request['productCategory'] as $cat) {
                $storeCategory = [
                    'product_id' => $id,
                    'category_id' => $cat,
                ];
                ProductCategory::create($storeCategory);
            }
        }

        if ($request['productVariant']) {
            $var = ProductVariant::where('product_id', '=', $id)->get();
            if ($var->count() > 0) {
                foreach ($var as $delVar) {
                    $delVar->delete();
                }
            }
            foreach ($request['productVariant'] as $var) {
                $data = new ProductVariant();
                $data->product_id = $id;
                $data->tipe = $var['tipe'];
                $data->variantName = $var['variantName'];
                $data->save();
            }
        }

        if ($request->hasFile('productPhoto')) {
            $pic = ProductPicture::where('product_id', '=', $id)->get();
            if ($pic->count() > 0) {
                foreach ($pic as $picture) {
                    $picture->delete();
                }
            }
            foreach ($request->file('productPhoto') as $image) {
                $name = str_replace(' ', '', $request['productName']) . Carbon::now()->timestamp . '.' . $image->getClientOriginalName();
                $store_path = 'public/fotoProduct';
                $image->storeAs($store_path, $name);

                $storePhoto = [
                    'product_id' => $store->id,
                    'productPicture' => isset($name) ? "/storage/fotoProduct/" . $name : '/storage/img/dummy.jpg',
                ];
                ProductPicture::create($storePhoto);
            }
        }
        return $this->sendResponse($store, 'Success', 200);
    }

    public function requestWithdraw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank' => 'required',
            'no_rek' => 'required|numeric',
            'amount' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validation Error.', 400);
        }

        $validasiWallet = Wallet::where('user_id', '=', $request->user()->id)->first();
        if ($validasiWallet->balance < 500000) {
            return $this->sendError([
                'message' => 'Balance anda tidak mencukupi untuk melakukan penarikan.'
            ], 'Error.', 400);
        }

        if ($request['amount'] < 500000 ) {
            return $this->sendError([
                'message' => 'Amount tidak boleh di bawah 500 ribu'
            ], 'Error.', 400);
        }
        if($request['amount'] > $validasiWallet->balance)
        {
            return $this->sendError([
                'message' => 'Amount yang di tarik lebih besar dari pada balance'
            ], 'Error.', 400);
        }

        $data = new Withdraw();
        $data->user_id = $request->user()->id;
        $data->bank = $request['bank'];
        $data->no_rek = $request['no_rek'];
        $data->amount = $request['amount'];
        $data->status = "Pending";
        $data->save();

        $validasiWallet->balance = $validasiWallet->balance - $request['amount'];
        $validasiWallet->update();

        return $this->sendResponse($data, 'Request withdraw.');
    }

    public function deleteProduct($id)
    {
        $data = Product::where('id', '=', $id)->first();
        $category = ProductCategory::where('product_id', '=', $id)->get();

        if ($category->count() > 0) {
            foreach ($category as $ct) {
                $ct->delete();
            }
        }

        $var = ProductVariant::where('product_id', '=', $id)->get();
        if ($var->count() > 0) {
            foreach ($var as $delVar) {
                $delVar->delete();
            }
        }

        $pic = ProductPicture::where('product_id', '=', $id)->get();
        if ($pic->count() > 0) {
            foreach ($pic as $picture) {
                $picture->delete();
            }
        }
        $data->delete();

        return $this->sendResponse($data,'Success');
    }

    public function listTransaction(Request $request)
    {
        $user = $request->user();
        $data = Transaction::query()->with(array('Product','Payment' => function($query) {
            $query->select('id','external_id','status');
        }))->where('supplier_id','=',$user->id)->get();
        return $this->sendResponse($data,'Success');
    }

    public function listWithdraw(Request $request)
    {
        $data = Withdraw::where('user_id','=',$request->user()->id)->get();
        return $this->sendResponse($data,'Success');
    }
}
