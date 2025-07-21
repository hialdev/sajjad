<?php

namespace App\Http\Controllers;

use App\Models\Bal;
use App\Models\BalUnpack;
use App\Models\User;
use Illuminate\Http\Request;

class BalUnpackController extends Controller
{

    public function add($id){
        $bal = Bal::findOrFail($id);
        $users = User::withoutRole('developer')->get();
        return view('bals.unpack.add', compact('bal', 'users'));
    }

    public function store($id, Request $request){
        $request->validate([
            'image' => 'required|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'user_id' => 'nullable|string|exists:users,id',
            'description' => 'nullable|string',
        ]);
        try {
            $bal = Bal::findOrFail($id);
            if($bal->products->count() <= 0)
                return redirect()->back()->withInput()->with('error', 'Gagal mebongkar Bal, Error: Tidak ada Produk Bal yang tercatat, Edit Bal / Kelola Produk Bal dahulu');

            $balUnpack = new BalUnpack();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('bal_unpacks', 'public');
                $balUnpack->image = $imagePath;
            }
            $balUnpack->bal_id = $id;
            $balUnpack->user_id = $request->get('user_id');
            $balUnpack->description = $request->get('description');
            $balUnpack->save();

            $bal->is_unpack = 1;
            $bal->save(); 

            return redirect()->route('bal.index')->with('success', 'Bal '.$bal->name.' berhasil dibongkar oleh '.$balUnpack->user->name.'.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membongkar Bal, Error: '.$e->getMessage());
        }
    }

    public function edit($id, $unpack_id){
        $bal = Bal::find($id);
        $users = User::withoutRole('developer')->get();
        $unpack = BalUnpack::find($unpack_id);
        return view('bals.unpack.edit', compact('unpack', 'users', 'bal'));
    }

    public function update($id, $unpack_id, Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'user_id' => 'nullable|string|exists:users,id',
            'description' => 'nullable|string',
        ]);

        try {
            $bal = Bal::find($id);

            $balUnpack = BalUnpack::findOrFail($unpack_id);
            if ($request->hasFile('image')) {
                if ($balUnpack->image && file_exists(storage_path('app/public/' . $bal->image))) {
                    unlink(storage_path('app/public/' . $balUnpack->image));
                }
                $imagePath = $request->file('image')->store('bal_unpacks', 'public');
                $balUnpack->image = $imagePath;
            }
            $balUnpack->user_id = $request->get('user_id');
            $balUnpack->description = $request->get('description');
            $balUnpack->save();

            return redirect()->route('bal.index')->with('success', 'Pembongkaran Bal '.$bal->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Pembongakran Bal, Error: '.$e->getMessage());
        }
    }

    public function destroy($id, $unpack_id){
        try {
            $bal = Bal::findOrFail($id);
            $balUnpack = BalUnpack::findOrFail($unpack_id);
            $balUnpack->delete();
            $bal->is_unpack = 0;
            $bal->save();

            return redirect()->route('bal.index')->with('success', 'Pembongakran Bal '.$bal->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Pembongkaran Bal, Error: '.$e->getMessage());
        }
    }
}
