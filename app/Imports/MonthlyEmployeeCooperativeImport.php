<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

//Import Modals
use App\Models\Payroll\MonthlyEmployeeCooperative;

class MonthlyEmployeeCooperativeImport implements WithHeadingRow, ToModel
{
    public function model(array $row)
    {
        \DB::table('monthly_employee_cooperatives')
        ->where('emp_code', $row['employee_id'])
        ->where('month_yr', $row['month'])
        ->update([
            'coop_amount' => $row['cooperative_deduction'],
            'insurance_prem' => $row['insurance_premium_deduction'],
            'misc_ded' => $row['miscellaneous_deduction'],
        ]);
    }
}