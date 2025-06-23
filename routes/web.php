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
use App\Http\Controllers\StockOpname\StockOpnameController as SoStockOpname;
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
    Route::get('/api/get-data-assets/{id}', [MovementOutController::class, 'getAjaxDataAssets']);
    Route::get('/api/get-asset-details/{id}', [MovementOutController::class, 'getAjaxAssetDetails']);
    Route::get('/admin/edit_data_movement/{id}', [MovementOutController::class, 'editDataDetailMovement']);
    Route::get('/api/get-location', [MovementOutController::class, 'getLocationUser']);
    Route::get('/api/get-condition', [MovementOutController::class, 'getCondition']);

    Route::get('/api/ajax-get-location', [MovementOutController::class, 'getLocation']);

    Route::get('/api/ajaxGetDataRegistAsset/{lokasi_user}', [MovementOutController::class, 'ajaxGetDataRegistAsset']);
    Route::get('/api/ajaxGetDataRegistAssetSO/{lokasi_user}', [MovementOutController::class, 'ajaxGetDataRegistAssetSO']);
    Route::get('/api/ajaxGetDataRegistDisposalAsset', [MovementOutController::class, 'ajaxGetDataRegistDisposalAsset']);
    Route::get('/api/searchRegisterAsset', [MovementOutController::class, 'searchRegisterAsset']);
    Route::get('/admin/get_detail_data_movement/{id}', [MovementOutController::class, 'dataDetailMovement']);

    Route::get('/api/get-out-details/{outId}', [MovementOutController::class, 'getOutDetails']);
    Route::get('/api/get-edit-out-details/{outId}', [MovementOutController::class, 'getEditOutDetails']);


    Route::get('/api/check-location-relation/{fromLoc}', [MovementOutController::class, 'checkLocationRelation']);
