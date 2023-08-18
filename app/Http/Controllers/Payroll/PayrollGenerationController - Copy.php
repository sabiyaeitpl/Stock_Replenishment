<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Process_attendance;
use App\Models\Employee\Employee_pay_structure;
use App\Models\LeaveApprover\Gpf_loan_apply;
use App\Models\LeaveApprover\Leave_apply;
use App\Models\Leave\Gpf_details;
use App\Models\Leave\Gpf_opening_balance;
use App\Models\Leave\Nps_details;
use App\Models\Masters\Gpf_rate_master;
use App\Models\Masters\Rate_details;
use App\Models\Masters\Rate_master;
use App\Models\Masters\Role_authorization;
use App\Models\Payroll\Payroll_detail;
use App\Models\Role\Employee;
use Illuminate\Http\Request;
use Session;
use View;

class PayrollGenerationController extends Controller
{

    public function payrollDashboard()
    {
        if (!empty(Session::get('admin'))) {
            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();

            return View('payroll/dashboard', $data);
        } else {
            return redirect('/');
        }
    }

    public function getPayroll()
    {

        if (!empty(Session::get('admin'))) {

            $data['payroll_rs'] = Payroll_detail::get();
            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();
            $data['rate_master'] = Rate_master::get();
            return view('payroll/view-payroll-generation', $data);
        } else {
            return redirect('/');
        }
    }

    public function viewPayroll()
    {
        if (!empty(Session::get('admin'))) {

            $data['Employee'] = Employee::where('status', '=', 'active')->orderBy('emp_fname', 'asc')->get();
            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();
            //return view('pis/add-payroll-generation',$data);
            return view('payroll/single-payroll-generation', $data);
        } else {
            return redirect('/');
        }
    }

    public function empPayrollAjax($empid, $month, $year)
    {
        if (!empty(Session::get('admin'))) {

            $mnth_yr = $month . '/' . $year;

            //$tomonthyr=date("Y-m-t");
            //$formatmonthyr=date("Y-m-01");
            $tomonthyr = $year . "-" . $month . "-31";
            $formatmonthyr = $year . "-" . $month . "-01";

            $employee_rs = Employee::leftJoin('employee_pay_structures', 'employee_pay_structures.employee_code', '=', 'employees.emp_code')
                ->where('employees.emp_code', '=', $empid)
                ->where('employees.emp_status', '!=', 'EX-EMPLOYEE')
                ->select('employees.*', 'employee_pay_structures.*')->first();

            $leave_rs = Leave_apply::leftJoin('leave_types', 'leave_types.id', '=', 'leave_applies.leave_type')
                ->where('leave_applies.employee_id', '=', $empid)
                ->where('leave_applies.status', '=', 'APPROVED')
                ->where('leave_applies.from_date', '>=', $formatmonthyr)
                ->where('leave_applies.to_date', '<=', $tomonthyr)
                ->select('leave_applies.*', 'leave_types.leave_type_name')
                ->get();

            $process_attendance = Process_attendance::where('process_attendances.employee_code', '=', $empid)
                ->where('process_attendances.month_yr', '=', $mnth_yr)
                ->first();
            //->toSql();

            $rate_rs = Rate_details::leftJoin('rate_masters', 'rate_masters.id', '=', 'rate_details.rate_id')
                ->select('rate_details.*', 'rate_masters.head_name', 'rate_masters.head_type')
                ->where('rate_details.from_date', '>=', date('Y-01-01'))
                ->where('rate_details.to_date', '<=', date('Y-12-31'))

                ->get();

            echo json_encode(array($employee_rs, $leave_rs, $process_attendance, $rate_rs));
        } else {
            return redirect('/');
        }
    }

    public function getEmpPayroll($empid, $month, $year)
    {
        if (!empty(Session::get('admin'))) {

            $mnth_yr = $month . '/' . $year;

            //$tomonthyr=date("Y-m-t");
            //$formatmonthyr=date("Y-m-01");
            $tomonthyr = $year . "-" . $month . "-31";
            $formatmonthyr = $year . "-" . $month . "-01";

            $employee_rs = Employee::leftJoin('employee_pay_structures', 'employee_pay_structures.employee_code', '=', 'employees.emp_code')
                ->where('employees.emp_code', '=', $empid)
                ->where('employees.emp_status', '!=', 'EX-EMPLOYEE')
                ->select('employees.*', 'employee_pay_structures.*')->first();

            $leave_rs = Leave_apply::leftJoin('leave_types', 'leave_types.id', '=', 'leave_applies.leave_type')
                ->where('leave_applies.employee_id', '=', $empid)
                ->where('leave_applies.status', '=', 'APPROVED')
                ->where('leave_applies.from_date', '>=', $formatmonthyr)
                ->where('leave_applies.to_date', '<=', $tomonthyr)
                ->select('leave_applies.*', 'leave_types.leave_type_name')
                ->get();

            $process_attendance = Process_attendance::where('process_attendances.employee_code', '=', $empid)
                ->where('process_attendances.month_yr', '=', $mnth_yr)
                ->first();
            //->toSql();

            $rate_rs = Rate_details::leftJoin('rate_masters', 'rate_masters.id', '=', 'rate_details.rate_id')
                ->select('rate_details.*', 'rate_masters.head_name', 'rate_masters.head_type')
                ->where('rate_details.from_date', '>=', date('Y-01-01'))
                ->where('rate_details.to_date', '<=', date('Y-12-31'))

                ->get();

            return json_encode(array($employee_rs, $leave_rs, $process_attendance, $rate_rs));
        } else {
            return json_encode(array());
        }
    }

    public function getPayrollallemployee()
    {

        if (!empty(Session::get('admin'))) {
            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();
            $data['payroll_rs'] = Payroll_detail::get();
            $data['rate_master'] = Rate_master::get();

            return view('payroll/payroll-generation-all-employee', $data);
        } else {
            return redirect('/');
        }
    }
    public function addPayrollallemployee()
    {
        if (!empty(Session::get('admin'))) {

            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();

            $data['result'] = '';

            return view('payroll/generate-payroll-all', $data);
        } else {
            return redirect('/');
        }
    }

