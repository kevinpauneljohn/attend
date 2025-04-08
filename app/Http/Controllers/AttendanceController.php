<?php

namespace App\Http\Controllers;

use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Jmrashed\Zkteco\Lib\Helper\Attendance;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified'])->only('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(EmployeeService $employeeService)
    {
        return Inertia::render('Employee/Attendances',[
            'attendances' => $employeeService->getEmployeesAttendance()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
