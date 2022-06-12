<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function AllCat()
    {
        // $categories = DB::table('categories')
        //     ->join('users', 'categories.user_id', 'users.id')
        //     ->select('categories.*', 'users.name')
        //     ->latest()->paginate(5);

        $categories = Category::latest()->paginate(5);

        //クエリビルダによるデータ
        // $categories = DB::table('categories')->latest()->paginate(5);
        return view('admin.category.index', compact('categories'));
    }

    public function AddCat(Request $request)
    {
        //バリデーション
        $validatedData = $request->validate([
            'category_name' => "required|unique:categories|max:255",
        ], [
            'category_name.required' => "Please Input Category Name",
            'category_name.max' => "Category Less Then 255 characters",
        ]);

        //Categoryモデル
        Category::insert([
            'category_name' => $request->category_name,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);

        //insert
        // $category = new Category;
        // $category->category_name = $request->category_name;
        // $category->user_id = Auth::user()->id;
        // $category->save();

        //クエリビルダによるデータの挿入
        // $data = array();
        // $data['category_name'] = $request->category_name;
        // $data['user_id'] = Auth::user()->id;
        // DB::table('categories')->insert($data);

        return redirect()->back()->with('success', 'category Inserted Successfully');
    }


    public function Edit($id)
    {
        // $categories = Category::find($id);

        //クエリビルダ
        $categories = DB::table('categories')->where('id', $id)->first();
        return view('admin/category/edit', compact('categories'));
    }

    public function Update(Request $request, $id)
    {
        // $update = Category::find($id)->update([
        //     'category_name' => $request->category_name,
        //     'user_id' => Auth::user()->id
        // ]);

        //クエリビルダ
        $data = array();
        $data['category_name'] = $request->category_name;
        $data['user_id'] = Auth::user()->id;
        DB::table('categories')->where('id', $id)->update($data);

        return redirect()->route('all.category')->with('success', 'category Updated Successfully');
    }
}