    public function listPayrollallemployee(Request $request)
    {

        if (!empty(Session::get('admin'))) {
            $email = Session::get('adminusernmae');
            $Roledata = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();
            $payrolldate = explode('/', $request['month_yr']);
            $payroll_date = "0" . ($payrolldate[0] - 2);
            $origDate = $payroll_date . "/" . $payrolldate[1];

            //$current_month_days = cal_days_in_month(CAL_GREGORIAN, $payrolldate[0], $payrolldate[1]);
            //dd($current_month_days);
            $datestring = $payrolldate[1] . '-' . $payrolldate[0] . '-01';
            // Converting string to date
            $date = strtotime($datestring);
            $current_month_days = date("t", strtotime(date("Y-m-t", $date)));

            $tomonthyr = $payrolldate[1] . "-" . $payroll_date . "-" . $current_month_days;
            $formatmonthyr = $payrolldate[1] . "-" . $payroll_date . "-01";

            $rate_rs = Rate_master::leftJoin('rate_details', 'rate_details.rate_id', '=', 'rate_masters.id')
                ->select('rate_details.*', 'rate_masters.head_name')
                ->get();

            $result = '';

            $emplist = Employee::where('status', '=', 'active')
                ->where('emp_status', '!=', 'TEMPORARY')
                ->where('employees.emp_status', '!=', 'EX-EMPLOYEE')
            // ->where('employees.emp_code', '=', '1831')
                ->orderBy('emp_fname', 'asc')
                ->get();

            foreach ($emplist as $mainkey => $emcode) {

                $process_payroll = $this->getEmpPayroll($emcode->emp_code, $payrolldate[0], $payrolldate[1]);
                $process_payroll = json_decode($process_payroll);

                //dd($process_payroll);

                $process_attendance = Process_attendance::where('process_attendances.employee_code', '=', $emcode->emp_code)
                    ->where('process_attendances.month_yr', '=', $origDate)
                    ->first();

                $employee_rs = Employee::leftJoin('employee_pay_structures', 'employee_pay_structures.employee_code', '=', 'employees.emp_code')
                    ->where('employees.emp_code', '=', $emcode->emp_code)
                    ->select('employees.*', 'employee_pay_structures.*')
                    ->first();

                $leave_rs = Leave_apply::leftJoin('leave_types', 'leave_types.id', '=', 'leave_applies.leave_type')
                    ->where('leave_applies.employee_id', '=', $emcode->emp_code)
                    ->where('leave_applies.status', '=', 'APPROVED')
                    ->whereBetween('leave_applies.from_date', array($formatmonthyr, $tomonthyr))
                    ->orwhereBetween('leave_applies.to_date', array($formatmonthyr, $tomonthyr))
                    ->select('leave_applies.*', 'leave_types.leave_type_name')
                    ->get();

                $previous_payroll = Payroll_detail::where('employee_id', '=', $emcode->emp_code)
                //->where('month_yr','<',$request['month_yr'])
                    ->orderBy('month_yr', 'desc')
                    ->first();

                $tot_cl = $tot_el = $tot_hpl = $tot_rh = $tot_cml = $tot_eol = $tot_ml = $tot_pl = $tot_ccl = $tot_tl = 0;
                foreach ($leave_rs as $ky => $val) {

                    if ($val->employee_id == $emcode->emp_code) {

                        if ($val->leave_type_name == 'CASUAL LEAVE') {

                            $frommonth = date("m", strtotime($val->from_date));
                            $tomonth = date("m", strtotime($val->to_date));
                            if ($frommonth == $tomonth) {
                                $tot_cl = $val->no_of_leave;
                            } else {

                                $to = \Carbon\Carbon::createFromFormat('Y-m-d', $tomonthyr);
                                $from = \Carbon\Carbon::createFromFormat('Y-m-d', $val->to_date);
                                $diff_in_days = $to->diffInDays($val->from_date);
                                $tot_cl = ($diff_in_days) + 1;
                            }
                        }

                        if ($val->leave_type_name == 'EARNED LEAVE') {
                            $frommonth = date("m", strtotime($val->from_date));
                            $tomonth = date("m", strtotime($val->to_date));
                            if ($frommonth == $tomonth) {
                                $tot_el = $val->no_of_leave;
                            } else {
                                $to = \Carbon\Carbon::createFromFormat('Y-m-d', $tomonthyr);
                                $from = \Carbon\Carbon::createFromFormat('Y-m-d', $val->to_date);
                                $diff_in_days = $to->diffInDays($val->from_date);
                                $tot_el = ($diff_in_days) + 1;
                            }
                        }

                        if ($val->leave_type_name == 'HALF PAY LEAVE') {

                            $frommonth = date("m", strtotime($val->from_date));
                            $tomonth = date("m", strtotime($val->to_date));
                            if ($frommonth == $tomonth) {
                                $tot_hpl = $val->no_of_leave;
                            } else {
                                $to = \Carbon\Carbon::createFromFormat('Y-m-d', $tomonthyr);
                                $from = \Carbon\Carbon::createFromFormat('Y-m-d', $val->to_date);
                                $diff_in_days = $to->diffInDays($val->from_date);
                                $tot_hpl = ($diff_in_days) + 1;
                            }
                        }

                        if ($val->leave_type_name == 'MEDICAL LEAVE') {
                            $frommonth = date("m", strtotime($val->from_date));
                            $tomonth = date("m", strtotime($val->to_date));
                            if ($frommonth == $tomonth) {
                                $tot_ml = $val->no_of_leave;
                            } else {
                                $to = \Carbon\Carbon::createFromFormat('Y-m-d', $tomonthyr);
                                $from = \Carbon\Carbon::createFromFormat('Y-m-d', $val->to_date);
                                $diff_in_days = $to->diffInDays($val->from_date);
                                $tot_ml = ($diff_in_days) + 1;
                            }
                        }

                        if ($val->leave_type_name == 'TOUR LEAVE') {
                            $frommonth = date("m", strtotime($val->from_date));
                            $tomonth = date("m", strtotime($val->to_date));
                            if ($frommonth == $tomonth) {
                                $tot_tl = $val->no_of_leave;
                            } else {
                                $to = \Carbon\Carbon::createFromFormat('Y-m-d', $tomonthyr);
                                $from = \Carbon\Carbon::createFromFormat('Y-m-d', $val->to_date);
                                $diff_in_days = $to->diffInDays($val->from_date);
                                $tot_tl = ($diff_in_days) + 1;
                            }
                        }
                    }
                }

                if (empty($process_attendance)) {

                    $calculate_basic_salary = $employee_rs->basic_pay;
                    $no_of_working_days = 0;
                    $no_of_present = 0;
                    $no_of_days_absent = 0;
                    $no_of_days_salary = 0;
                } else {

                    $calculate_basic_salary = round(($employee_rs->basic_pay / $current_month_days) * ($process_attendance->no_of_working_days - $process_attendance->no_of_days_absent));

                    $no_of_working_days = $process_attendance->no_of_working_days;
                    $no_of_present = $process_attendance->no_of_present;
                    $no_of_days_absent = $process_attendance->no_of_days_absent;
                    $no_of_days_salary = $process_attendance->no_of_days_salary;
                }
/*
$ta_rate = 0;
$da_on_ta = 0;

$ta_value = Rate_details::where('rate_id', '=', 3)
->where(function ($query) use ($calculate_basic_salary) {
$query->where('min_basic', '<=', $calculate_basic_salary);
$query->where('max_basic', '>=', $calculate_basic_salary);
})
->first();

$da_value = Rate_details::where('rate_id', '=', '2')->first();

if ($emcode->emp_code == '6678') {

$ta_rate = 3600;
$da_on_ta = 612;
} else {

if ($ta_value) {
if ($no_of_days_absent > 0) {

$absent_deduction = round(($ta_value->inrupees / $current_month_days) * $no_of_days_absent);

if ($emcode->emp_physical_status == 'yes') {
$ta_rate = round(($ta_value->inrupees - $absent_deduction) * 2);
} else {
$ta_rate = round(($ta_value->inrupees - $absent_deduction));
}
} else {

if ($emcode->emp_physical_status == 'yes') {
$ta_rate = round($ta_value->inrupees * 2);
} else {

$ta_rate = $ta_value->inrupees;
}
}

$da_on_ta = round($ta_rate * $da_value->inpercentage / 100);
}
}

foreach ($rate_rs as $ratekey => $rateval) {

if ($rateval->head_name == 'HRA') {
if ($employee_rs->hra == '1') {
$actual_hra = $calculate_basic_salary * $rateval->inpercentage / 100;
if ($actual_hra <= 5400) {
$hra = '5400';
} else {
$hra = $actual_hra;
}
} else {
$hra = 0;
}
}

if ($rateval->head_name == 'DA') {

if ($employee_rs->da == '1') {
$da = round($calculate_basic_salary * $rateval->inpercentage / 100);
} else {

$da = 0;
}
}

$ltc = 0;
if ($rateval->head_name == 'LTC') {
if ($employee_rs->ltc == '1') {
$ltc = $rateval->inpercentage;
} else {
$ltc = 0;
}
}

$cea = 0;
if ($rateval->head_name == 'CEA') {
if ($employee_rs->cea == '1') {
$cea = $rateval->inpercentage;
} else {
$cea = 0;
}
}

$tr_a = 0;
if ($rateval->head_name == 'TR_A') {
if ($employee_rs->travelling_allowance == '1') {
$tr_a = $rateval->inpercentage;
} else {
$tr_a = 0;
}
}

$dla = 0;
if ($rateval->head_name == 'DLA') {
if ($employee_rs->daily_allowance == '1') {
$dla = $rateval->inpercentage;
} else {
$dla = 0;
}
}

$adv = 0;
if ($rateval->head_name == 'ADV') {
if ($employee_rs->advance == '1') {
$adv = $rateval->inpercentage;
} else {
$adv = 0;
}
}

$adjadv = 0;
if ($rateval->head_name == 'ADJ_ADV') {
if ($employee_rs->adjustment_advance == '1') {
$adjadv = $rateval->inpercentage;
} else {
$adjadv = 0;
}
}

$mr = 0;
if ($rateval->head_name == 'MR') {
if ($employee_rs->medical_reimbursement == '1') {
$mr = $rateval->inpercentage;
} else {
$mr = 0;
}
}

$sa = 0;
if ($rateval->head_name == 'SA') {
if ($employee_rs->spcl_allowance == '1') {

//$sa=$rateval->inpercentage;
if (!empty($previous_payroll->emp_spcl)) {
$sa = $previous_payroll->emp_spcl;
} else {
$sa = 0;
}
} else {
$sa = 0;
}
}

$cha = 0;
if ($rateval->head_name == 'CHA') {
if ($employee_rs->cash_handling_allowance == '1') {
if (!empty($previous_payroll->emp_cash_handle)) {
$cha = $previous_payroll->emp_cash_handle;
} else {
$cha = 0;
}
} else {
$cha = 0;
}
}

$tot_nps = 0;
if ($rateval->head_name == 'NPS') {

if ($employee_rs->nps == '1') {
$nps = $calculate_basic_salary + $da;
$tot_nps = round($nps * $rateval->inpercentage / 100);
} else {

$tot_nps = 0;
}
}

$gsli = 0;
if ($rateval->head_name == 'GSLI') {

if ($employee_rs->gsli == '1') {
//$gsli=$rateval->inrupees;
if (!empty($previous_payroll->emp_gslt)) {
$gsli = $previous_payroll->emp_gslt;
} else {
$gsli = 0;
}
} else {
$gsli = 0;
}
}

$gpf = 0;
if ($rateval->head_name == 'GPF') {

if ($employee_rs->gpf == '1') {

if (!empty($previous_payroll->emp_gpf)) {
$gpf = $previous_payroll->emp_gpf;
} else {
$gpf = 0;
}
} else {
$gpf = 0;
}
}

$income_tax = 0;
if ($employee_rs->income_tax == '1') {

if (!empty($previous_payroll->emp_income_tax)) {
$income_tax = $previous_payroll->emp_income_tax;
} else {
$income_tax = 0;
}
} else {
$income_tax = 0;
}

$cess = 0;
if ($employee_rs->cess == '1') {
//$cess=$rateval->inpercentage;
if (!empty($previous_payroll->emp_cess)) {
$cess = $previous_payroll->emp_cess;
} else {
$cess = 0;
}
} else {
$cess = 0;
}

$other2 = 0;
if ($employee_rs->other_deduction == '1') {

if (!empty($previous_payroll->other_deduction)) {
$other2 = $previous_payroll->other_deduction;
} else {
$other2 = 0;
}
} else {
$other2 = 0;
}
}
 */

                //Earnings
                $e_da = 0;
                $e_da_show = '';
                $e_vda = 0;
                $e_vda_show = '';
                $e_hra = 0;
                $e_hra_show = '';
                $e_tiffalw = 0;
                $e_tiffalw_show = '';
                $e_othalw = 0;
                $e_othalw_show = '';
                $e_conv = 0;
                $e_conv_show = '';
                $e_medical = 0;
                $e_medical_show = '';
                $e_miscalw = 0;
                $e_miscalw_show = '';
                $e_overtime = 0;
                $e_overtime_show = '';
                $e_bonus = 0;
                $e_bonus_show = '';
                $e_leaveenc = 0;
                $e_leaveenc_show = '';
                $e_hta = 0;
                $e_hta_show = '';
                $e_others = 0;
                $e_others_show = '';

                //Deductions
                $d_proftax = 0;
                $d_proftax_show = '';
                $d_pf = 0;
                $d_pf_show = '';
                $d_pfint = 0;
                $d_pfint_show = '';
                $d_apf = 0;
                $d_apf_show = '';
                $d_itax = 0;
                $d_itax_show = '';
                $d_insuprem = 0;
                $d_insuprem_show = '';
                $d_pfloan = 0;
                $d_pfloan_show = '';
                $d_esi = 0;
                $d_esi_show = '';
                $d_adv = 0;
                $d_adv_show = '';
                $d_hrd = 0;
                $d_hrd_show = '';
                $d_coop = 0;
                $d_coop_show = '';
                $d_furniture = 0;
                $d_furniture_show = '';
                $d_miscded = 0;
                $d_miscded_show = '';
                $d_incometax = 0;
                $d_incometax_show = '';
                $d_others = 0;
                $d_others_show = '';

                for ($j = 0; $j < sizeof($process_payroll[3]); $j++) {

                    //DA
                    if ($process_payroll[3][$j]->rate_id == '1') {

                        if ($process_payroll[0]->da == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $emp_da = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_da = $emp_da;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_da = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_da_show = 'readonly';
                        } else if ($process_payroll[0]->da != null && $process_payroll[0]->da != '') {

                            $e_da = $process_payroll[0]->da;
                            //$e_da_show = '';

                        } else {
                            $emp_da = 0;
                            $e_da = $emp_da;
                            $e_da_show = 'readonly';
                        }
                    }

                    //vda
                    if ($process_payroll[3][$j]->rate_id == '2') {
                        if ($process_payroll[0]->vda == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $emp_vda = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_vda = $emp_vda;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_vda = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_vda_show = "readonly";
                        } else if ($process_payroll[0]->vda != null && $process_payroll[0]->vda != '') {
                            $e_vda = $process_payroll[0]->vda;
                            //$e_vda_show = "";
                        } else {
                            $emp_vda = 0;
                            $e_vda = $emp_vda;
                            $e_vda_show = "readonly";
                        }
                    }

                    //hra
                    if ($process_payroll[3][$j]->rate_id == '3') {
                        if ($process_payroll[0]->hra == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $emp_hra = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_hra = $emp_hra;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_hra = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_hra_show = "readonly";
                        } else if ($process_payroll[0]->hra != null && $process_payroll[0]->hra != '') {
                            $e_hra = $process_payroll[0]->hra;
                            //$e_hra_show = "";
                        } else {
                            $emp_hra = 0;
                            $e_hra = $emp_hra;
                            $e_hra_show = "readonly";
                        }
                    }

                    //other alw
                    if ($process_payroll[3][$j]->rate_id == '5') {
                        if ($process_payroll[0]->others_alw == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_othalw = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_othalw = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_othalw_show = "readonly";
                        } else if ($process_payroll[0]->others_alw != null && $process_payroll[0]->others_alw != '') {
                            $e_othalw = $process_payroll[0]->others_alw;
                            //$e_othalw_show = "";
                        } else {
                            $valc = 0;
                            $e_othalw = $valc;
                            $e_othalw_show = "readonly";
                        }
                    }

                    //tiff alw
                    if ($process_payroll[3][$j]->rate_id == '6') {
                        if ($process_payroll[0]->tiff_alw == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_tiffalw = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_tiffalw = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_tiffalw_show = "readonly";
                        } else if ($process_payroll[0]->tiff_alw != null && $process_payroll[0]->tiff_alw != '') {
                            $e_tiffalw = $process_payroll[0]->tiff_alw;
                            //$e_tiffalw_show = "";
                        } else {
                            $valc = 0;
                            $e_tiffalw = $valc;
                            $e_tiffalw_show = "readonly";
                        }
                    }

                    //conv
                    if ($process_payroll[3][$j]->rate_id == '7') {
                        if ($process_payroll[0]->conv == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_conv = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_conv = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_conv_show = "readonly";
                        } else if ($process_payroll[0]->conv != null && $process_payroll[0]->conv != '') {
                            $e_conv = $process_payroll[0]->conv;
                            // $e_conv_show = "";
                        } else {
                            $valc = 0;
                            $e_conv = $valc;
                            $e_conv_show = "readonly";
                        }
                    }

                    //medical
                    if ($process_payroll[3][$j]->rate_id == '8') {
                        if ($process_payroll[0]->medical == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_medical = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_medical = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_medical_show = "readonly";
                        } else if ($process_payroll[0]->medical != null && $process_payroll[0]->medical != '') {
                            $e_medical = $process_payroll[0]->medical;
                            // $e_medical_show = "";
                        } else {
                            $valc = 0;
                            $e_medical = $valc;
                            $e_medical_show = "readonly";
                        }
                    }

                    //misc_alw
                    if ($process_payroll[3][$j]->rate_id == '9') {
                        if ($process_payroll[0]->misc_alw == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_miscalw = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_miscalw = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_miscalw_show = "readonly";
                        } else if ($process_payroll[0]->misc_alw != null && $process_payroll[0]->misc_alw != '') {
                            $e_miscalw = $process_payroll[0]->misc_alw;
                            // $e_miscalw_show = "";
                        } else {
                            $valc = 0;
                            $e_miscalw = $valc;
                            $e_miscalw_show = "readonly";
                        }
                    }

                    //over_time
                    if ($process_payroll[3][$j]->rate_id == '10') {
                        if ($process_payroll[0]->over_time == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_overtime = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_overtime = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_overtime_show = "readonly";
                        } else if ($process_payroll[0]->over_time != null && $process_payroll[0]->over_time != '') {
                            $e_overtime = $process_payroll[0]->over_time;
                            //$e_overtime_show = "";
                        } else {
                            $valc = 0;
                            $e_overtime = $valc;
                            $e_overtime_show = "readonly";
                        }
                    }

                    //bouns
                    if ($process_payroll[3][$j]->rate_id == '11') {
                        if ($process_payroll[0]->bouns == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_bonus = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_bonus = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_bonus_show = "readonly";
                        } else if ($process_payroll[0]->bouns != null && $process_payroll[0]->bouns != '') {
                            $e_bonus = $process_payroll[0]->bouns;
                            //      $e_bonus_show = "";
                        } else {
                            $valc = 0;
                            $e_bonus = $valc;
                            $e_bonus_show = "readonly";
                        }
                    }

                    //leave_inc
                    if ($process_payroll[3][$j]->rate_id == '12') {
                        if ($process_payroll[0]->leave_inc == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_leaveenc = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_leaveenc = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_leaveenc_show = "readonly";
                        } else if ($process_payroll[0]->leave_inc != null && $process_payroll[0]->leave_inc != '') {
                            $e_leaveenc = $process_payroll[0]->leave_inc;
                            //                           $e_leaveenc_show = "";
                        } else {
                            $valc = 0;
                            $e_leaveenc = $valc;
                            $e_leaveenc_show = "readonly";
                        }
                    }

                    //hta
                    if ($process_payroll[3][$j]->rate_id == '13') {
                        if ($process_payroll[0]->hta == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $e_hta = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $e_hta = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $e_hta_show = "readonly";
                        } else if ($process_payroll[0]->hta != null && $process_payroll[0]->hta != '') {
                            $e_hta = $process_payroll[0]->hta;
                            //                           $e_hta_show = "";
                        } else {
                            $valc = 0;
                            $e_hta = $valc;
                            $e_hta_show = "readonly";
                        }
                    }

                    //pf
                    if ($process_payroll[3][$j]->rate_id == '15') {
                        if ($process_payroll[0]->pf == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_pf = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_pf = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_pf_show = "readonly";
                        } else if ($process_payroll[0]->pf != null && $process_payroll[0]->pf != '') {
                            $d_pf = $process_payroll[0]->pf;
                            //                           $d_pf_show = "";
                        } else {
                            $valc = 0;
                            $d_pf = $valc;
                            $d_pf_show = "readonly";
                        }
                    }

                    //pf_int
                    if ($process_payroll[3][$j]->rate_id == '16') {
                        if ($process_payroll[0]->pf_int == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_pfint = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_pfint = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_pfint_show = "readonly";
                        } else if ($process_payroll[0]->pf_int != null && $process_payroll[0]->pf_int != '') {
                            $d_pfint = $process_payroll[0]->pf_int;
                            //                          $d_pfint_show = "";
                        } else {
                            $valc = 0;
                            $d_pfint = $valc;
                            $d_pfint_show = "readonly";
                        }
                    }

                    //apf
                    if ($process_payroll[3][$j]->rate_id == '17') {
                        if ($process_payroll[0]->apf == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_apf = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_apf = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_apf_show = "readonly";
                        } else if ($process_payroll[0]->apf != null && $process_payroll[0]->apf != '') {
                            $d_apf = $process_payroll[0]->apf;
                            //                           $d_apf_show = "";
                        } else {
                            $valc = 0;
                            $d_apf = $valc;
                            $d_apf_show = "readonly";
                        }
                    }

                    //i_tax
                    if ($process_payroll[3][$j]->rate_id == '18') {
                        if ($process_payroll[0]->i_tax == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_itax = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_itax = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_itax_show = "readonly";
                        } else if ($process_payroll[0]->i_tax != null && $process_payroll[0]->i_tax != '') {
                            $d_itax = $process_payroll[0]->i_tax;
                            //                           $d_itax_show = "";
                        } else {
                            $valc = 0;
                            $d_itax = $valc;
                            $d_itax_show = "readonly";
                        }
                    }

                    //insu_prem
                    if ($process_payroll[3][$j]->rate_id == '19') {
                        if ($process_payroll[0]->insu_prem == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_insuprem = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_insuprem = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_insuprem_show = "readonly";
                        } else if ($process_payroll[0]->insu_prem != null && $process_payroll[0]->insu_prem != '') {
                            $d_insuprem = $process_payroll[0]->insu_prem;
                            //                           $d_insuprem_show = "";
                        } else {
                            $valc = 0;
                            $d_insuprem = $valc;
                            $d_insuprem_show = "readonly";
                        }
                    }

                    //pf_loan
                    if ($process_payroll[3][$j]->rate_id == '20') {
                        if ($process_payroll[0]->pf_loan == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_pfloan = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_pfloan = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_pfloan_show = "readonly";
                        } else if ($process_payroll[0]->pf_loan != null && $process_payroll[0]->pf_loan != '') {
                            $d_pfloan = $process_payroll[0]->pf_loan;
                            //                           $d_pfloan_show = "";
                        } else {
                            $valc = 0;
                            $d_pfloan = $valc;
                            $d_pfloan_show = "readonly";
                        }
                    }

                    //esi
                    if ($process_payroll[3][$j]->rate_id == '21') {
                        if ($process_payroll[0]->esi == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_esi = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_esi = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_esi_show = "readonly";
                        } else if ($process_payroll[0]->esi != null && $process_payroll[0]->esi != '') {
                            $d_esi = $process_payroll[0]->esi;
                            //                           $d_esi_show = "";
                        } else {
                            $valc = 0;
                            $d_esi = $valc;
                            $d_esi_show = "readonly";
                        }
                    }

                    //adv
                    if ($process_payroll[3][$j]->rate_id == '22') {
                        if ($process_payroll[0]->adv == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_adv = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_adv = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_adv_show = "readonly";
                        } else if ($process_payroll[0]->adv != null && $process_payroll[0]->adv != '') {
                            $d_adv = $process_payroll[0]->adv;
                            //                           $d_adv_show = "";
                        } else {
                            $valc = 0;
                            $d_adv = $valc;
                            $d_adv_show = "readonly";
                        }
                    }

                    //hrd
                    if ($process_payroll[3][$j]->rate_id == '23') {
                        if ($process_payroll[0]->hrd == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_hrd = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_hrd = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_hrd_show = "readonly";
                        } else if ($process_payroll[0]->hrd != null && $process_payroll[0]->hrd != '') {
                            $d_hrd = $process_payroll[0]->hrd;
                            //                           $d_hrd_show = "";
                        } else {
                            $valc = 0;
                            $d_hrd = $valc;
                            $d_hrd_show = "readonly";
                        }
                    }

                    //co_op
                    if ($process_payroll[3][$j]->rate_id == '24') {
                        if ($process_payroll[0]->co_op == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_coop = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_coop = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_coop_show = "readonly";
                        } else if ($process_payroll[0]->co_op != null && $process_payroll[0]->co_op != '') {
                            $d_coop = $process_payroll[0]->co_op;
                            //                           $d_coop_show = "";
                        } else {
                            $valc = 0;
                            $d_coop = $valc;
                            $d_coop_show = "readonly";
                        }
                    }

                    //furniture
                    if ($process_payroll[3][$j]->rate_id == '25') {
                        if ($process_payroll[0]->furniture == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_furniture = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_furniture = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_furniture_show = "readonly";
                        } else if ($process_payroll[0]->furniture != null && $process_payroll[0]->furniture != '') {
                            $d_furniture = $process_payroll[0]->furniture;
                            //  $d_furniture_show = "";
                        } else {
                            $valc = 0;
                            $d_furniture = $valc;
                            $d_furniture_show = "readonly";
                        }
                    }

                    //misc_ded
                    if ($process_payroll[3][$j]->rate_id == '26') {
                        if ($process_payroll[0]->misc_ded == '1') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_miscded = $valc;
                            } else {
                                if (($calculate_basic_salary <= $process_payroll[3][$j]->max_basic) && ($calculate_basic_salary >= $process_payroll[3][$j]->min_basic)) {
                                    $d_miscded = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_miscded_show = "readonly";
                        } else if ($process_payroll[0]->misc_ded != null && $process_payroll[0]->misc_ded != '') {
                            $d_miscded = $process_payroll[0]->misc_ded;
                            //                           $d_miscded_show = "";
                        } else {
                            $valc = 0;
                            $d_miscded = $valc;
                            $d_miscded_show = "readonly";
                        }
                    }

                }

                //dd($process_payroll[3][0]->rate_id);
                $total_of_earnings = $e_da + $e_vda + $e_hra + $e_tiffalw + $e_othalw + $e_conv + $e_medical + $e_miscalw + $e_overtime + $e_bonus + $e_leaveenc + $e_hta + $e_others;

                //$total_gross = round($calculate_basic_salary + $da + $hra + $da_on_ta + $ta_rate + $ltc + $cea + $tr_a + $dla + $adv + $adjadv + $mr + $sa + $cha);

                //Gross Salary
                $total_gross = round($calculate_basic_salary + $total_of_earnings);

                for ($j = 0; $j < sizeof($process_payroll[3]); $j++) {
                    if ($process_payroll[3][$j]->rate_id == '4') {
                        if ($process_payroll[0]->prof_tax == '1' || $process_payroll[0]->prof_tax > '0') {
                            if ($process_payroll[3][$j]->inpercentage != '0') {
                                $valc = round($calculate_basic_salary * $process_payroll[3][$j]->inpercentage / 100);
                                $d_proftax = $valc;
                            } else {
                                if (($total_gross <= $process_payroll[3][$j]->max_basic) && ($total_gross >= $process_payroll[3][$j]->min_basic)) {
                                    $d_proftax = $process_payroll[3][$j]->inrupees;
                                }
                                if (($total_gross >= $process_payroll[3][$j]->max_basic) && ($total_gross <= $process_payroll[3][$j]->min_basic)) {
                                    $d_proftax = $process_payroll[3][$j]->inrupees;
                                }
                            }
                            $d_proftax_show = "readonly";
                        } else if ($process_payroll[0]->prof_tax != null && $process_payroll[0]->prof_tax != '') {
                            $d_proftax = $process_payroll[0]->prof_tax;
//                            $d_proftax_show = "";
                        } else {
                            $emp_hra = 0;
                            $d_proftax = $emp_hra;
                            $d_proftax_show = "readonly";
                        }

                    }

                }

                $total_deduction = round($d_proftax + $d_pf + $d_pfint + $d_apf + $d_itax + $d_insuprem + $d_pfloan + $d_esi + $d_adv + $d_hrd + $d_coop + $d_furniture + $d_miscded + $d_incometax + $d_others);

                /* $ptax = 0;
                foreach ($rate_rs as $ratekey => $rateval) {

                if ($rateval->head_name == 'PTAX') {
                if ($employee_rs->professional_tax == '1') {

                if (($total_gross >= $rateval->min_basic) && ($total_gross <= $rateval->max_basic)) {
                $ptax = $rateval->inrupees;
                }
                } else {
                $ptax = 0;
                }
                }
                }*/

                //$total_deduction = round($tot_nps + $gsli + $ptax + $gpf + $income_tax + $cess + $other2);
                $netsalary = round($total_gross - $total_deduction);

                $result .= '<tr id="' . $emcode->emp_code . '">
								<td style="width:10px;"><div class="checkbox"><label><input type="checkbox" name="empcode_check[]" id="chk_' . $emcode->emp_code . '" value="' . $emcode->emp_code . '" class="checkhour"></label></div></td>
								<td><input type="text" readonly class="form-control" name="emp_code' . $emcode->emp_code . '" style="width:100px;" value="' . $employee_rs->emp_code . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_name' . $emcode->emp_code . '" style="width:120px;" value="' . $employee_rs->emp_fname . ' ' . $employee_rs->emp_mname . ' ' . $employee_rs->emp_lname . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_designation' . $emcode->emp_code . '" style="width:100px;" value="' . $employee_rs->emp_designation . '"></td>
								<td><input type="text" readonly class="form-control" name="month_yr' . $emcode->emp_code . '" style="width:74px;" value="' . $request['month_yr'] . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_basic_pay' . $emcode->emp_code . '" style="width:100px;" value="' . $calculate_basic_salary . '"  id="emp_basic_pay_' . $emcode->emp_code . '" ></td>
								<td><input type="text" readonly class="form-control" name="emp_no_of_working' . $emcode->emp_code . '" value="' . $no_of_working_days . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_no_of_present' . $emcode->emp_code . '" value="' . $no_of_present . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_no_of_days_absent' . $emcode->emp_code . '" value="' . $no_of_days_absent . '"></td>
				  				<td><input type="text" readonly class="form-control" name="emp_no_of_days_salary' . $emcode->emp_code . '" value="' . $no_of_days_salary . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_tot_cl' . $emcode->emp_code . '" style="width:50px;" value="' . $tot_cl . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_tot_el' . $emcode->emp_code . '" style="width:50px;" value="' . $tot_el . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_tot_hpl' . $emcode->emp_code . '" style="width:50px;" value="' . $tot_hpl . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_tot_rh' . $emcode->emp_code . '" style="width:50px;" value="' . $tot_rh . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_tot_cml' . $emcode->emp_code . '" style="width:50px;" value="' . $tot_cml . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_tot_eol' . $emcode->emp_code . '" style="width:50px;" value="' . $tot_eol . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_lnd' . $emcode->emp_code . '" value="0" style="width:50px;"></td>
								<td><input type="text" readonly class="form-control" name="emp_tot_ml' . $emcode->emp_code . '" style="width:50px;" value="' . $tot_ml . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_tot_pl' . $emcode->emp_code . '" style="width:50px;" value="' . $tot_pl . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_totccl' . $emcode->emp_code . '" style="width:50px;" value="' . $tot_ccl . '"></td>
								<td><input type="text" readonly class="form-control" name="emp_tour_leave' . $emcode->emp_code . '" style="width:50px;" value="' . $tot_tl . '"></td>';
                //Earnings
                $result .= '<td><input type="text" style="width:100px;" class="form-control" id="e_da_' . $emcode->emp_code . '" name="e_da' . $emcode->emp_code . '" value="' . $e_da . '" ' . $e_da_show . ' onblur="recalculate(this);" ></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" id="e_vda_' . $emcode->emp_code . '" name="e_vda' . $emcode->emp_code . '" value="' . $e_vda . '" ' . $e_vda_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" id="e_hra_' . $emcode->emp_code . '" name="e_hra' . $emcode->emp_code . '" value="' . $e_hra . '" ' . $e_hra_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" id="e_tiffalw_' . $emcode->emp_code . '" name="e_tiffalw' . $emcode->emp_code . '" value="' . $e_tiffalw . '" ' . $e_tiffalw_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" id="e_othalw_' . $emcode->emp_code . '" name="e_othalw' . $emcode->emp_code . '" value="' . $e_othalw . '" ' . $e_othalw_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="e_conv' . $emcode->emp_code . '" value="' . $e_conv . '" id="e_conv_' . $emcode->emp_code . '" ' . $e_conv_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="e_medical' . $emcode->emp_code . '" value="' . $e_medical . '" id="e_medical_' . $emcode->emp_code . '" ' . $e_medical_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="e_miscalw' . $emcode->emp_code . '" value="' . $e_miscalw . '" id="e_miscalw_' . $emcode->emp_code . '" ' . $e_miscalw_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="e_overtime' . $emcode->emp_code . '" value="' . $e_overtime . '" id="e_overtime_' . $emcode->emp_code . '" ' . $e_overtime_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="e_bonus' . $emcode->emp_code . '" value="' . $e_bonus . '" id="e_bonus_' . $emcode->emp_code . '" ' . $e_bonus_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="e_leaveenc' . $emcode->emp_code . '" value="' . $e_leaveenc . '" id="e_leaveenc_' . $emcode->emp_code . '" ' . $e_leaveenc_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="e_hta' . $emcode->emp_code . '" value="' . $e_hta . '" id="e_hta_' . $emcode->emp_code . '" ' . $e_hta_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" class="form-control" name="e_others' . $emcode->emp_code . '" value="' . $e_others . '" style="width:100px;" id="e_others_' . $emcode->emp_code . '" ' . $e_others_show . ' onblur="recalculate(this);"></td>';

                //deductions
                $result .= '<td><input type="text" style="width:100px;" class="form-control" id="d_proftax_' . $emcode->emp_code . '" name="d_proftax' . $emcode->emp_code . '" style="width:50px;" value="' . $d_proftax . '" ' . $d_proftax_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="d_pf' . $emcode->emp_code . '" style="width:50px;" value="' . $d_pf . '" ' . $d_pf_show . ' id="d_pf_' . $emcode->emp_code . '" onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="d_pfint' . $emcode->emp_code . '" style="width:50px;" value="' . $d_pfint . '" ' . $d_pfint_show . ' id="d_pfint_' . $emcode->emp_code . '" onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="d_apf' . $emcode->emp_code . '" style="width:50px;" value="' . $d_apf . '" id="d_apf_' . $emcode->emp_code . '" ' . $d_apf_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="d_itax' . $emcode->emp_code . '" style="width:50px;" value="' . $d_itax . '" id="d_itax_' . $emcode->emp_code . '" ' . $d_itax_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="d_insuprem' . $emcode->emp_code . '" value="' . $d_insuprem . '" id="d_insuprem_' . $emcode->emp_code . '" ' . $d_insuprem_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" style="width:100px;" class="form-control" name="d_pfloan' . $emcode->emp_code . '" value="' . $d_pfloan . '" id="d_pfloan_' . $emcode->emp_code . '" ' . $d_pfloan_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" class="form-control" name="d_esi' . $emcode->emp_code . '" style="width:100px;" value="' . $d_esi . '" id="d_esi_' . $emcode->emp_code . '" ' . $d_esi_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" class="form-control" name="d_adv' . $emcode->emp_code . '" style="width:100px;" value="' . $d_adv . '" id="d_adv_' . $emcode->emp_code . '" ' . $d_adv_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" class="form-control" name="d_hrd' . $emcode->emp_code . '" style="width:100px;" value="' . $d_hrd . '" id="d_hrd_' . $emcode->emp_code . '" ' . $d_hrd_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" class="form-control" name="d_coop' . $emcode->emp_code . '" style="width:100px;" value="' . $d_coop . '" id="d_coop_' . $emcode->emp_code . '" ' . $d_coop_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" class="form-control" name="d_furniture' . $emcode->emp_code . '" style="width:100px;" value="' . $d_furniture . '" id="d_furniture_' . $emcode->emp_code . '" ' . $d_furniture_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" class="form-control" name="d_miscded' . $emcode->emp_code . '" style="width:100px;" value="' . $d_miscded . '" id="d_miscded_' . $emcode->emp_code . '" ' . $d_miscded_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" class="form-control" name="d_incometax' . $emcode->emp_code . '" style="width:100px;" value="' . $d_incometax . '" id="d_incometax_' . $emcode->emp_code . '" ' . $d_incometax_show . ' onblur="recalculate(this);"></td>';
                $result .= '<td><input type="text" class="form-control" name="d_others' . $emcode->emp_code . '" style="width:100px;" value="' . $d_others . '" id="d_others_' . $emcode->emp_code . '" ' . $d_others_show . ' onblur="recalculate(this);"></td>';

                $result .= '<td><input type="text" class="form-control" name="emp_total_gross' . $emcode->emp_code . '" style="width:120px;" value="' . $total_gross . '" id="emp_total_gross_' . $emcode->emp_code . '" readonly ></td>
								<td><input type="text" class="form-control" name="emp_total_deduction' . $emcode->emp_code . '" style="width:120px;" value="' . $total_deduction . '" id="emp_total_deduction_' . $emcode->emp_code . '" readonly></td>
								<td><input type="text" class="form-control" name="emp_net_salary' . $emcode->emp_code . '" style="width:120px;" value="' . $netsalary . '" id="emp_net_salary_' . $emcode->emp_code . '" readonly></td>
					</tr> ';
                // print_r($result);
                // die();
            }
            // print_r($result);
            // die();
            $month_yr_new = $request['month_yr'];
            return view('payroll/generate-payroll-all', compact('result', 'Roledata', 'month_yr_new'));
        } else {
            return redirect('/');
        }
    }

