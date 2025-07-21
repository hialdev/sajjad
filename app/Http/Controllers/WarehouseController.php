<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'name',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'asc',
        ];

        $warehouses = Warehouse::where('name', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        return view('warehouses.index', compact('warehouses', 'filter'));
    }

    public function add(){
        $cities = City::orderBy('city_name', 'asc')->get();
        return view('warehouses.add', compact('cities'));
    }

    public function store(Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.warehouses,name',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
            'fax' => 'nullable|numeric',
            'description' => 'nullable|string',

            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);
        try {
            $warehouse = new Warehouse();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('warehouses', 'public');
                $warehouse->image = $imagePath;
            }
            $warehouse->name = $request->get('name');
            $warehouse->email = $request->get('email');
            $warehouse->phone = $request->get('phone');
            $warehouse->fax = $request->get('fax');
            $warehouse->description = $request->get('description');
            
            $warehouse->city = $request->get('city');
            $warehouse->postal_code = $request->get('postal_code');
            $warehouse->address = $request->get('address');
            $warehouse->save();

            return redirect()->route('warehouse.index', $warehouse->id)->with('success', 'Gudang '.$warehouse->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan warehouse, Error: '.$e->getMessage());
        }
    }
    
    public function edit($id){
        $warehouse = Warehouse::findOrFail($id);
        $cities = City::orderBy('city_name', 'asc')->get();
        return view('warehouses.edit', compact('cities', 'warehouse'));
    }

    public function stock($id){
        $warehouse = Warehouse::findOrFail($id);
        return view('warehouses.stock', compact('warehouse'));
    }

    public function update($id, Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.warehouses,name,'.$id,
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
            'fax' => 'nullable|numeric',
            'description' => 'nullable|string',

            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);

        try {
            $warehouse = Warehouse::find($id);
            if ($request->hasFile('image')) {
                if ($warehouse->image && file_exists(storage_path('app/public/' . $warehouse->image))) {
                    unlink(storage_path('app/public/' . $warehouse->image));
                }
                $imagePath = $request->file('image')->store('warehouses', 'public');
                $warehouse->image = $imagePath;
            }
            $warehouse->name = $request->get('name');
            $warehouse->email = $request->get('email');
            $warehouse->phone = $request->get('phone');
            $warehouse->fax = $request->get('fax');
            $warehouse->description = $request->get('description');
            
            $warehouse->city = $request->get('city');
            $warehouse->postal_code = $request->get('postal_code');
            $warehouse->address = $request->get('address');
            $warehouse->save();


            return redirect()->route('warehouse.index')->with('success', 'Gudang '.$warehouse->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui warehouse, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $warehouse = Warehouse::find($id);
            $warehouse->delete();

            return redirect()->route('warehouse.index')->with('success', 'Gudang '.$warehouse->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus warehouse, Error: '.$e->getMessage());
        }
    }
}
