<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\{
    EmployeesController,
    LeavesController,
    JobsController,
    TimeShiftController,
    UserController,
    TaskController,
    LevelController,
    DepartmentController,
    SignDocumentController,
    BillController,
    ProofOfWorkController,
    RotaController,
    RotaEmployeeController,
    AttendanceController
};
use App\Http\Controllers\Auth\{
    LoginController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Route::group(['middleware' => 'cros'], function () 
Route::middleware(['cros'])->group(function () {
  Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
      return $request->user();
  });
  
  Route::get('/check', function () {
      return response('welcome');
  });
  
  //database connect check
  Route::get('/test-database', function () {
      try {
          DB::connection()->getPdo();
          echo "Connected successfully to RDS database!";
      } catch (\Exception $e) {
          die("Could not connect to the database. Error: " . $e->getMessage());
      }
  });
  
  Route::get('/login', [LoginController::class, 'loginPage'])->name('loginPage');
  Route::post('/login', [LoginController::class, 'login'])->name('login');
  Route::post('/register', [UserController::class, 'register'])->name('register');
  
  Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
      return $request->user();
  });
  
  Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('leaves')->group(function () {
        Route::get('/', [LeavesController::class, 'index'])->name('leaves.list');
        Route::post('/store', [LeavesController::class, 'store'])->name('leaves.store');
        Route::post('/update', [LeavesController::class, 'store'])->name('leaves.update');
        Route::get('/show/{id}', [LeavesController::class, 'show'])->name('leaves.show');
        Route::delete('/{id}', [LeavesController::class, 'destroy'])->name('leaves.destroy');
    });

    // Task
    Route::prefix('task')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('task.list');
        Route::post('/store', [TaskController::class, 'store'])->name('task.store');
        Route::post('/update', [TaskController::class, 'store'])->name('task.update');
        Route::get('/show/{id}', [TaskController::class, 'show'])->name('task.show');
        Route::delete('/{id}', [TaskController::class, 'destroy'])->name('task.destroy');
    });

    // SignDocument
    Route::prefix('sign-document')->group(function () {
        Route::get('/', [SignDocumentController::class, 'index'])->name('sign-document.list');
        Route::post('/store', [SignDocumentController::class, 'store'])->name('sign-document.store');
        Route::post('/update', [SignDocumentController::class, 'store'])->name('sign-document.update');
        Route::get('/show/{id}', [SignDocumentController::class, 'show'])->name('sign-document.show');
        Route::delete('/{id}', [SignDocumentController::class, 'destroy'])->name('sign-document.destroy');
    });
    
    // ProofOfWork
    Route::prefix('proof-of-work')->group(function () {
        Route::get('/', [ProofOfWorkController::class, 'index'])->name('proof-of-work.list');
        Route::post('/store', [ProofOfWorkController::class, 'store'])->name('proof-of-work.store');
        Route::post('/update', [ProofOfWorkController::class, 'store'])->name('proof-of-work.update');
        Route::get('/show/{id}', [ProofOfWorkController::class, 'show'])->name('proof-of-work.show');
        Route::delete('/{id}', [ProofOfWorkController::class, 'destroy'])->name('proof-of-work.destroy');
    });

    // employee route
    Route::prefix('employee')->group(function () {
        Route::get('/', [EmployeesController::class, 'index']);
        Route::post('/', [EmployeesController::class, 'index'])->name('employee');
        Route::post('/store', [EmployeesController::class, 'store'])->name('employee.store');
        Route::post('/update', [EmployeesController::class, 'store'])->name('employee.update');
        Route::get('/show/{id}', [EmployeesController::class, 'show'])->name('employee.show');
        Route::delete('/{id}', [EmployeesController::class, 'destroy'])->name('employee.destroy');
        Route::get('/search/{search}', [EmployeesController::class, 'search'])->name('employee.search');
        Route::get('/names', [EmployeesController::class, 'employeeNames'])->name('employee.names');
    });

    // job route
    Route::prefix('job')->group(function () {
        Route::get('/', [JobsController::class, 'index']);
        Route::post('/', [JobsController::class, 'index'])->name('job');
        Route::post('/store', [JobsController::class, 'store'])->name('job.store');
        Route::post('/update', [JobsController::class, 'store'])->name('job.update');
        Route::get('/show/{id}', [JobsController::class, 'show'])->name('job.show');
        Route::delete('/{id}', [JobsController::class, 'destroy'])->name('job.destroy');
    });

    // user
    Route::prefix('user')->group(function () {
        Route::post('/profile', [UserController::class, 'profile']);
        Route::post('/get-organizarion', [UserController::class, 'getOrganizarion']);
        Route::post('/', [EmployeesController::class, 'index'])->name('user');
        Route::post('/store', [EmployeesController::class, 'store'])->name('user.store');
    });

    // organization
    Route::prefix('organizations')->group(function () {
        Route::get('/', [UserController::class, 'AllOrganizations']);
        Route::post('/update', [UserController::class, 'updateOrganization']);
        Route::get('/delete/{id}', [UserController::class, 'deleteOrganization']);
        Route::post('/create', [UserController::class, 'storeOrganization']);
    });

    Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1'], function () {
        Route::get('attendance', [AttendanceController::class, 'index']);
        Route::post('attendance/record', [AttendanceController::class, 'record']);
        Route::put('attendance/update/{id}', [AttendanceController::class, 'update']);
        Route::post('attendance/mark', [AttendanceController::class, 'mark']);
    });

    Route::get('/students', [StudentController::class, 'list'])->name('students.list');
    Route::get('/waiting-list', [StudentController::class, 'waitingList'])->name('waiting.list');
  });

});