    public function getProcessPayroll()
    {
        if (!empty(Session::get('admin'))) {
            $data['monthlist'] = Payroll_detail::select('month_yr')->distinct('month_yr')->get();
            $data['process_payroll'] = "";
            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();
            $data['rate_master'] = Rate_master::get();
            return view('payroll/vw-process-payroll', $data);
        } else {
            return redirect('/');
        }
    }

    public function vwProcessPayroll(Request $request)
    {
        if (!empty(Session::get('admin'))) {

            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();
            $data['process_payroll'] = Payroll_detail::where('month_yr', '=', $request['month_yr'])
                ->where('proces_status', '=', 'process')
                ->get();

            // print_r(count($data['process_payroll']));
            // die();

            if (count($data['process_payroll']) == 0) {
                // print_r('Empty');
                // die();
                Session::flash('error', 'No Data Found.');
            }
            $data['rate_master'] = Rate_master::get();
            $data['monthlist'] = Payroll_detail::select('month_yr')->distinct('month_yr')->get();

            return view('payroll/vw-process-payroll', $data);
        } else {
            return redirect('/');
        }
    }

    public function addbalgpfemployee()
    {
        if (!empty(Session::get('admin'))) {
            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();

            return view('payroll/opening-bal-generation', $data);
        } else {
            return redirect('/');
        }
    }

