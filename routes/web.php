<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AdminAuth\LoginController;
use App\Http\Controllers\Admin\SerchController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\DashBordController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CashTypeController;
use App\Http\Controllers\Admin\CourtTypeController;
use App\Http\Controllers\Admin\CourtController;
use App\Http\Controllers\Admin\CaseStatusController;
use App\Http\Controllers\Admin\JudgeController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\DatabaseBackupController;
use App\Http\Controllers\Admin\InvoiceSettingController;
use App\Http\Controllers\Admin\ExpenseTypeController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\CaseRunningController;
use App\Http\Controllers\Admin\ClientUserController;
use App\Http\Controllers\Admin\SmtpController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\GeneralSettingDateController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ServiceController;

Route::get('/backup', function () {
    $exitCode = Artisan::call('backup:run --only-db');
    echo 'DONE'; //Return anything
});

Route::get('/createlink', function () {
    Artisan::call('storage:link');
    echo 'created';
});

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('config:clear');
    echo 'DONE'; //Return anything
});

//---------------------------Country State City Filter-----------------------//
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login-as-admin', [LoginController::class, 'loginAsAdmin'])->name('loginAs.admin');
Route::get('/login-as-staff', [LoginController::class, 'loginAsStaff'])->name('loginAs.staff');

Route::get('f/country', [SerchController::class, 'getCountry'])->name('get.country');
Route::get('f/state', [SerchController::class, 'getState'])->name('get.state');
Route::get('f/city', [SerchController::class, 'getCity'])->name('get.city');

Route::post('common_check_exist', [Controller::class, 'common_check_exist'])->name('common_check_exist');

Route::post('getCaseSubType', [Controller::class, 'getCaseSubType']);
Route::post('getCourt', [Controller::class, 'getCourt']);
Route::post('getTaxById', [Controller::class, 'getTaxById']);

Route::post('common_change_state', [Controller::class, 'common_change_state'])->name('common_change_state');

