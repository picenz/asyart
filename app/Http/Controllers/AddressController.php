namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
// Nampilin daftar alamat user
public function index()
{
$addresses = Address::where('user_id', Auth::id())->get();
return view('profile.addresses', compact('addresses'));
}

// Nampilin form edit alamat berdasarkan ID
public function edit($id)
{
$address = Address::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
return view('profile.edit-address', compact('address'));
}

// Proses update data alamat ke database
public function update(Request $request, $id)
{
$request->validate([
'address_name' => 'required|string|max:255',
'full_address' => 'required|string',
'city' => 'required|string',
'postal_code' => 'required|string',
'phone' => 'required|string',
]);

$address = Address::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

$address->update([
'address_name' => $request->address_name,
'full_address' => $request->full_address,
'city' => $request->city,
'postal_code' => $request->postal_code,
'phone' => $request->phone,
]);

return redirect()->route('profile.addresses')->with('success', 'Alamat berhasil diperbarui!');
}
}