    public function listbalgpfemployee(Request $request)
    {

        if (!empty(Session::get('admin'))) {

            $employeelist = Employee::where('status', '=', 'active')
                ->where('emp_status', '!=', 'TEMPORARY')
                ->where('emp_status', '!=', 'EX-EMPLOYEE')
                ->where('emp_pf_type', '=', 'gpf')
                ->orderBy('emp_fname', 'asc')

                ->get();

            $opening_balance = 0;
            foreach ($employeelist as $employee) {
                $data['month_yr'] = $request['month_yr'];
                $employeegpf = Gpf_opening_balance::where('month_yr', '=', $request['month_yr'])
                    ->where('employee_id', '=', $employee->emp_code)
                    ->get();

                if (count($employeegpf) != '0') {

                    $opening_balance = $employeegpf[0]->opening_balance;
                } else {
                    $opening_balance = '0';
                }

                $emp_name = $employee->emp_fname . ' ' . $employee->emp_mname . ' ' . $employee->emp_lname;

                $data['employee_gpf'][] = array('emp_name' => $emp_name, 'emp_designation' => $employee->emp_designation, 'emp_code' => $employee->emp_code, 'opening_balance' => $opening_balance);
            }
            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();
            return view('payroll/generate-gpf-bal-all', $data);
        } else {
            return redirect('/');
        }
    }

