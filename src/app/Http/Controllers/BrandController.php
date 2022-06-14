<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Multipic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Image;

class BrandController extends Controller
{
    public function AllBrand()
    {
        $brands = Brand::latest()->paginate(5);
        return view('admin.brand.index', compact('brands'));
    }

    public function StoreBrand(Request $request)
    {
        //バリデーション
        $validatedData = $request->validate([
            'brand_name' => "required|unique:brands|min:4",
            'brand_image' => "required|mimes:jpg,jpeg,png",
        ], [
            'brand_name.required' => "Please Input Brand Name",
            'brand_name.min' => "Brand Longer then 4 Characters",
        ]);

        //画像アップロード
        $brand_image = $request->file('brand_image');

        //ユニークな名前
        // $name_gen = hexdec(uniqid());
        // $img_ext = strtolower($brand_image->getClientOriginalExtension());
        // $img_name = $name_gen . '.' . $img_ext;
        // $up_location = 'image/brand/';
        // $last_img = $up_location . $img_name;
        // $brand_image->move($up_location, $img_name);

        //ユニーク名生成→サイズ調整→ファイルへ保存
        $name_gen = hexdec(uniqid()) . '.' . $brand_image->getClientOriginalExtension();
        Image::make($brand_image)->resize(300, 200)->save('image/brand/' . $name_gen);

        //DB保存する際の名前
        $last_img = "image/brand/" . $name_gen;

        Brand::insert([
            'brand_name' => $request->brand_name,
            'brand_image' => $last_img,
            'created_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Brand Inserted Successfully');
    }


    public function Edit($id)
    {
        $brands = Brand::find($id);
        return view('admin.brand.edit', compact('brands'));
    }

    public function Update(Request $request, $id)
    {
        //バリデーション
        $validatedData = $request->validate([
            'brand_name' => "required|min:4",
        ], [
            'brand_name.required' => "Please Input Brand Name",
            'brand_name.min' => "Brand Longer then 4 Characters",
        ]);

        //更新前の画像
        $old_image = $request->old_image;

        //画像アップロード
        $brand_image = $request->file('brand_image');

        //画像付き・画像なしのデータ更新
        //画像が更新されたら全部更新
        if ($brand_image) {
            //ユニークな名前
            $name_gen = hexdec(uniqid());
            $img_ext = strtolower($brand_image->getClientOriginalExtension());
            $img_name = $name_gen . '.' . $img_ext;
            //保存場所
            $up_location = 'image/brand/';
            //DB保存名
            $last_img = $up_location . $img_name;
            //ファイルの保存先
            $brand_image->move($up_location, $img_name);

            unlink($old_image);
            Brand::find($id)->update([
                'brand_name' => $request->brand_name,
                'brand_image' => $last_img,
                'created_at' => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'Brand Update Successfully');
        } else {
            Brand::find($id)->update([
                'brand_name' => $request->brand_name,
                'created_at' => Carbon::now(),
            ]);
            return redirect()->back()->with('success', 'Brand Update Successfully');
        }
    }


    public function Delete($id)
    {
        //ファイル内の画像を含む削除
        $image = Brand::find($id);
        $old_image = $image->brand_image;
        unlink($old_image);

        Brand::find($id)->delete();
        return redirect()->back()->with('success', 'Brand Delete Successfully');
    }




    // This is for Multi Image All Methods

    public function MultiPic()
    {
        $images = Multipic::all();
        return view('admin.multipic.index', compact('images'));
    }


    //複数画像アップロード
    public function StoreImage(Request $request)
    {
        //画像アップロード
        $image = $request->file('image');

        foreach ($image as $multi_img) {
            //ユニーク名生成→サイズ調整→ファイルへ保存
            $name_gen = hexdec(uniqid()) . '.' . $multi_img->getClientOriginalExtension();
            Image::make($multi_img)->resize(300, 300)->save('image/multi/' . $name_gen);

            //DB保存する際の名前
            $last_img = "image/multi/" . $name_gen;

            Multipic::insert([
                'image' => $last_img,
                'created_at' => Carbon::now(),
            ]);
        } //end of the foreach
        return redirect()->back()->with('success', 'Brand Inserted Successfully');
    }
}
