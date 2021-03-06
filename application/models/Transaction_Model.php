<?php
class Transaction_Model extends CI_Model{
  public function get_count_no($company_id, $field_name, $tbl_name){
    $this->db->select('MAX('.$field_name.') as num');
    $this->db->where('company_id', $company_id);
    $this->db->from($tbl_name);
    $query = $this->db->get();
    $result =  $query->result_array();
    if($result){
      $old_num = $result[0]['num'];
    } else{
      $old_num = 0;
    }
    $value2 = $old_num + 1;
    return $value = $value2;
  }

  public function get_paper_by_customer($customer_id){
    $this->db->select('pdetails.*,newspaper.*');
    $this->db->from('customer_pdetails as pdetails');
    $this->db->where('pdetails.customer_id',$customer_id);
    $this->db->join('newspaper_info as newspaper', 'pdetails.newspaper_info_id = newspaper.newspaper_info_id', 'LEFT');
    $query = $this->db->get();
    // $q = $this->db->last_query();
    $result = $query->result();
    return $result;
  }

  public function bill_list($company_id){
    $this->db->select('bill.*,bill_cycle.*,customer.*');
    $this->db->from('bill');
    $this->db->where('bill.company_id',$company_id);
    $this->db->join('bill_cycle', 'bill.bill_cycle_id = bill_cycle.bill_cycle_id', 'LEFT');
    $this->db->join('customer', 'bill.customer_id = customer.customer_id', 'LEFT');
    $query = $this->db->get();
    // $q = $this->db->last_query();
    $result = $query->result();
    return $result;
  }

  public function bill_paper_list($bill_id){
    $this->db->select('bill_paper.*,newspaper.*');
    $this->db->from('bill_paper');
    $this->db->where('bill_paper.bill_id',$bill_id);
    $this->db->join('newspaper_info as newspaper', 'bill_paper.newspaper_info_id = newspaper.newspaper_info_id', 'LEFT');
    $query = $this->db->get();
    // $q = $this->db->last_query();
    $result = $query->result();
    return $result;
  }

  public function bill_scheme_list($bill_id){
    $this->db->select('bill_scheme.*,scheme.*');
    $this->db->from('bill_scheme');
    $this->db->where('bill_scheme.bill_id',$bill_id);
    $this->db->join('scheme_info as scheme', 'bill_scheme.scheme_info_id = scheme.scheme_info_id', 'LEFT');
    $query = $this->db->get();
    // $q = $this->db->last_query();
    $result = $query->result();
    return $result;
  }

  public function get_scheme_by_customer($customer_id){
    $this->db->select('schdetails.*,scheme.*');
    $this->db->from('customer_schdetails as schdetails');
    $this->db->where('schdetails.customer_id',$customer_id);
    $this->db->where('scheme.scheme_type_id !=',2);
    $this->db->join('scheme_info as scheme', 'schdetails.scheme_info_id = scheme.scheme_info_id', 'LEFT');
    $query = $this->db->get();
    // $q = $this->db->last_query();
    $result = $query->result();
    return $result;
  }

  public function receipt_list($company_id){
    $this->db->select('receipt.*,customer.*,delivery_line.*');
    $this->db->from('receipt');
    $this->db->where('receipt.company_id',$company_id);
    $this->db->join('delivery_line', 'receipt.delivery_line_id = delivery_line.delivery_line_id', 'LEFT');
    $this->db->join('customer', 'receipt.customer_id = customer.customer_id', 'LEFT');
    $query = $this->db->get();
    // $q = $this->db->last_query();
    $result = $query->result();
    return $result;
  }

  public function get_cust_by_delivery_line($company_id,$delivery_line_id){
    $this->db->select('*');
    $this->db->from('customer');
    if($company_id != ''){
      $this->db->where('company_id',$company_id);
    }
    if($delivery_line_id != ''){
      $this->db->where('delivery_line_id',$delivery_line_id);
    }
    $query = $this->db->get();
    // $q = $this->db->last_query();
    $result = $query->result();
    return $result;
  }

  public function cust_total_bill($customer_id){
    $this->db->select('SUM(tot_net_amt) as cust_total_bill');
    $this->db->from('bill');
    $this->db->where('customer_id',$customer_id);
    $query = $this->db->get();
    // $q = $this->db->last_query();
    $result = $query->result_array();
    return $result[0]['cust_total_bill'];
  }

