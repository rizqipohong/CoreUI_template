<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cron_model_v1 extends APP_Model_V1
{

    public function init_early_month()
    {
        $dateFilter = explode('-', date('n-Y'));

        $query = "SELECT
                      a.register_code,
                      '" . $dateFilter[0] . "' as bulan,
                      '" . $dateFilter[1] . "' as tahun
                    FROM
                      `tb_fintech_borrower_detail_commercial_sme` a
                        ";


        $dataInit = $this->db->query($query)->result_array();
        $ct = 0;
        foreach ($dataInit as $row) {
            $ret = $this->insertInit($row['register_code'], $row['bulan'], $row['tahun'], $row);
            if (!$ret) {
                return false;
            }
            $ct++;
        }
        return true;
    }

    private function insertInit($register_code, $bulan, $tahun, $data)
    {
        if (!$this->isExist($register_code, $bulan, $tahun)) {
            $this->db->insert('tb_fintech_sme_progress_plan', $data);
            $this->lib_log->create_log('Insert Promissory Note', $data, 'Insert');
        }
        return true;
    }

    public function recap_last_month()
    {
        $dateFilter = explode('-', date('n-Y'));

        // GET PERSENTASE
        $hariIni = new DateTime();
        $tanggal = strftime('%d', $hariIni->getTimestamp());
        $persentase = 100 / 100; //minggu 3
        if ($tanggal <= 7)
            $persentase = 33 / 100; // minggu 1
        else if ($tanggal <= 14)
            $persentase = 67 / 100;// minggu 2

        $query = "SELECT
                      d.*,
                      '" . $dateFilter[0] . "' as bulan,
                      '" . $dateFilter[1] . "' as tahun ,
                      b.disbursement_amount,
                      c.disbursement_amount/3 AS plan_amount,
                      (c.disbursement_amount/3)*" . $persentase . " AS plan_percentage_amount,

                      c.disbursement_amount/3 AS average_disburse_amount,
                      a.register_code
                    FROM
                      `tb_fintech_borrower_detail_commercial_sme` a
                      LEFT JOIN
                        (SELECT
                          register_code,
                          SUM(loan_amount) AS disbursement_amount
                        FROM
                          tb_invoice_borrower_loan
                        WHERE loan_status IN ('Disbursed', 'default', 'Done')
                          AND MONTH(loan_start_date) = MONTH(CURRENT_DATE())
                          AND YEAR(loan_start_date) = YEAR(CURRENT_DATE())
                         GROUP BY register_code) b
                        ON a.register_code = b.register_code
                        LEFT JOIN
                        (SELECT
                          register_code,
                          SUM(loan_amount) AS disbursement_amount
                        FROM
                          tb_invoice_borrower_loan
                        WHERE loan_status IN ('Disbursed', 'default', 'Done')
                          AND DATE(loan_start_date) BETWEEN DATE(
                            (
                              (
                                PERIOD_ADD(EXTRACT(YEAR_MONTH FROM CURDATE()),- 3) * 100
                              ) + 1
                            )
                          )
                          AND (LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH)))
                        GROUP BY register_code) c
                            ON a.register_code = c.register_code
                        LEFT JOIN `tb_fintech_sme_progress_plan` d
                            ON a.register_code = d.register_code
                             AND d.bulan = '" . $dateFilter[0] . "'
                            AND d.tahun = '" . $dateFilter[1] . "'
                        LEFT JOIN `tb_fintech_status_plan` e
                            ON d.status_plan_code = e.status_plan_code
                            ";
        $dataInit = $this->db->query($query)->result_array();

        foreach ($dataInit as $row) {
            $row['difference_amount'] = $row['plan_percentage_amount'] - $row['disbursement_amount'];
            $ret = $this->replace_progress_plan($row['register_code'], $row['bulan'], $row['tahun'], $row);
            if (!$ret) {
                return false;
            }
        }
        return true;
    }


    public function isExist($register_code, $bulan, $tahun)
    {
        $this->db->where('register_code', $register_code);
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);
        $count = $this->db->get('tb_fintech_sme_progress_plan')->num_rows();

        return $count > 0;
    }

    public function replace_progress_plan($register_code, $bulan, $tahun, $data)
    {
        if ($this->isExist($register_code, $bulan, $tahun)) {
            $this->db->where('register_code', $register_code);
            $this->db->where('bulan', $bulan);
            $this->db->where('tahun', $tahun);
            $update = $this->db->update('tb_fintech_sme_progress_plan', $data);
            // membuat log
            $this->lib_log->create_log('Update Progress Plan', $data, 'Update');
        } else {
            $this->db->insert('tb_fintech_sme_progress_plan', $data);
            $this->lib_log->create_log('Insert Progress Plan', $data, 'Insert');
        }

        return true;
    }
}
