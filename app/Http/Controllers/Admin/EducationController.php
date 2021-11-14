<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index()
    {
        $data = Education::orderBy('group','ASC')->get();
        return view('admin.edukasi.index',compact('data'));
    }

    public function create()
    {
        return view('admin.edukasi.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'group' => 'required',
            'title' => 'required',
            'url' => 'required'
        ]);

        $store = new Education();
        $store->group = $request['group'];
        $store->title = $request['title'];
        $store->url_youtube = $request['url'];
        $store->save();

        return redirect()->route('admin.education')->with('message','Success add education');
    }

    public function edit($id)
    {
        $data = Education::find($id);
        return view('admin.edukasi.edit',compact('data'));
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'group' => 'required',
            'title' => 'required',
            'url' => 'required'
        ]);

        $updt = Education::where('id','=',$id)->first();
        $updt->group = $request['group'];
        $updt->title = $request['title'];
        $updt->url_youtube = $request['url'];
        $updt->update();

        return redirect()->route('admin.education')->with('message','Success update education');
    }

    public function delete($id)
    {
        $del = Education::where('id','=',$id)->first();
        $del->delete();
        return redirect()->route('admin.education')->with('message','Success delete education');
    }
}
