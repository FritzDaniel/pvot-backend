<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    public function create()
    {
        return view('admin.testimony.create');
    }

    public function edit($id)
    {
        $data = Testimoni::where('id','=',$id)->first();
        return view('admin.testimony.edit',compact('data'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'age' => 'required|numeric',
            'testimony' => 'required',
            'company' => 'required'
        ]);

        try {

            if ($request->hasFile('photo')){
                if ($request->file('photo')->isValid()){
                    $name = Carbon::now()->timestamp.'.'.$request->file('photo')->getClientOriginalExtension();
                    $store_path = 'public/fotoTestimoni';
                    $request->file('photo')->move($store_path,$name);
                }
            }

            $store = new Testimoni();
            $store->photo = isset($name) ? "/storage/fotoTestimoni/".$name : '/storage/img/dummyUser.jpg';
            $store->name = $request['name'];
            $store->age = $request['age'];
            $store->testimoni = $request['testimony'];
            $store->company = $request['company'];
            $store->save();

            return redirect()->route('admin.testimony')->with('message','Testimony is successfully created');

        } catch (\Exception $e)
        {
            return redirect()->back()->with('error','Testimony is failed to created');
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'age' => 'required|numeric',
            'testimony' => 'required',
            'company' => 'required'
        ]);

        if ($request->hasFile('photo')){
            if ($request->file('photo')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('photo')->getClientOriginalExtension();
                $store_path = 'public/fotoTestimoni';
                $request->file('photo')->storeAs($store_path,$name);
            }
        }

        $data = Testimoni::where('id','=',$id)->first();
        if ($request->hasFile('photo')) {

            if($data->photo !== "/storage/img/dummyUser.jpg")
            {
                $images_path = public_path().$data->photo;
                unlink($images_path);
            }
            $data->photo = isset($name) ? "/storage/fotoTestimoni/" . $name : '/storage/img/dummyUser.jpg';
        }
        $data->name = $request['name'];
        $data->age = $request['age'];
        $data->testimoni = $request['testimony'];
        $data->company = $request['company'];
        $data->update();

        return redirect()->route('admin.testimony')->with('message','Testimony is successfully updated');
    }

    public function delete($id)
    {
        $data = Testimoni::where('id','=',$id)->first();
        if($data->photo !== "/storage/img/dummyUser.jpg")
        {
            $images_path = public_path().$data->photo;
            unlink($images_path);
        }
        $data->delete();

        return redirect()->route('admin.testimony')->with('message','Testimony is successfully deleted');
    }
}
