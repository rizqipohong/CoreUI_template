<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Cron
 *
 * @property Cron_model_v1 cronV1Model
 */
class Cron extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(Cron_model_v1::class,'cronV1Model');

    }

    // CRON SUMMARY
    public function summary_init_early_month()
    {
      // AWAL BULAN
        $data = $this->master_model->data('v1', '
        a.register_code,
        ', 'tb_fintech_register a')
        ->join('tb_fintech_borrower_detail_commercial_sme b', 'a.register_code = b.register_code')
        ->where("a.register_status", 'Borrower')
        ->order_by("a.register_code", "DESC")
        ->group_by("a.register_code")
        ->get()->result();

        foreach ($data as $key => $value) {
          $init_summary = [
            'register_code' => $value->register_code,
            'sum_periode_month' => date('m'),
            'sum_periode_year' => date('Y')
          ];
          $check_data = $this->master_model->data('v1', '', 'tb_summary_sme', $init_summary)->get()->num_rows();
          if ($check_data == 0) {
            $this->master_model->save($init_summary, 'tb_summary_sme', 'v1');
          }
        }

        $return = [
          'status' => 'success',
          'message' => 'Summary init awal bulan berhasil dijalankan',
        ];
        echo json_encode($return);
    }

    public function summary_recap_end_month()
    {
      // AKHIR BULAN
      $db1 = $this->load->database("default", TRUE);
      $data = $this->master_model->data('v1', '
      a.register_code,
      c.borrower_business_name as sum_vendor_name,
      b.bio_fullname as sum_pic,

      (SELECT sub_a.payor_company_name FROM tb_fintech_borrower_payor_sme sub_a JOIN tb_fintech_borrower_payor sub_b ON sub_a.payor_code = sub_b.payor_code WHERE sub_b.register_code = a.register_code ORDER BY sub_a.created_at DESC LIMIT 1) as sum_payor,

      (SELECT COALESCE(SUM(sub_a.cost), 0) FROM tb_fintech_facility_fee_sme sub_a WHERE sub_a.register_code = a.register_code AND sub_a.end_date >= CURDATE()) as sum_plafond,
      (SELECT sub_a.start_date FROM tb_fintech_facility_fee_sme sub_a WHERE sub_a.register_code = a.register_code ORDER BY sub_a.start_date ASC LIMIT 1) as sum_facility_periode_start,
      (SELECT sub_a.end_date FROM tb_fintech_facility_fee_sme sub_a WHERE sub_a.register_code = a.register_code ORDER BY sub_a.end_date DESC LIMIT 1) as sum_facility_periode_end,
      (SELECT sub_a.rate FROM tb_fintech_facility_fee_sme sub_a WHERE sub_a.register_code = a.register_code ORDER BY sub_a.rate DESC LIMIT 1) as sum_facility_fee,

      e.param_loan_rate_interest as sum_interest, e.param_loan_withdrawal_fee as sum_withdrawal_fee, e.param_loan_tenor as sum_tenor,

      (SELECT COALESCE(SUM(sub_b.payment_periode_principal), 0) FROM tb_invoice_borrower_loan sub_a JOIN tb_invoice_borrower_payment_periode sub_b ON sub_b.id_borrower_loan = sub_a.id_borrower_loan WHERE sub_a.register_code = a.register_code AND sub_a.loan_status IN ( "Disbursed", "Closed" ) AND sub_b.payment_periode_status_principal IN ( \'Late, Not Yet Paid\', "default" ) AND sub_a.loan_start_date <= CURDATE()) as sum_outstanding,

      j.status_plan as sum_status_plan,
      (SELECT COALESCE(AVG(sub_a.loan_amount), 0) FROM tb_invoice_borrower_loan sub_a WHERE sub_a.register_code = a.register_code AND sub_a.loan_status IN ("Disbursed","default","Done") AND loan_start_date >= LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 4 MONTH
      AND loan_start_date <= DATE_SUB(NOW(),INTERVAL DAYOFMONTH(NOW())-0 DAY)) as sum_dis_plan,
      (SELECT COALESCE(SUM(sub_a.loan_amount), 0) FROM tb_invoice_borrower_loan sub_a WHERE sub_a.register_code = a.register_code AND sub_a.loan_status IN ("Disbursed","default","Done") AND loan_start_date >= LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
      AND loan_start_date <= LAST_DAY(NOW())) as sum_dis_actual,

      h.sum_rep_payment_promise_date,
      (SELECT sub_b.payment_periode_date FROM tb_invoice_borrower_loan sub_a JOIN tb_invoice_borrower_payment_periode sub_b ON sub_b.id_borrower_loan = sub_a.id_borrower_loan WHERE sub_a.register_code = a.register_code AND sub_a.loan_status IN ( "Disbursed", "Closed" ) AND sub_b.payment_periode_status_principal IN ( \'Late, Not Yet Paid\', "default" ) AND sub_b.payment_periode_date <= CURDATE() ORDER BY sub_b.payment_periode_date ASC LIMIT 1) as sum_rep_due_date,
      CASE
      WHEN h.sum_rep_plan IS NULL THEN (SELECT COALESCE(SUM(sub_b.payment_periode_principal + sub_b.payment_periode_interest + sub_b.payment_periode_pinalty), 0) FROM tb_invoice_borrower_loan sub_a JOIN tb_invoice_borrower_payment_periode sub_b ON sub_a.id_borrower_loan = sub_b.id_borrower_loan WHERE sub_a.loan_status IN ("Disbursed","default","Done") AND sub_a.register_code = a.register_code AND sub_b.payment_periode_date >= LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 1 MONTH AND sub_b.payment_periode_date <= LAST_DAY(NOW()))
      WHEN h.sum_rep_plan = 0 THEN (SELECT COALESCE(SUM(sub_b.payment_periode_principal + sub_b.payment_periode_interest + sub_b.payment_periode_pinalty), 0) FROM tb_invoice_borrower_loan sub_a JOIN tb_invoice_borrower_payment_periode sub_b ON sub_a.id_borrower_loan = sub_b.id_borrower_loan WHERE sub_a.loan_status IN ("Disbursed","default","Done") AND sub_a.register_code = a.register_code AND sub_b.payment_periode_date >= LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 1 MONTH AND sub_b.payment_periode_date <= LAST_DAY(NOW()))
      ELSE h.sum_rep_plan
      END as sum_rep_plan,

      (SELECT COALESCE(SUM(sub_b.payment_periode_principal + sub_b.payment_periode_interest + sub_b.payment_periode_pinalty), 0) FROM tb_invoice_borrower_loan sub_a JOIN tb_invoice_borrower_payment_periode sub_b ON sub_a.id_borrower_loan = sub_b.id_borrower_loan WHERE sub_a.loan_status IN ("Disbursed","default","Done") AND sub_a.register_code = a.register_code AND sub_b.date_payment >= LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 1 MONTH AND sub_b.date_payment <= LAST_DAY(NOW())) as sum_rep_actual,

      g.user_fullname as sum_pic_farmer,
      h.id as id_sum,
      h.sum_note,
      l.pipeline_name as sum_pipeline
      ', 'tb_fintech_register a')
      ->join('tb_fintech_borrower_bio b', 'a.register_code = b.register_code', 'LEFT')
      ->join('tb_fintech_borrower_detail_commercial_sme c', 'a.register_code = c.register_code')
      ->join('tb_fintech_borrower_parameter_loan e', 'a.register_code = e.register_code', 'LEFT')
      ->join('tb_fintech_borrower_pic_farmer f', 'a.register_code = f.register_code', 'LEFT')
      ->join($db1->database.'.main_user as g', 'f.id_backend_users = g.user_id', 'LEFT')
      ->join('tb_summary_sme h', 'a.register_code = h.register_code and h.sum_periode_month = '.date('m').' and h.sum_periode_year = '.date('Y').'')
      ->join('tb_borrower_status_plan i', 'a.register_code = i.register_code', 'LEFT')
      ->join('tb_fintech_status_plan j', 'i.id_status_plan = j.status_plan_code', 'LEFT')
      ->join($db1->database.'.tb_borrower_pipeline_sme as k', 'a.register_code = k.register_code', 'LEFT')
      ->join($db1->database.'.tb_master_pipeline as l', 'k.id_pipeline = l.id', 'LEFT')
      ->where("a.register_status", 'Borrower')
      ->order_by("a.register_code", "DESC")
      ->group_by("a.register_code")
      ->get()->result();

      foreach ($data as $key => $value) {
        $where = [
          'register_code' => $value->register_code,
          'sum_periode_month' => date('m'),
          'sum_periode_year' => date('Y')
        ];

        $sum_remaining_limit = intval($value->sum_plafond) - intval($value->sum_outstanding);
        $sum_dis_ach = $value->sum_dis_plan != 0 ? (intval($value->sum_dis_actual)/intval($value->sum_dis_plan))*100 : 0;
        $sum_rep_ach = $value->sum_rep_plan != 0 ? (intval($value->sum_rep_actual)/intval($value->sum_rep_plan))*100 : 0;
        $sum_estimated_remaining_limit = (intval($sum_remaining_limit) - intval($value->sum_dis_actual)) + intval($value->sum_rep_actual);
        $sum_current_limit = ((intval($sum_remaining_limit) - intval($value->sum_dis_actual)) + intval($value->sum_rep_actual));
        $sum_current_outstanding = ((intval($value->sum_outstanding) + intval($value->sum_dis_actual)) - intval($value->sum_rep_actual));


        $recap_summary = [
          'sum_vendor_name' => $value->sum_vendor_name,
          'sum_pic' => $value->sum_pic,
          'sum_payor' => $value->sum_payor,
          'sum_plafond' => $value->sum_plafond,
          'sum_facility_periode_start' => $value->sum_facility_periode_start,
          'sum_facility_periode_end' => $value->sum_facility_periode_end,
          'sum_facility_fee' => $value->sum_facility_fee,
          'sum_interest' => $value->sum_interest,
          'sum_withdrawal_fee' => $value->sum_withdrawal_fee,
          'sum_tenor' => $value->sum_tenor,
          'sum_outstanding' => $value->sum_outstanding,
          'sum_remaining_limit' => $sum_remaining_limit,
          'sum_status_plan' => $value->sum_status_plan,
          'sum_dis_plan' => $value->sum_dis_plan,
          'sum_dis_actual' => $value->sum_dis_actual,
          'sum_dis_ach' => $sum_dis_ach,
          'sum_rep_payment_promise_date' => $value->sum_rep_payment_promise_date,
          'sum_rep_due_date' => $value->sum_rep_due_date,
          'sum_rep_plan' => $value->sum_rep_plan,
          'sum_rep_actual' => $value->sum_rep_actual,
          'sum_rep_ach' => $sum_rep_ach,
          'sum_estimated_remaining_limit' => $sum_estimated_remaining_limit,
          'sum_current_limit' => $sum_current_limit,
          'sum_current_outstanding' => $sum_current_outstanding,
          'sum_pic_farmer' => $value->sum_pic_farmer,
          'sum_note' => $value->sum_note,
          'sum_pipeline' => $value->sum_pipeline,
        ];
        $this->master_model->update($recap_summary, $where, 'tb_summary_sme', 'v1');
      }

      $return = [
        'status' => 'success',
        'message' => 'Summary rekap akhir bulan berhasil dijalankan',
      ];
      echo json_encode($return);
    }

    public function init_early_month_progress_plan()
    {
        $success = $this->cronV1Model->init_early_month();
        if ($success) {
            $output['success'] = true;
            $output['message'] = 'Data berhasil disimpan.';
        } else {
            $output['success'] = false;
            $output['message'] = 'Data gagal disimpan.';
        }
        echo json_encode($output);
    }

    public function recap_last_month_progress_plan()
    {
        $success = $this->cronV1Model->recap_last_month();
        if ($success) {
            $output['success'] = true;
            $output['message'] = 'Data berhasil disimpan.';
        } else {
            $output['success'] = false;
            $output['message'] = 'Data gagal disimpan.';
        }
        echo json_encode($output);
    }

    public function calculate_target_disbursement()
    {
      // AKHIR BULAN DAN AWAL BULAN
      $where = [
        'a.target_month' => date('m'),
        'a.target_year' => date('Y')
      ];

      $target = $this->master_model->data('', '', 'tb_target_disbursement_sme a', $where)->get()->row();

      $bor_farmer = $this->master_model->data('v1', '
        a.register_code,
        (
          SELECT
            COALESCE( SUM(sub_a.loan_amount), 0 )
          FROM
            tb_invoice_borrower_loan sub_a
          WHERE
            sub_a.register_code = a.register_code
          AND sub_a.loan_status IN ( "Disbursed","default","Done" )
          AND MONTH(sub_a.loan_start_date) = MONTH(NOW())
          AND YEAR(sub_a.loan_start_date) = YEAR(NOW())
        ) as loan_bulan_berjalan
      ', 'tb_fintech_register a')
        ->where('
        (
          SELECT
          	COUNT( sub_a.id_borrower_loan )
          FROM
          	tb_invoice_borrower_loan sub_a
          WHERE
          	sub_a.register_code = a.register_code
          AND sub_a.loan_status IN ( "Disbursed","default","Done" )
          AND sub_a.loan_start_date < LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
        ) > 0
        ', NULL)
        ->where('a.register_status', 'Borrower')
        ->where('a.is_sme', 1)
        // ->get_compiled_select();
        ->get()->result_array();
        // dd($bor_farmer);
      $farmer_actual = array_sum(array_column($bor_farmer, 'loan_bulan_berjalan'));

      $db2 = $this->load->database("v1", TRUE);
      $hunter_actual = $this->master_model->data('', '
        COALESCE(SUM((
          SELECT
            COALESCE( SUM( sub_a.loan_amount ), 0 )
          FROM
            '.$db2->database.'.tb_invoice_borrower_loan sub_a
          WHERE
            b.register_code = sub_a.register_code
          AND sub_a.loan_status IN ( "Disbursed","default","Done" )
          AND MONTH(loan_start_date) = MONTH(NOW())
          AND YEAR(loan_start_date) = YEAR(NOW())
        )), 0) as hunter_actual
        ', 'tb_borrower_hunter a')
        ->join('tb_confirm_borrower_hunter b', 'a.id = b.n_confirm_bor_id_borrower_hunter')
        ->join('tb_borrower_hunter_periode c', 'a.id = c.id_borrower_hunter')
        ->where('c.bor_periode_month', date('m'))
        ->where('c.bor_periode_year', date('Y'))
        // ->get_compiled_select();
        ->get()->row()->hunter_actual;
        // dd($hunter_actual);

      if (empty($target)) {
        $data = [
          'target_month' => date('m'),
          'target_year' => date('Y'),
          'target_sme_actual' => $farmer_actual + $hunter_actual,
          'target_farmer_actual' => $farmer_actual,
          'target_hunter_actual' => $hunter_actual,
        ];
        $this->master_model->save($data, 'tb_target_disbursement_sme');
      }else{
        $target_farmer_success_rate_cur = $target->target_farmer_plan_potency == 0 ? 0 : ($target->target_farmer_actual/$target->target_farmer_plan_potency)*100;
        $target_hunter_success_rate_cur = $target->target_hunter_plan_potency == 0 ? 0 : ($target->target_hunter_actual/$target->target_hunter_plan_potency)*100;
        $target_sme_success_rate_cur = ($target_farmer_success_rate_cur + $target_hunter_success_rate_cur);

        $data = [
          'target_sme_actual' => $farmer_actual + $hunter_actual,
          'target_farmer_actual' => $farmer_actual,
          'target_hunter_actual' => $hunter_actual,
          'target_sme_success_rate_cur' => $target_sme_success_rate_cur,
          'target_farmer_success_rate_cur' => $target_farmer_success_rate_cur,
          'target_hunter_success_rate_cur' => $target_hunter_success_rate_cur,
        ];
        $this->master_model->update($data, ['id' => $target->id], 'tb_target_disbursement_sme');
      }

      $return = [
        'status' => 'success',
        'message' => 'Kalkulasi target disbursement berhasil dijalankan',
      ];
      echo json_encode($return);
    }

    public function borrower_hunter_new_month()
    {
      // AWAL BULAN
      $bor_hunter = $this->master_model->data('', 'a.*, b.register_code', 'tb_borrower_hunter a', ['a.status' => 'Active', 'is_delete' => 'No'])
        ->join('tb_confirm_borrower_hunter b', 'a.id = b.n_confirm_bor_id_borrower_hunter', 'LEFT')
        ->get()->result_array();

      foreach ($bor_hunter as $key => $value) {
        $disbursed = 0;
        if ($value['register_code'] != '') {
          $disbursed = $this->master_model->data('v1', '
          COALESCE(SUM(a.loan_amount), 0) as disbursed
          ', 'tb_invoice_borrower_loan a')
            ->where('a.register_code', $value['register_code'])
            ->where_in('a.loan_status', ["Disbursed","default","Done"])
            ->get()->row()->disbursed;
        }

        if ($disbursed == 0) {
          $periode = $this->master_model->data('', '', 'tb_borrower_hunter_periode', ['id_borrower_hunter' => $value['id']])->order_by('created_at', 'DESC')->get()->row_array();
          $check_periode = $this->master_model->data('', '', 'tb_borrower_hunter_periode', ['id_borrower_hunter' => $value['id'], 'bor_periode_month' => date('m'), 'bor_periode_year' => date('Y')])->get()->row_array();
          if (!empty($periode)) {
            if (!empty($check_periode)) {

            }else{
              $new_periode = [
                'id_borrower_hunter' => $value['id'],
                'bor_periode_plan_potency' => $periode['bor_periode_plan_potency'],
                'bor_periode_month' => date('m'),
                'bor_periode_year' => date('Y'),
              ];
              $this->master_model->save($new_periode, 'tb_borrower_hunter_periode');
            }
          }else{
            $new_periode = [
              'id_borrower_hunter' => $value['id'],
              'bor_periode_plan_potency' => $periode['bor_periode_plan_potency'],
              'bor_periode_month' => date('m'),
              'bor_periode_year' => date('Y'),
            ];
            $this->master_model->save($new_periode, 'tb_borrower_hunter_periode');
          }
        }
      }

      $return = [
        'status' => 'success',
        'message' => 'Init borrower hunter awal bulan berhasil dijalankan',
      ];
      echo json_encode($return);
    }

    public function recap_actual_borrower_hunter()
    {
      // AKHIR BULAN
      $bor_hunter = $this->master_model->data('', 'a.*, b.register_code, c.id as id_periode', 'tb_borrower_hunter a', ['a.is_delete' => 'No', 'c.bor_periode_month' => date('m'), 'c.bor_periode_year' => date('Y')])
        ->join('tb_confirm_borrower_hunter b', 'a.id = b.n_confirm_bor_id_borrower_hunter')
        ->join('tb_borrower_hunter_periode c', 'a.id = c.id_borrower_hunter')
        ->get()->result_array();

      foreach ($bor_hunter as $key => $value) {
        $disbursed = 0;
        if ($value['register_code'] != '') {
          $disbursed = $this->master_model->data('v1', '
          COALESCE(SUM(a.loan_amount), 0) as disbursed
          ', 'tb_invoice_borrower_loan a')
            ->where('a.register_code', $value['register_code'])
            ->where('MONTH(a.loan_start_date) = MONTH(NOW())', NULL)
            ->where('YEAR(a.loan_start_date) = YEAR(NOW())', NULL)
            ->where_in('a.loan_status', ["Disbursed","default","Done"])
            ->get()->row()->disbursed;
            $this->master_model->update(['bor_periode_actual' => $disbursed], ['id' => $value['id_periode']], 'tb_borrower_hunter_periode');
        }
      }

      $return = [
        'status' => 'success',
        'message' => 'Rekap aktual borrower hunter berhasil dijalankan',
      ];
      echo json_encode($return);
    }
}
