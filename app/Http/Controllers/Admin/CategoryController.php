<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required'
        ]);

        if ($request->hasFile('logo')){
            if ($request->file('logo')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('logo')->getClientOriginalExtension();
                $store_path = 'public/fotoKategori';
                $request->file('logo')->storeAs($store_path,$name);
            }
        }

        $store = [
            'name' => $request['name'],
            'logo' => isset($name) ? "/storage/fotoKategori/".$name : '/storage/img/dummy.jpg',
        ];
        Category::create($store);

        return redirect()->route('admin.category')->with('message','Category is successfully created');
    }

    public function edit($id)
    {
        $data = Category::find($id);
        return view('admin.category.edit',compact('data'));
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name' => 'required'
        ]);

        if ($request->hasFile('logo')){
            if ($request->file('logo')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('logo')->getClientOriginalExtension();
                $store_path = 'public/fotoKategori';
                $request->file('logo')->storeAs($store_path,$name);
            }
        }

        $data = Category::where('id','=',$id)->first();
        if($request['name'])
        {
            $data->name = $request['name'];
        }
        if ($request->hasFile('logo')) {

            if($data->logo !== "/storage/img/dummy.jpg")
            {
                $images_path = public_path().$data->logo;
                unlink($images_path);
            }
            $data->logo = isset($name) ? "/storage/fotoKategori/" . $name : '/storage/img/dummy.jpg';
        }
        $data->update();

        return redirect()->route('admin.category')->with('message','Category is successfully updated');
    }

    public function delete($id)
    {
        $data = Category::where('id','=',$id)->first();
        if($data->logo !== "/storage/img/dummy.jpg")
        {
            $images_path = public_path().$data->logo;
            unlink($images_path);
        }
        $data->delete();

        return redirect()->route('admin.category')->with('message','Category is successfully deleted');
    }
}