//


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
        Route::get('/registration/add-assets-registration', 'addAssetsRegist')->middleware(['permission:view registration add']);
        Route::post('/registration/add-assets-registration', 'InsertAssetsRegist')->middleware(['permission:view registration add']);
        Route::get('/registration/edit-assets-registration/{id}', 'EditAssetsRegist')->middleware(['permission:view registration update']);
        Route::post('/registration/update-assets-registration/{id}', 'UpdateAssetsRegist')->middleware(['permission:view registration']);
        Route::post('/registration/delete-assets-registration/{id}', 'DeleteDataRegistrasiAsset')->middleware(['permission:view registration']);
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
        Route::get('/registration/details/{register_code}', 'TampilDataQR')->name('assets.details');

        // Route::get('/lihat_data_registrasi_asset', [RegistrasiAssetController::class, 'HalamanRegistrasiAsset']);
        // Route::post('/tambah_data_registrasi_asset', [RegistrasiAssetController::class, 'AddDataRegistrasiAsset']);
        // Route::get('/update/{id}', [RegistrasiAssetController::class, 'GetDetailDataRegistrasiAsset']);
        // Route::get('/laman_tambah_registrasi_asset', [RegistrasiAssetController::class, 'LamanTambahRegistrasi'])->name('laman_tambah_registrasi_asset');

        // Route::get('/registration/approval-ops-sm', 'HalamanApproval')->middleware(['permission:view registration']);


        // Route::get('/admin/approval-reg', [AssetsController::class, 'HalamanApproval']);
        // Route::get('/admin/approval-reg', [AssetsController::class, 'HalamanApproval'])->name('Admin.approval-reg');
        // Route::post('/add-approval-reg', [AssetsController::class, 'AddDataApproval'])->name('add.approval-reg');
        // Route::get('/get-approval-reg', [AssetsController::class, 'GetApproval'])->name('get.approval-reg');
        // Route::get('/admin/approval-regs', [AssetsController::class, 'Index'])->name('Admin.approval-reg');
        // Route::get('/admin/approval-regs/edit/{id}', [AssetsController::class, 'showEditForm'])->name('edit.approval-reg');
        // Route::post('/admin/approval-regs/edit/{id}', [AssetsController::class, 'updateDataApproval'])->name('update.approval-reg');
        // Route::post('/admin/approval-regs/delete/{id}', [AssetsController::class, 'deleteDataApproval'])->name('delete.approval-reg');

    });
    Route::controller(RequestMoveout::class)->group(function () {
        Route::get('/api/asset-transfer/get-from-locations', 'getFromLocations')->middleware(['permission:view at request moveout']);
        Route::get('/api/asset-transfer/get-dest-locations', 'getDestLocations')->middleware(['permission:view at request moveout']);
        Route::get('/api/asset-transfer/get-data-assets', 'getAjaxDataAssets')->middleware(['permission:view at request moveout']);

        Route::get('/asset-transfer/request-moveout', 'HalamanMoveOut')->middleware(['permission:view at request moveout']);
        Route::post('/asset-transfer/request-moveout/filter', 'filter')->middleware(['permission:view at request moveout']);
        Route::get('/asset-transfer/request-moveout/edit/{id}', 'editDataDetailMovement')->middleware(['permission:view at request moveout edit']);
        Route::post('/asset-transfer/request-moveout/update/{id}', 'updateDataMoveOut')->middleware(['permission:view at request moveout edit']);
        Route::post('/asset-transfer/request-moveout/delete/{id}', 'deleteDataMoveOut')->middleware(['permission:view at request moveout']);

        Route::get('/asset-transfer/add-request-moveout', 'LihatFormMoveOut')->middleware(['permission:view at request moveout add']);
        Route::post('/asset-transfer/add-request-moveout', 'AddDataMoveOut')->middleware(['permission:view at request moveout add']);
        Route::get('/asset-transfer/detail-request-moveout/{id}', 'dataDetailMovement')->middleware(['permission:view at request moveout']);
        Route::get('/asset-transfer/pdf-request-moveout/{id}', 'previewPDF')->middleware(['permission:view at request moveout']);
    });
    Route::controller(ApprovalOpsAM::class)->group(function () {
        Route::get('/asset-transfer/approval-ops-am', 'index')->middleware(['permission:view at approval ops am']);
        Route::post('/asset-transfer/approval-ops-am/update/{id}', 'updateApprovalAmAM')->middleware(['permission:view at approval ops am']);
    });
    Route::controller(ApprovalOpsRM::class)->group(function () {
        Route::get('/asset-transfer/approval-ops-rm', 'index')->middleware(['permission:view at approval ops rm']);
        Route::post('/asset-transfer/approval-ops-rm/edit/{id}', 'updateDataApprRM')->middleware(['permission:view at approval ops rm']);
    });
    Route::controller(ApprovalSDGAsset::class)->group(function () {
        Route::get('/asset-transfer/approval-sdg-asset', 'index')->middleware(['permission:view at approval ops sdg']);
        Route::post('/asset-transfer/approval-sdg-asset/edit/{id}', 'updateDataApprSDG')->middleware(['permission:view at approval ops sdg']);
    });
    Route::controller(RequestMoveIn::class)->group(function () {
        Route::get('/asset-transfer/request-movement-in', 'index')->middleware(['permission:view at request movement in']);
    });
    Route::controller(DeliveryOrder::class)->group(function () {
        Route::get('/asset-transfer/delivery-order', 'index')->middleware(['permission:view at delivery order']);
    });
    Route::controller(ConfirmAsset::class)->group(function () {
        Route::get('/asset-transfer/confirm-asset', 'index')->middleware(['permission:view at confirm asset']);
        Route::post('/asset-transfer/confirm-asset/edit/{id}', 'updateDataConfirm')->middleware(['permission:view at confirm asset']);
    });
    Route::controller(ReviewAssetTransfer::class)->group(function () {
        Route::get('/asset-transfer/review', 'head')->middleware(['permission:view at head']);
    });
    Route::controller(RequestDisposal::class)->group(function () {
        Route::get('/disposal/request-disposal', 'index')->middleware(['permission:view dis request disposal']);
        Route::get('/disposal/add-request-disposal', 'AddRequestDisposal')->middleware(['permission:view dis request disposal add']);
        Route::post('/disposal/add-request-disposal', 'AddDataDisOut')->middleware(['permission:view dis request disposal add']);
        Route::get('/disposal/request-disposal/get_detail_data_disposal_out/{id}', 'detailPageDataDisposalOut')->middleware(['permission:view dis request disposal']);
        Route::get('/disposal/request-disposal/get_pdf/{id}', 'previewPDF')->middleware(['permission:view dis request disposal']);
        Route::get('/disposal/request-disposal/edit_data_disposal/{id}', 'editDetailDataDisout')->middleware(['permission:view dis request disposal edit']);
        Route::post('/disposal/request-disposal/update_data_disposal/{id}', 'updateDataDisOut')->middleware(['permission:view dis request disposal edit']);
        Route::post('/disposal/request-disposal/delete/{id}', 'deleteDataDisOut')->middleware(['permission:view dis request disposal']);
        Route::get('/disposal/request-disposal/filter_data_disposal', 'filter')->middleware(['permission:view dis request disposal']);


        // Route::post('/admin/disouts/delete/{id}', [DisposalOutController::class, 'deleteDataDisOut'])->name('delete.disout');
        // Route::get('/admin/disouts/get-asset-details/{id}', [DisposalOutController::class, 'getAssetDetails']);
        // Route::get('/admin/disouts/detail/{id}', [DisposalOutController::class, 'getDisoutDetail']);
        // Route::get('/fetch-disout-details/{id}', [DisposalOutController::class, 'getDetails']);
        // Route::get('/disout/{id}', [DisposalOutController::class, 'getDisOutById']);
        // Route::get('/disout/{id}/edit', 'DisposalController@edit');
        // Route::get('/assets/{id}', 'AssetController@show');
    });

    Route::controller(DisApprovalOpsAM::class)->group(function () {
        Route::get('/disposal/approval-ops-am', 'index')->middleware(['permission:view dis approval ops am']);
        Route::post('/disposal/approval-ops-am/update/{id}', 'updateApprovalAM')->middleware(['permission:view dis approval ops am']);
        Route::get('/disposal/approval-ops-am/detail/{id}', 'detailApprovalAM')->middleware(['permission:view dis approval ops am']);
    });
    Route::controller(DisApprovalOpsRM::class)->group(function () {
        Route::get('/disposal/approval-ops-rm', 'index')->middleware(['permission:view dis approval ops rm']);
        Route::post('/disposal/approval-ops-rm/update/{id}', 'update')->middleware(['permission:view dis approval ops rm']);
        Route::get('/disposal/approval-ops-rm/detail/{id}', 'detailApprovalRM')->middleware(['permission:view dis approval ops rm']);
    });
    Route::controller(DisApprovalOpsSDG::class)->group(function () {
        Route::get('/disposal/approval-sdg-asset', 'index')->middleware(['permission:view dis approval ops sdg']);
        Route::post('/disposal/approval-sdg-asset/update/{id}', 'Update')->middleware(['permission:view dis approval ops sdg']);
        Route::get('/disposal/approval-sdg-asset/detail/{id}', 'DetailApprovalSdg')->middleware(['permission:view dis approval ops sdg']);
    });
    Route::controller(Review::class)->group(function(){
        Route::get('/disposal/review', 'index')->middleware(['permission:view dis review']);
        Route::get('/disposal/review/detail/{id}', 'DetailReview')->middleware(['permission:view dis review']);
    });
     Route::controller(SoStockOpname::class)->group(function(){
        Route::get('/stockopname', 'index')->middleware(['permission:view stockopname']);
        Route::get('/stockopname/add', 'add')->middleware(['permission:view stockopname add']);
        Route::post('/stockopname/add', 'insert')->middleware(['permission:view stockopname add']);
        Route::post('/stockopname/update/{id}', 'update')->middleware(['permission:view stockopname update']);
        Route::post('/stockopname/delete/{id}', 'delete')->middleware(['permission:view stockopname delete']);
        Route::get('/stockopname/approval-sdg', 'approvalSDG')->middleware(['permission:view stockopname approval sdg']);
        Route::post('/stockopname/approval-sdg/update/{id}', 'approvalSDGUpdate')->middleware(['permission:view stockopname approval sdg update']);
        Route::get('/stockopname/print-pdf/{id}', 'printPDF')->middleware(['permission:view stockopname']);
        Route::post('/stockopname/import', 'import')->middleware(['permission:view stockopname']);
    });
    Route::controller(MDAsset::class)->group(function(){
        Route::get('/master-data/asset', 'HalamanAssets')->middleware(['permission:view md asset']);
        Route::post('/master-data/asset/add{id}', 'add')->middleware(['permission:view md asset']);
        Route::post('/master-data/asset/update{id}', 'update')->middleware(['permission:view md asset']);
        Route::post('/master-data/asset/delete{id}', 'delete')->middleware(['permission:view md asset']);
    });
    Route::controller(MDAssetEquipment::class)->group(function(){
        Route::get('master-data/asset-equipment', 'index')->middleware(['permission:view md asset equipment']);
        Route::post('/master-data/add-new-data-asset-equipment', 'NewAddDataAssetEquipment')->middleware(['permission:view md asset equipment']);
    //        Route::get('/master-data/get-new-data-asset-equipment', 'GetAjaxDataAssetEquipment')->middleware(['permission:view md asset equipment']);
        Route::post('/master-data/update-new-data-asset-equipment/{id}', 'NewUpdateDataAssetsEquipment')->middleware(['permission:view md asset equipment']);
        Route::post('/master-data/delete-new-data-asset-equipment/{id}', 'NewDeleteDataAssetsEquipment')->middleware(['permission:view md asset equipment']);
    });
    Route::controller(MDBrand::class)->group(function(){
        Route::get('master-data/brand', 'index')->middleware(['permission:view md brand']);
        Route::post('/master-data/add-new-brand', 'NewAddBrand')->middleware(['permission:view md brand']);
        Route::post('/master-data/update-new-brand/{id}', 'NewUpdateDataBrand')->middleware(['permission:view md brand']);
        Route::post('/master-data/delete-new-brand/{id}', 'NewDeleteDataBrand')->middleware(['permission:view md brand']);
    });
    Route::controller(MDCategory::class)->group(function(){
        Route::get('master-data/category', 'index')->middleware(['permission:view md kategori']);;
        Route::post('/master-data/add-new-category', 'NewAddDataCategory')->middleware(['permission:view md kategori']);
        Route::post('/master-data/update-new-category/{id}', 'NewUpdateDataCategory')->middleware(['permission:view md kategori']);
        Route::post('/master-data/delete-new-category/{id}', 'NewDeleteDataCategory')->middleware(['permission:view md kategori']);
    });
    Route::controller(MDSubCategory::class)->group(function(){
        Route::get('master-data/sub-category', 'index')->middleware(['permission:view md sub kategori']);
        Route::post('/master-data/add-new-sub-category', 'NewAddDataSubCategory')->middleware(['permission:view md sub kategori']);
        Route::post('/master-data/update-new-sub-category/{id}', 'NewUpdateDataSubCategory')->middleware(['permission:view md sub kategori']);
        Route::post('/master-data/delete-sub-category/{id}', 'NewDeleteDataSubCategory')->middleware(['permission:view md sub kategori']);
    });
    Route::controller(MDChecklist::class)->group(function(){
        Route::get('master-data/checklist', 'index')->middleware(['permission:view md checklist']);
    });
    Route::controller(MDCondition::class)->group(function(){
        Route::get('master-data/condition', 'index')->middleware(['permission:view md kondisi']);
        Route::post('/master-data/add-new-condition', 'NewAddDataCondition')->middleware(['permission:view md kondisi']);
        Route::post('/master-data/update-new-condition/{id}', 'NewUpdateDataCondition')->middleware(['permission:view md kondisi']);
        Route::post('/master-data/delete-new-condition/{id}', 'NewDeleteDataCondition')->middleware(['permission:view md kondisi']);
    });
    Route::controller(MDControl::class)->group(function(){
        Route::get('master-data/control-checklist', 'index')->middleware(['permission:view md kontrol checklist']);
        Route::post('/master-data/add-new-control-checklist', 'NewAddDataControlNameList')->middleware(['permission:view md kontrol checklist']);
        Route::post('/master-data/update-new-control-checklist/{id}', 'NewUpdateDataControlNameList')->middleware(['permission:view md kontrol checklist']);
        Route::post('/master-data/delete-new-control-checklist/{id}', 'NewDeleteDataControlNameList')->middleware(['permission:view md kontrol checklist']);
    });
    Route::controller(MDDepartement::class)->group(function(){
        Route::get('master-data/departement', 'index')->middleware(['permission:view md departement']);
        Route::post('/master-data/add-new-departement', 'NewAddDataDepartement')->middleware(['permission:view md departement']);
        Route::post('/master-data/update-new-departement/{id}', 'NewUpdateDataDepartement')->middleware(['permission:view md departement']);
        Route::post('/master-data/delete-new-departement/{id}', 'NewDeleteDataDepartement')->middleware(['permission:view md departement']);
    });
    Route::controller(MDDivision::class)->group(function(){
        Route::get('master-data/division', 'index')->middleware(['permission:view md division']);
        Route::post('/master-data/add-new-division', 'NewAddDataDivision')->middleware(['permission:view md division']);
        Route::post('/master-data/update-new-division/{id}', 'NewUpdateDataDivision')->middleware(['permission:view md division']);
        Route::post('/master-data/delete-new-division/{id}', 'NewDeleteDataDivision')->middleware(['permission:view md division']);
    });
    Route::controller(MDGroupUser::class)->group(function(){
        Route::get('master-data/group-user', 'index')->middleware(['permission:view md group user']);
        Route::post('/master-data/add-new-group-user', 'NewAddDataGroupUser')->middleware(['permission:view md group user']);
        Route::post('/master-data/update-new-group-user/{id}', 'NewUpdateDataGroupUser')->middleware(['permission:view md group user']);
        Route::post('/master-data/delete-new-group-user/{id}', 'NewDeleteDataGroupUser')->middleware(['permission:view md group user']);
    });
    Route::controller(MDJobLevel::class)->group(function(){
        Route::get('master-data/job-level', 'index')->middleware(['permission:view md job level']);
        Route::post('/master-data/add-new-job-level', 'NewAddDataJobLevel')->middleware(['permission:view md job level']);
        Route::post('/master-data/update-new-job-level/{id}', 'NewUpdateDataJobLevel')->middleware(['permission:view md job level']);
        Route::post('/master-data/delete-new-job-level/{id}', 'NewDeleteDataJobLevel')->middleware(['permission:view md job level']);
    });
    Route::controller(MDLayout::class)->group(function(){
        Route::get('master-data/layout', 'index')->middleware(['permission:view md tata letak']);
        Route::post('/master-data/add-new-layout', 'NewAddDataLayout')->middleware(['permission:view md tata letak']);
        Route::post('/master-data/update-new-layout/{id}', 'NewUpdateDataLayout')->middleware(['permission:view md tata letak']);
        Route::post('/master-data/delete-new-layout/{id}', 'NewDeleteDataLayout')->middleware(['permission:view md tata letak']);
    });
    Route::controller(MDMaintenance::class)->group(function(){
        Route::get('master-data/maintenance', 'index')->middleware(['permission:view md maintenance']);
        Route::post('/master-data/add-new-maintenance', 'NewAddDataMaintenance')->middleware(['permission:view md maintenance']);
        Route::post('/master-data/update-new-maintenance/{id}', 'NewUpdateDataMaintenance')->middleware(['permission:view md maintenance']);
        Route::post('/master-data/delete-new-data/{id}', 'NewDeleteDataMaintenance')->middleware(['permission:view md maintenance']);
    });
    Route::controller(MDPeople::class)->group(function(){
        Route::get('master-data/people', 'index')->middleware(['permission:view md people']);
        Route::post('/master-data/add-new-people', 'NewAddDataPeople')->middleware(['permission:view md people']);
        Route::post('/master-data/update-new-people/{id}', 'NewUpdateDataPeople')->middleware(['permission:view md people']);
        Route::post('/master-data/delete-new-data/{id}', 'NewDeleteDataPeople')->middleware(['permission:view md people']);
    });
    Route::controller(MDTypeAsset::class)->group(function(){
        Route::get('master-data/type-asset', 'index')->middleware(['permission:view md tipe asset']);
        Route::post('/master-data/add-new-type-asset', 'NewAddDataAssetType')->middleware(['permission:view md tipe asset']);
        Route::post('/master-data/update-new-type-asset/{id}', 'NewUpdateDataAssetType')->middleware(['permission:view md tipe asset']);
        Route::post('/master-data/delete-new-type-asset/{id}', 'NewDeleteDataAssetType')->middleware(['permission:view md tipe asset']);
    });
    Route::controller(MDMaintenanceAsset::class)->group(function(){
        Route::get('master-data/type-maintenance-asset', 'index')->middleware(['permission:view md tipe maintenance asset']);
        Route::post('/master-data/add-new-type-maintenance-asset', 'NewAddDataTypeMaintenance')->middleware(['permission:view md tipe maintenance asset']);
        Route::post('/master-data/update-new-type-maintenance-asset/{id}', 'NewUpdateDataTypeMaintenance')->middleware(['permission:view md tipe maintenance asset']);
        Route::post('/master-data/delete-new-type-maintenance-asset/{id}', 'NewDeleteDataTypeMaintenance')->middleware(['permission:view md tipe maintenance asset']);
    });
    Route::controller(MDPriority::class)->group(function(){
        Route::get('master-data/priority', 'index')->middleware(['permission:view md prioritas']);
        Route::post('/master-data/add-new-priority', 'NewAddDataPriority')->middleware(['permission:view md prioritas']);
        Route::post('/master-data/update-new-priority/{id}', 'NewUpdateDataPriority')->middleware(['permission:view md prioritas']);
        Route::post('/master-data/delete-new-priority/{id}', 'NewDeleteDataPriority')->middleware(['permission:view md prioritas']);
    });
    Route::controller(MDAlasanMutasi::class)->group(function(){
        Route::get('master-data/alasan-mutasi', 'index')->middleware(['permission:view md alasan mutasi']);
        Route::post('/master-data/add-new-alasan-mutasi', 'NewAddDataAlasanMutasi')->middleware(['permission:view md alasan mutasi']);
        Route::post('/master-data/update-new-alasan-mutasi/{id}', 'NewUpdateDataAlasanMutasi')->middleware(['permission:view md alasan mutasi']);
        Route::post('/master-data/delete-new-alasan-mutasi/{id}', 'NewDeleteDataAlasanMutasi')->middleware(['permission:view md alasan mutasi']);
    });
    Route::controller(MDAlasanStockOpname::class)->group(function(){
        Route::get('master-data/alasan-stock-opname', 'index')->middleware(['permission:view md alasan stock opname']);
        Route::post('/master-data/add-new-alasan-stock-opname', 'NewAddDataAlasanStockOpname')->middleware(['permission:view md alasan stock opname']);
        Route::post('/master-data/update-new-alasan-stock-opname/{id}', 'NewUpdateDataAlasanStockOpname')->middleware(['permission:view md alasan stock opname']);
        Route::post('/master-data/delete-new-alasan-stock-opname/{id}', 'NewDeleteDataAlasanStockOpname')->middleware(['permission:view md alasan stock opname']);
    });
    Route::controller(MDRegional::class)->group(function(){
        Route::get('master-data/regional', 'index')->middleware(['permission:view md regional']);
    });
    Route::controller(MDRepair::class)->group(function(){
        Route::get('master-data/repair', 'index')->middleware(['permission:view md perbaikan']);
        Route::post('/master-data/add-new-repair', 'NewAddDataRepair')->middleware(['permission:view md perbaikan']);
        Route::post('/master-data/update-new-repair/{id}', 'NewUpdateDataRepair')->middleware(['permission:view md perbaikan']);
        Route::post('/master-data/delete-new-repair/{id}', 'NewDeleteDataRepair')->middleware(['permission:view md perbaikan']);
    });
    Route::controller(MDSupplier::class)->group(function(){
        Route::get('master-data/supplier', 'index')->middleware(['permission:view md pemasok']);
        Route::post('/master-data/add-new-supplier', 'NewAddDataSupplier')->middleware(['permission:view md pemasok']);
        Route::post('/master-data/update-new-supplier/{id}', 'NewUpdateDataSupplier')->middleware(['permission:view md pemasok']);
        Route::post('/master-data/delete-new-supplier/{id}', 'NewDeleteDataSupplier')->middleware(['permission:view md pemasok']);
    });
    Route::controller(MDUOM::class)->group(function(){
        Route::get('master-data/uom', 'index')->middleware(['permission:view md satuan']);
        Route::post('/master-data/add-new-uom', 'NewAddDataUOM')->middleware(['permission:view md satuan']);
        Route::post('/master-data/update-new-uom/{id}', 'NewUpdateDataUOM')->middleware(['permission:view md satuan']);
        Route::post('/master-data/delete-new-uom/{id}', 'NewDeleteDataUOM')->middleware(['permission:view md satuan']);
    });
    Route::controller(MDWarranty::class)->group(function(){
        Route::get('master-data/warranty', 'index')->middleware(['permission:view md garansi']);
        Route::post('/master-data/add-new-warranty', 'NewAddDataWarranty')->middleware(['permission:view md garansi']);
        Route::post('/master-data/update-new-warranty/{id}', 'NewUpdateDataWarranty')->middleware(['permission:view md garansi']);
        Route::post('/master-data/delete-new-warranty/{id}', 'NewDeleteDataWarranty')->middleware(['permission:view md garansi']);
    });
    Route::controller(MDCity::class)->group(function(){
        Route::get('/master-data/city', 'index')->middleware(['permission:view md kota']);

    });
    Route::controller(MDResto::class)->group(function(){
        Route::get('/master-data/resto', 'index')->middleware(['permission:view md resto']);
        Route::get('/master-data/resto/get-cities', 'getCities')->middleware(['permission:view md resto']);
        Route::get('/master-data/resto/get-regions', 'getRegions')->middleware(['permission:view md resto']);
    });
    Route::controller(MDApprovalMaintenance::class)->group(function(){
        Route::get('/master-data/approval-maintenance', 'index')->middleware(['permission:view md approval maintenance']);
    });

    // REPORT ROUTES
    Route::get('/reports/registrasi_asset_report', [ReportController::class, 'ReportRegistrasiAsset']);
    Route::get('/reports/registration/generate-pdf/{id}', [RegistrationController::class, 'generatePDF']);
    Route::get('/reports/registration/detail_data_registrasi_asset/{id}', [RegistrationController::class, 'DetailDataRegistrasiAsset']);
    Route::get('/reports/export_report_asset_regist', [ReportController::class, 'ExportAssetRegist']);

    Route::get('/reports/get_data_registrasi_asset_report', [ReportController::class, 'ReportGetDataRegistrasiAsset']);

    Route::get('/reports/mutasi_stock_asset', [ReportController::class, 'ReportMutasiStock']);
    Route::get('/reports/mutasi_stock_asset/{id}/detail', [ReportController::class, 'detReportMutasi']);
    Route::get('/reports/export_excel_mutasi_stock', [ReportController::class, 'ExportExcelMutasiStock']);
    
    Route::get('/reports/kartu_stock_asset', [ReportController::class, 'ReportKartuStock']);
    Route::get('/reports/kartu_stock_asset/{id}/detail', [ReportController::class, 'detReportKartuStock']);
    Route::get('/reports/export/excel_kartu_stock{id}', [ReportController::class, 'ExportExcelKartuStock']);


    Route::get('/reports/checklist_asset', [ReportController::class, 'ReportChecklistAsset']);
    Route::get('/reports/maintenance_asset', [ReportController::class, 'ReportMaintenaceAsset']);
    Route::get('/reports/history_maintenance_asset', [ReportController::class, 'ReportHistoryMaintenace']);

    Route::get('/reports/stock_asset_per_location', [ReportController::class, 'ReportStockAssetPerLocation']);
    Route::get('/reports/export_stock_asset_per_location', [ReportController::class, 'ExportStockAssetPerLocation']);


    Route::get('/reports/garansi_asset', [ReportController::class, 'ReportGaransiAsset']);
    
    Route::get('/reports/disposal_asset', [ReportController::class, 'ReportDisposalAsset']);
    Route::get('/reports/export_excel_disposal_out', [ReportController::class, 'ExportExcelDisposalAssetData']);


    Route::get('/reports/stock_opname', [ReportController::class, 'ReportStockOpname']);
    Route::get('/reports/export_stock_opname', [ReportController::class, 'ExportStockopname']);

    Route::get('/reports/trend_issue_maintenace', [ReportController::class, 'ReportTrendIssue']);
    // END REPORT ROUTES

    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'HalamanUser')->middleware('permission:view user');
        Route::post('/user/add-user', 'AddDataUser')->middleware('permission:view user');
        Route::post('/user/update-user/{id}', 'updateDataUser')->middleware('permission:view user');
        Route::post('/user/delete/{id}', 'deleteDataUser')->middleware('permission:view user');
        Route::get('/user/user-get-location', 'userGetLocation')->middleware('permission:view user');
        Route::get('/user/user-get-area', 'userGetArea')->middleware('permission:view user');
        Route::get('/user/user-get-region', 'userGetRegion')->middleware('permission:view user');
        Route::get('/permission', 'userPermission')->middleware(['permission:view permission'])->name('permission');
        Route::post('/permission/add-permission', 'addPermission')->middleware(['permission:view permission']);
        Route::post('/permission/{id}/update-permission', 'updatePermission')->middleware(['permission:view permission'])->name('permission.update');
        Route::get('/role', 'userRole');

        Route::get('/admin/user', [UserController::class, 'HalamanUser']);
        Route::get('/admin/user', [UserController::class, 'HalamanUser'])->name('Admin.user');
        Route::post('/add-user', [UserController::class, 'AddDataUser'])->name('add.user');
        Route::get('/get-user', [UserController::class, 'GetUser'])->name('get.user');
        Route::get('/admin/users', [UserController::class, 'Index'])->name('Admin.user');
        Route::get('/admin/users/edit/{id}', [UserController::class, 'showEditForm'])->name('edit.user');
        Route::post('/admin/users/edit/{id}', [UserController::class, 'updateDataUser'])->name('update.user');

    });
});
