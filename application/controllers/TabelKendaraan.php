<?php
defined('BASEPATH') or exit('No direct script Kendaraancess allowed');

class TabelKendaraan extends CI_Controller
{
    public function __construct()
    {
        parent:: __construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->library('form_validation');
        $this->load->model('Kendaraan_model');

    }

    public function index()
    {
        $this->load->model('Kendaraan_model');
        $data['kendaraan'] = $this->Kendaraan_model->getAllKendaraan();
        $data['service'] = $this->Kendaraan_model->getAllService();
        $data['history'] = $this->Kendaraan_model->getAllHistory();
        $this->load->model('Chart_model');
        $data['jmlhkendaraan'] = $this->Chart_model->getJumlahKendaraan();
        $this->load->model('Kategori_model');
        $data['kategori_kendaraan'] = $this->Kategori_model->getAllKategoriKendaraan();
        $data['jenis_barang'] = $this->Kategori_model->getAllJenisBarang();
        $data['kualitas'] = $this->Kategori_model->getAllKualitas();
        $data['user'] = $this->db->get_where('user',['email'=> $this->session->userdata('email')])->row_array();
        $this->load->view('tabel_kendaraan', $data);
    }
    
    public function tambah(){

        $this->form_validation->set_rules('no_registrasi','No Registrasi','required|trim');
        $this->form_validation->set_rules('kode_barang','Kode Barang','required|trim');

        if ($this->form_validation->run() == false) {

        $this->load->model('Chart_model');
        $this->load->model('Kendaraan_model');
        $data['kendaraan'] = $this->Kendaraan_model->getAllKendaraan();
        $data['service'] = $this->Kendaraan_model->getAllService();
        $data['history'] = $this->Kendaraan_model->getAllHistory();
        $this->load->model('Chart_model');
        $data['jmlhkendaraan'] = $this->Chart_model->getJumlahKendaraan();
        $this->load->model('Kategori_model');
        $data['kategori_kendaraan'] = $this->Kategori_model->getAllKategoriKendaraan();
        $data['jenis_barang'] = $this->Kategori_model->getAllJenisBarang();
        $data['kualitas'] = $this->Kategori_model->getAllKualitas();
        $this->load->view('tabel_kendaraan', $data);
            
        } else {
            $this->load->model('Kendaraan_model');
            $this->Kendaraan_model->tambahDataKendaraan();
            if($this->db->affected_rows() > 0){
				echo "<script>alert('Data berhasil ditambah')</script>";
			}
				echo "<script>window.location='".site_url('tabelKendaraan')."'</script>";
        }
    }

    public function edit($id)
	{
	    $data['user'] = $this->db->get_where('user',['email'=> $this->session->userdata('email')])->row_array();
		$this->form_validation->set_rules('no_registrasi','No Registrasi','required|trim');
        $this->form_validation->set_rules('kode_barang','Kode Barang','required|trim');

        if ($this->form_validation->run() == false) {
			$query = $this->Kendaraan_model->getKendaraanByID($id);
			if($query->num_rows() > 0){
				$data['row'] = $query->row();
				$this->load->view('tabel_kendaraan_edit', $data);
			} else {
				echo "<script>alert('Data Tidak ditemukan')";
				echo "window.location='".site_url('tabelKendaraan')."'</script>";
			}
		} else {
			$post = $this->input->post(null, TRUE);
			$this->Kendaraan_model->editDataKendaraan($post);
			if($this->db->affected_rows() > 0){
				echo "<script>alert('Data berhasil diedit')</script>";
			}
				echo "<script>window.location='".site_url('tabelKendaraan')."'</script>";	
		}	
	}
	
	public function editHistory($id)
	{
	    $data['user'] = $this->db->get_where('user',['email'=> $this->session->userdata('email')])->row_array();
	    $data['kendaraan'] = $this->Kendaraan_model->getAllKendaraan();
        $this->form_validation->set_rules('id_service','Service','required|trim');

        if ($this->form_validation->run() == false) {
			$query = $this->Kendaraan_model->getHistoryKendaraanByID($id);
			if($query->num_rows() > 0){
				$data['row'] = $query->row();
				$this->load->view('tabel_history_kendaraan_edit', $data);
			} else {
				echo "<script>alert('Data Tidak ditemukan')";
				echo "window.location='".site_url('tabelKendaraan')."'</script>";
			}
		} else {
			$post = $this->input->post(null, TRUE);
			$this->Kendaraan_model->editHistoryKendaraan($post);
			if($this->db->affected_rows() > 0){
				echo "<script>alert('Data berhasil diedit')</script>";
			}
				echo "<script>window.location='".site_url('tabelKendaraan')."'</script>";	
		}	
	}
    
    public function hapus($id){
        
        $this->load->model('Kendaraan_model');
        $this->Kendaraan_model->hapusDataKendaraan($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        if($this->db->affected_rows() > 0){
				echo "<script>alert('Data berhasil dihapus')</script>";
			}
				echo "<script>window.location='".site_url('tabelKendaraan')."'</script>";

    }
    public function ubah($id){
        $data['kendaraan'] = $this->Kendaraan_model->getKendaraanByID($id);
        $data = [
            "kode_barang" =>$this->input->post('kode_barang', true), 
            "jenis_barang"=> 1, 
            "kategori"=>$this->input->post('kategori', true), 
            "nama"=>$this->input->post('nama', true), 
            "foto"=>' profile.png', 
            "keterangan" => $this->input->post('keterangan', true),
            "kualitas"=>$this->input->post('kualitas', true), 
            "no_plat"=>$this->input->post('no_plat', true),
            "tgl_masuk"=>$this->input->post('date', true)
        ];
        $this->db->insert('tb_kendaraan',$data);
    }
    public function tambahHistory(){
        $data['user'] = $this->db->get_where('user',['email'=> $this->session->userdata('email')])->row_array();
        $this->form_validation->set_rules('date','Tanggal','required|trim');
        
        if ($this->form_validation->run() == false) {

        $this->load->model('Chart_model');
        $this->load->model('Kendaraan_model');
            $data['kendaraan'] = $this->Kendaraan_model->getAllKendaraan();
            $data['service'] = $this->Kendaraan_model->getAllService();
            $data['history'] = $this->Kendaraan_model->getAllHistory();
            $this->load->model('Chart_model');
            $data['jmlhkendaraan'] = $this->Chart_model->getJumlahKendaraan();
            $this->load->model('Kategori_model');
            $data['kategori_kendaraan'] = $this->Kategori_model->getAllKategoriKendaraan();
            $data['jenis_barang'] = $this->Kategori_model->getAllJenisBarang();
            $data['kualitas'] = $this->Kategori_model->getAllKualitas();
            $this->load->view('tabel_kendaraan', $data);
            
        } else {
            $this->load->model('Kendaraan_model');
            $this->Kendaraan_model->tambahHistoryKendaraan();
            if($this->db->affected_rows() > 0){
				echo "<script>alert('History berhasil ditambah')</script>";
			}
				echo "<script>window.location='".site_url('tabelKendaraan')."'</script>";	
        }
        
    }
    
    public function hapusHistory($id){
        
        $this->load->model('Kendaraan_model');
        $this->Kendaraan_model->hapusHistoryKendaraan($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        if($this->db->affected_rows() > 0){
				echo "<script>alert('Data berhasil dihapus')</script>";
			}
				echo "<script>window.location='".site_url('tabelKendaraan')."'</script>";

    }
    
}