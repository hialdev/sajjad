<?php

use App\Http\Controllers\AJAXController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BalController;
use App\Http\Controllers\BalUnpackController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ClientAddressController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientPicController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\CustomerOrderProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LogisticAddressController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\PartnerAddressController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PartnerPicController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PrincipalAddressController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\PrincipalPicController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderFileController;
use App\Http\Controllers\PurchaseOrderInvoiceController;
use App\Http\Controllers\PurchaseOrderPaymentController;
use App\Http\Controllers\PurchaseOrderProductController;
use App\Http\Controllers\PurchaseReceiveController;
use App\Http\Controllers\PurchaseReceiveProductController;
use App\Http\Controllers\RequestOrderController;
use App\Http\Controllers\RequestOrderFileController;
use App\Http\Controllers\RequestOrderInvoiceController;
use App\Http\Controllers\RequestOrderPaymentController;
use App\Http\Controllers\RequestOrderProductController;
use App\Http\Controllers\RequestProcessController;
use App\Http\Controllers\RequestProcessProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockProductController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TransportInvoiceController;
use App\Http\Controllers\TransportPaymentController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function(){
//     return redirect()->route('home');
// });
Route::get('/app.css', function () {
    $theme = config('al.theme'); // Memuat konfigurasi tema
    return response()
        ->view('styles.app', ['theme' => $theme])
        ->header('Content-Type', 'text/css');
});

// -------------- Auth Routes ----------------
Route::get('/login', function () {
    Session::put('prev_url', env('APP_URL'));
    return redirect()->to(urlApp('ACC', '/login'));
})->name('login');
Route::get('/register', function () {
    Session::put('prev_url', env('APP_URL'));
    return redirect()->to(urlApp('ACC', '/register'));
})->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
//Auth::routes();
// -------------- End Auth Routes ----------------

// -------------- Unauthenticated routes ------------------
Route::middleware(['auth', 'check.app.permission'])->group(function(){
    // --------------------------------
    // AJAX
    // --------------------------------
    Route::post('/ajax/showBy', [AJAXController::class, 'showBy'])->name('ajax.showBy');
    Route::post('/ajax/showRelation', [AJAXController::class, 'showRelation'])->name('ajax.showRelation');

    // --------------------------------
    // Routes
    // --------------------------------
    
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::get('/profile', function(){ return redirect()->to(urlApp('ACC','/profile')); })->name('profile.index');

    Route::get('/stock/analytic', [StockController::class, 'analytic'])->name('stock.index');
    Route::get('/stock/distributions', [StockController::class, 'index'])->name('stock.distribution');
    Route::get('/stock/move', [StockController::class, 'move'])->name('stock.move');
    Route::post('/stock/move', [StockController::class, 'moved'])->name('stock.moved');
    Route::get('/stock/move/{id}/setting', [StockController::class, 'setting'])->name('stock.setting');
    Route::post('/stock/move/{id}/edit', [StockController::class, 'moveUpdate'])->name('stock.update');
    Route::post('/stock/move/{id}/addcart', [StockController::class, 'addCart'])->name('stock.addCart');
    Route::post('/stock/move/{id}/removecart', [StockController::class, 'removeCart'])->name('stock.removeCart');
    Route::post('/stock/{id}/process',[StockController::class, 'process'])->name('stock.process');
    Route::delete('/stock/move/{id}/destroy', [StockController::class, 'moveDestroy'])->name('stock.destroy');
    Route::post('/stock/{id}/product/store',[StockProductController::class, 'store'])->name('stock.product.store');

    Route::get('/unit',[UnitController::class, 'index'])->name('unit.index');
    Route::post('/unit/store',[UnitController::class, 'store'])->name('unit.store');
    Route::post('/unit/{id}/update',[UnitController::class, 'update'])->name('unit.update');
    Route::delete('/unit/{id}/destroy',[UnitController::class, 'destroy'])->name('unit.destroy');

    Route::get('/pack',[PackController::class, 'index'])->name('pack.index');
    Route::post('/pack/store',[PackController::class, 'store'])->name('pack.store');
    Route::post('/pack/{id}/update',[PackController::class, 'update'])->name('pack.update');
    Route::delete('/pack/{id}/destroy',[PackController::class, 'destroy'])->name('pack.destroy');

    Route::get('/payment',[PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/add',[PaymentController::class, 'add'])->name('payment.add');
    Route::post('/payment/store',[PaymentController::class, 'store'])->name('payment.store');
    Route::get('/payment/{id}/edit',[PaymentController::class, 'edit'])->name('payment.edit');
    Route::post('/payment/{id}/update',[PaymentController::class, 'update'])->name('payment.update');
    Route::delete('/payment/{id}/destroy',[PaymentController::class, 'destroy'])->name('payment.destroy');

    Route::get('/bal',[BalController::class, 'index'])->name('bal.index');
    Route::get('/bal/add',[BalController::class, 'add'])->name('bal.add');
    Route::post('/bal/store',[BalController::class, 'store'])->name('bal.store');
    Route::get('/bal/{id}/edit',[BalController::class, 'edit'])->name('bal.edit');
    Route::post('/bal/{id}/update',[BalController::class, 'update'])->name('bal.update');
    Route::delete('/bal/{id}/destroy',[BalController::class, 'destroy'])->name('bal.destroy');

    Route::get('/bal/{id}/unpack/add',[BalUnpackController::class, 'add'])->name('bal.unpack.add');
    Route::post('/bal/{id}/unpack/store',[BalUnpackController::class, 'store'])->name('bal.unpack.store');
    Route::get('/bal/{id}/unpack/{unpack_id}/edit',[BalUnpackController::class, 'edit'])->name('bal.unpack.edit');
    Route::post('/bal/{id}/unpack/{unpack_id}/update',[BalUnpackController::class, 'update'])->name('bal.unpack.update');
    Route::delete('/bal/{id}/unpack/{unpack_id}/destroy',[BalUnpackController::class, 'destroy'])->name('bal.unpack.destroy');

    Route::get('/product-type',[ProductTypeController::class, 'index'])->name('product_type.index');
    Route::get('/product-type/add',[ProductTypeController::class, 'add'])->name('product_type.add');
    Route::post('/product-type/store',[ProductTypeController::class, 'store'])->name('product_type.store');
    Route::get('/product-type/{id}/edit',[ProductTypeController::class, 'edit'])->name('product_type.edit');
    Route::post('/product-type/{id}/update',[ProductTypeController::class, 'update'])->name('product_type.update');
    Route::delete('/product-type/{id}/destroy',[ProductTypeController::class, 'destroy'])->name('product_type.destroy');

    Route::get('/product',[ProductController::class, 'index'])->name('product.index');
    Route::get('/product/length',[ProductController::class, 'qtyStockByLength'])->name('product.checklength');
    Route::get('/product/add',[ProductController::class, 'add'])->name('product.add');
    Route::post('/product/store',[ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{id}/stock',[ProductController::class, 'stock'])->name('product.stock');
    Route::get('/product/{id}/edit',[ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/{id}/update',[ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}/destroy',[ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('/store',[StoreController::class, 'index'])->name('store.index');
    Route::get('/store/add',[StoreController::class, 'add'])->name('store.add');
    Route::post('/store/store',[StoreController::class, 'store'])->name('store.store');
    Route::get('/store/{id}/edit',[StoreController::class, 'edit'])->name('store.edit');
    Route::get('/store/{id}/stock',[StoreController::class, 'stock'])->name('store.stock');
    Route::post('/store/{id}/update',[StoreController::class, 'update'])->name('store.update');
    Route::delete('/store/{id}/destroy',[StoreController::class, 'destroy'])->name('store.destroy');
    
    Route::get('/warehouse',[WarehouseController::class, 'index'])->name('warehouse.index');
    Route::get('/warehouse/add',[WarehouseController::class, 'add'])->name('warehouse.add');
    Route::post('/warehouse/store',[WarehouseController::class, 'store'])->name('warehouse.store');
    Route::get('/warehouse/{id}/edit',[WarehouseController::class, 'edit'])->name('warehouse.edit');
    Route::get('/warehouse/{id}/stock',[WarehouseController::class, 'stock'])->name('warehouse.stock');
    Route::post('/warehouse/{id}/update',[WarehouseController::class, 'update'])->name('warehouse.update');
    Route::delete('/warehouse/{id}/destroy',[WarehouseController::class, 'destroy'])->name('warehouse.destroy');

    Route::get('/principal',[PrincipalController::class, 'index'])->name('principal.index');
    Route::get('/principal/add',[PrincipalController::class, 'add'])->name('principal.add');
    Route::post('/principal/store',[PrincipalController::class, 'store'])->name('principal.store');
    Route::get('/principal/{id}/setting',[PrincipalController::class, 'setting'])->name('principal.setting');
    Route::post('/principal/{id}/update',[PrincipalController::class, 'update'])->name('principal.update');
    Route::delete('/principal/{id}/destroy',[PrincipalController::class, 'destroy'])->name('principal.destroy');
    
    Route::post('/principal/{id}/address/store',[PrincipalAddressController::class, 'store'])->name('principal.address.store');
    Route::post('/principal/{id}/address/{address_id}/update',[PrincipalAddressController::class, 'update'])->name('principal.address.update');
    Route::delete('/principal/{id}/address/{address_id}/destroy',[PrincipalAddressController::class, 'destroy'])->name('principal.address.destroy');

    Route::post('/principal/{id}/pic/store',[PrincipalPicController::class, 'store'])->name('principal.pic.store');
    Route::post('/principal/{id}/pic/{pic_id}/update',[PrincipalPicController::class, 'update'])->name('principal.pic.update');
    Route::delete('/principal/{id}/pic/{pic_id}/destroy',[PrincipalPicController::class, 'destroy'])->name('principal.pic.destroy');

    Route::get('/partner',[PartnerController::class, 'index'])->name('partner.index');
    Route::get('/partner/add',[PartnerController::class, 'add'])->name('partner.add');
    Route::post('/partner/store',[PartnerController::class, 'store'])->name('partner.store');
    Route::get('/partner/{id}/setting',[PartnerController::class, 'setting'])->name('partner.setting');
    Route::post('/partner/{id}/update',[PartnerController::class, 'update'])->name('partner.update');
    Route::delete('/partner/{id}/destroy',[PartnerController::class, 'destroy'])->name('partner.destroy');
    
    Route::post('/partner/{id}/address/store',[PartnerAddressController::class, 'store'])->name('partner.address.store');
    Route::post('/partner/{id}/address/{address_id}/update',[PartnerAddressController::class, 'update'])->name('partner.address.update');
    Route::delete('/partner/{id}/address/{address_id}/destroy',[PartnerAddressController::class, 'destroy'])->name('partner.address.destroy');

    Route::post('/partner/{id}/pic/store',[PartnerPicController::class, 'store'])->name('partner.pic.store');
    Route::post('/partner/{id}/pic/{pic_id}/update',[PartnerPicController::class, 'update'])->name('partner.pic.update');
    Route::delete('/partner/{id}/pic/{pic_id}/destroy',[PartnerPicController::class, 'destroy'])->name('partner.pic.destroy');

    Route::get('/client',[ClientController::class, 'index'])->name('client.index');
    Route::get('/client/add',[ClientController::class, 'add'])->name('client.add');
    Route::post('/client/store',[ClientController::class, 'store'])->name('client.store');
    Route::get('/client/{id}/setting',[ClientController::class, 'setting'])->name('client.setting');
    Route::post('/client/{id}/update',[ClientController::class, 'update'])->name('client.update');
    Route::delete('/client/{id}/destroy',[ClientController::class, 'destroy'])->name('client.destroy');
    
    Route::post('/client/{id}/address/store',[ClientAddressController::class, 'store'])->name('client.address.store');
    Route::post('/client/{id}/address/{address_id}/update',[ClientAddressController::class, 'update'])->name('client.address.update');
    Route::delete('/client/{id}/address/{address_id}/destroy',[ClientAddressController::class, 'destroy'])->name('client.address.destroy');

    Route::post('/client/{id}/pic/store',[ClientPicController::class, 'store'])->name('client.pic.store');
    Route::post('/client/{id}/pic/{pic_id}/update',[ClientPicController::class, 'update'])->name('client.pic.update');
    Route::delete('/client/{id}/pic/{pic_id}/destroy',[ClientPicController::class, 'destroy'])->name('client.pic.destroy');

    Route::get('/customer',[CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/add',[CustomerController::class, 'add'])->name('customer.add');
    Route::post('/customer/store',[CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customer/{id}/setting',[CustomerController::class, 'setting'])->name('customer.setting');
    Route::post('/customer/{id}/update',[CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customer/{id}/destroy',[CustomerController::class, 'destroy'])->name('customer.destroy');

    Route::get('/logistic',[LogisticController::class, 'index'])->name('logistic.index');
    Route::get('/logistic/add',[LogisticController::class, 'add'])->name('logistic.add');
    Route::post('/logistic/store',[LogisticController::class, 'store'])->name('logistic.store');
    Route::get('/logistic/{id}/setting',[LogisticController::class, 'setting'])->name('logistic.setting');
    Route::post('/logistic/{id}/update',[LogisticController::class, 'update'])->name('logistic.update');
    Route::post('/logistic/{id}/cp',[LogisticController::class, 'cp'])->name('logistic.cp');
    Route::delete('/logistic/{id}/destroy',[LogisticController::class, 'destroy'])->name('logistic.destroy');
    
    Route::post('/logistic/{id}/address/store',[LogisticAddressController::class, 'store'])->name('logistic.address.store');
    Route::post('/logistic/{id}/address/{address_id}/update',[LogisticAddressController::class, 'update'])->name('logistic.address.update');
    Route::delete('/logistic/{id}/address/{address_id}/destroy',[LogisticAddressController::class, 'destroy'])->name('logistic.address.destroy');
    
    // -------------------------------------
    // Business Process
    // -------------------------------------
    Route::get('/purchase-order',[PurchaseOrderController::class, 'index'])->name('purchase-order.index');
    Route::get('/purchase-order/add',[PurchaseOrderController::class, 'add'])->name('purchase-order.add');
    Route::post('/purchase-order/store',[PurchaseOrderController::class, 'store'])->name('purchase-order.store');
    Route::get('/purchase-order/{id}/setting',[PurchaseOrderController::class, 'setting'])->name('purchase-order.setting');
    Route::post('/purchase-order/{id}/update',[PurchaseOrderController::class, 'update'])->name('purchase-order.update');
    Route::post('/purchase-order/{id}/process',[PurchaseOrderController::class, 'process'])->name('purchase-order.process');
    Route::post('/purchase-order/{id}/addCart',[PurchaseOrderController::class, 'addCart'])->name('purchase-order.addCart');
    Route::post('/purchase-order/{id}/refetch',[PurchaseOrderController::class, 'refetch'])->name('purchase-order.refetch');
    Route::post('/purchase-order/{id}/removeCart',[PurchaseOrderController::class, 'removeCart'])->name('purchase-order.removeCart');
    Route::delete('/purchase-order/{id}/destroy',[PurchaseOrderController::class, 'destroy'])->name('purchase-order.destroy');
    
    Route::post('/purchase-order/{id}/invoice',[PurchaseOrderController::class, 'invoice'])->name('purchase-order.generate');
    Route::post('/purchase-order/{id}/invoiceClientPartial',[PurchaseOrderController::class, 'invoiceClientPartial'])->name('purchase-order.generatePartialClient');

    Route::post('/purchase-order/{id}/product/store',[PurchaseOrderProductController::class, 'store'])->name('purchase-order.product.store');

    Route::post('/purchase-order/{id}/file/store',[PurchaseOrderFileController::class, 'store'])->name('purchase-order.file.store');
    Route::delete('/purchase-order/{id}/file/{file_id}/destroy',[PurchaseOrderFileController::class, 'destroy'])->name('purchase-order.file.destroy');
    
    Route::get('/receive',[PurchaseReceiveController::class, 'index'])->name('receive.index');
    Route::get('/receive/add',[PurchaseReceiveController::class, 'add'])->name('receive.add');
    Route::post('/receive/store',[PurchaseReceiveController::class, 'store'])->name('receive.store');
    Route::get('/receive/{id}/setting',[PurchaseReceiveController::class, 'setting'])->name('receive.setting');
    Route::post('/receive/{id}/update',[PurchaseReceiveController::class, 'update'])->name('receive.update');
    Route::delete('/receive/{id}/destroy',[PurchaseReceiveController::class, 'destroy'])->name('receive.destroy');
    Route::post('/receive/{id}/product/add',[PurchaseReceiveProductController::class, 'add'])->name('receive.product.add');
    Route::post('/receive/{id}/process',[PurchaseReceiveController::class, 'process'])->name('receive.process');
    Route::get('/receive/{id}/products',[PurchaseReceiveController::class, 'products'])->name('receive.products');


    Route::get('/invoice/purchase-order',[PurchaseOrderInvoiceController::class, 'index'])->name('purchase-order.invoice');
    Route::post('/invoice/purchase-order',[PurchaseOrderInvoiceController::class, 'store'])->name('purchase-order.invoice.store');
    Route::get('/invoice/purchase-order/{id}',[PurchaseOrderInvoiceController::class, 'show'])->name('purchase-order.invoice.show');
    Route::delete('/invoice/purchase-order/{id}/destroy',[PurchaseOrderInvoiceController::class, 'destroy'])->name('purchase-order.invoice.destroy');
    Route::post('/invoice/purchase-order/{id}/payment/store',[PurchaseOrderPaymentController::class, 'store'])->name('purchase-order.payment.store');
    Route::delete('/invoice/purchase-order/{id}/payment/{pay_id}/delete',[PurchaseOrderPaymentController::class, 'destroy'])->name('purchase-order.payment.destroy');

    Route::get('/request-order',[RequestOrderController::class, 'index'])->name('request-order.index');
    Route::get('/request-order/add',[RequestOrderController::class, 'add'])->name('request-order.add');
    Route::post('/request-order/store',[RequestOrderController::class, 'store'])->name('request-order.store');
    Route::get('/request-order/{id}/edit',[RequestOrderController::class, 'edit'])->name('request-order.edit');
    Route::get('/request-order/{id}/setting',[RequestOrderController::class, 'setting'])->name('request-order.setting');
    Route::post('/request-order/{id}/update',[RequestOrderController::class, 'update'])->name('request-order.update');
    Route::post('/request-order/{id}/addCart',[RequestOrderController::class, 'addCart'])->name('request-order.addCart');
    Route::post('/request-order/{id}/refetch',[RequestOrderController::class, 'refetch'])->name('request-order.refetch');
    Route::post('/request-order/{id}/removeCart',[RequestOrderController::class, 'removeCart'])->name('request-order.removeCart');
    Route::delete('/request-order/{id}/destroy',[RequestOrderController::class, 'destroy'])->name('request-order.destroy');

    Route::get('/request-order/{id}/invoice',[RequestOrderInvoiceController::class, 'streamPdf'])->name('request-order.getInvoice');
    Route::post('/request-order/{id}/invoice',[RequestOrderController::class, 'invoice'])->name('request-order.generate');

    Route::post('/request-order/{id}/product/store',[RequestOrderProductController::class, 'store'])->name('request-order.product.store');

    Route::post('/request-order/{id}/file/store',[RequestOrderFileController::class, 'store'])->name('request-order.file.store');
    Route::delete('/request-order/{id}/file/{file_id}/destroy',[RequestOrderFileController::class, 'destroy'])->name('request-order.file.destroy');

    Route::get('/request-process',[RequestProcessController::class, 'index'])->name('request-process.index');
    Route::get('/request-process/add',[RequestProcessController::class, 'add'])->name('request-process.add');
    Route::post('/request-process/store',[RequestProcessController::class, 'store'])->name('request-process.store');
    Route::get('/request-process/{id}/setting',[RequestProcessController::class, 'setting'])->name('request-process.setting');
    Route::post('/request-process/{id}/update',[RequestProcessController::class, 'update'])->name('request-process.update');
    Route::post('/request-process/{id}/process',[RequestProcessController::class, 'process'])->name('request-process.process');
    Route::post('/request-process/{id}/addCart',[RequestProcessController::class, 'addCart'])->name('request-process.addCart');
    Route::post('/request-process/{id}/refetch',[RequestProcessController::class, 'refetch'])->name('request-process.refetch');
    Route::post('/request-process/{id}/removeCart',[RequestProcessController::class, 'removeCart'])->name('request-process.removeCart');
    Route::delete('/request-process/{id}/destroy',[RequestProcessController::class, 'destroy'])->name('request-process.destroy');
    
    Route::post('/request-process/{id}/invoice',[RequestProcessController::class, 'invoice'])->name('request-process.generate');
    Route::post('/request-process/{id}/invoiceClientPartial',[RequestProcessController::class, 'invoiceClientPartial'])->name('request-process.generatePartialClient');

    Route::post('/request-process/{id}/product/store',[RequestProcessProductController::class, 'store'])->name('request-process.product.store');

    Route::get('/invoice/request-order',[RequestOrderInvoiceController::class, 'index'])->name('request-order.invoice');
    Route::post('/invoice/request-order',[RequestOrderInvoiceController::class, 'store'])->name('request-order.invoice.store');
    Route::get('/invoice/request-order/{id}',[RequestOrderInvoiceController::class, 'show'])->name('request-order.invoice.show');
    Route::delete('/invoice/request-order/{id}/destroy',[RequestOrderInvoiceController::class, 'destroy'])->name('request-order.invoice.destroy');
    Route::post('/invoice/request-order/{id}/payment/store',[RequestOrderPaymentController::class, 'store'])->name('request-order.payment.store');
    Route::delete('/invoice/request-order/{id}/payment/{pay_id}/destroy',[RequestOrderPaymentController::class, 'destroy'])->name('request-order.payment.destroy');
    
    Route::get('/customer-order',[CustomerOrderController::class, 'index'])->name('customer-order.index');
    Route::get('/customer-order/add',[CustomerOrderController::class, 'add'])->name('customer-order.add');
    Route::post('/customer-order/store',[CustomerOrderController::class, 'store'])->name('customer-order.store');
    Route::get('/customer-order/{id}/edit',[CustomerOrderController::class, 'edit'])->name('customer-order.edit');
    Route::get('/customer-order/{id}/setting',[CustomerOrderController::class, 'setting'])->name('customer-order.setting');
    Route::post('/customer-order/{id}/update',[CustomerOrderController::class, 'update'])->name('customer-order.update');
    Route::post('/customer-order/{id}/confirm',[CustomerOrderController::class, 'confirmPayment'])->name('customer-order.confirmPayment');
    Route::post('/customer-order/{id}/addCart',[CustomerOrderController::class, 'addCart'])->name('customer-order.addCart');
    Route::post('/customer-order/{id}/refetch',[CustomerOrderController::class, 'refetch'])->name('customer-order.refetch');
    Route::post('/customer-order/{id}/removeCart',[CustomerOrderController::class, 'removeCart'])->name('customer-order.removeCart');
    Route::delete('/customer-order/{id}/destroy',[CustomerOrderController::class, 'destroy'])->name('customer-order.destroy');

    Route::get('/customer-order/{id}/invoice',[InvoiceController::class, 'streamPdf'])->name('customer-order.invoice');

    Route::post('/customer-order/{id}/product/store',[CustomerOrderProductController::class, 'store'])->name('customer-order.product.store');
    Route::get('/customer-order/partner-debt',[CustomerOrderController::class, 'pdebtIndex'])->name('customer-order.partner.index');
    Route::post('/customer-order/{id}/partner-debt/{debt_id}',[CustomerOrderController::class, 'pdebtPay'])->name('customer-order.partner.pay');


    Route::get('/let-see-the/pdf/{id}', [PDFController::class, 'debug'])->name('pdf.debug');

    Route::get('/transport',[TransportController::class, 'index'])->name('transport.index');
    Route::post('/transport/store',[TransportController::class, 'store'])->name('transport.store');
    Route::post('/transport/{id}/update',[TransportController::class, 'update'])->name('transport.update');
    Route::delete('/transport/{id}/destroy',[TransportController::class, 'destroy'])->name('transport.destroy');
    
    Route::get('/invoice/transport',[TransportInvoiceController::class, 'index'])->name('transport.invoice');
    Route::post('/invoice/transport',[TransportInvoiceController::class, 'store'])->name('transport.invoice.store');
    Route::get('/invoice/transport/{id}',[TransportInvoiceController::class, 'show'])->name('transport.invoice.show');
    Route::post('/invoice/transport/{id}/payment/store',[TransportPaymentController::class, 'store'])->name('transport.payment.store');
    Route::delete('/invoice/transport/{id}/payment/{pay_id}/destroy',[TransportPaymentController::class, 'destroy'])->name('transport.payment.destroy');

    Route::post('/let-see-the/pdf', [PDFController::class, 'preview'])->name('pdf.preview');

    Route::prefix('invoice')->name('invoice.')->group(function () {
      // Preview invoice (HTML view)
      Route::get('/preview', [InvoiceController::class, 'preview'])->name('preview');
      // Generate and download PDF
      Route::post('/generate-pdf', [InvoiceController::class, 'generatePdf'])->name('generate.pdf');
      Route::get('/generate-pdf', [InvoiceController::class, 'generatePdf'])->name('generate.pdf.get');
      // Stream PDF to browser (view in browser)
      Route::post('/stream-pdf', [InvoiceController::class, 'streamPdf'])->name('stream.pdf');
      Route::get('/stream-pdf', [InvoiceController::class, 'streamPdf'])->name('stream.pdf.get');
      // Save PDF to storage
      Route::post('/save-pdf', [InvoiceController::class, 'savePdf'])->name('save.pdf');
      // Send invoice via email
      Route::post('/send-email', [InvoiceController::class, 'sendEmail'])->name('send.email');
      // Show specific invoice by ID
      Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
      // Download invoice PDF by ID
      Route::get('/{id}/download', [InvoiceController::class, 'downloadById'])->name('download');
      // API routes for AJAX calls
      Route::prefix('api')->name('api.')->group(function () {
         Route::post('/generate-pdf', [InvoiceController::class, 'generatePdf'])->name('generate.pdf');
         Route::post('/stream-pdf', [InvoiceController::class, 'streamPdf'])->name('stream.pdf');
         Route::post('/save-pdf', [InvoiceController::class, 'savePdf'])->name('save.pdf');
         Route::post('/send-email', [InvoiceController::class, 'sendEmail'])->name('send.email');
      });
   });
});