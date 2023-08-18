<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

//Import Modals
use App\Models\Payroll\MonthlyEmployeeCooperative;

class MonthlyEmployeeCooperativeExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $date;
    
    public function __construct($date)
    {
        $this->date = $date;
    }
    
    public function collection()
    {
        $employee_rs = MonthlyEmployeeCooperative::join('employees', 'employees.emp_code', '=', 'monthly_employee_cooperatives.emp_code')
            ->select('employees.emp_code', 'employees.old_emp_code', 'employees.emp_fname', 'employees.emp_designation', 'monthly_employee_cooperatives.month_yr',
                'monthly_employee_cooperatives.coop_amount', 'monthly_employee_cooperatives.insurance_prem', 'monthly_employee_cooperatives.misc_ded')
            ->where('monthly_employee_cooperatives.month_yr', '=', $this->date)
            ->orderBy('employees.emp_fname', 'asc')
            ->get();
            
        return collect($employee_rs);
    }
    
    public function headings(): array
    {
        return [
            'Employee Id',
            'Employee Code',
            'Employee Name',
            'Employee Designation',
            'Month',
            'Cooperative Deduction',
            'Insurance Premium Deduction',
            'Miscellaneous Deduction',
        ];
    }
}