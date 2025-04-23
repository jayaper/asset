<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RegistrasiAssetController;

use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\UserAccountController;
use App\Http\Controllers\AM\AmController;

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\ControlController;
use App\Http\Controllers\DeptController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\GroupUserController;
use App\Http\Controllers\JobLevelController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MtcController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PeriodicMtcController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\ReasonController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UomController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TipeMaintenanceController;
use App\Http\Controllers\MovementOutController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\PreventiveMaintenanceController;
use App\Http\Controllers\CorrectiveMaintenanceController;
use App\Http\Controllers\RM\RestoManagerController;
use App\Http\Controllers\SM\StoreManagerController;
use App\Http\Controllers\SDG\SDGControllers;
use App\Http\Controllers\AssetTransfer\RequestMoveout as RequestMoveout;
use App\Http\Controllers\AssetTransfer\ApprovalOpsAM as ApprovalOpsAM;
use App\Http\Controllers\AssetTransfer\ApprovalOpsRM as ApprovalOpsRM;
use App\Http\Controllers\AssetTransfer\ApprovalSDGAsset as ApprovalSDGAsset;
use App\Http\Controllers\AssetTransfer\RequestMoveIn as RequestMoveIn;
use App\Http\Controllers\AssetTransfer\DeliveryOrder as DeliveryOrder;
use App\Http\Controllers\AssetTransfer\ConfirmAsset as ConfirmAsset;
use App\Http\Controllers\AssetTransfer\ReviewAssetTransfer as ReviewAssetTransfer;
use App\Http\Controllers\Disposal\RequestDisposal as RequestDisposal;
use App\Http\Controllers\Disposal\ApprovalOpsAM as DisApprovalOpsAM;
use App\Http\Controllers\Disposal\ApprovalOpsRM as DisApprovalOpsRM;
use App\Http\Controllers\Disposal\ApprovalOpsSDG as DisApprovalOpsSDG;
use App\Http\Controllers\Disposal\Review;
use App\Http\Controllers\MasterData\Asset as MDAsset;
use App\Http\Controllers\MasterData\AssetEquipment as MDAssetEquipment;
use App\Http\Controllers\MasterData\Brand as MDBrand;
use App\Http\Controllers\MasterData\Category as MDCategory;
use App\Http\Controllers\MasterData\SubCategory as MDSubCategory;
use App\Http\Controllers\MasterData\Checklist as MDChecklist;
use App\Http\Controllers\MasterData\Condition as MDCondition;
use App\Http\Controllers\MasterData\Control  as MDControl;
use App\Http\Controllers\MasterData\Departement as MDDepartement;
use App\Http\Controllers\MasterData\Division as MDDivision;
use App\Http\Controllers\MasterData\GroupUser as MDGroupUser;
use App\Http\Controllers\MasterData\JobLevel as MDJobLevel;
use App\Http\Controllers\MasterData\Layout as MDLayout;
use App\Http\Controllers\MasterData\Maintenance as MDMaintenance;
use App\Http\Controllers\MasterData\People as MDPeople;
use App\Http\Controllers\MasterData\TypeAsset as MDTypeAsset;
use App\Http\Controllers\MasterData\MaintenanceAsset as MDMaintenanceAsset;
use App\Http\Controllers\MasterData\Priority as MDPriority;
use App\Http\Controllers\MasterData\AlasanMutasi as MDAlasanMutasi;
use App\Http\Controllers\MasterData\AlasanStockOpname as MDAlasanStockOpname;
use App\Http\Controllers\MasterData\Regional as MDRegional;
use App\Http\Controllers\MasterData\Repair as MDRepair;
use App\Http\Controllers\MasterData\Supplier as MDSupplier;
use App\Http\Controllers\MasterData\UOM as MDUOM;
use App\Http\Controllers\MasterData\Waranty as MDWarranty;
use App\Http\Controllers\MasterData\City as MDCity;
use App\Http\Controllers\MasterData\Resto as MDResto;
use App\Http\Controllers\MasterData\ApprovalMaintenance as MDApprovalMaintenance;



use App\Models\Master\MasterRegistrasiModel;

// Show the login form
Route::get('/', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login'); // Pastikan nama metode ditulis kecil
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});


//api
    Route::get('/api/get-from-locations', [MovementOutController::class, 'getFromLocations']);
    Route::get('/api/get-dest-locations', [MovementOutController::class, 'getDestLocations']);
    Route::get('/api/get-data-assets', [MovementOutController::class, 'getAjaxDataAssets']);
    Route::get('/api/get-asset-details/{id}', [MovementOutController::class, 'getAjaxAssetDetails']);
    Route::get('/admin/edit_data_movement/{id}', [MovementOutController::class, 'editDataDetailMovement']);
    Route::get('/api/get-location', [MovementOutController::class, 'getLocationUser']);
    Route::get('/api/get-condition', [MovementOutController::class, 'getCondition']);

    Route::get('/api/ajax-get-location', [MovementOutController::class, 'getLocation']);

    Route::get('/api/ajaxGetDataRegistAsset', [MovementOutController::class, 'ajaxGetDataRegistAsset']);
    Route::get('/api/ajaxGetDataRegistDisposalAsset', [MovementOutController::class, 'ajaxGetDataRegistDisposalAsset']);
    Route::get('/api/searchRegisterAsset', [MovementOutController::class, 'searchRegisterAsset']);
    Route::get('/admin/get_detail_data_movement/{id}', [MovementOutController::class, 'dataDetailMovement']);

    Route::get('/api/get-out-details/{outId}', [MovementOutController::class, 'getOutDetails']);
    Route::get('/api/get-edit-out-details/{outId}', [MovementOutController::class, 'getEditOutDetails']);


    Route::get('/api/check-location-relation/{fromLoc}', [MovementOutController::class, 'checkLocationRelation']);
//endapi



// registrasi data asset
Route::prefix('admin/registrasi_asset')->group(function () {
    Route::get('/lihat_data_registrasi_asset', [RegistrasiAssetController::class, 'HalamanRegistrasiAsset']);
    Route::get('/get_data_registrasi_asset', [RegistrasiAssetController::class, 'GetDataRegistrasiAsset']);
    Route::post('/tambah_data_registrasi_asset', [RegistrasiAssetController::class, 'AddDataRegistrasiAsset']);
    Route::get('/update/{id}', [RegistrasiAssetController::class, 'GetDetailDataRegistrasiAsset']);
    Route::put('/admin/registrasi_asset/update_data_registrasi_asset/{id}', [RegistrasiAssetController::class, 'update']);
    Route::delete('/delete_data_registrasi_asset/{id}', [RegistrasiAssetController::class, 'DeleteDataRegistrasiAsset']);
    Route::get('/export_data_asset', [RegistrasiAssetController::class, 'ExportToExcel']);
    Route::post('/import', [RegistrasiAssetController::class, 'import'])->name('import');
    Route::get('/laman_tambah_registrasi_asset', [RegistrasiAssetController::class, 'LamanTambahRegistrasi'])->name('laman_tambah_registrasi_asset');
    Route::get('/detail_data_registrasi_asset/{id}', [RegistrasiAssetController::class, 'DetailDataRegistrasiAsset']);
});
//cetak ke pdf
Route::post('/tambah_data_registrasi_asset', [RegistrasiAssetController::class, 'AddDataRegistrasiAsset']);


Route::get('/admin/registrasi_asset/get_detail/{id}', [RegistrasiAssetController::class, 'GetDetailDataRegistrasiAsset']);
Route::put('/admin/registrasi_asset/update_data_registrasi_asset/{id}', [RegistrasiAssetController::class, 'update']);
Route::post('admin/registrasi_asset/approve', [RegistrasiAssetController::class, 'approve']);
Route::get('/assets/details/{register_code}', [RegistrasiAssetController::class, 'TampilDataQR'])->name('assets.details');
Route::get('/get-location/{id}', [LocationController::class, 'GetLocation']);

