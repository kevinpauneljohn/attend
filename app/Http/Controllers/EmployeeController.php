<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Jmrashed\Zkteco\Lib\ZKTeco;

class EmployeeController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','verified'])->only(['index']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(EmployeeService $employeeService)
    {
        return Inertia::render('Employee/Employee',[
            'employees' => $employeeService->getEmployeesFromMainApp(),
            'used_biometrics_userid' => collect($employeeService->getEmployeesFromMainApp())->pluck('biometrics_id')->all(),
            'owner_id' => config('api.owner_id'),
            'biometric_users' => $employeeService->getBiometricUsers(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zkTeco = new ZKTeco('192.168.254.10');
        $zkTeco->connect();

        $zkTeco->disableDevice();
        $zkTeco->setUser(
            15,
            0015,
            'Nenalyn Fernando',
            4321
        );

//        $zkTeco->removeUser(7);


        return $zkTeco->enableDevice();

//        return $zkTeco->enableDevice();

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }

    public function connect_employee_to_biometrics_form(Request $request, $employee_id, EmployeeService $employeeService)
    {
        return $employeeService->connectEmployeeToBiometrics($request->biometrics_user, $employee_id);
    }
}