  public function cust_tot_sceme_amt($customer_id){
    $this->db->select('SUM(sch_amount) as cust_tot_sceme_amt');
    $this->db->from('customer_schdetails');
    $this->db->where('customer_id',$customer_id);
    $query = $this->db->get();
    // $q = $this->db->last_query();
    $result = $query->result_array();
    return $result[0]['cust_tot_sceme_amt'];
  }

  public function cust_tot_received($customer_id){
    $this->db->select('SUM(rec_amount) as cust_tot_received');
    $this->db->from('receipt');
    $this->db->where('customer_id',$customer_id);
    $query = $this->db->get();
    // $q = $this->db->last_query();
    $result = $query->result_array();
    return $result[0]['cust_tot_received'];
  }

  public function expenses_list($company_id){
    $this->db->select('expense.*, expense_type.*');
    $this->db->from('expense');
    $this->db->where('expense.company_id',$company_id);
    $this->db->join('expense_type', 'expense_type.expense_type_id = expense.expense_type', 'LEFT');
    $query = $this->db->get();
    $result = $query->result();
    return $result;
  }

  public function check_bill_cycle($company_id,$bill_cycle_from,$bill_cycle_to){
    $this->db->select('*');
    $this->db->from('bill_cycle');
    $this->db->where('company_id',$company_id);
    $this->db->where('bill_cycle_from',$bill_cycle_from);
    $this->db->where('bill_cycle_to',$bill_cycle_to);
    $query = $this->db->get();
    $result = $query->result_array();
    return $result;
  }

  public function get_customer_by_delivery_line($delivery_line_id){
    $this->db->select('*');
    $this->db->from('customer');
    if($delivery_line_id != ''){
      $this->db->where('delivery_line_id',$delivery_line_id);
    }
    $query = $this->db->get();
    $result = $query->result();
    return $result;
  }

  // Used in get_supplier_outstanding_amount & Add Payment..
  public function get_supplier_purchase_amount($supplier_id,$from_date,$to_date){
    $this->db->select('SUM(purchase_tot_amt) as purchase_tot_amt');
    $this->db->from('purchase');
    if($supplier_id != ''){
    $this->db->where('supplier_id',$supplier_id);
    }
    if($from_date != '' && $to_date == ''){
      $this->db->where("str_to_date(purchase_date,'%d-%m-%Y')  < str_to_date('$from_date','%d-%m-%Y')");
    }
    if($from_date != '' && $to_date != ''){
      $this->db->where("str_to_date(purchase_date,'%d-%m-%Y')  BETWEEN str_to_date('$from_date','%d-%m-%Y') AND str_to_date('$to_date','%d-%m-%Y')");
    }
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['purchase_tot_amt'];
  }

  public function get_supplier_purchase_pay_amount($supplier_id,$from_date,$to_date){
    $this->db->select('SUM(purchase_pay_amt) as purchase_pay_amt');
    $this->db->from('purchase');
    if($supplier_id != ''){
    $this->db->where('supplier_id',$supplier_id);
    }
    if($from_date != '' && $to_date == ''){
      $this->db->where("str_to_date(purchase_date,'%d-%m-%Y')  < str_to_date('$from_date','%d-%m-%Y')");
    }
    if($from_date != '' && $to_date != ''){
      $this->db->where("str_to_date(purchase_date,'%d-%m-%Y')  BETWEEN str_to_date('$from_date','%d-%m-%Y') AND str_to_date('$to_date','%d-%m-%Y')");
    }
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['purchase_pay_amt'];
  }

  public function get_supplier_payment_amount($supplier_id,$from_date,$to_date){
    $this->db->select('SUM(paid_amount) as paid_amount');
    $this->db->from('payment');
    if($supplier_id != ''){
    $this->db->where('supplier_id',$supplier_id);
    }
    if($from_date != '' && $to_date == ''){
      $this->db->where("str_to_date(payment_date,'%d-%m-%Y')  < str_to_date('$from_date','%d-%m-%Y')");
    }
    if($from_date != '' && $to_date != ''){
      $this->db->where("str_to_date(payment_date,'%d-%m-%Y')  BETWEEN str_to_date('$from_date','%d-%m-%Y') AND str_to_date('$to_date','%d-%m-%Y')");
    }
    $query = $this->db->get();
    $result = $query->result_array();
    return $result[0]['paid_amount'];
  }
}
?>
