<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthnRegisterController;
use App\Http\Controllers\Auth\WebAuthnLoginController;

Auth::routes(['register' => false]);

Route::get('/login-option','Auth\LoginController@loginOption')->name('login-option');

Route::post('webauthn/register/options', [WebAuthnRegisterController::class, 'options'])
     ->name('webauthn.register.options');
Route::post('webauthn/register', [WebAuthnRegisterController::class, 'register'])
     ->name('webauthn.register');

Route::post('webauthn/login/options', [WebAuthnLoginController::class, 'options'])
     ->name('webauthn.login.options');
Route::post('webauthn/login', [WebAuthnLoginController::class, 'login'])
     ->name('webauthn.login');

Route::get('checkin-checkout', 'CheckInCheckOutController@index');
Route::post('/checkin-checkout/store', 'CheckInCheckOutController@checkInCheckOutStore');

Route::middleware('auth')->group(function(){
    Route::get('/','PageController@home')->name('home');

    Route::resource('employee', 'EmployeeController');
    Route::get('employee/datable/ssd','EmployeeController@ssd');
    
    Route::get('profile','ProfileController@profile')->name('profiles.profile');
    Route::get('/profile/biometric-data','ProfileController@bimoetricData');
    Route::delete('/profile/biometric-data/delete/{id}','ProfileController@bimoetricDataDelete');

    Route::resource('department', 'DepartmentController');
    Route::get('department/datable/ssd','DepartmentController@ssd');

    Route::resource('role', 'RoleController');
    Route::get('role/datable/ssd','RoleController@ssd');

    Route::resource('permission', 'PermissionController');
    Route::get('permission/datable/ssd','PermissionController@ssd');

    Route::resource('company_setting', 'CompanySettingController')->only(['edit','update','show']);

    Route::resource('attendance', 'AttendanceController');
    Route::get('attendance/datable/ssd','AttendanceController@ssd');
    Route::get('/attendance-overview','AttendanceController@overview')->name('attendance.overview');
    Route::get('/attendance-overview-table','AttendanceController@overviewTable')->name('attendance.overview.table');

    Route::get('attendance-scan','AttendanceScanController@scan')->name('attendance-scan');
    Route::post('/attendance-scan/store','AttendanceScanController@scanStore')->name('attendance-scan.store');
   
    Route::get('/my-attendance/datable/ssd','MyAttendanceController@ssd');
    Route::get('/my-attendance-overview-table','MyAttendanceController@overviewTable');

    Route::resource('salary', 'SalaryController');
    Route::get('salary/datable/ssd','SalaryController@ssd');

    Route::get('/payroll','PayrollController@payroll')->name('payroll');
    Route::get('/payroll-table','PayrollController@payrollTable');
    Route::get('/my-payroll','MyPayrollController@ssd')->name('payroll');
    Route::get('/my-payroll-table','MyPayrollController@payrollTable');

    Route::resource('project', 'ProjectController');
    Route::get('project/datable/ssd','ProjectController@ssd');

    Route::resource('my-project', 'MyProjectController')->only(['index','show']);
    Route::get('my-project/datable/ssd','MyProjectController@ssd');

    Route::resource('task','TaskController');
    Route::get('/task-data','TaskController@tasksData');
    Route::get('/task-draggable','TaskController@taskDraggable');
});
