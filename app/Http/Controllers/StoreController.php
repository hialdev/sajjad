<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'name',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'asc',
        ];

        $stores = Store::where('name', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        return view('stores.index', compact('stores', 'filter'));
    }

    public function add(){
        $cities = City::orderBy('city_name', 'asc')->get();
        return view('stores.add', compact('cities'));
    }
    
    public function store(Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.stores,name',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
            'fax' => 'nullable|numeric',
            'description' => 'nullable|string',

            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);
        try {
            $store = new Store();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('stores', 'public');
                $store->image = $imagePath;
            }
            $store->name = $request->get('name');
            $store->email = $request->get('email');
            $store->phone = $request->get('phone');
            $store->fax = $request->get('fax');
            $store->description = $request->get('description');
            
            $store->city = $request->get('city');
            $store->postal_code = $request->get('postal_code');
            $store->address = $request->get('address');
            $store->save();

            return redirect()->route('store.index', $store->id)->with('success', 'Toko '.$store->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan toko, Error: '.$e->getMessage());
        }
    }
    
    public function edit($id){
        $store = Store::findOrFail($id);
        $cities = City::orderBy('city_name', 'asc')->get();
        return view('stores.edit', compact('cities', 'store'));
    }

    public function stock($id){
        $store = Store::findOrFail($id);
        return view('stores.stock', compact('store'));
    }

    public function update($id, Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.stores,name,'.$id,
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
            'fax' => 'nullable|numeric',
            'description' => 'nullable|string',

            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);

        try {
            $store = Store::find($id);
            if ($request->hasFile('image')) {
                if ($store->image && file_exists(storage_path('app/public/' . $store->image))) {
                    unlink(storage_path('app/public/' . $store->image));
                }
                $imagePath = $request->file('image')->store('stores', 'public');
                $store->image = $imagePath;
            }
            $store->name = $request->get('name');
            $store->email = $request->get('email');
            $store->phone = $request->get('phone');
            $store->fax = $request->get('fax');
            $store->description = $request->get('description');
            
            $store->city = $request->get('city');
            $store->postal_code = $request->get('postal_code');
            $store->address = $request->get('address');
            $store->save();


            return redirect()->route('store.index')->with('success', 'Toko '.$store->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui toko, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $store = Store::find($id);
            $store->delete();

            return redirect()->route('store.index')->with('success', 'Toko '.$store->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus toko, Error: '.$e->getMessage());
        }
    }
}