    public function addPayrollbalgpfemployee()
    {

        if (!empty(Session::get('admin'))) {

            $data['employeelist'] = Employee::where('status', '=', 'active')
                ->where('emp_status', '!=', 'TEMPORARY')
                ->where('emp_status', '!=', 'EX-EMPLOYEE')
                ->where('emp_pf_type', '=', 'gpf')
                ->orderBy('emp_fname', 'asc')

                ->get();
            $data['employeegpf'] = Gpf_opening_balance::get();

            return view('payroll/generate-gpf-bal-all', $data);
        } else {
            return redirect('/');
        }
    }

    public function listPayrollbalgpfemployee(Request $request)
    {
        if (!empty(Session::get('admin'))) {

            Gpf_opening_balance::where('month_yr', '=', $request['month_yr'])
                ->delete();
            foreach ($request->emp_code as $key => $value) {

                if (!empty($value)) {

                    $data['employee_id'] = $value;
                    $data['emp_name'] = $request->emp_name[$key];
                    $data['emp_designation'] = $request->emp_designation[$key];
                    $data['month_yr'] = $request['month_yr'];
                    $data['crated_time'] = date('Y-m-d');
                    $data['opening_balance'] = $request->open_bal[$key];
                    Gpf_opening_balance::insert($data);
                }
            }
            Session::flash('message', 'GPF Opening Balance Successfully Saved.');
            $employeelist = Employee::where('status', '=', 'active')
                ->where('emp_status', '!=', 'TEMPORARY')
                ->where('emp_status', '!=', 'EX-EMPLOYEE')
                ->where('emp_pf_type', '=', 'gpf')
                ->orderBy('emp_fname', 'asc')

                ->get();

            foreach ($employeelist as $employee) {
                $data['month_yr'] = $request['month_yr'];
                $employeegpf = Gpf_opening_balance::where('month_yr', '=', $request['month_yr'])
                    ->where('employee_id', '=', $employee->emp_code)
                    ->get();

                if (!empty($employeegpf)) {
                    $opening_balance = $employeegpf[0]->opening_balance;
                } else {
                    $opening_balance = '0';
                }
                $emp_name = $employee->emp_fname . ' ' . $employee->emp_mname . ' ' . $employee->emp_lname;

                $data['employee_gpf'][] = array('emp_name' => $emp_name, 'emp_designation' => $employee->emp_designation, 'emp_code' => $employee->emp_code, 'opening_balance' => $opening_balance);
            }
            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();

            return view('payroll/generate-gpf-bal-all', $data);
        } else {
            return redirect('/');
        }
    }