Route::prefix('admin')->namespace('Admin')->middleware('admin')->group(function () {
    // Dashboard
    Route::resource('/dashboard', DashBordController::class);
    Route::post('/dashboard', [DashBordController::class, 'index']);
    Route::get('/ajaxCalander', [DashBordController::class, 'ajaxCalander']);
    Route::post('dashboard-all-caseList', [DashBordController::class, 'dashboardAllCaseList']);
    Route::post('dashboard-appointment-list', [DashBordController::class, 'appointmentList'])->name('dashboard-appointment-list');
    Route::get('downloadCaseBoard/{date}', [DashBordController::class, 'downloadCaseBoard']);
    Route::get('printCaseBoard/{date}', [DashBordController::class, 'printCaseBoard']);

    //---------------------------Client-----------------------//
    Route::resource('clients', ClientController::class);
    Route::post('clients/data-list', [ClientController::class, 'ClientList'])->name('clients.list');
    Route::post('clients/data-status', [ClientController::class, 'changeStatus'])->name('clients.status');
    Route::post('check_client_email_exits', [ClientController::class, 'check_client_email_exits'])->name('check_client_email_exits');
    Route::get('client/case-list/{id}', [ClientController::class, 'caseDetail'])->name('clients.case-list');
    Route::get('client/account-list/{id}', [ClientController::class, 'AccountDetail'])->name('clients.account-list');

    //---------------------------Task-----------------------//
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/data-list', [TaskController::class, 'TaskList'])->name('task.list');
    Route::post('tasks/data-status', [TaskController::class, 'changeStatus'])->name('task.status');

    //-----------------------Vendor-------------------------//
    Route::resource('vendor', VendorController::class);
    Route::post('vendor/data-list', [VendorController::class, 'VendorList'])->name('vendor.list');
    Route::post('vendor/data-status', [VendorController::class, 'changeStatus'])->name('vendor.status');

    //-----------------------Invoice---------------------------//
    Route::resource('invoice', InvoiceController::class);
    Route::post('invoice-list', [InvoiceController::class, 'InvoiceList'])->name('invoice-list');
    Route::post('invoice-list-client', [InvoiceController::class, 'InvoiceClientList'])->name('invoice-list-client');
    Route::get('show_payment_history/{id}', [InvoiceController::class, 'paymentHistory'])->name('paymentHistory');
    Route::get('create-Invoice-view/{id?}', [InvoiceController::class, 'CreateInvoiceView']);
    Route::get('create-Invoice-view-detail/{id}/{p}', [InvoiceController::class, 'CreateInvoiceViewDetail']);
    Route::post('getClientDetailById', [InvoiceController::class, 'getClientDetailById'])->name('getClientDetailById');
    Route::post('add_invoice', [InvoiceController::class, 'storeInvoice'])->name('store_invoice');
    Route::post('edit_invoice', [InvoiceController::class, 'editInvoice'])->name('edit_invoice');

    //------------------Appointment----------------------------//
    Route::resource('appointment', AppointmentController::class);
    Route::post('appointment/data-list', [AppointmentController::class, 'appointmentList'])->name('appointment.list');
    Route::post('getMobileno', [AppointmentController::class, 'getMobileno'])->name('getMobileno');

    //----------------------Setting Case Type------------------//
    Route::resource('case-type', CashTypeController::class);
    Route::post('cash-type-list', [CashTypeController::class, 'cashTypeList'])->name('cash.type.list');
    Route::post('cash-type-list/changestatus', [CashTypeController::class, 'changeStatus'])->name('cash.type.casetype.status');

    //---------------------Setting Court Type--------------------------//
    Route::resource('court-type', CourtTypeController::class);
    Route::post('court-type-list', [CourtTypeController::class, 'courtTypeList'])->name('court.type.list');
    Route::post('court-type-list/changestatus', [CourtTypeController::class, 'changeStatus'])->name('court.type.courttype.status');

    // Setting Court
    Route::resource('court', CourtController::class);
    Route::post('court-list', [CourtController::class, 'cashList'])->name('court.list');
    Route::post('court-list/changestatus', [CourtController::class, 'changeStatus'])->name('court.status');

    // Setting Case Status
    Route::resource('case-status', CaseStatusController::class);
    Route::post('case-status-list', [CaseStatusController::class, 'caseStatusList'])->name('case.status.list');
    Route::post('case-status-list/changestatus', [CaseStatusController::class, 'changeStatus'])->name('case.status');

    // Setting Judge
    Route::resource('judge', JudgeController::class);
    Route::post('judge-list', [JudgeController::class, 'caseStatusList'])->name('judge.list');
    Route::post('judge-status-list/changestatus', [JudgeController::class, 'changeStatus'])->name('judge.status');

    // Setting Tax
    Route::resource('tax', TaxController::class);
    Route::post('tax-list', [TaxController::class, 'taxList'])->name('tax.list');
    Route::post('tax-status-list', [TaxController::class, 'changeStatus'])->name('tax.status');

    Route::resource('database-backup', DatabaseBackupController::class);
    Route::get('database-restore/{id}', [DatabaseBackupController::class, 'restore'])->name('database-backup.restore');
    Route::post('database-backup-list', [DatabaseBackupController::class, 'List'])->name('database-backup.list');

    // Setting Invoice Setting
    Route::resource('invoice-setting', InvoiceSettingController::class);

    // Expense Type
    Route::resource('expense-type', ExpenseTypeController::class);
    Route::post('expense-type-list', [ExpenseTypeController::class, 'expenceList'])->name('expense.type.list');
    Route::post('expense-type-status-list', [ExpenseTypeController::class, 'changeStatus'])->name('expense.status');
    Route::resource('expense', ExpenseController::class);
    Route::get('expense-create/{id?}', [ExpenseController::class, 'expenseCreate']);
    Route::post('edit_expense', [ExpenseController::class, 'editExpense'])->name('edit_expense');
    Route::post('expense-list', [ExpenseController::class, 'expenseList'])->name('expense-list');
    Route::get('expense-account-list/{id}', [ExpenseController::class, 'AccountDetail']);
    Route::post('expense-filter-list', [ExpenseController::class, 'expenseFilterClientList']);
    Route::post('add_expense_payment', [ExpenseController::class, 'addExpensePayment'])->name('addExpensePayment');
    Route::get('show_payment_made_history/{id}', [ExpenseController::class, 'paymentMadeHistory'])->name('paymentMadeHistory');
    Route::get('create-expence-view-detail/{id}/{p}', [ExpenseController::class, 'CreateExpenseViewDetail']);
    Route::post('getVendorDetailById', [ExpenseController::class, 'getVendorDetailById'])->name('getVendorDetailById');

    //---------------------------Case Running-----------------//
    Route::resource('case-running', CaseRunningController::class);
    Route::post('allCaseList', [CaseRunningController::class, 'allCaseList']);
    Route::get('select2Case', [CaseRunningController::class, 'select2Case'])->name('select2Case');
    Route::get('case-list/{id}', [CaseRunningController::class, 'caseListByClientId']);
    Route::post('client/client_case_list', [CaseRunningController::class, 'client_case_list'])->name('client.case_view.list');
    Route::post('allCaseList', [CaseRunningController::class, 'allCaseList']);
    Route::get('/case-nb', [CaseRunningController::class, 'caseNB']);
    Route::get('/case-important', [CaseRunningController::class, 'caseImportant']);
    Route::get('/case-archived', [CaseRunningController::class, 'caseArchived']);
    Route::post('allCaseHistoryList', [CaseRunningController::class, 'allCaseHistoryList']);
    Route::get('addNextDate/{case_id}', [CaseRunningController::class, 'addNextDate']);
    Route::get('restoreCase/{case_id}', [CaseRunningController::class, 'restoreCase']);
    Route::post('case-next-date', [CaseRunningController::class, 'caseNextDate']);
    Route::get('/getNextDateModal/{case_id}', [CaseRunningController::class, 'getNextDateModal'])->name('getnextmodal');
    Route::get('/getChangeCourtModal/{case_id}', [CaseRunningController::class, 'getChangeCourtModal'])->name('transfermodal');
    Route::get('/case-history/{case_id}', [CaseRunningController::class, 'caseHistory']);
    Route::get('/case-transfer/{case_id}', [CaseRunningController::class, 'caseTransfer']);
    Route::get('/getCaseImportantModal/{case_id}', [CaseRunningController::class, 'getCaseImportantModal']);
    Route::post('allCaseTransferList', [CaseRunningController::class, 'allCaseTransferList']);
    Route::post('changeCasePriority', [CaseRunningController::class, 'changeCasePriority']);
    Route::post('transferCaseCourt', [CaseRunningController::class, 'transferCaseCourt']);
    Route::get('case-running-download/{id}/{action}', [CaseRunningController::class, 'downloadPdf']);

    //-----------------------Invite Member-----------------------//
    Route::resource('client_user', ClientUserController::class);
    Route::post('client-user-list', [ClientUserController::class, 'clientUserList'])->name('client-user-list');
    Route::post('client-user/status', [ClientUserController::class, 'changeStatus'])->name('client_user.status');
    Route::post('check_user_email_exits', [ClientUserController::class, 'check_user_email_exits'])->name('check_user_email_exits');
    Route::post('check_user_name_exits', [ClientUserController::class, 'check_user_name_exits'])->name('check_user_name_exits');

    Route::resource('mail-setup', SmtpController::class);
    Route::resource('general-setting', GeneralSettingController::class);
    Route::get('database-backups', [GeneralSettingController::class, 'databaseBackup']);
    Route::resource('date-timezone', GeneralSettingDateController::class);

    Route::resource('admin-profile', ProfileController::class);
    Route::post('edit-profile', [ProfileController::class, 'editProfile']);
    Route::post('image-crop', [ProfileController::class, 'imageCropPost']);
    Route::get('change/password', [ProfileController::class, 'change_pass']);
    Route::post('changed-password', [ProfileController::class, 'changedPassword']);

    //-----------Role----------------------//
    Route::resource('role', RoleController::class);
    Route::post('role/data-list', [RoleController::class, 'roleList'])->name('role.list');

    Route::resource('permission', PermissionController::class);

    //--------------------Service--------------------------------//
    Route::resource('service', ServiceController::class);
    Route::post('service/data-list', [ServiceController::class, 'serviceList'])->name('service.list');
    Route::post('service/status', [ServiceController::class, 'changeStatus'])->name('service.status');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'loginAsAdmin']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);

    Route::post('/password/email', [LoginController::class, 'sendResetLinkEmail'])->name('password.request');
    Route::post('/password/reset', [LoginController::class, 'reset'])->name('password.email');
    Route::get('/password/reset', [LoginController::class, 'showLinkRequestForm'])->name('password.reset');
    Route::get('/password/reset/{token}', [LoginController::class, 'showResetForm']);
});