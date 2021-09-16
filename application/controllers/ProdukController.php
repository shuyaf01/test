<?php
/** 
 * Ket :
 *  
 * 
*/

class ProdukController extends CI_Controller{
    /**
     * ProdukController constructor.
    */
    public function __construct()
    {
        parent::__construct();
        if(!$this->session->logged_in){
            redirect(site_url("logincontroller/login"));
            exit;
        }else{
            if ($this->session->user->hak_akses == "Admin"){
                $this->load->view('templates/header_admin');
            }
            else{
                $this->load->view('templates/header_directur');
            }
        }
        $this->load->model("ProdukModel","",TRUE);
    }

    function index()
    {
        $data['dataPD'] = $this->ProdukModel->get_Product();
        $this->load->view("produk/main_page", $data);
        $this->load->view('templates/footer');
    }

    protected function setValidationRules()
	{
		$this->form_validation->set_rules('id_produk', 'ID Produk', 'trim|required|max_length[128]');
		$this->form_validation->set_rules('nama_produk', 'Nama Produk', 'trim|required');
        
        $this->form_validation->set_message('required','Kosong. Inputkan %s!');
        $this->form_validation->set_message('max_length','Nilai %s melebihi batas.');
    }

    function formCreate()
    {
        $data['new_id'] = $this->setIdPemasukan();
        $this->load->view('produk/create',$data);
        $this->load->view('templates/footer'); 
    }

    public function processCreate()
	{
		$this->setValidationRules();	
		if ($this->form_validation->run()) 
        {
            //Form validation success. Insert Record into database
            $dataPD = array(
                "id_produk" => $this->input->post("id_produk"),
                "nama_produk" => $this->input->post("nama_produk"),
            );

			$data['created_at'] = date('Y-m-d H:i:s');

            $config['upload_path'] = './assets/images/products';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            
            $this->load->library('upload', $config);
            if (empty($_FILES['foto_produk']['name'])) {
                //
                if($this->ProdukModel->insert_Produk($dataPD)){  
                    $this->session->set_flashdata('success', 'Data Produk berhasil ditambahkan');
                    redirect(site_url("ProdukController"));
                }else{
                    redirect(site_url("ProdukController/formcreate"));
                }
            } else {

                if (!$this->upload->do_upload('foto_produk')){
                    $this->session->set_flashdata('error', 'File yang dinputkan tidak sesuai. Masukan file dengan format jpeg, jpg, png atau gif');
                    redirect(site_url("ProdukController"));
                } else {

                    $upload_data = $this->upload->data();
                    $dataPD['foto_produk'] = base_url("assets/images/products/").$upload_data['file_name'];
                    if($this->ProdukModel->insert_Produk($dataPD)){  
                        $this->session->set_flashdata('success', 'Data Produk berhasil ditambahkan');
                        redirect(site_url("ProdukController"));
                    }else{
                        redirect(site_url("ProdukController/formcreate"));
                    }
                }            
            }
            
		}else{
            $data['new_id'] = $this->setIdPemasukan();
            $this->load->view('produk/create',$data);
            $this->load->view('templates/footer'); 
        }
    }

    function formUpdate($id)
    {
        $record = $this->ProdukModel->get_ProdukById($id)->row();
		$data['record'] = $record;
        $this->load->view('produk/update',$data);
        $this->load->view('templates/footer'); 
    }

    public function processUpdate($id)
	{

		$this->setValidationRules();	
		if ($this->form_validation->run()) 
        {
			//Form validation success. Insert Record into database
            $dataPD = array(
                "id_produk" => $this->input->post("id_produk"),
                "nama_produk" => $this->input->post("nama_produk"),
            );

			$data['updated_at'] = date('Y-m-d H:i:s');

            $config['upload_path'] = './assets/images/products';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            
            $this->load->library('upload', $config);
 
            if (empty($_FILES['foto_produk']['name'])) {
                //
                if($this->ProdukModel->update_Produk($id,$dataPD)){  
                    $this->session->set_flashdata('success', 'Data Produk berhasil ditambahkan');
                    redirect(site_url("ProdukController"));
                }else{
                    redirect(site_url("ProdukController/formcreate"));
                }
            } else {

                if (!$this->upload->do_upload('foto_produk')){
                    $this->session->set_flashdata('error', 'File yang dinputkan tidak sesuai. Masukan file dengan format jpeg, jpg, png atau gif');
                    redirect(site_url("ProdukController"));
                } else {

                    $upload_data = $this->upload->data();
                    $dataPD['foto_produk'] = base_url("assets/images/products/").$upload_data['file_name'];
                    if($this->ProdukModel->update_Produk($id,$dataPD)){  
                        $this->session->set_flashdata('success', 'Data Produk berhasil ditambahkan');
                        redirect(site_url("ProdukController"));
                    }else{
                        redirect(site_url("ProdukController/formcreate"));
                    }
                }            
            }
		}else{
            $record = $this->ProdukModel->get_ProdukById($id)->row();
            $data['record'] = $record;
            $data['new_id'] = $this->setIdPemasukan();
            $this->load->view('produk/update',$data);
            $this->load->view('templates/footer'); 
        }
    }

    public function readbyid($id){
        $record = $this->ProdukModel->get_ProdukById($id)->row();
		$data['record'] = $record;
        $this->load->view('produk/read',$data);
        $this->load->view('templates/footer'); 
    }

    public function setIdPemasukan(){
        
        $new_id = $this->ProdukModel->get_idmax()->result();
        if($new_id > 0) {
            foreach ($new_id as $key) {
                $auto_id = $key->id_produk;
            }
            return $id_produk = $this->ProdukModel->get_newid($auto_id,'PD');
        } 
    }

    public function processDelete($id){
        $this->ProdukModel->delete_Produk($id);
        $this->session->set_flashdata("info", "Data Produk Berhasil Dihapus!");
        redirect(site_url("ProdukController"));
    }
}
?>