    public function updateProcessPayroll(Request $request)
    {

        if (!empty(Session::get('admin'))) {

            if (isset($request['payroll_id']) && count($request['payroll_id']) != 0) {
                foreach ($request['payroll_id'] as $payroll) {
                    $dataUpdate = Payroll_detail::where('id', '=', $payroll)
                        ->update(['proces_status' => 'completed']);
                }
                Session::flash('message', 'Pay Detail Save Successfully.');
            } else {
                Session::flash('error', 'No Pay Detail is Selected.');
            }
            return redirect('payroll/vw-process-payroll');
        } else {
            return redirect('/');
        }
    }

    public function savePayrollDetails(Request $request)
    {
        if (!empty(Session::get('admin'))) {

            if (empty($request->emp_gross_salary)) {
                Session::flash('message', 'Gross Salary Cannot be Blank.');
                return redirect('payroll/vw-payroll-generation');
            }

            if (empty($request->emp_total_deduction)) {

                Session::flash('message', 'Total Salary Cannot be Blank.');
                return redirect('payroll/vw-payroll-generation');
            }

            if (empty($request->emp_net_salary)) {

                Session::flash('message', 'Net Salary Cannot be Blank.');
                return redirect('payroll/vw-payroll-generation');
            }

            $monthyr = $request->month_yr;
            $mnt_yr = date('m/Y', strtotime("$monthyr"));

            $data['employee_id'] = $request->empname;
            $data['emp_name'] = $request->emp_name;
            $data['emp_designation'] = $request->emp_designation;
            $data['emp_basic_pay'] = $request->emp_basic_pay;
            $data['month_yr'] = $mnt_yr;
            $data['emp_present_days'] = $request->emp_present_days;
            $data['emp_cl'] = $request->emp_cl;
            $data['emp_el'] = $request->emp_el;
            $data['emp_hpl'] = $request->emp_hpl;
            $data['emp_absent_days'] = $request->emp_absent_days;
            $data['emp_rh'] = $request->emp_rh;
            $data['emp_cml'] = $request->emp_cml;
            $data['emp_eol'] = $request->emp_eol;
            $data['emp_lnd'] = $request->emp_lnd;
            $data['emp_maternity_leave'] = $request->emp_maternity_leave;
            $data['emp_paternity_leave'] = $request->emp_paternity_leave;
            $data['emp_ccl'] = $request->emp_ccl;
            $data['emp_el'] = $request->emp_el;
            $data['emp_da'] = $request->emp_da;
            $data['emp_vda'] = $request->emp_vda;
            $data['emp_hra'] = $request->emp_hra;
            $data['emp_prof_tax'] = $request->emp_prof_tax;
            $data['emp_others_alw'] = $request->emp_others_alw;
            $data['emp_tiff_alw'] = $request->emp_tiff_alw;
            $data['emp_conv'] = $request->emp_conv;
            $data['emp_medical'] = $request->emp_medical;
            $data['emp_misc_alw'] = $request->emp_misc_alw;
            $data['emp_over_time'] = $request->emp_over_time;
            $data['emp_bouns'] = $request->emp_bouns;
            $data['emp_pf'] = $request->emp_pf;
            $data['emp_pf_int'] = $request->emp_pf_int;
            $data['emp_co_op'] = $request->emp_co_op;
            $data['emp_apf'] = $request->emp_apf;
            $data['emp_i_tax'] = $request->emp_i_tax;
            $data['emp_insu_prem'] = $request->emp_insu_prem;
            $data['emp_pf_loan'] = $request->emp_pf_loan;
            $data['emp_esi'] = $request->emp_esi;
            $data['emp_adv'] = $request->emp_adv;
            $data['emp_absent_deduction'] = $request->emp_absent_deduction;
            $data['emp_gross_salary'] = $request->emp_gross_salary;
            $data['emp_hrd'] = $request->emp_hrd;
            $data['emp_gross_salary'] = $request->emp_gross_salary;
            $data['emp_total_deduction'] = $request->emp_total_deduction;
            $data['emp_net_salary'] = $request->emp_net_salary;
            $data['emp_furniture'] = $request->emp_furniture;
            $data['emp_misc_ded'] = $request->emp_misc_ded;
            $data['emp_leave_inc'] = $request->emp_leave_inc;
            $data['emp_hta'] = $request->emp_hta;
            $data['emp_income_tax'] = $request->emp_income_tax;
            $data['other_deduction'] = $request->other_deduction;
            $data['other_addition'] = $request->other_addition;
            $data['proces_status'] = 'process';
            $data['created_at'] = date('Y-m-d');

            $employee_pay_structure = Payroll_detail::where('employee_id', '=', $request->empname)
                ->where('month_yr', '=', $mnt_yr)
                ->first();

            if (!empty($employee_pay_structure)) {
                Session::flash('message', 'Payroll for this employee already generated for the month of "' . date('m-Y') . '". ');
            } else {

                Payroll_detail::insert($data);

                $check_gpf = $this->checkGpfEligibility($data['employee_id']);

                if ($check_gpf->pf == '1') {
                    //$this->npsMonthlyEnty($data);
                    $this->gpfMonthlyEnty($data);
                }

                Session::flash('message', 'Payroll Information Successfully Saved.');
            }

            return redirect('payroll/vw-payroll-generation');
        } else {
            return redirect('/');
        }
    }

