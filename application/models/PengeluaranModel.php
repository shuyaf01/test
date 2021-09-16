<?php

class PengeluaranModel extends CI_Model {

    function get_Detail_Pembelian(){
        return $this->db->get("tb_detail_pembelian");
    }

    function get_Pengeluaran_Pembelian(){
        $this->db->where('jenis_pengeluaran','Pembelian Bahan Baku');
        return $this->db->get("tb_pengeluaran");
    }

    function get_Pengeluaran_Lainnya(){
        $this->db->where('jenis_pengeluaran !=','Pembelian Bahan Baku');
        return $this->db->get("tb_pengeluaran");
    }

    function insert_Pengeluaran($data){
        return $this->db->insert("tb_pengeluaran",$data);
    }

    function insert_Detail($data){
        return $this->db->insert("tb_detail_pembelian",$data);
    }
    
    function get_PengeluaranById($id){
        $this->db->where("id_pengeluaran",$id);
        return $this->db->get("tb_pengeluaran");
    }
    
    function get_DetailById($id){
        $this->db->where("id_pengeluaran",$id);
        return $this->db->get("tb_detail_pembelian");
    }

    function get_idmax(){
        $date=date('ymd'); 
        $this->db->like("id_pengeluaran",$date);
        $this->db->select_max("id_pengeluaran");
        $this->db->from("tb_pengeluaran");
        $query = $this->db->get();
        return $query;
    }

    function get_newid($auto_id, $prefix){

        $date=date('ymd-');      
        $newId = substr($auto_id, -3);
        $tambah = (int)$newId + 1;

        if (strlen($tambah) == 1 ){
            $id_pengeluaran = $prefix.$date."00" .$tambah;
        }
        else if (strlen($tambah) == 2 ){
            $id_pengeluaran = $prefix.$date."0" .$tambah;
        }
        else if (strlen($tambah) == 3 ){
            $id_pengeluaran = $prefix.$date.$tambah;
        }
        return $id_pengeluaran;
    } 

    function update_Pengeluaran($id, $data){
        $this->db->where("id_pengeluaran",$id);
        return $this->db->update('tb_pengeluaran',$data);
    }

    function update_Detail($id, $data){
        $this->db->where("id_pengeluaran",$id);
        return $this->db->update('tb_detail_pembelian',$data);
    }

    function delete_Detail($id){
        $this->db->where('id_pengeluaran',$id);
        return $this->db->delete('tb_detail_pembelian');
    }

    function delete_Pengeluaran($id){
        $this->db->where('id_pengeluaran',$id);
        $query = $this->db->delete('tb_kas');
        $this->db->where('id_pengeluaran',$id);
        return $this->db->delete('tb_pengeluaran');
    }

    public function count_Pengeluaran(){
        $date=date('Y'); 
        $this->db->like("created_at",$date);
        $this->db->select_sum('nominal_pengeluaran');
        $query = $this->db->get('tb_pengeluaran');
        if($query->num_rows()>0){
            return $query->row()->nominal_pengeluaran;
        }
        else{
            return 0;
        }
    }
}