Route::get('/generate-pdf/{registerCode}', [RegistrasiAssetController::class, 'generatePdf']);


Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->middleware(['permission:view dashboard'])->name('dashboard');
        Route::get('/get-data-resto-json', 'getDataResto')->middleware(['permission:view dashboard'])->name('dashboard.get-data-resto-json');
    });
    Route::controller(RegistrationController::class)->group(function () {
        Route::get('/registration/assets-registration', 'AssetsRegist')->middleware(['permission:view registration']);
        Route::get('/registration/tracking-asset-registration/{id}', 'trackingAsset')->middleware(['permission:view registration']);
        Route::get('/registration/detail_data_registrasi_asset/{id}', 'DetailDataRegistrasiAsset')->middleware(['permission:view registration']);
        Route::get('/registration/generate-pdf/{id}', 'generatePdf')->middleware(['permission:view registration']);
        Route::get('/registration/add-assets-registration', 'addAssetsRegist')->middleware(['permission:view registration']);
        Route::post('/registration/add-assets-registration', 'InsertAssetsRegist')->middleware(['permission:view registration']);
        Route::get('/registration/edit-assets-registration/{id}', 'EditAssetsRegist')->middleware(['permission:view registration']);
        Route::put('/registration/update-assets-registration/{id}', 'UpdateAssetsRegist')->middleware(['permission:view registration']);
        Route::put('/registration/delete-assets-registration/{id}', 'DeleteDataRegistrasiAsset')->middleware(['permission:view registration']);
        Route::get('/registration/get-regist', 'getAssets')->middleware(['permission:view registration']);
        Route::get('/registration/get-assets-json', 'getAssetsJson')->middleware(['permission:view registration']);
        Route::get('/registration/get-type-json', 'getTypeJson')->middleware(['permission:view registration']);
        Route::get('/registration/get-category-json', 'getCategoryJson')->middleware(['permission:view registration']);
        Route::get('/registration/get-priority-json', 'getPriorityJson')->middleware(['permission:view registration']);
        Route::get('/registration/get-brand-json', 'getBrandJson')->middleware(['permission:view registration']);
        Route::get('/registration/get-uom-json', 'getUomJson')->middleware(['permission:view registration']);
        Route::get('/registration/get-resto-json', 'getRestoJson')->middleware(['permission:view registration']);
        Route::get('/registration/get-layout-json', 'getLayoutJson')->middleware(['permission:view registration']);
        Route::get('/registration/get-condition-json', 'getConditionJson')->middleware(['permission:view registration']);
        Route::get('/registration/get-supplier-json', 'getSupplierJson')->middleware(['permission:view registration']);
        Route::get('/registration/get-warranty-json', 'getWarrantyJson')->middleware(['permission:view registration']);
        Route::get('/registration/get-periodic-json', 'getPeriodicMtcJson')->middleware(['permission:view registration']);
        Route::get('/registration/export-data-assets', 'ExportToExcel')->middleware(['permission:view registration']);
        Route::post('/registration/import-data-assets', 'import')->middleware(['permission:view registration']);
        Route::get('/registration/get_data_registrasi_asset', 'GetDataRegistrasiAsset')->middleware(['permission:view registration']);
        Route::get('/registration/detail_data_registrasi_asset', 'DetailDataRegistrasiAsset')->middleware(['permission:view registration']);

        // Route::get('/lihat_data_registrasi_asset', [RegistrasiAssetController::class, 'HalamanRegistrasiAsset']);
        // Route::post('/tambah_data_registrasi_asset', [RegistrasiAssetController::class, 'AddDataRegistrasiAsset']);
        // Route::get('/update/{id}', [RegistrasiAssetController::class, 'GetDetailDataRegistrasiAsset']);
        // Route::get('/laman_tambah_registrasi_asset', [RegistrasiAssetController::class, 'LamanTambahRegistrasi'])->name('laman_tambah_registrasi_asset');

        // Route::get('/registration/approval-ops-sm', 'HalamanApproval')->middleware(['permission:view registration']);


        Route::get('/admin/approval-reg', [AssetsController::class, 'HalamanApproval']);
        Route::get('/admin/approval-reg', [AssetsController::class, 'HalamanApproval'])->name('Admin.approval-reg');
        Route::post('/add-approval-reg', [AssetsController::class, 'AddDataApproval'])->name('add.approval-reg');
        Route::get('/get-approval-reg', [AssetsController::class, 'GetApproval'])->name('get.approval-reg');
        Route::get('/admin/approval-regs', [AssetsController::class, 'Index'])->name('Admin.approval-reg');
        Route::get('/admin/approval-regs/edit/{id}', [AssetsController::class, 'showEditForm'])->name('edit.approval-reg');
        Route::put('/admin/approval-regs/edit/{id}', [AssetsController::class, 'updateDataApproval'])->name('update.approval-reg');
        Route::delete('/admin/approval-regs/delete/{id}', [AssetsController::class, 'deleteDataApproval'])->name('delete.approval-reg');

    });
    Route::controller(RequestMoveout::class)->group(function () {
        Route::get('/api/asset-transfer/get-from-locations', 'getFromLocations')->middleware(['permission:view at request moveout']);
        Route::get('/api/asset-transfer/get-dest-locations', 'getDestLocations')->middleware(['permission:view at request moveout']);
        Route::get('/api/asset-transfer/get-data-assets', 'getAjaxDataAssets')->middleware(['permission:view at request moveout']);

        Route::get('/asset-transfer/request-moveout', 'HalamanMoveOut')->middleware(['permission:view at request moveout']);
        Route::get('/asset-transfer/request-moveout/edit/{id}', 'editDataDetailMovement')->middleware(['permission:view at request moveout']);
        Route::put('/asset-transfer/request-moveout/update/{id}', 'updateDataMoveOut')->middleware(['permission:view at request moveout']);
        Route::delete('/asset-transfer/request-moveout/delete/{id}', 'deleteDataMoveOut')->middleware(['permission:view at request moveout']);

        Route::get('/asset-transfer/add-request-moveout', 'LihatFormMoveOut')->middleware(['permission:view at request moveout']);
        Route::post('/asset-transfer/add-request-moveout', 'AddDataMoveOut')->middleware(['permission:view at request moveout']);
        Route::get('/asset-transfer/detail-request-moveout/{id}', 'dataDetailMovement')->middleware(['permission:view at request moveout']);
        Route::get('/asset-transfer/pdf-request-moveout/{id}', 'previewPDF')->middleware(['permission:view at request moveout']);
    });
    Route::controller(ApprovalOpsAM::class)->group(function () {
        Route::get('/asset-transfer/approval-ops-am', 'index')->middleware(['permission:view at approval ops am']);
        Route::put('/asset-transfer/approval-ops-am/update/{id}', 'updateApprovalAmAM')->middleware(['permission:view at approval ops am']);
    });
    Route::controller(ApprovalOpsRM::class)->group(function () {
        Route::get('/asset-transfer/approval-ops-rm', 'index')->middleware(['permission:view at approval ops rm']);
        Route::put('/asset-transfer/approval-ops-rm/edit/{id}', 'updateDataApprRM')->middleware(['permission:view at approval ops rm']);
    });
    Route::controller(ApprovalSDGAsset::class)->group(function () {
        Route::get('/asset-transfer/approval-sdg-asset', 'index')->middleware(['permission:view at approval ops sdg']);
        Route::put('/asset-transfer/approval-sdg-asset/edit/{id}', 'updateDataApprSDG')->middleware(['permission:view at approval ops sdg']);
    });
    Route::controller(RequestMoveIn::class)->group(function () {
        Route::get('/asset-transfer/request-movement-in', 'index')->middleware(['permission:view at request movement in']);
    });
    Route::controller(DeliveryOrder::class)->group(function () {
        Route::get('/asset-transfer/delivery-order', 'index')->middleware(['permission:view at delivery order']);
    });
    Route::controller(ConfirmAsset::class)->group(function () {
        Route::get('/asset-transfer/confirm-asset', 'index')->middleware(['permission:view at confirm asset']);
        Route::put('/asset-transfer/confirm-asset/edit/{id}', 'updateDataConfirm')->middleware(['permission:view at confirm asset']);
    });
    Route::controller(ReviewAssetTransfer::class)->group(function () {
        Route::get('/asset-transfer/review-head', 'head')->middleware(['permission:view at head']);
        Route::get('/asset-transfer/review-mnr', 'mnr')->middleware(['permission:view at mnr']);
        Route::get('/asset-transfer/review-taf', 'taf')->middleware(['permission:view at taf']);
    });
    Route::controller(RequestDisposal::class)->group(function () {
        Route::get('/disposal/request-disposal', 'index')->middleware(['permission:view dis request disposal']);
        Route::get('/disposal/add-request-disposal', 'AddRequestDisposal')->middleware(['permission:view dis request disposal']);
        Route::get('/disposal/add-request-disposal', 'AddRequestDisposal')->middleware(['permission:view dis request disposal']);
        Route::post('/disposal/add-request-disposal', 'AddDataDisOut')->middleware(['permission:view dis request disposal']);
        Route::get('/disposal/request-disposal/get_detail_data_disposal_out/{id}', 'detailPageDataDisposalOut')->middleware(['permission:view dis request disposal']);
        Route::get('/disposal/request-disposal/get_pdf/{id}', 'previewPDF')->middleware(['permission:view dis request disposal']);
        Route::get('/disposal/request-disposal/edit_data_disposal/{id}', 'editDetailDataDisout')->middleware(['permission:view dis request disposal']);

        // Disposal
        Route::get('/admin/disout', [DisposalOutController::class, 'HalamanDisOut']);
        Route::get('/admin/disout', [DisposalOutController::class, 'HalamanDisOut'])->name('Admin.disout');
        Route::post('/add-disout', [DisposalOutController::class, 'AddDataDisOut'])->name('add.disposal_out');
        Route::get('/get-disout', [DisposalOutController::class, 'GetDisOut'])->name('get.disout');
        Route::get('/admin/disouts', [DisposalOutController::class, 'Index'])->name('Admin.disout');
        //  Route::get('/admin/disouts/edit/{id}', [DisposalOutController::class, 'showEditForm'])->name('edit.disout');
        //  Route::get('/admin/disouts/put/{id}', [DisposalOutController::class, 'showPutForm']);
        //  Route::put('/admin/disouts/edit/{id}', [DisposalOutController::class, 'updateDataDisOut'])->name('update.disout');

        Route::get('/admin/get_detail_data_disposal_out/{id}', [DisposalOutController::class, 'detailPageDataDisposalOut']);

        Route::post('/admin/filter_data_disposal', [DisposalOutController::class, 'filter'])->name('admin.filterdisout');

        Route::get('/admin/disout/print/{id}', [DisposalOutController::class, 'previewPDF'])->name('admin.disoutPDF');

        Route::get('/admin/edit_data_disposal_out/{id}', [DisposalOutController::class, 'editDetailDataDisout']);

        Route::get('/api/get-asset-disposal-detail/{id}', [DisposalOutController::class, 'getAjaxAssetDisposalDetails']);

        Route::put('/admin/update_data_disposal_out/{outId}', [DisposalOutController::class, 'updateDataDisOut']);

        Route::get('/api/get_data_disposal', [DisposalOutController::class, 'getAjaxDataDisposal']);


        Route::delete('/admin/disouts/delete/{id}', [DisposalOutController::class, 'deleteDataDisOut'])->name('delete.disout');
        Route::get('/admin/disouts/get-asset-details/{id}', [DisposalOutController::class, 'getAssetDetails']);
        Route::get('/admin/disouts/detail/{id}', [DisposalOutController::class, 'getDisoutDetail']);
        Route::get('/fetch-disout-details/{id}', [DisposalOutController::class, 'getDetails']);
        Route::get('/disout/{id}', [DisposalOutController::class, 'getDisOutById']);
        Route::get('/disout/{id}/edit', 'DisposalController@edit');
        Route::get('/assets/{id}', 'AssetController@show');
    });

    Route::controller(DisApprovalOpsAM::class)->group(function () {
        Route::get('/disposal/approval-ops-am', 'index')->middleware(['permission:view dis approval ops am']);
        Route::put('/disposal/approval-ops-am/update/{id}', 'updateApprovalAM')->middleware(['permission:view dis approval ops am']);
        Route::get('/disposal/approval-ops-am/detail/{id}', 'detailApprovalAM')->middleware(['permission:view dis approval ops am']);
    });
    Route::controller(DisApprovalOpsRM::class)->group(function () {
        Route::get('/disposal/approval-ops-rm', 'index')->middleware(['permission:view dis approval ops rm']);
        Route::put('/disposal/approval-ops-rm/update/{id}', 'update')->middleware(['permission:view dis approval ops rm']);
        Route::get('/disposal/approval-ops-rm/detail/{id}', 'detailApprovalRM')->middleware(['permission:view dis approval ops rm']);
    });
    Route::controller(DisApprovalOpsSDG::class)->group(function () {
        Route::get('/disposal/approval-sdg-asset', 'index')->middleware(['permission:view dis approval ops sdg']);
        Route::put('/disposal/approval-sdg-asset/update/{id}', 'Update')->middleware(['permission:view dis approval ops sdg']);
        Route::get('/disposal/approval-sdg-asset/detail/{id}', 'DetailApprovalSdg')->middleware(['permission:view dis approval ops rm']);

        Route::get('/admin/apprdis-sdgasset', [DisposalController::class, 'HalamanAmd3']);
         Route::get('/admin/apprdis-sdgasset', [DisposalController::class, 'HalamanAmd3'])->name('Admin.apprdis-sdgasset');
         Route::post('/add-apprdis-sdgasset', [DisposalController::class, 'AddDataAmd3'])->name('add.apprdis-sdgasset');
         Route::get('/get-apprdis-sdgasset', [DisposalController::class, 'GetAmd1'])->name('get.apprdis-sdgasset');
         Route::get('/admin/apprdis-sdgassets', [DisposalController::class, 'Index3'])->name('Admin.apprdis-sdgasset');
         Route::get('/admin/apprdis-sdgassets/edit/{id}', [DisposalController::class, 'showEditForm3'])->name('edit.apprdis-sdgasset');
         Route::put('/admin/apprdis-sdgassets/edit/{id}', [DisposalController::class, 'updateDataAmd3'])->name('update.apprdis-sdgasset');
         Route::delete('/admin/apprdis-sdgassets/delete/{id}', [DisposalController::class, 'deleteDataAmd3'])->name('delete.apprdis-sdgasset');
    });
    Route::controller(Review::class)->group(function(){
        Route::get('/disposal/review', 'index')->middleware(['permission:view dis review']);
        Route::get('/disposal/review/detail/{id}', 'DetailReview')->middleware(['permission:view dis review']);
    });
    Route::controller(MDAsset::class)->group(function(){
        Route::get('/master-data/asset', 'HalamanAssets')->middleware(['permission:view md asset']);
        Route::post('/master-data/add-new-data-asset', 'NewAddDataAsset')->middleware(['permission:view md asset']);
        Route::get('/master-data/get-new-data-asset', 'GetAjaxDataAsset')->middleware(['permission:view md asset']);
        Route::put('/master-data/update-new-data-asset/{id}', 'NewUpdateDataAssetsStatus')->middleware(['permission:view md asset']);
        Route::delete('/master-data/delete-new-data-asset/{id}', 'NewDeleteDataAssets')->middleware(['permission:view md asset']);
    });
    Route::controller(MDAssetEquipment::class)->group(function(){
        Route::get('master-data/asset-equipment', 'index')->middleware(['permission:view md asset equipment']);
        Route::post('/master-data/add-new-data-asset-equipment', 'NewAddDataAssetEquipment')->middleware(['permission:view md asset equipment']);
//        Route::get('/master-data/get-new-data-asset-equipment', 'GetAjaxDataAssetEquipment')->middleware(['permission:view md asset equipment']);
        Route::put('/master-data/update-new-data-asset-equipment/{id}', 'NewUpdateDataAssetsEquipment')->middleware(['permission:view md asset equipment']);
        Route::delete('/master-data/delete-new-data-asset-equipment/{id}', 'NewDeleteDataAssetsEquipment')->middleware(['permission:view md asset equipment']);
    });
    Route::controller(MDBrand::class)->group(function(){
        Route::get('master-data/brand', 'index')->middleware(['permission:view md brand']);
        Route::post('/master-data/add-new-brand', 'NewAddBrand')->middleware(['permission:view md brand']);
        Route::put('/master-data/update-new-brand/{id}', 'NewUpdateDataBrand')->middleware(['permission:view md brand']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataBrand')->middleware(['permission:view md brand']);
    });
    Route::controller(MDCategory::class)->group(function(){
        Route::get('master-data/category', 'index')->middleware(['permission:view md kategori']);;
        Route::post('/master-data/add-new-category', 'NewAddDataCategory')->middleware(['permission:view md kategori']);
        Route::put('/master-data/update-new-category/{id}', 'NewUpdateDataCategory')->middleware(['permission:view md kategori']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataCategory')->middleware(['permission:view md kategori']);
    });
    Route::controller(MDSubCategory::class)->group(function(){
        Route::get('master-data/sub-category', 'index')->middleware(['permission:view md sub kategori']);
        Route::post('/master-data/add-new-sub-category', 'NewAddDataSubCategory')->middleware(['permission:view md sub kategori']);
        Route::put('/master-data/update-new-sub-category/{id}', 'NewUpdateDataSubCategory')->middleware(['permission:view md sub kategori']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataSubCategory')->middleware(['permission:view md sub kategori']);
    });
    Route::controller(MDChecklist::class)->group(function(){
        Route::get('master-data/checklist', 'index')->middleware(['permission:view md checklist']);
    });
    Route::controller(MDCondition::class)->group(function(){
        Route::get('master-data/condition', 'index')->middleware(['permission:view md kondisi']);
        Route::post('/master-data/add-new-condition', 'NewAddDataCondition')->middleware(['permission:view md kondisi']);
        Route::put('/master-data/update-new-condition/{id}', 'NewUpdateDataCondition')->middleware(['permission:view md kondisi']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataCondition')->middleware(['permission:view md kondisi']);
    });
    Route::controller(MDControl::class)->group(function(){
        Route::get('master-data/control-checklist', 'index')->middleware(['permission:view md kontrol checklist']);
        Route::post('/master-data/add-new-control-checklist', 'NewAddDataControlNameList')->middleware(['permission:view md kontrol checklist']);
        Route::put('/master-data/update-new-control-checklist/{id}', 'NewUpdateDataControlNameList')->middleware(['permission:view md kontrol checklist']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataControlNameList')->middleware(['permission:view md kontrol checklist']);
    });
    Route::controller(MDDepartement::class)->group(function(){
        Route::get('master-data/departement', 'index')->middleware(['permission:view md departement']);
        Route::post('/master-data/add-new-departement', 'NewAddDataDepartement')->middleware(['permission:view md departement']);
        Route::put('/master-data/update-new-departement/{id}', 'NewUpdateDataDepartement')->middleware(['permission:view md departement']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataDepartement')->middleware(['permission:view md departement']);
    });
    Route::controller(MDDivision::class)->group(function(){
        Route::get('master-data/division', 'index')->middleware(['permission:view md division']);
        Route::post('/master-data/add-new-division', 'NewAddDataDivision')->middleware(['permission:view md division']);
        Route::put('/master-data/update-new-division/{id}', 'NewUpdateDataDivision')->middleware(['permission:view md division']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataDivision')->middleware(['permission:view md division']);
    });
    Route::controller(MDGroupUser::class)->group(function(){
        Route::get('master-data/group-user', 'index')->middleware(['permission:view md group user']);
        Route::post('/master-data/add-new-group-user', 'NewAddDataGroupUser')->middleware(['permission:view md group user']);
        Route::put('/master-data/update-new-group-user/{id}', 'NewUpdateDataGroupUser')->middleware(['permission:view md group user']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataGroupUser')->middleware(['permission:view md group user']);
    });
    Route::controller(MDJobLevel::class)->group(function(){
        Route::get('master-data/job-level', 'index')->middleware(['permission:view md job level']);
        Route::post('/master-data/add-new-job-level', 'NewAddDataJobLevel')->middleware(['permission:view md job level']);
        Route::put('/master-data/update-new-job-level/{id}', 'NewUpdateDataJobLevel')->middleware(['permission:view md job level']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataJobLevel')->middleware(['permission:view md job level']);
    });
    Route::controller(MDLayout::class)->group(function(){
        Route::get('master-data/layout', 'index')->middleware(['permission:view md tata letak']);
        Route::post('/master-data/add-new-layout', 'NewAddDataLayout')->middleware(['permission:view md tata letak']);
        Route::put('/master-data/update-new-layout/{id}', 'NewUpdateDataLayout')->middleware(['permission:view md tata letak']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataLayout')->middleware(['permission:view md tata letak']);
    });
    Route::controller(MDMaintenance::class)->group(function(){
        Route::get('master-data/maintenance', 'index')->middleware(['permission:view md maintenance']);
        Route::post('/master-data/add-new-maintenance', 'NewAddDataMaintenance')->middleware(['permission:view md maintenance']);
        Route::put('/master-data/update-new-maintenance/{id}', 'NewUpdateDataMaintenance')->middleware(['permission:view md maintenance']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataMaintenance')->middleware(['permission:view md maintenance']);
    });
    Route::controller(MDPeople::class)->group(function(){
        Route::get('master-data/people', 'index')->middleware(['permission:view md people']);
        Route::post('/master-data/add-new-people', 'NewAddDataPeople')->middleware(['permission:view md people']);
        Route::put('/master-data/update-new-people/{id}', 'NewUpdateDataPeople')->middleware(['permission:view md people']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataPeople')->middleware(['permission:view md people']);
    });
    Route::controller(MDTypeAsset::class)->group(function(){
        Route::get('master-data/type-asset', 'index')->middleware(['permission:view md tipe asset']);
        Route::post('/master-data/add-new-type-asset', 'NewAddDataAssetType')->middleware(['permission:view md tipe asset']);
        Route::put('/master-data/update-new-type-asset/{id}', 'NewUpdateDataAssetType')->middleware(['permission:view md tipe asset']);
        Route::delete('/master-data/delete-new-data/{id}', 'NewDeleteDataAssetType')->middleware(['permission:view md tipe asset']);
    });
    Route::controller(MDMaintenanceAsset::class)->group(function(){
        Route::get('master-data/type-maintenance-asset', 'index')->middleware(['permission:view md tipe maintenance asset']);
    });
    Route::controller(MDPriority::class)->group(function(){
        Route::get('master-data/priority', 'index')->middleware(['permission:view md prioritas']);
    });
    Route::controller(MDAlasanMutasi::class)->group(function(){
        Route::get('master-data/alasan-mutasi', 'index')->middleware(['permission:view md alasan mutasi']);
    });
    Route::controller(MDAlasanStockOpname::class)->group(function(){
        Route::get('master-data/alasan-stock-opname', 'index')->middleware(['permission:view md alasan stock opname']);
    });
    Route::controller(MDRegional::class)->group(function(){
        Route::get('master-data/regional', 'index')->middleware(['permission:view md regional']);
    });
    Route::controller(MDRepair::class)->group(function(){
        Route::get('master-data/repair', 'index')->middleware(['permission:view md perbaikan']);
    });
    Route::controller(MDSupplier::class)->group(function(){
        Route::get('master-data/supplier', 'index')->middleware(['permission:view md pemasok']);
    });
    Route::controller(MDUOM::class)->group(function(){
        Route::get('master-data/uom', 'index')->middleware(['permission:view md satuan']);
    });
    Route::controller(MDWarranty::class)->group(function(){
        Route::get('master-data/warranty', 'index')->middleware(['permission:view md garansi']);
    });
    Route::controller(MDCity::class)->group(function(){
        Route::get('master-data/city', 'index')->middleware(['permission:view md kota']);
    });
    Route::controller(MDResto::class)->group(function(){
        Route::get('master-data/resto', 'index')->middleware(['permission:view md resto']);
    });
    Route::controller(MDApprovalMaintenance::class)->group(function(){
        Route::get('master-data/approval-maintenance', 'index')->middleware(['permission:view md approval maintenance']);
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/permission', 'userPermission')->name('permission');
        Route::post('/permission/add-permission', 'addPermission');
        Route::put('/permission/{id}/update-permission', 'updatePermission')->name('permission.update');
        Route::get('role', 'userRole');
    });

    // Route::get('/admin/dashboard', [AdminController::class, 'index']);
    Route::group([RoleMiddleware::class => ':admin'], function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/admin/get-resto-json', [AdminController::class, 'getDataResto']);
        // Asset Route
        Route::get('/admin/halaman_asset', [AdminController::class, 'HalamanAsset']);
        Route::get('/admin/GetDataAsset', [AdminController::class, 'GetDataAsset']);
        Route::get('/admin/get_detail_data_asset/{id}', [AdminController::class, 'GetDetailDataAsset']);
        Route::post('/admin/add_data_asset', [AdminController::class, 'AddDataAsset']);

        // Regist Assets
        Route::get('/admin/regist', [AssetsController::class, 'HalamanAssets']);
        Route::get('/admin/regist', [AssetsController::class, 'HalamanAssets'])->name('Admin.asset');
        Route::post('/add-regist', [AssetsController::class, 'AddDataAssets'])->name('add-regist');
        Route::get('/get-regist', [AssetsController::class, 'GetAssets'])->name('get.asset');
        Route::get('/admin/regists', [AssetsController::class, 'Index'])->name('Admin.asset');
        Route::get('/admin/regists/edit/{id}', [AssetsController::class, 'showEditForm'])->name('edit.asset');
        Route::put('/admin/regists/edit/{id}', [AssetsController::class, 'updateDataAssets'])->name('update.asset');
        Route::delete('/admin/regists/delete/{id}', [AssetsController::class, 'deleteDataAssets'])->name('delete.asset');
        Route::get('/add-regist', [AssetsController::class, 'showForm'])->name('addDataAsset');

        Route::get('/admin/get-regist', [AssetsController::class, 'GetAssets']);

        Route::get('/admin/get-regist-ajax', [AssetsController::class, 'GetNameAssetAjax']);

        Route::get('/admin/regist/export-master-asset', [AssetsController::class, 'ExportAssetExcel']);

        Route::post('/admin/regist/import-master-asset', [AssetsController::class, 'ImportMasterAssetExcel']);

        Route::put('/admin/regist/update_master_asset/{id}', [AssetsController::class, 'updateDataAssets']);

        // Regist Assets Equipment
        Route::get('/admin/registeqp', [AssetsController::class, 'HalamanAssetsEquipment']);
        Route::get('/admin/registeqp', [AssetsController::class, 'HalamanAssetsEquipment'])->name('Admin.assetequipment');
        Route::post('/add-regist', [AssetsController::class, 'AddDataAssets'])->name('add-regist');
        Route::get('/get-regist', [AssetsController::class, 'GetAssets'])->name('get.asset');
        Route::get('/admin/regists', [AssetsController::class, 'Index'])->name('Admin.asset');
        Route::get('/admin/regists/edit/{id}', [AssetsController::class, 'showEditForm'])->name('edit.asset');
        Route::put('/admin/regists/edit/{id}', [AssetsController::class, 'updateDataAssets'])->name('update.asset');
        Route::delete('/admin/regists/delete/{id}', [AssetsController::class, 'deleteDataAssets'])->name('delete.asset');
        Route::get('/add-regist', [AssetsController::class, 'showForm'])->name('addDataAsset');

        // Approval Reg OPS SM
        Route::get('/admin/approval-reg', [AssetsController::class, 'HalamanApproval']);
        Route::get('/admin/approval-reg', [AssetsController::class, 'HalamanApproval'])->name('Admin.approval-reg');
        Route::post('/add-approval-reg', [AssetsController::class, 'AddDataApproval'])->name('add.approval-reg');
        Route::get('/get-approval-reg', [AssetsController::class, 'GetApproval'])->name('get.approval-reg');
        Route::get('/admin/approval-regs', [AssetsController::class, 'Index'])->name('Admin.approval-reg');
        Route::get('/admin/approval-regs/edit/{id}', [AssetsController::class, 'showEditForm'])->name('edit.approval-reg');
        Route::put('/admin/approval-regs/edit/{id}', [AssetsController::class, 'updateDataApproval'])->name('update.approval-reg');
        Route::delete('/admin/approval-regs/delete/{id}', [AssetsController::class, 'deleteDataApproval'])->name('delete.approval-reg');

        // Review Reg OPS SM
        Route::get('/admin/review-reg', [AssetsController::class, 'HalamanReview']);
        Route::get('/admin/review-reg', [AssetsController::class, 'HalamanReview'])->name('Admin.review-reg');
        Route::post('/add-review-reg', [AssetsController::class, 'AddDataReview'])->name('add.review-reg');
        Route::get('/get-review-reg', [AssetsController::class, 'GetReview'])->name('get.review-reg');
        Route::get('/admin/review-regs', [AssetsController::class, 'Index'])->name('Admin.review-reg');
        Route::get('/admin/review-regs/edit/{id}', [AssetsController::class, 'showEditForm'])->name('edit.review-reg');
        Route::put('/admin/review-regs/edit/{id}', [AssetsController::class, 'updateDataReview'])->name('update.review-reg');
        Route::delete('/admin/review-regs/delete/{id}', [AssetsController::class, 'deleteDataReview'])->name('delete.review-reg');

        // Route::get('/admin/regist', [AssetsController::class, 'index'])->name('admin.assets');
        // Route::post('/admin/regist', [AssetsController::class, 'addDataAssets'])->name('add-asset');
        // Route::get('/admin/regists/{id}', [AssetsController::class, 'getAssets'])->name('get.asset');
        // Route::get('/admin/regists/edit/{id}', [AssetsController::class, 'showEditForm'])->name('edit.asset');
        // Route::put('/admin/regists/edit/{id}', [AssetsController::class, 'updateDataAssets'])->name('update.asset');
        // Route::delete('/admin/regists/{id}', [AssetsController::class, 'deleteDataAssets'])->name('delete.asset');

        // Brand
        Route::get('/admin/brand', [BrandController::class, 'HalamanBrand']);
        Route::get('/admin/brand', [BrandController::class, 'HalamanBrand'])->name('Admin.brand');
        Route::post('/add-brand', [BrandController::class, 'AddDataBrand'])->name('add.brand');
        Route::get('/get-brand', [BrandController::class, 'GetBrand'])->name('get.brand');
        Route::get('/admin/brands', [BrandController::class, 'Index'])->name('Admin.brand');
        Route::get('/admin/brands/edit/{id}', [BrandController::class, 'showEditForm'])->name('edit.brand');
        Route::put('/admin/brands/edit/{id}', [BrandController::class, 'updateDataBrand'])->name('update.brand');
        Route::delete('/admin/brands/delete/{id}', [BrandController::class, 'deleteDataBrand'])->name('delete.brand');
        Route::get('/admin/get-brand', [BrandController::class, 'GetBrand'])->name('get.brand');

        // Category
        Route::get('/admin/category', [CategoryController::class, 'HalamanCategory']);
        Route::get('/admin/category', [CategoryController::class, 'HalamanCategory'])->name('Admin.category');
        Route::post('/add-category', [CategoryController::class, 'AddDataCategory'])->name('add-category');
        Route::get('/get-category', [CategoryController::class, 'GetCategory'])->name('get.category');
        Route::get('/admin/categorys', [CategoryController::class, 'Index'])->name('Admin.category');
        Route::get('/admin/categorys/edit/{id}', [CategoryController::class, 'showEditForm'])->name('edit.category');
        Route::put('/admin/categorys/edit/{id}', [CategoryController::class, 'updateDataCategory'])->name('update.category');
        Route::delete('/admin/categorys/delete/{id}', [CategoryController::class, 'deleteDataCategory'])->name('delete.category');
        // Sub Category
        Route::get('/admin/subcategory', [SubCategoryController::class, 'HalamanSubCategory']);
        Route::get('/admin/subcategory', [SubCategoryController::class, 'HalamanSubCategory'])->name('Admin.subcategory');
        Route::post('/add-subcategory', [SubCategoryController::class, 'AddDataSubCategory'])->name('add-subcategory');
        Route::get('/get-subcategory', [SubCategoryController::class, 'GetSubCategory'])->name('get.subcategory');
        Route::get('/admin/subcategorys', [SubCategoryController::class, 'Index'])->name('Admin.subcategory');
        Route::get('/admin/subcategorys/edit/{id}', [SubCategoryController::class, 'showEditForm'])->name('edit.subcategory');
        Route::put('/admin/subcategorys/edit/{id}', [SubCategoryController::class, 'updateDataSubCategory'])->name('update.subcategory');
        Route::delete('/admin/subcategorys/delete/{id}', [SubCategoryController::class, 'deleteDataSubCategory'])->name('delete.subcategory');
        // Checklist
        Route::get('/admin/checklist', [ChecklistController::class, 'HalamanChecklist']);
        Route::get('/admin/checklist', [ChecklistController::class, 'HalamanChecklist'])->name('Admin.checklist');
        Route::post('/add-checklist', [ChecklistController::class, 'AddDataChecklist'])->name('add.checklist');
        Route::get('/get-checklist', [ChecklistController::class, 'GetChecklist'])->name('get.checklist');
        Route::get('/admin/checklists', [ChecklistController::class, 'Index'])->name('Admin.checklist');
        Route::get('/admin/checklists/edit/{id}', [ChecklistController::class, 'showEditForm'])->name('edit.checklist');
        Route::put('/admin/checklists/edit/{id}', [ChecklistController::class, 'updateDataChecklist']);
        Route::delete('/admin/checklists/delete/{mtc_type_id}', [ChecklistController::class, 'deleteDataChecklist'])->name('delete.checklist');
        // Control
        Route::get('/admin/control', [ControlController::class, 'HalamanControl']);
        Route::get('/admin/control', [ControlController::class, 'HalamanControl'])->name('Admin.control');
        Route::post('/add-control', [ControlController::class, 'AddDataControl'])->name('add.control');
        Route::get('/get-control', [ControlController::class, 'GetControl'])->name('get.control');
        Route::get('/admin/controls', [ControlController::class, 'Index'])->name('Admin.control');
        Route::get('/admin/controls/edit/{id}', [ControlController::class, 'showEditForm'])->name('edit.control');
        Route::put('/admin/controls/edit/{id}', [ControlController::class, 'updateDataControl'])->name('update.control');
        Route::delete('/admin/controls/delete/{id}', [ControlController::class, 'deleteDataControl'])->name('delete.control');
        // Condition
        Route::get('/admin/condition', [ConditionController::class, 'HalamanCondition']);
        Route::get('/admin/condition', [ConditionController::class, 'HalamanCondition'])->name('Admin.condition');
        Route::post('/add-condition', [ConditionController::class, 'AddDataCondition'])->name('add-condition');
        Route::get('/get-condition', [ConditionController::class, 'GetCondition'])->name('get.condition');
        Route::get('/admin/conditions', [ConditionController::class, 'Index'])->name('Admin.condition');
        Route::get('/admin/conditions/edit/{id}', [ConditionController::class, 'showEditForm'])->name('edit.condition');
        Route::put('/admin/conditions/edit/{id}', [ConditionController::class, 'updateDataCondition'])->name('update.condition');
        Route::delete('/admin/conditions/delete/{id}', [ConditionController::class, 'deleteDataCondition'])->name('delete.condition');

        Route::get('/admin/get-condition', [ConditionController::class, 'GetCondition']);

        // Dept
        Route::get('/admin/dept', [DeptController::class, 'HalamanDept']);
        Route::get('/admin/dept', [DeptController::class, 'HalamanDept'])->name('Admin.dept');
        Route::post('/add-dept', [DeptController::class, 'AddDataDept'])->name('add.dept');
        Route::get('/get-dept', [DeptController::class, 'GetDept'])->name('get.dept');
        Route::get('/admin/depts', [DeptController::class, 'Index'])->name('Admin.dept');
        Route::get('/admin/depts/edit/{id}', [DeptController::class, 'showEditForm'])->name('edit.dept');
        Route::put('/admin/depts/edit/{id}', [DeptController::class, 'updateDataDept'])->name('update.dept');
        Route::delete('/admin/depts/delete/{id}', [DeptController::class, 'deleteDataDept'])->name('delete.dept');
        // Division
        Route::get('/admin/division', [DivisionController::class, 'HalamanDivision']);
        Route::get('/admin/division', [DivisionController::class, 'HalamanDivision'])->name('Admin.division');
        Route::post('/add-division', [DivisionController::class, 'AddDataDivision'])->name('add.division');
        Route::get('/get-division', [DivisionController::class, 'GetDivision'])->name('get.division');
        Route::get('/admin/divisions', [DivisionController::class, 'Index'])->name('Admin.division');
        Route::get('/admin/divisions/edit/{id}', [DivisionController::class, 'showEditForm'])->name('edit.division');
        Route::put('/admin/divisions/edit/{id}', [DivisionController::class, 'updateDataDivision'])->name('update.division');
        Route::delete('/admin/divisions/delete/{id}', [DivisionController::class, 'deleteDataDivision'])->name('delete.division');
        // Group User
        Route::get('/admin/groupuser', [GroupUserController::class, 'HalamanGroupUser']);
        Route::get('/admin/groupuser', [GroupUserController::class, 'HalamanGroupUser'])->name('Admin.groupuser');
        Route::post('/add-groupuser', [GroupUserController::class, 'AddDataGroupUser'])->name('add-groupuser');
        Route::get('/get-groupuser', [GroupUserController::class, 'GetGroupUser'])->name('get.groupuser');
        Route::get('/admin/groupusers', [GroupUserController::class, 'Index'])->name('Admin.groupuser');
        Route::get('/admin/groupusers/edit/{id}', [GroupUserController::class, 'showEditForm'])->name('edit.groupuser');
        Route::put('/admin/groupusers/edit/{id}', [GroupUserController::class, 'updateDataGroupUser'])->name('update.groupuser');
        Route::delete('/admin/groupusers/delete/{id}', [GroupUserController::class, 'deleteDataGroupUser'])->name('delete.groupuser');
        // Job Level
        Route::get('/admin/joblevel', [JobLevelController::class, 'HalamanJobLevel']);
        Route::get('/admin/joblevel', [JobLevelController::class, 'HalamanJobLevel'])->name('Admin.joblevel');
        Route::post('/add-joblevel', [JobLevelController::class, 'AddDataJobLevel'])->name('add.joblevel');
        Route::get('/get-joblevel', [JobLevelController::class, 'GetJobLevel'])->name('get.joblevel');
        Route::get('/admin/joblevels', [JobLevelController::class, 'Index'])->name('Admin.joblevel');
        Route::get('/admin/joblevels/edit/{id}', [JobLevelController::class, 'showEditForm'])->name('edit.joblevel');
        Route::put('/admin/joblevels/edit/{id}', [JobLevelController::class, 'updateDataJobLevel'])->name('update.joblevel');
        Route::delete('/admin/joblevels/delete/{id}', [JobLevelController::class, 'deleteDataJobLevel'])->name('delete.joblevel');
        // Layout
        Route::get('/admin/layout', [LayoutController::class, 'HalamanLayout']);
        Route::get('/admin/layout', [LayoutController::class, 'HalamanLayout'])->name('Admin.layout');
        Route::post('/add-layout', [LayoutController::class, 'AddDataLayout'])->name('add.layout');
        Route::get('/get-layout', [LayoutController::class, 'GetLayout'])->name('get.layout');
        Route::get('/admin/layouts', [LayoutController::class, 'Index'])->name('Admin.layout');
        Route::get('/admin/layouts/edit/{id}', [LayoutController::class, 'showEditForm'])->name('edit.layout');
        Route::put('/admin/layouts/edit/{id}', [LayoutController::class, 'updateDataLayout'])->name('update.layout');
        Route::delete('/admin/layouts/delete/{id}', [LayoutController::class, 'deleteDataLayout'])->name('delete.layout');
        // Location
        Route::get('/admin/location', [LocationController::class, 'HalamanLocation']);
        Route::post('/add-location', [LocationController::class, 'AddDataLocation'])->name('add.location');
        Route::get('/get-location', [LocationController::class, 'GetLocation'])->name('get.location');
        Route::get('/admin/locations/edit/{id}', [LocationController::class, 'showEditForm'])->name('edit.location');
        Route::put('/admin/locations/edit/{id}', [LocationController::class, 'updateDataLocation'])->name('update.location');
        Route::delete('/admin/locations/delete/{id}', [LocationController::class, 'deleteDataLocation'])->name('delete.location');
        // Mtc
        // Route::get('/admin/mtc', [MtcController::class, 'HalamanMtc']);
        // Route::get('/admin/mtc', [MtcController::class, 'HalamanMtc'])->name('Admin.mtc');
        // Route::post('/add-mtc', [MtcController::class, 'AddDataMtc'])->name('add.mtc');
        // Route::get('/get-mtc', [MtcController::class, 'GetMtc'])->name('get.mtc');
        // Route::get('/admin/mtcs', [MtcController::class, 'Index'])->name('Admin.mtc');
        // Route::get('/admin/mtcs/edit/{id}', [MtcController::class, 'showEditForm'])->name('edit.mtc');
        // Route::put('/admin/mtcs/edit/{id}', [MtcController::class, 'updateDataMtc'])->name('update.mtc');
        // Route::delete('/admin/mtcs/delete/{id}', [MtcController::class, 'deleteDataMtc'])->name('delete.mtc');
        // People
        Route::get('/admin/people', [PeopleController::class, 'HalamanPeople']);
        Route::get('/admin/people', [PeopleController::class, 'HalamanPeople'])->name('Admin.people');
        Route::post('/add-people', [PeopleController::class, 'AddDataPeople'])->name('add.people');
        Route::get('/get-people', [PeopleController::class, 'GetPeople'])->name('get.people');
        Route::get('/admin/peoples', [PeopleController::class, 'Index'])->name('Admin.people');
        Route::get('/admin/peoples/edit/{id}', [PeopleController::class, 'showEditForm'])->name('edit.people');
        Route::put('/admin/peoples/edit/{id}', [PeopleController::class, 'updateDataPeople'])->name('update.people');
        Route::delete('/admin/peoples/delete/{id}', [PeopleController::class, 'deleteDataPeople'])->name('delete.people');
        // Periodic Mtc
        Route::get('/admin/periodic', [PeriodicMtcController::class, 'HalamanPeriodicMtc']);
        Route::get('/admin/periodic', [PeriodicMtcController::class, 'HalamanPeriodicMtc'])->name('Admin.periodic');
        Route::post('/add-periodic', [PeriodicMtcController::class, 'AddDataPeriodicMtc'])->name('add.periodic');
        Route::get('/get-periodic', [PeriodicMtcController::class, 'GetPeriodicMtc'])->name('get.periodic');
        Route::get('/admin/periodics', [PeriodicMtcController::class, 'Index'])->name('Admin.periodic');
        Route::get('/admin/periodics/edit/{id}', [PeriodicMtcController::class, 'showEditForm'])->name('edit.periodic');
        Route::put('/admin/periodics/edit/{id}', [PeriodicMtcController::class, 'updateDataPeriodicMtc'])->name('update.periodic');
        Route::delete('/admin/periodics/delete/{id}', [PeriodicMtcController::class, 'deleteDataPeriodicMtc'])->name('delete.periodic');

        Route::get('/admin/get-periodic', [PeriodicMtcController::class, 'GetPeriodicMtc']);

        // Priority
        Route::get('/admin/priority', [PriorityController::class, 'HalamanPriority']);
        Route::get('/admin/priority', [PriorityController::class, 'HalamanPriority'])->name('Admin.priority');
        Route::post('/add-priority', [PriorityController::class, 'AddDataPriority'])->name('add-priority');
        Route::get('/get-priority', [PriorityController::class, 'GetPriority'])->name('get.priority');
        Route::get('/admin/prioritys', [PriorityController::class, 'Index'])->name('Admin.priority');
        Route::get('/admin/prioritys/edit/{id}', [PriorityController::class, 'showEditForm'])->name('edit.priority');
        Route::put('/admin/prioritys/edit/{id}', [PriorityController::class, 'updateDataPriority'])->name('update.priority');
        Route::delete('/admin/prioritys/delete/{id}', [PriorityController::class, 'deleteDataPriority'])->name('delete.priority');
        // Reason
        Route::get('/admin/reason', [ReasonController::class, 'HalamanReason']);
        Route::get('/admin/reason', [ReasonController::class, 'HalamanReason'])->name('Admin.reason');
        Route::post('/add-reason', [ReasonController::class, 'AddDataReason'])->name('add.reason');
        Route::get('/get-reason', [ReasonController::class, 'GetReason'])->name('get.reason');
        Route::get('/admin/reasons', [ReasonController::class, 'Index'])->name('Admin.reason');
        Route::get('/admin/reasons/edit/{id}', [ReasonController::class, 'showEditForm'])->name('edit.reason');
        Route::put('/admin/reasons/edit/{id}', [ReasonController::class, 'updateDataReason'])->name('update.reason');
        Route::delete('/admin/reasons/delete/{id}', [ReasonController::class, 'deleteDataReason'])->name('delete.reason');
        // Reason SO
        Route::get('/admin/reasonso', [ReasonSoController::class, 'HalamanReasonSo']);
        Route::get('/admin/reasonso', [ReasonSoController::class, 'HalamanReasonSo'])->name('Admin.reasonso');
        Route::post('/add-reasonso', [ReasonSoController::class, 'AddDataReasonSo'])->name('add.reasonso');
        Route::get('/get-reasonso', [ReasonSoController::class, 'GetReasonSo'])->name('get.reasonso');
        Route::get('/admin/reasonsos', [ReasonSoController::class, 'IndexSo'])->name('Admin.reasonso');
        Route::get('/admin/reasonsos/edit/{id}', [ReasonSoController::class, 'showEditFormSo'])->name('edit.reasonso');
        Route::put('/admin/reasonsos/edit/{id}', [ReasonSoController::class, 'updateDataReasonSo'])->name('update.reasonso');
        Route::delete('/admin/reasonsos/delete/{id}', [ReasonSoController::class, 'deleteDataReasonSo'])->name('delete.reasonso');
        // Region
        Route::get('/admin/region', [RegionController::class, 'HalamanRegion']);
        Route::get('/admin/region', [RegionController::class, 'HalamanRegion'])->name('Admin.region');
        Route::post('/add-region', [RegionController::class, 'AddDataRegion'])->name('add.region');
        Route::get('/get-region', [RegionController::class, 'GetRegion'])->name('get.region');
        Route::get('/admin/regions', [RegionController::class, 'Index'])->name('Admin.region');
        Route::get('/admin/regions/edit/{id}', [RegionController::class, 'showEditForm'])->name('edit.region');
        Route::put('/admin/regions/edit/{id}', [RegionController::class, 'updateDataRegion'])->name('update.region');
        Route::delete('/admin/regions/delete/{id}', [RegionController::class, 'deleteDatsRegion'])->name('delete.region');

        Route::get('/admin/get-region', [RegionController::class, 'getRegion'])->name('get.region');

        // Repair
        Route::get('/admin/repair', [RepairController::class, 'HalamanRepair']);
        Route::get('/admin/repair', [RepairController::class, 'HalamanRepair'])->name('Admin.repair');
        Route::post('/add-repair', [RepairController::class, 'AddDataRepair'])->name('add.repair');
        Route::get('/get-repair', [RepairController::class, 'GetRepair'])->name('get.repair');
        Route::get('/admin/repairs', [RepairController::class, 'Index'])->name('Admin.repair');
        Route::get('/admin/repairs/edit/{id}', [RepairController::class, 'showEditForm'])->name('edit.repair');
        Route::put('/admin/repairs/edit/{id}', [RepairController::class, 'updateDataRepair'])->name('update.repair');
        Route::delete('/admin/repairs/delete/{id}', [RepairController::class, 'deleteDataRepair'])->name('delete.repair');
        // Supplier
        Route::get('/admin/supplier', [SupplierController::class, 'HalamanSupplier']);
        Route::get('/admin/supplier', [SupplierController::class, 'HalamanSupplier'])->name('Admin.supplier');
        Route::post('/add-supplier', [SupplierController::class, 'AddDataSupplier'])->name('add.supplier');
        Route::get('/get-supplier', [SupplierController::class, 'GetSupplier'])->name('get.supplier');
        Route::get('/admin/suppliers', [SupplierController::class, 'Index'])->name('Admin.supplier');
        Route::get('/admin/suppliers/edit/{id}', [SupplierController::class, 'showEditForm'])->name('edit.supplier');
        Route::put('/admin/suppliers/edit/{id}', [SupplierController::class, 'updateDataSupplier'])->name('update.supplier');
        Route::delete('/admin/suppliers/delete/{id}', [SupplierController::class, 'deleteDataSupplier'])->name('delete.supplier');

        Route::get('/admin/get-supplier', [SupplierController::class, 'GetSupplier']);

        // Type
        Route::get('/admin/type', [TypeController::class, 'HalamanType']);
        Route::get('/admin/type', [TypeController::class, 'HalamanType'])->name('Admin.type');
        Route::post('/add-type', [TypeController::class, 'AddDataType'])->name('add.type');
        Route::get('/get-type', [TypeController::class, 'GetType'])->name('get.type');
        Route::get('/admin/types', [TypeController::class, 'Index'])->name('Admin.type');
        Route::get('/admin/types/edit/{id}', [TypeController::class, 'showEditForm'])->name('edit.type');
        Route::put('/admin/types/edit/{id}', [TypeController::class, 'updateDataType'])->name('update.type');
        Route::delete('/admin/types/delete/{id}', [TypeController::class, 'deleteDataType'])->name('delete.type');
        // Uom
        Route::get('/admin/uom', [UomController::class, 'HalamanUom']);
        Route::get('/admin/uom', [UomController::class, 'HalamanUom'])->name('Admin.uom');
        Route::post('/add-uom', [UomController::class, 'AddDataUom'])->name('add.uom');
        Route::get('/get-uom', [UomController::class, 'GetUom'])->name('get.uom');
        Route::get('/admin/uoms', [UomController::class, 'Index'])->name('Admin.uom');
        Route::get('/admin/uoms/edit/{id}', [UomController::class, 'showEditForm'])->name('edit.uom');
        Route::put('/admin/uoms/edit/{id}', [UomController::class, 'updateDataUom'])->name('update.uom');
        Route::delete('/admin/uoms/delete/{id}', [UomController::class, 'deleteDataUom'])->name('delete.uom');
        // User
        Route::get('/admin/user', [UserController::class, 'HalamanUser']);
        Route::get('/admin/user', [UserController::class, 'HalamanUser'])->name('Admin.user');
        Route::post('/add-user', [UserController::class, 'AddDataUser'])->name('add.user');
        Route::get('/get-user', [UserController::class, 'GetUser'])->name('get.user');
        Route::get('/admin/users', [UserController::class, 'Index'])->name('Admin.user');
        Route::get('/admin/users/edit/{id}', [UserController::class, 'showEditForm'])->name('edit.user');
        Route::put('/admin/users/edit/{id}', [UserController::class, 'updateDataUser'])->name('update.user');
        Route::delete('/admin/users/delete/{id}', [UserController::class, 'deleteDataUser'])->name('delete.user');

        // Warranty
        Route::get('/admin/warranty', [WarrantyController::class, 'HalamanWarranty']);
        Route::get('/admin/warranty', [WarrantyController::class, 'HalamanWarranty'])->name('Admin.warranty');
        Route::post('/add-warranty', [WarrantyController::class, 'AddDataWarranty'])->name('add.warranty');
        Route::get('/get-warranty', [WarrantyController::class, 'GetWarranty'])->name('get.warranty');
        Route::get('/admin/warrantys', [WarrantyController::class, 'Index'])->name('Admin.warranty');
        Route::get('/admin/warrantys/edit/{id}', [WarrantyController::class, 'showEditForm'])->name('edit.warranty');
        Route::put('/admin/warrantys/edit/{id}', [WarrantyController::class, 'updateDataWarranty'])->name('update.warranty');
        Route::delete('/admin/warrantys/delete/{id}', [WarrantyController::class, 'deleteDataWarranty'])->name('delete.warranty');

        Route::get('/admin/get-warranty', [WarrantyController::class, 'GetWarranty']);

        // City
        Route::get('/admin/city', [CityController::class, 'HalamanCity']);
        Route::get('/admin/city', [CityController::class, 'HalamanCity'])->name('Admin.city');
        Route::post('/add-city', [CityController::class, 'AddDataCity'])->name('add.city');
        Route::get('/get-city', [CityController::class, 'GetCity'])->name('get.city');
        Route::get('/admin/citys', [CityController::class, 'Index'])->name('Admin.city');
        Route::get('/admin/citys/edit/{id}', [CityController::class, 'showEditForm'])->name('edit.city');
        Route::put('/admin/citys/edit/{id}', [CityController::class, 'updateDataCity'])->name('update.city');
        Route::delete('/admin/citys/delete/{id}', [CityController::class, 'deleteDataCity'])->name('delete.city');

        //tipe asset
        Route::get('/admin/tipe_asset', [TipeAssetController::class, 'HalamanTipe']);
        Route::get('/admin/tipe_asset', [TipeAssetController::class, 'HalamanTipe'])->name('Admin.type');
        Route::post('/add-type', [TipeAssetController::class, 'AddDataTipe'])->name('add.type');
        Route::get('/get-type', [TipeAssetController::class, 'getTipe'])->name('get.type');
        Route::get('/admin/types', [TipeAssetController::class, 'Index'])->name('Admin.type');
        Route::get('/admin/types/edit/{id}', [TipeAssetController::class, 'showEditForm'])->name('edit.type');
        Route::put('/admin/types/edit/{id}', [TipeAssetController::class, 'updateDataTipe'])->name('update.type');
        Route::delete('/admin/types/delete/{id}', [TipeAssetController::class, 'deleteDataTipe'])->name('delete.type');

        //Resto
        Route::get('/admin/get-resto', [RestoController::class, 'GetDataResto']);
        Route::get('/admin/get-initial-resto', [RestoController::class, 'getInitialRegisterLocation']);

        //Approval Maintenance

        //API Data
        // Route::get('/get-related-data', [RegistrasiAssetController::class, 'getAssetName']);
        // Route::get('/get_testing', [RegistrasiAssetController::class, 'getRelasiDatabase']);

        Route::get('/fetch-assets', [RegistrasiAssetController::class, 'getAssets']);


        //ASSET MOVEMENT

        // Disposal

        // Route::get('/admin/moveout', [MovementOutController::class, 'HalamanMoveOut'])->name('Admin.moveout');
        Route::get('/admin/add_data_moveout', [MovementOutController::class, 'LihatFormMoveOut'])->name('Admin.addMovement');

        //   Route::get('/admin/moveout', [MovementOutController::class, 'HalamanMoveOut']);
        Route::get('/admin/moveout', [MovementOutController::class, 'HalamanMoveOut'])->name('Admin.moveout');
        Route::post('/add-moveout', [MovementOutController::class, 'AddDataMoveOut'])->name('add.moveout');
        Route::get('/get-moveout', [MovementOutController::class, 'GetMoveOut'])->name('get.moveout');
        Route::get('/admin/moveouts', [MovementOutController::class, 'Index'])->name('Admin.moveouts');
        Route::get('/admin/moveouts/edit/{id}', [MovementOutController::class, 'showEditForm'])->name('edit.moveout');
        Route::get('/admin/moveouts/put/{id}', [MovementOutController::class, 'getDetails']);
        Route::get('/out-data/{out_id}', 'MovementOutController@getOutData');
        Route::get('/asset-data/{asset_id}', 'MovementOutController@getAssetData');
        Route::get('/admin/moveouts/put/{outId}', [MovementOutController::class, 'showPutFormMoveout']);
        Route::get('/admin/moveoutdetails/put/{outId}', [MovementOutController::class, 'showPutFormMoveoutDetail']);
        Route::put('/admin/moveouts/edit/{outId}', [MovementOutController::class, 'updateDataMoveOut'])->name('update.moveout');
        Route::delete('/admin/moveouts/delete/{id}', [MovementOutController::class, 'deleteDataMoveOut'])->name('delete.moveout');
        Route::get('/admin/moveouts/get-asset-details/{id}', [MovementOutController::class, 'getAssetDetails']);
        Route::get('/admin/moveouts/detail/{id}', [MovementOutController::class, 'getMoveoutDetail']);
        Route::get('/fetch-moveout-details/{id}', [MovementOutController::class, 'getDetails']);
        Route::get('/moveout/{id}', [MovementOutController::class, 'getMoveOutById']);
        Route::get('/moveout/{id}/edit', 'MovementOutController@edit');
        Route::get('/assets/{id}', 'AssetController@show');
        Route::get('admin/moveouts/print/{id}', [MovementOutController::class, 'printPDF'])->name('moveout.print');
        Route::get('/admin/moveout/preview', 'MovementOutController@previewPDF')->name('moveout.preview');
        Route::get('/admin/moveout/download', 'MovementOutController@downloadPDF')->name('moveout.download');
        Route::get('/admin/moveout/pdf-moveout/{out_id}', [MovementOutController::class, 'previewPDF'])->name('moveout.preview');
        // Route::get('/admin/moveout/filter', [MovementOutController::class, 'filter'])->name('moveout.filter');
        Route::post('/admin/moveout', [MovementOutController::class, 'filter'])->name('moveout.filter');

        Route::get('/admin/movein', [MovementInController::class, 'HalamanMoveIn']);
        Route::get('/admin/movein', [MovementInController::class, 'HalamanMoveIn'])->name('Admin.movein');
        Route::post('/add-movein', [MovementInController::class, 'AddDataMoveIn'])->name('add.movein');
        Route::get('/get-movein', [MovementInController::class, 'GetMoveIn'])->name('get.movein');
        Route::get('/admin/moveins', [MovementInController::class, 'Index'])->name('Admin.movein');
        Route::get('/admin/moveins/edit/{id}', [MovementInController::class, 'showEditForm'])->name('edit.movein');
        Route::put('/admin/moveins/edit/{id}', [MovementInController::class, 'updateDataMoveIn'])->name('update.movein');
        Route::delete('/admin/moveins/delete/{id}', [MovementInController::class, 'deleteDataMoveIn'])->name('delete.movein');
        Route::get('/get-asset-details/{id}', [MovementInController::class, 'getAssetDetails']);
        Route::get('/admin/moveins/detail/{id}', [MovementInController::class, 'getMoveinDetail']);
        Route::get('/fetch-movein-details/{id}', [MovementInController::class, 'getDetails']);
        Route::get('/movein/{id}/edit', 'MoveInController@edit');
        Route::get('/assets/{id}', 'AssetController@show');

        Route::get('/admin/data-movement', [MovementController::class, 'HalamanMove']);
        Route::get('/admin/data-movement', [MovementController::class, 'HalamanMove'])->name('Admin.data-movement');
        Route::post('/add-data-movement', [MovementController::class, 'AddDataMove'])->name('add.data-movement');
        Route::get('/get-data-movement', [MovementController::class, 'GetMove'])->name('get.data-movement');
        Route::get('/admin/data-movements', [MovementController::class, 'Index'])->name('Admin.data-movement');
        Route::get('/admin/data-movements/edit/{id}', [MovementController::class, 'showEditForm'])->name('edit.data-movement');
        Route::put('/admin/data-movements/edit/{id}', [MovementController::class, 'updateDataMove'])->name('update.data-movement');
        Route::delete('/admin/data-movements/delete/{id}', [MovementController::class, 'deleteDataMove'])->name('delete.data-movement');



        // Delivery & Confirm
        Route::get('/admin/delivery', [DeliveryController::class, 'HalamanDelivery']);
        Route::get('/admin/delivery', [DeliveryController::class, 'HalamanDelivery'])->name('Admin.delivery');
        Route::post('/add-delivery', [DeliveryController::class, 'AddDataDelivery'])->name('add.delivery');
        Route::get('/get-delivery', [DeliveryController::class, 'GetDelivery'])->name('get.delivery');
        Route::get('/admin/deliverys', [DeliveryController::class, 'Index'])->name('Admin.delivery');
        Route::get('/admin/deliverys/edit/{id}', [DeliveryController::class, 'showEditForm'])->name('edit.delivery');
        Route::put('/admin/deliverys/edit/{id}', [DeliveryController::class, 'updateDataDelivery'])->name('update.delivery');
        Route::delete('/admin/deliverys/delete/{id}', [DeliveryController::class, 'deleteDataDelivery'])->name('delete.delivery');

        Route::get('/admin/confirm', [DeliveryController::class, 'HalamanConfirm']);
        Route::get('/admin/confirm', [DeliveryController::class, 'HalamanConfirm'])->name('Admin.confirm');
        Route::post('/add-confirm', [DeliveryController::class, 'AddDataConfirm'])->name('add.confirm');
        Route::get('/get-confirm', [DeliveryController::class, 'GetConfirm'])->name('get.confirm');
        Route::get('/admin/confirms', [DeliveryController::class, 'IndexConfirm'])->name('Admin.confirm');
        Route::get('/admin/confirms/edit/{id}', [DeliveryController::class, 'showEditForm'])->name('edit.confirm');
        Route::put('/admin/confirms/edit/{id}', [DeliveryController::class, 'updateDataConfirm'])->name('update.confirm');
        Route::delete('/admin/confirms/delete/{id}', [DeliveryController::class, 'deleteDataConfirm'])->name('delete.confirm');


        // Approval
        Route::get('/admin/apprmoveout-am', [MovementController::class, 'HalamanAmo1']);
        Route::get('/admin/apprmoveout-am', [MovementController::class, 'HalamanAmo1'])->name('Admin.apprmoveout-am');
        Route::post('/add-apprmoveout-am', [MovementController::class, 'AddDataAmo1'])->name('add.apprmoveout-am');
        Route::get('/get-apprmoveout-am', [MovementController::class, 'GetAmo1'])->name('get.apprmoveout-am');
        Route::get('/admin/apprmoveout-ams', [MovementController::class, 'Index1'])->name('Admin.apprmoveout-am');
        Route::get('/admin/apprmoveout-ams/edit/{id}', [MovementController::class, 'showEditForm1'])->name('edit.apprmoveout-am');
        Route::put('/admin/apprmoveout-ams/edit/{id}', [MovementController::class, 'updateDataAmo1'])->name('update.apprmoveout-am');
        Route::delete('/admin/apprmoveout-ams/delete/{id}', [MovementController::class, 'deleteDataAmo1'])->name('delete.apprmoveout-am');

        Route::get('/admin/apprmoveout-rm', [MovementController::class, 'HalamanAmo2']);
        Route::get('/admin/apprmoveout-rm', [MovementController::class, 'HalamanAmo2'])->name('Admin.apprmoveout-rm');
        Route::post('/add-apprmoveout-rm', [MovementController::class, 'AddDataAmo2'])->name('add.apprmoveout-rm');
        Route::get('/get-apprmoveout-rm', [MovementController::class, 'GetAmo1'])->name('get.apprmoveout-rm');
        Route::get('/admin/apprmoveout-rms', [MovementController::class, 'Index2'])->name('Admin.apprmoveout-rm');
        Route::get('/admin/apprmoveout-rms/edit/{id}', [MovementController::class, 'showEditForm2'])->name('edit.apprmoveout-rm');
        Route::put('/admin/apprmoveout-rms/edit/{id}', [MovementController::class, 'updateDataAmo2'])->name('update.apprmoveout-rm');
        Route::delete('/admin/apprmoveout-rms/delete/{id}', [MovementController::class, 'deleteDataAmo2'])->name('delete.apprmoveout-rm');

        Route::get('/admin/apprmoveout-sdgasset', [MovementController::class, 'HalamanAmo3']);
        Route::get('/admin/apprmoveout-sdgasset', [MovementController::class, 'HalamanAmo3'])->name('Admin.apprmoveout-sdgasset');
        Route::post('/add-apprmoveout-sdgasset', [MovementController::class, 'AddDataAmo3'])->name('add.apprmoveout-sdgasset');
        Route::get('/get-apprmoveout-sdgasset', [MovementController::class, 'GetAmo1'])->name('get.apprmoveout-sdgasset');
        Route::get('/admin/apprmoveout-sdgassets', [MovementController::class, 'Index3'])->name('Admin.apprmoveout-sdgasset');
        Route::get('/admin/apprmoveout-sdgassets/edit/{id}', [MovementController::class, 'showEditForm3'])->name('edit.apprmoveout-sdgasset');
        Route::put('/admin/apprmoveout-sdgassets/edit/{id}', [MovementController::class, 'updateDataAmo3'])->name('update.apprmoveout-sdgasset');
        Route::delete('/admin/apprmoveout-sdgassets/delete/{id}', [MovementController::class, 'deleteDataAmo3'])->name('delete.apprmoveout-sdgasset');


        //Review
        Route::get('/admin/rev-head', [MovementController::class, 'HalamanHead']);
        Route::get('/admin/rev-head', [MovementController::class, 'HalamanHead'])->name('Admin.rev-head');
        Route::get('/admin/rev-mnr', [MovementController::class, 'HalamanMnr']);
        Route::get('/admin/rev-mnr', [MovementController::class, 'HalamanMnr'])->name('Admin.rev-mnr');
        Route::get('/admin/rev-taf', [MovementController::class, 'HalamanTaf']);
        Route::get('/admin/rev-taf', [MovementController::class, 'HalamanTaf'])->name('Admin.rev-taf');



        // Disposal
        Route::get('/admin/disout', [DisposalOutController::class, 'HalamanDisOut']);
        Route::get('/admin/disout', [DisposalOutController::class, 'HalamanDisOut'])->name('Admin.disout');
        Route::post('/add-disout', [DisposalOutController::class, 'AddDataDisOut'])->name('add.disposal_out');
        Route::get('/get-disout', [DisposalOutController::class, 'GetDisOut'])->name('get.disout');
        Route::get('/admin/disouts', [DisposalOutController::class, 'Index'])->name('Admin.disout');
        //  Route::get('/admin/disouts/edit/{id}', [DisposalOutController::class, 'showEditForm'])->name('edit.disout');
        //  Route::get('/admin/disouts/put/{id}', [DisposalOutController::class, 'showPutForm']);
        //  Route::put('/admin/disouts/edit/{id}', [DisposalOutController::class, 'updateDataDisOut'])->name('update.disout');
        Route::delete('/admin/disouts/delete/{id}', [DisposalOutController::class, 'deleteDataDisOut'])->name('delete.disout');
        Route::get('/admin/disouts/get-asset-details/{id}', [DisposalOutController::class, 'getAssetDetails']);
        Route::get('/admin/disouts/detail/{id}', [DisposalOutController::class, 'getDisoutDetail']);
        Route::get('/fetch-disout-details/{id}', [DisposalOutController::class, 'getDetails']);
        Route::get('/disout/{id}', [DisposalOutController::class, 'getDisOutById']);
        Route::get('/disout/{id}/edit', 'DisposalController@edit');
        Route::get('/assets/{id}', 'AssetController@show');


        Route::get('/admin/add_data_disposal', [DisposalOutController::class, 'addPageDataDisposalOut']);

        Route::get('/admin/get_detail_data_disposal_out/{id}', [DisposalOutController::class, 'detailPageDataDisposalOut']);

        Route::post('/admin/filter_data_disposal', [DisposalOutController::class, 'filter'])->name('admin.filterdisout');

        Route::get('/admin/disout/print/{id}', [DisposalOutController::class, 'previewPDF'])->name('admin.disoutPDF');

        Route::get('/admin/edit_data_disposal_out/{id}', [DisposalOutController::class, 'editDetailDataDisout']);

        Route::get('/api/get-asset-disposal-detail/{id}', [DisposalOutController::class, 'getAjaxAssetDisposalDetails']);

        Route::put('/admin/update_data_disposal_out/{outId}', [DisposalOutController::class, 'updateDataDisOut']);

        Route::get('/api/get_data_disposal', [DisposalOutController::class, 'getAjaxDataDisposal']);


        Route::get('/admin/disin', [DisposalInController::class, 'HalamanMoveIn']);
        Route::get('/admin/disin', [DisposalInController::class, 'HalamanMoveIn'])->name('Admin.movein');
        Route::post('/add-disin', [DisposalInController::class, 'AddDataMoveIn'])->name('add.movein');
        Route::get('/get-disin', [DisposalInController::class, 'GetMoveIn'])->name('get.movein');
        Route::get('/admin/disins', [DisposalInController::class, 'Index'])->name('Admin.movein');
        Route::get('/admin/disins/edit/{id}', [DisposalInController::class, 'showEditForm'])->name('edit.movein');
        Route::put('/admin/disins/edit/{id}', [DisposalInController::class, 'updateDataMoveIn'])->name('update.movein');
        Route::delete('/admin/disins/delete/{id}', [DisposalInController::class, 'deleteDataMoveIn'])->name('delete.movein');
        Route::get('/get-asset-details/{id}', [DisposalInController::class, 'getAssetDetails']);
        Route::get('/admin/disins/detail/{id}', [DisposalInController::class, 'getMoveinDetail']);
        Route::get('/fetch-disin-details/{id}', [DisposalInController::class, 'getDetails']);
        Route::get('/disin/{id}/edit', 'MoveInController@edit');
        Route::get('/assets/{id}', 'AssetController@show');

        Route::get('/admin/data-disposal', [DisposalController::class, 'HalamanDisposal']);
        Route::get('/admin/data-disposal', [DisposalController::class, 'HalamanDisposal'])->name('Admin.data-disposal');
        Route::post('/add-data-disposal', [DisposalController::class, 'AddDataDisposal'])->name('add.data-disposal');
        Route::get('/get-data-disposal', [DisposalController::class, 'GetDisposal'])->name('get.data-disposal');
        Route::get('/admin/data-disposals', [DisposalController::class, 'Index'])->name('Admin.data-disposal');
        Route::get('/admin/data-disposals/edit/{id}', [DisposalController::class, 'showEditForm'])->name('edit.data-disposal');
        Route::put('/admin/data-disposals/edit/{id}', [DisposalController::class, 'updateDataDisposal'])->name('update.data-disposal');
        Route::delete('/admin/data-disposals/delete/{id}', [DisposalController::class, 'deleteDataDisposal'])->name('delete.data-disposal');


        // Delivery Dispostal
        Route::get('/admin/deliverydisposal', [DeliveryDisposalController::class, 'HalamanDeliveryDisposal']);
        Route::get('/admin/deliverydisposal', [DeliveryDisposalController::class, 'HalamanDeliveryDisposal'])->name('Admin.deliverydisposal');
        Route::post('/add-deliverydisposal', [DeliveryDisposalController::class, 'AddDataDeliveryDisposal'])->name('add.deliverydisposal');
        Route::get('/get-deliverydisposal', [DeliveryDisposalController::class, 'GetDeliveryDisposal'])->name('get.deliverydisposal');
        Route::get('/admin/deliverydisposals', [DeliveryDisposalController::class, 'Index'])->name('Admin.deliverydisposal');
        Route::get('/admin/deliverydisposals/edit/{id}', [DeliveryDisposalController::class, 'showEditForm'])->name('edit.deliverydisposal');
        Route::put('/admin/deliverydisposals/edit/{id}', [DeliveryDisposalController::class, 'updateDataDeliveryDisposal'])->name('update.deliverydisposal');
        Route::delete('/admin/deliverydisposals/delete/{id}', [DeliveryDisposalController::class, 'deleteDataDeliveryDisposal'])->name('delete.deliverydisposal');

        Route::get('/admin/confirmdis', [DeliveryDisposalController::class, 'HalamanConfirmDis']);
        Route::get('/admin/confirmdis', [DeliveryDisposalController::class, 'HalamanConfirmDis'])->name('Admin.confirmdis');
        Route::post('/add-confirmdis', [DeliveryDisposalController::class, 'AddDataConfirmDis'])->name('add.confirmdis');
        Route::get('/get-confirmdis', [DeliveryDisposalController::class, 'GetConfirmDis'])->name('get.confirmdis');
        Route::get('/admin/confirmdiss', [DeliveryDisposalController::class, 'IndexConfirmDis'])->name('Admin.confirmdis');
        Route::get('/admin/confirmdiss/edit/{id}', [DeliveryDisposalController::class, 'showEditForm'])->name('edit.confirmdis');
        Route::put('/admin/confirmdiss/edit/{id}', [DeliveryDisposalController::class, 'updateDataConfirmDis'])->name('update.confirmdis');
        Route::delete('/admin/confirmdiss/delete/{id}', [DeliveryDisposalController::class, 'deleteDataConfirmDis'])->name('delete.confirmdis');



        // Approval
        Route::get('/admin/apprdis-am', [DisposalController::class, 'HalamanAmd1']);
        Route::get('/admin/apprdis-am', [DisposalController::class, 'HalamanAmd1'])->name('Admin.apprdis-am');
        Route::post('/add-apprdis-am', [DisposalController::class, 'AddDataAmd1'])->name('add.apprdis-am');
        Route::get('/get-apprdis-am', [DisposalController::class, 'GetAmd1'])->name('get.apprdis-am');
        Route::get('/admin/apprdis-ams', [DisposalController::class, 'Index1'])->name('Admin.apprdis-am');
        Route::get('/admin/apprdis-ams/edit/{id}', [DisposalController::class, 'showEditForm1'])->name('edit.apprdis-am');
        Route::put('/admin/apprdis-ams/edit/{id}', [DisposalController::class, 'updateDataAmd1'])->name('update.apprdis-am');
        Route::delete('/admin/apprdis-ams/delete/{id}', [DisposalController::class, 'deleteDataAmd1'])->name('delete.apprdis-am');

        Route::get('/admin/apprdis-rm', [DisposalController::class, 'HalamanAmd2']);
        Route::get('/admin/apprdis-rm', [DisposalController::class, 'HalamanAmd2'])->name('Admin.apprdis-rm');
        Route::post('/add-apprdis-rm', [DisposalController::class, 'AddDataAmd2'])->name('add.apprdis-rm');
        Route::get('/get-apprdis-rm', [DisposalController::class, 'GetAmd1'])->name('get.apprdis-rm');
        Route::get('/admin/apprdis-rms', [DisposalController::class, 'Index2'])->name('Admin.apprdis-rm');
        Route::get('/admin/apprdis-rms/edit/{id}', [DisposalController::class, 'showEditForm2'])->name('edit.apprdis-rm');
        Route::put('/admin/apprdis-rms/edit/{id}', [DisposalController::class, 'updateDataAmd2'])->name('update.apprdis-rm');
        Route::delete('/admin/apprdis-rms/delete/{id}', [DisposalController::class, 'deleteDataAmd2'])->name('delete.apprdis-rm');

        Route::get('/admin/apprdis-sdgasset', [DisposalController::class, 'HalamanAmd3']);
        Route::get('/admin/apprdis-sdgasset', [DisposalController::class, 'HalamanAmd3'])->name('Admin.apprdis-sdgasset');
        Route::post('/add-apprdis-sdgasset', [DisposalController::class, 'AddDataAmd3'])->name('add.apprdis-sdgasset');
        Route::get('/get-apprdis-sdgasset', [DisposalController::class, 'GetAmd1'])->name('get.apprdis-sdgasset');
        Route::get('/admin/apprdis-sdgassets', [DisposalController::class, 'Index3'])->name('Admin.apprdis-sdgasset');
        Route::get('/admin/apprdis-sdgassets/edit/{id}', [DisposalController::class, 'showEditForm3'])->name('edit.apprdis-sdgasset');
        Route::put('/admin/apprdis-sdgassets/edit/{id}', [DisposalController::class, 'updateDataAmd3'])->name('update.apprdis-sdgasset');
        Route::delete('/admin/apprdis-sdgassets/delete/{id}', [DisposalController::class, 'deleteDataAmd3'])->name('delete.apprdis-sdgasset');


        //Review
        Route::get('/admin/review-disposal', [DisposalController::class, 'HalamanReview']);
        Route::get('/admin/revdis-head', [DisposalController::class, 'HalamanHead'])->name('Admin.revdis-head');
        Route::get('/admin/revdis-mnr', [DisposalController::class, 'HalamanMnr']);
        Route::get('/admin/revdis-mnr', [DisposalController::class, 'HalamanMnr'])->name('Admin.revdis-mnr');
        Route::get('/admin/revdis-taf', [DisposalController::class, 'HalamanTaf']);
        Route::get('/admin/revdis-taf', [DisposalController::class, 'HalamanTaf'])->name('Admin.revdis-taf');


        // Stock Opname
        Route::get('/admin/stockopname', [StockOpnameController::class, 'HalamanStockOpname'])->name('admin.stockopname');
        Route::post('/add-stockopname', [StockOpnameController::class, 'AddDataStockOpname'])->name('add.stockopname');
        Route::get('/get-stockopname', [StockOpnameController::class, 'GetStockOpname'])->name('get.stockopname');
        Route::get('/admin/stockopnames', [StockOpnameController::class, 'Index'])->name('Admin.stockopname');
        Route::get('/admin/stockopnames/edit/{id}', [StockOpnameController::class, 'showEditForm'])->name('edit.stockopname');
        Route::get('/admin/stockopnames/put/{opnameId}', [StockOpnameController::class, 'showPutFormStockOpname']);
        Route::put('/admin/stockopnames/edit/{id}', [StockOpnameController::class, 'UpdateDetailDataStockOpname'])->name('update.stockopname');
        Route::delete('/admin/stockopnames/delete/{id}', [StockOpnameController::class, 'deleteDataStockOpname'])->name('delete.stockopname');
        Route::get('/get-asset-details/{id}', [StockOpnameController::class, 'getAssetDetails']);
        Route::get('/admin/stockopnames/detail/{id}', [StockOpnameController::class, 'getStockOpnameDetail']);
        Route::get('/fetch-stockopname-details/{id}', [StockOpnameController::class, 'getDetails']);
        Route::get('/stockopname/{id}', [StockOpnameController::class, 'getStockOpnameById']);
        Route::get('/stockopname/{id}/edit', 'StockOpnameController@edit');
        Route::post('/stockopname/upload_excel', [StockOpnameController::class, 'ImportDataExcelStockOpname'])->name('import.stockopname');
        Route::get('/assets/{id}', 'AssetController@show');
        Route::post('/admin/stockopname', [StockOpnameController::class, 'filter'])->name('stockopname.filter');
        //  Route::get('/admin/stockopname/pdf-stockopname/{opname_id}', [StockOpnameController::class, 'previewPDF'])->name('stockopname.preview');
        Route::get('/admin/stockopname/export_pdf/{id}', [StockOpnameController::class, 'ExportPDFStockOpname']);


        Route::get('/admin/stockopname/add_data_stockopname', [StockOpnameController::class, 'HalamanAddDataStockOpname']);
        Route::get('/admin/stockopname/edit_data_stockopname', [StockOpnameController::class, 'HalamanEditDataStockOpname']);

        Route::get('/api/getCodeStockOpname', [StockOpnameController::class, 'GetCodeStockOpname']);
        Route::get('/api/get-stockopname-asset-details', [StockOpnameController::class, 'getAjaxDataAssetsStockOpname']);

        Route::get('/api/get-asset-details-stockopname/{id}', [StockOpnameController::class, 'getAjaxAssetDetailsStockOpname']);

        Route::get('/admin/stockopname/get_detail_data_stock_opname/{id}', [StockOpnameController::class, 'GetDetailDataStockOpname']);
        Route::get('/api/get-out-stock-opname-detail/{outId}', [StockOpnameController::class, 'GetDetailDataStockOpnameDetails']);

        Route::get('/admin/stokopname/edit_detail_data_stock_opname/{id}', [StockOpnameController::class, 'EditDetailDataStockOpname']);
        Route::put('/admin/stockopname/update_detail_data_stock_opname/{id}', [StockOpnameController::class, 'UpdateDetailDataStockOpname']);


        //ADJUSMENT STOCK
        Route::get('/admin/adjustmentstock', [StockOpnameController::class, 'LihatDataAdjusmentStock']);



        //TIPE MAINTENANCE ROUTES
        Route::get('/admin/tipe_maintenance', [TipeMaintenanceController::class, 'HalamanTipeMaintenance']);
        Route::get('/admin/tipe_maintenance', [TipeMaintenanceController::class, 'HalamanTipeMaintenance'])->name('Admin.tipe_maintenance');
        Route::post('/add-tipe-maintenance', [TipeMaintenanceController::class, 'AddDataTipeMaintenance'])->name('add-tipe-maintenance');
        Route::get('/admin/ajax-get-tipe-maintenance', [TipeMaintenanceController::class, 'GetTipeMaintenance']);
        Route::get('/admin/get_tipe_maintenance', [TipeMaintenanceController::class, 'Index'])->name('Admin.tipe_maintenance');
        Route::get('/admin/tipe_maintenance/edit/{id}', [TipeMaintenanceController::class, 'showEditForm'])->name('edit.tipe_maintenance');
        Route::put('/admin/tipe_maintenance/edit/{id}', [TipeMaintenanceController::class, 'updateDataTipeMaintenance'])->name('update.tipe_maintenance');
        Route::delete('/admin/tipe_maintenance/delete/{id}', [TipeMaintenanceController::class, 'deleteDataTipeMaintenance'])->name('delete.tipe_maintenance');

        // REPORT ROUTES
        Route::get('/reports/registrasi_asset_report', [ReportController::class, 'ReportRegistrasiAsset']);

        Route::get('/reports/get_data_registrasi_asset_report', [ReportController::class, 'ReportGetDataRegistrasiAsset']);

        Route::get('/reports/mutasi_stock_asset', [ReportController::class, 'ReportMutasiStock']);
        Route::get('/reports/get_data_mutasi_stock', [ReportController::class, 'ReportMutasiStockData']);

        Route::get('/reports/kartu_stock_asset', [ReportController::class, 'ReportKartuStock']);
        Route::get('/reports/get_data_kartu_stock_asset', [ReportController::class, 'GetDataKartuStock']);

        Route::get('/reports/export_excel_mutasi_stock', [ReportController::class, 'ExportExcelMutasiStock']);

        Route::get('/reports/checklist_asset', [ReportController::class, 'ReportChecklistAsset']);
        Route::get('/reports/maintenance_asset', [ReportController::class, 'ReportMaintenaceAsset']);
        Route::get('/reports/history_maintenance_asset', [ReportController::class, 'ReportHistoryMaintenace']);

        Route::get('/reports/stock_asset_per_location', [ReportController::class, 'ReportStockAssetPerLocation']);
        Route::get('/reports/get_data_stock_assset_per_location', [ReportController::class, 'GetDataStockAssetPerLocation']);


        Route::get('/reports/garansi_asset', [ReportController::class, 'ReportGaransiAsset']);
        Route::get('/reports/disposal_asset', [ReportController::class, 'ReportDisposalAsset']);

        Route::get('/reports/export_excel_disposal_out', [ReportController::class, 'ExportExcelDisposalAssetData']);

        Route::get('/reports/get_data_disposal_asset', [ReportController::class, 'ReportDisposalAssetData']);

        Route::get('/reports/stock_opname', [ReportController::class, 'ReportStockOpname']);
        Route::get('/reports/get_data_stock_opname', [ReportController::class, 'ReportStockOpnameData']);

        Route::get('/reports/trend_issue_maintenace', [ReportController::class, 'ReportTrendIssue']);


        //Route Master Resto
        Route::get('/admin/resto', [RestoController::class, 'DataResto']);

        // Adjustment Stock Opname
        //  Route::get('/admin/adjuststock', [StockOpnameController::class, 'HalamanAdjustStock']);
        //  Route::get('/admin/adjuststock', [StockOpnameController::class, 'HalamanAdjustStock'])->name('Admin.adjuststock');
        //  Route::post('/add-adjuststock', [StockOpnameController::class, 'AddDataAdjustStock'])->name('add.adjuststock');
        //  Route::get('/get-adjuststock', [StockOpnameController::class, 'GetAdjustStock'])->name('get.adjuststock');
        //  Route::get('/admin/adjuststocks', [StockOpnameController::class, 'IndexA'])->name('Admin.adjuststock');
        //  Route::get('/admin/adjuststocks/edit/{id}', [StockOpnameController::class, 'showEditForm'])->name('edit.adjuststock');
        //  Route::get('/admin/adjuststocks/put/{opnameId}', [StockOpnameController::class, 'showPutFormAdjustStock']);
        //  Route::put('/admin/adjuststocks/edit/{id}', [StockOpnameController::class, 'updateDataAdjustStock'])->name('update.adjuststock');
        //  Route::delete('/admin/adjuststocks/delete/{id}', [StockOpnameController::class, 'deleteDataAdjustStock'])->name('delete.adjuststock');
        //  Route::get('/get-asset-details/{id}', [StockOpnameController::class, 'getAssetDetails']);
        //  Route::get('/admin/adjuststocks/detail/{id}', [StockOpnameController::class, 'getAdjustStockDetail']);
        //  Route::get('/fetch-adjuststock-details/{id}', [StockOpnameController::class, 'getDetails']);
        //  Route::get('/adjuststock/{id}', [StockOpnameController::class, 'getAdjustStockById']);
        //  Route::get('/adjuststock/{id}/edit', 'StockOpnameController@edit');
        //  Route::get('/assets/{id}', 'AssetController@show');

    });
});

// Schedule Routes
Route::prefix('schedule')->group(function () {
    Route::get('lihat_data_schedule', [ScheduleController::class, 'LihatDataSchedule']);
    Route::get('detail_data_schedule', [ScheduleController::class, 'DetailDataSchedule']);
});

//Preventive Maintenance Routes
Route::prefix('preventive_maintenance')->group(function () {
    Route::get('lihat_data_preventive_maintenance', [PreventiveMaintenanceController::class, 'LihatDataPreventiveMaintenance']);
});

//corrective maintenance routes
Route::prefix('corrective_maintenance')->group(function () {
    Route::get('lihat_data_corrective_maintenance', [CorrectiveMaintenanceController::class, 'LihatDataCorrectiveMaintenance']);
});

Route::prefix('checklist')->group(function () {
    Route::get('lihat_data_checklist', [ChecklistController::class, 'HalamanChecklist']);
});



Route::group([RoleMiddleware::class => ':user'], function () {
    Route::get('/user/dashboard', [UserAccountController::class, 'Index']);
    Route::get('/user/get_data_total_asset', [UserAccountController::class, 'GetTotalDataAsset']);
    Route::get('/user/get_data_total_asset_rusak', [UserAccountController::class, 'GetTotalDataAssetRusak']);
    Route::get('/user/get_data_total_asset_bagus', [UserAccountController::class, 'GetTotalDataAssetBagus']);
});


Route::group(['middleware' => [RoleMiddleware::class . ':am'], 'prefix' => 'am'], function () {

    Route::get('/dashboard', [AmController::class, 'dashboard']);
    Route::get('/moveout', [AmController::class, 'HalamanMovementOut']);

    //disposal
    Route::get('/disposal', [AmController::class, 'HalamanDisposalAM']);
    Route::get('/disposal', [AmController::class, 'HalamanDisposalAM'])->name('am.disposal');
    Route::get('/tambah_data_disposal', [AmController::class, 'HalamanAddDataDisposal']);

    Route::post('/add-disout', [AmController::class, 'AddDataDisOut'])->name('add.disout');

    Route::post('/filter_data_disposal', [AmController::class, 'filterDisposal']);

    Route::get('/get_detail_data_disposal/{id}', [AmController::class, 'detailPageDataDisposalOut']);

    Route::get('/apprdis-am', [AmController::class, 'HalamanAmd1']);
    //end disposal

    Route::get('/apprmoveout-am', [AmController::class, 'HalamanAmo1']);
    Route::get('/apprmoveout-am', [AmController::class, 'HalamanAmo1'])->name('Admin.apprmoveout-am');
    Route::post('/add-apprmoveout-am', [AmController::class, 'AddDataAmo1'])->name('add.apprmoveout-am');
    Route::get('/get-apprmoveout-am', [AmController::class, 'GetAmo1'])->name('get.apprmoveout-am');
    Route::get('/apprmoveout-ams', [AmController::class, 'Index1'])->name('Admin.apprmoveout-am');
    Route::get('/apprmoveout-ams/edit/{id}', [AmController::class, 'showEditForm1'])->name('edit.apprmoveout-am');
    Route::put('/apprmoveout-ams/edit/{id}', [AmController::class, 'updateDataAmo1'])->name('update.apprmoveout-am');

    Route::get('/confirm', [AmController::class, 'HalamanConfirm']);
});



Route::group([RoleMiddleware::class => ':sdg', 'prefix' => 'sdg'], function () {
    Route::get('/dashboard', [SDGControllers::class, 'index']);
    Route::get('/get-resto-json', [SDGControllers::class, 'GetDataResto']);
    Route::get('/apprdis-sdgasset', [SDGControllers::class, 'HalamanAmd3']);
    Route::get('/get_detail_data_disposal/{id}', [SDGControllers::class, 'DetailPageDataDisposalOut']);
});

Route::group([RoleMiddleware::class => ':ops'], function () {});


Route::group([RoleMiddleware::class => ':mnr'], function () {});


Route::group([RoleMiddleware::class => ':taf'], function () {});

Route::group([RoleMiddleware::class => ':rm', 'prefix' => 'rm'], function () {
    Route::get('/dashboard', [RestoManagerController::class, 'dashboard']);

    //halaman register

    Route::get('/registrasi_asset', [RestoManagerController::class, 'HalamanRegistrasiAsset']);

    //halaman movement
    Route::get('/movement/lihat_data_movement', [RestoManagerController::class, 'HalamanLihatDataMovement']);

    Route::get('/apprmoveout-rm', [MovementController::class, 'HalamanAmo2']);
    Route::get('/apprmoveout-rm', [MovementController::class, 'HalamanAmo2'])->name('Admin.apprmoveout-rm');
    Route::post('/add-apprmoveout-rm', [MovementController::class, 'AddDataAmo2'])->name('add.apprmoveout-rm');
    Route::get('/get-apprmoveout-rm', [MovementController::class, 'GetAmo1'])->name('get.apprmoveout-rm');
    Route::get('/apprmoveout-rms', [MovementController::class, 'Index2'])->name('Admin.apprmoveout-rm');
    Route::get('/apprmoveout-rms/edit/{id}', [MovementController::class, 'showEditForm2'])->name('edit.apprmoveout-rm');
    Route::put('/apprmoveout-rms/edit/{id}', [MovementController::class, 'updateDataAmo2'])->name('update.apprmoveout-rm');

    Route::put('/confirms/edit/{id}', [DeliveryController::class, 'updateDataConfirm'])->name('update.confirm');

    Route::get('/confirm', [RestoManagerController::class, 'HalamanConfirm']);

    Route::get('/apprdis-rm', [RestoManagerController::class, 'HalamanAmd2']);
    Route::get('/get_detail_data_disposal/{id}', [RestoManagerController::class, 'DetailPageDataDisposalOut']);
});

Route::group([RoleMiddleware::class => ':sm', 'prefix' => 'sm'], function () {

    // Route::get('/dashboard', [StoreManagerController::class, 'dashboard']);


    //halaman registrasi asset
    Route::get('/registrasi_asset', [StoreManagerController::class, 'HalamanRegistrasiAsset']);

    //halaman movement
    Route::get('/movement/lihat_data_movement', [StoreManagerController::class, 'HalamanMovement']);
    Route::get('/movement/add_data_movement', [StoreManagerController::class, 'HalamanAddMoveout'])->name('SM.addMovement');
    Route::get('/api/get-location', [StoreManagerController::class, 'getLocationUser']);
    Route::post('/movement/tambah_data_movement', [StoreManagerController::class, 'AddDataMoveOut']);
    Route::post('/movement/lihat_data_movement', [StoreManagerController::class, 'filter'])->name('moveout.filter.sm');

    Route::get('/confirm', [StoreManagerController::class, 'HalamanConfirm']);


    Route::get('/api/ajaxGetAssetMovement', [StoreManagerController::class, 'ajaxGetAssetMovement']);
    Route::get('/api/get-data-movement', [StoreManagerController::class, 'getAjaxDataMovement']);


    Route::get('/movement/data_confirm', [StoreManagerController::class, 'DataConfirmationSM']);
    Route::put('/movement/confirms/edit/{id}', [StoreManagerController::class, 'updateDataConfirm'])->name('update.confirm_sm');

    Route::get('/api/get-movement-details/{id}', [StoreManagerController::class, 'getAjaxMovementSMDetails']);

    //disposal routes
    Route::get('/disposal', [StoreManagerController::class, 'HalamanDisposalSM']);
    Route::get('/disposal', [StoreManagerController::class, 'HalamanDisposalSM'])->name('sm.disposal');
    Route::get('/tambah_data_disposal', [StoreManagerController::class, 'HalamanAddDataDisposal']);

    Route::post('/add-disout', [StoreManagerController::class, 'AddDataDisOut'])->name('add.disout');

    Route::post('/filter_data_disposal', [StoreManagerController::class, 'filterDisposal']);

    Route::get('/get_detail_data_disposal_out/{id}', [StoreManagerController::class, 'detailPageDataDisposalOut']);


    Route::get('/api/get-data-disposal', [StoreManagerController::class, 'getAjaxDataDisposal']);
    Route::get('/api/ajaxGetAssetDisposal', [StoreManagerController::class, 'ajaxGetAssetDisposal']);
    Route::get('/api/get-disposal-details/{id}', [StoreManagerController::class, 'getAjaxDisposalSMDetails']);
});


// Route::group(['middleware' => ['auth', 'role:am']], function () {
//   Route::get('/am/dashboard', [AmController::class, 'dashboard'])->name('am.dashboard');
// });