    public function SavePayrollAll(Request $request)
    {

        if (!empty(Session::get('admin'))) {
            dd($request->empcode_check);
            if (isset($request->empcode_check) && count($request->empcode_check) != 0) {

                dd($request->empcode_check);
                foreach ($request->empcode_check as $key => $value) {
                    $data['employee_id'] = $value;
                    $data['emp_name'] = $request['emp_name' . $value];
                    $data['emp_designation'] = $request['emp_designation' . $value];
                    $data['emp_basic_pay'] = $request['emp_basic_pay' . $value];
                    $data['month_yr'] = $request['month_yr' . $value];

                    //dd($request->all());

                    $data['emp_present_days'] = $request['emp_no_of_present' . $value];
                    $data['emp_cl'] = $request['emp_tot_cl' . $value];
                    $data['emp_el'] = $request['emp_tot_el' . $value];
                    $data['emp_hpl'] = $request['emp_tot_hpl' . $value];
                    $data['emp_absent_days'] = $request['emp_no_of_days_absent' . $value];
                    $data['emp_rh'] = $request['emp_tot_rh' . $value];
                    $data['emp_cml'] = $request['emp_tot_cml' . $value];
                    $data['emp_eol'] = $request['emp_tot_eol' . $value];
                    $data['emp_lnd'] = $request['emp_lnd' . $value];
                    $data['emp_maternity_leave'] = $request['emp_tot_ml' . $value];
                    $data['emp_paternity_leave'] = $request['emp_tot_pl' . $value];
                    $data['emp_ccl'] = $request['emp_totccl' . $value];

                    //Earnings
                    $data['emp_da'] = $request['e_da' . $value];
                    $data['emp_vda'] = $request['e_vda' . $value];
                    $data['emp_hra'] = $request['e_hra' . $value];
                    $data['emp_others_alw'] = $request['e_othalw' . $value];
                    $data['emp_tiff_alw'] = $request['e_tiffalw' . $value];
                    $data['emp_conv'] = $request['e_conv' . $value];
                    $data['emp_medical'] = $request['e_medical' . $value];
                    $data['emp_misc_alw'] = $request['e_miscalw' . $value];
                    $data['emp_over_time'] = $request['e_overtime' . $value];
                    $data['emp_bouns'] = $request['e_bonus' . $value];
                    $data['emp_leave_inc'] = $request['e_leaveenc' . $value];
                    $data['emp_hta'] = $request['e_hta' . $value];
                    $data['other_addition'] = $request['e_others' . $value];

                    //Deductions
                    $data['emp_prof_tax'] = $request['d_proftax' . $value];
                    $data['emp_pf'] = $request['d_pf' . $value];
                    $data['emp_pf_int'] = $request['d_pfint' . $value];
                    $data['emp_co_op'] = $request['d_coop' . $value];
                    $data['emp_apf'] = $request['d_apf' . $value];
                    $data['emp_i_tax'] = $request['d_itax' . $value];
                    $data['emp_insu_prem'] = $request['d_insuprem' . $value];
                    $data['emp_pf_loan'] = $request['d_pfloan' . $value];
                    $data['emp_esi'] = $request['d_esi' . $value];
                    $data['emp_adv'] = $request['d_adv' . $value];
                    $data['emp_furniture'] = $request['d_furniture' . $value];
                    $data['emp_misc_ded'] = $request['d_miscded' . $value];
                    $data['emp_income_tax'] = $request['d_incometax' . $value];
                    $data['other_deduction'] = $request['d_others' . $value];
                    $data['emp_hrd'] = $request['d_hrd' . $value];

                    $data['emp_gross_salary'] = $request['emp_total_gross' . $value];
                    $data['emp_total_deduction'] = $request['emp_total_deduction' . $value];
                    $data['emp_net_salary'] = $request['emp_net_salary' . $value];
                    $data['proces_status'] = 'process';
                    $data['created_at'] = date('Y-m-d');

                    //dd($data);

                    $employee_pay_structure = Payroll_detail::where('employee_id', '=', $value)
                        ->where('month_yr', '=', $request['month_yr' . $value])
                        ->first();

                    if (!empty($employee_pay_structure)) {
                        Session::flash('message', 'Payroll already generated for said period');
                    } else {

                        Payroll_detail::insert($data);
                        $check_gpf = $this->checkGpfEligibility($data['employee_id']);

                        if ($check_gpf->pf == '1') {
                            //$this->npsMonthlyEnty($data);
                            $this->gpfMonthlyEnty($data);
                        }
                        Session::flash('message', 'Payroll Information Successfully Saved.');
                    }
                }
            } else {
                Session::flash('error', 'No Payroll Generation is selected');
            }

            return redirect('payroll/vw-payroll-generation-all-employee');
        } else {
            return redirect('/');
        }
    }

    public function checkGpfEligibility($employee_id)
    {

        $check_gpf_status = Employee_pay_structure::where('employee_code', '=', $employee_id)->first();

        return $check_gpf_status;
    }

    public function npsMonthlyEnty($data)
    {
        //echo "<pre>"; print_r($data); exit;
        $get_current_month_nps = Nps_details::where('emp_code', '=', $data['employee_id'])
            ->where('month_year', '=', $data['month_yr'])
            ->first();

        if (empty($get_current_month_nps)) {

            $get_last_month_nps = Nps_details::where('emp_code', '=', $data['employee_id'])
                ->orderBy('id', 'desc')
                ->first();

            if (empty($get_last_month_nps)) {
                $opening_balance = 0;
            } else {
                $opening_balance = $get_last_month_nps->closing_balance;
            }

            $closing_balance = $opening_balance + $data['emp_nps'] + $data['emp_nps'];

            Nps_details::insert(
                ['emp_code' => $data['employee_id'], 'month_year' => $data['month_yr'], 'opening_balance' => $opening_balance, 'own_share' => $data['emp_nps'], 'company_share' => $data['emp_nps'], 'closing_balance' => $closing_balance, 'updated_at' => date("Y-m-d H:i:s"), 'created_at' => date("Y-m-d H:i:s")]
            );
        }
    }

    public function gpfMonthlyEnty($data)
    {

        $current_date = date('Y-m-d');
        $get_current_month_gpf = Gpf_details::where('emp_code', '=', $data['employee_id'])
            ->where('month_year', '=', $data['month_yr'])
            ->first();

        $current_month = '';
        $current_year = '';
        $previous_year = '';
        $next_year = '';
        $year = '';
        $current_day = '';
        $current_month1 = '';

        $current_month = date('d', strtotime('02/' . $data['month_yr']));
        $current_year = date('Y', strtotime('02/' . $data['month_yr']));
        $previous_year = $current_year - 1;
        $next_year = $current_year + 1;

        if (date('m') <= '3') {
            $year = $previous_year . '-' . $current_year;
        } elseif (date('m') > '3') {
            $year = $current_year . '-' . $next_year;
        }

        $current_day = $current_year;
        $current_day .= '-' . $current_month;
        $current_day .= '-01';

        $current_month1 = date('Y-m-d', strtotime($current_day));
        $rate_of_interest = Gpf_rate_master::where('from_date', '<=', $current_month1)
            ->where('to_date', '>=', $current_month1)
            ->first();

        if (empty($get_current_month_gpf)) {

            $get_last_month_gpf = Gpf_details::where('emp_code', $data['employee_id'])
                ->orderBy('id', 'desc')
                ->first();

            if (empty($get_last_month_gpf)) {

                $get_open_bal_gpf = Gpf_opening_balance::where('employee_id', $data['employee_id'])
                    ->where('month_yr', '=', $year)

                    ->orderBy('id', 'desc')
                    ->first();

                if (empty($get_open_bal_gpf)) {
                    $gpf_opening_balance = 0;
                } else {
                    $gpf_opening_balance = $get_open_bal_gpf->opening_balance;
                }
            } else {
                $gpf_opening_balance = $get_last_month_gpf->closing_balance;
            }

            if (!empty($rate_of_interest)) {

                $date1 = date_create($rate_of_interest->from_date);
                $date2 = date_create($rate_of_interest->to_date);
                $diff = date_diff($date1, $date2);

                round($diff->format("%R%a") / 30);

                $rte_in = ($rate_of_interest->rate_of_interest) / 12;

                $int = $gpf_opening_balance + $data['emp_pf'];

                $interest_amt = (($int * $rte_in) / 100);
            } else {
                $rte_in = 0;
                $interest_amt = 0;
            }

            if (!empty($get_last_month_gpf)) {
                $get_close_bal_gpf = Gpf_loan_apply::where('employee_code', $data['employee_id'])
                    ->where('updated_at', '>', $get_last_month_gpf->updated_at)
                    ->Where('loan_status', '=', 'Paid')
                    ->orderBy('id', 'desc')
                    ->first();
                if (!empty($get_close_bal_gpf)) {
                    $close = $get_close_bal_gpf->loan_amount;
                } else {
                    $close = 0;
                }
            } else {
                $close = 0;
            }

            $gpf_closing_balance = $gpf_opening_balance + $data['emp_pf'] - $close;

            Gpf_details::insert(['emp_code' => $data['employee_id'], 'month_year' => $data['month_yr'], 'opening_balance' => $gpf_opening_balance, 'own_share' => $data['emp_pf'], 'company_share' => $data['emp_pf'], 'rate_of_interest' => $rte_in, 'interest_amount' => $interest_amt, 'closing_balance' => $gpf_closing_balance, 'updated_at' => date("Y-m-d H:i:s"), 'created_at' => date("Y-m-d H:i:s"), 'loan_amount' => $close]);
        }
    }

    public function deletePayrolldeatisl($paystructure_id)
    {
        if (!empty(Session::get('admin'))) {
            $emp_dtl = Payroll_detail::where('id', $paystructure_id)->first();
            $this->deleteNps($emp_dtl->month_yr, $emp_dtl->employee_id);
            $this->deleteGpf($emp_dtl->month_yr, $emp_dtl->employee_id);
            $result = Payroll_detail::where('id', $paystructure_id)->delete();
            Session::flash('message', 'Deleted Successfully.');
            return redirect('payroll/vw-payroll-generation');
        } else {
            return redirect('/');
        }
    }

    public function deletePayrollAll($paystructure_id)
    {
        if (!empty(Session::get('admin'))) {
            $emp_dtl = Payroll_detail::where('id', $paystructure_id)->first();
            $this->deleteNps($emp_dtl->month_yr, $emp_dtl->employee_id);
            $this->deleteGpf($emp_dtl->month_yr, $emp_dtl->employee_id);
            $result = Payroll_detail::where('id', $paystructure_id)->delete();
            Session::flash('message', 'Deleted Successfully.');
            return redirect('payroll/vw-payroll-generation-all-employee');
        } else {
            return redirect('/');
        }
    }

    public function deletePayroll($paystructure_id)
    {
        if (!empty(Session::get('admin'))) {
            $emp_dtl = Payroll_detail::where('id', $paystructure_id)->first();
            $this->deleteNps($emp_dtl->month_yr, $emp_dtl->employee_id);
            $this->deleteGpf($emp_dtl->month_yr, $emp_dtl->employee_id);
            $result = Payroll_detail::where('id', $paystructure_id)->delete();
            Session::flash('message', 'Pay Detail Deleted Successfully.');
            return redirect('payroll/vw-process-payroll');
        } else {
            return redirect('/');
        }
    }

    public function deleteNps($month, $emp_code)
    {
        $result = Nps_details::where('month_year', $month)
            ->where('emp_code', $emp_code)
            ->delete();

    }

    public function deleteGpf($month, $emp_code)
    {
        $result = Gpf_details::where('month_year', $month)
            ->where('emp_code', $emp_code)
            ->delete();

    }
}
