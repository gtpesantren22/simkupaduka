<?php

use SebastianBergmann\Environment\Console;

defined('BASEPATH') or exit('No direct script access allowed');

class Kasir extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('KasirModel', 'model');
        $this->load->model('Auth_model');

        $this->db2 = $this->load->database('dekos', true);
        $this->db3 = $this->load->database('sekretaris', true);
        $this->db4 = $this->load->database('santri', true);

        $user = $this->Auth_model->current_user();
        $this->tahun = $this->session->userdata('tahun');
        // $this->jenis = ['A. Belanja Barang', 'B. Langganan & Jasa', 'Belanja Kegiatan', 'D. Umum'];
        $this->bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $api = $this->model->apiKey()->row();
        $this->apiKey = $api->nama_key;
        $this->lembaga = $user->lembaga;
        $this->user = $user->nama;

        if ((!$this->Auth_model->current_user() && $user->level != 'kasir') || (!$this->Auth_model->current_user() && $user->level != 'admin')) {
            redirect('login/logout');
        }
    }

    public function index()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $bos = $this->model->getBySum('bos', 'tahun', $this->tahun, 'nominal')->row();
        $pembayaran = $this->model->getBySum('pembayaran', 'tahun', $this->tahun, 'nominal')->row();
        $pesantren = $this->model->getBySum('pesantren', 'tahun', $this->tahun, 'nominal')->row();
        $kebijakan = $this->model->getBySum('kebijakan', 'tahun', $this->tahun, 'nominal')->row();
        $realis = $this->model->getBySum('realis', 'tahun', $this->tahun, 'nom_serap')->row();
        $keluar = $this->model->getBySum('keluar', 'tahun', $this->tahun, 'nominal')->row();
        $data['dekos'] = $this->model->getDekosSum($this->tahun)->row();
        $data['nikmus'] = $this->model->getNikmusSum($this->tahun)->row();

        $sumPinjam = $this->model->getBySum('peminjaman', 'tahun', $this->tahun, 'nominal')->row();
        $sumCicil = $this->model->getBySum('cicilan', 'tahun', $this->tahun, 'nominal')->row();
        $realSisa = $this->model->getBySum('real_sisa', 'tahun', $this->tahun, 'sisa')->row();
        $cadangan = $this->model->getBySum('cadangan', 'tahun', $this->tahun, 'nominal')->row();
        $panjar = $this->model->getBySum('panjar', 'tahun', $this->tahun, 'nominal')->row();

        $data['masuk'] = $bos->jml + $pembayaran->jml + $pesantren->jml + $sumCicil->jml + $realSisa->jml + $cadangan->jml;
        $data['keluar'] = $kebijakan->jml + $realis->jml + $data['dekos']->nominal + $data['nikmus']->nom_kriteria + $data['nikmus']->transport + $data['nikmus']->sopir + $keluar->jml + $sumPinjam->jml + $panjar->jml;

        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();

        $data['saldo'] = $this->model->getBy2('saldo', 'name', 'bank', 'tahun', $data['tahun']);
        $data['cash'] = $this->model->getBy2('saldo', 'name', 'cash', 'tahun', $data['tahun']);

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/index', $data);
        $this->load->view('kasir/foot');
    }

    public function pengajuan()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['data'] = $this->model->getPengajuan($this->tahun)->result();
        // $data['lembaga'] = $this->model->getBy2('lembaga', 'kode'$this->tahun)->result();
        // $data['pj'] = $this->model->getPjn('pengajuan', $this->lembaga, $this->tahun)->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/pengajuan', $data);
        $this->load->view('kasir/foot');
    }

    public function cairProses($kode)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['pjn'] = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode)->row();
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $data['pjn']->lembaga, 'tahun', $this->tahun)->row();


        $crr = $this->model->getBySum('pencairan', 'kode_pengajuan', $kode, 'nominal_cair')->row();
        $dt2 = $this->model->getBySum('real_sm', 'kode_pengajuan', $kode, 'nominal')->row();

        $data['mitra'] = $this->model->getAll('mitra')->result();

        if ($crr->jml) {
            $data['tbl_slct'] = 'realis';
            $sts_tmbl = 'disabled';
            $data['dcair'] = $crr->jml;
            $data['dblm'] = 0;
        } else {
            $data['tbl_slct'] = 'real_sm';
            $sts_tmbl = '';
            $data['dcair'] = 0;
            $data['dblm'] = $dt2->jml;
        }

        $data['rls'] = $this->model->getBy2($data['tbl_slct'], 'kode_pengajuan', $kode, 'stas', 'tunai')->result();
        $data['rls2'] = $this->model->getBy2($data['tbl_slct'], 'kode_pengajuan', $kode, 'stas', 'non tunai')->result();
        foreach ($data['rls2'] as $key => $ls_jns) {
            $data['rls2'][$key]->pjnDataMitra = $this->model->getByJoin2('order_mitra', 'mitra', 'id_mitra', 'id_mitra', 'order_mitra.kode', $ls_jns->kode, 'order_mitra.kode_pengajuan', $ls_jns->kode_pengajuan)->row();
        }

        $data['mitraHasil'] = $this->model->getByGroup('order_mitra', 'kode_pengajuan', $kode, 'id_mitra')->result();
        foreach ($data['mitraHasil'] as $key) {
            $id_mitra = $key->id_mitra;
            $data['isiMitra'][$id_mitra] = $this->model->getBy2('order_mitra', 'id_mitra', $id_mitra, 'kode_pengajuan', $kode)->num_rows();
            $data['infoMitra'][$id_mitra] = $this->model->getBy('mitra', 'id_mitra', $id_mitra)->row();
        }

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/cair', $data);
        $this->load->view('kasir/foot');
    }

    public function editSerap()
    {
        $id = $this->input->post('id', true);
        $kode_pengajuan = $this->input->post('kode_pengajuan', true);
        $serap = rmRp($this->input->post('serap', true));
        $nom_cair = rmRp($this->input->post('nom_cair', true));
        $table = $this->input->post('table', true);

        if ($serap > $nom_cair) {
            $this->session->set_flashdata('error', 'Maaf. Nominal terserapnyanya lebih dari disetujui');
            redirect('kasir/cairProses/' . $kode_pengajuan);
        } else {
            $this->model->update($table, ['nom_serap' => $serap], 'id_realis', $id);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'Nominal serap berhasil diperbarui');
                redirect('kasir/cairProses/' . $kode_pengajuan);
            } else {
                $this->session->set_flashdata('error', 'Nominal serap tidak berhasil diperbarui');
                redirect('kasir/cairProses/' . $kode_pengajuan);
            }
        }
    }

    public function cairkan()
    {
        $id = $this->uuid->v4();

        $kd_pnj = $this->input->post('kode_pengajuan', true);
        $dataPj = $this->model->getBy('pengajuan', 'kode_pengajuan', $kd_pnj)->row();
        $jml = $this->db->query("SELECT SUM(nom_cair) as nom_cair, SUM(nom_serap) as nom_serap FROM real_sm WHERE kode_pengajuan = '$kd_pnj' ")->row();
        $dataReal = $this->model->getBy('real_sm', 'kode_pengajuan', $kd_pnj)->result();

        $lembaga =  $this->model->getBy2('lembaga', 'tahun', $this->tahun, 'kode', $dataPj->lembaga)->row();
        $tgl_cair = $this->input->post('tgl_cair', true);
        $kasir = $this->input->post('kasir', true);
        $penerima = $this->input->post('penerima', true);

        $data = [
            'id_cair' => $id,
            'kode_pengajuan' => $kd_pnj,
            'lembaga' => $dataPj->lembaga,
            'nominal' => $jml->nom_cair,
            'nominal_cair' => $jml->nom_serap,
            'tgl_cair' => $tgl_cair,
            'kasir' => $kasir,
            'penerima' => $penerima,
            'tahun' => $this->tahun,
        ];

        $data2 = ['cair' => 1];
        $this->model->input('pencairan', $data);
        $this->model->update('pengajuan', $data2, 'kode_pengajuan', $kd_pnj);

        foreach ($dataReal as $x) {
            $id_pnj = $x->id_realis;
            $dt = [
                'id_realis' => $id_pnj,
                'lembaga' => $x->lembaga,
                'bidang' => $x->bidang,
                'jenis' => $x->jenis,
                'kode' => $x->kode,
                'vol' => $x->vol,
                'nominal' => $x->nominal,
                'tgl' => $x->tgl,
                'pj' => $x->pj,
                'bulan' => $x->bulan,
                'tahun' => $x->tahun,
                'ket' => $x->ket,
                'kode_pengajuan' => $x->kode_pengajuan,
                'nom_cair' => $x->nom_cair,
                'nom_serap' => $x->nom_serap,
                'stas' => $x->stas
            ];

            $this->model->input('realis', $dt);
            $this->model->delete('real_sm', 'id_realis', $id_pnj);
        }

        $psn = '
*INFORMASI PENCAIRAN PENGAJUAN*

Pencairan pengajuan dari :
    
Lembaga : ' . $lembaga->nama . '
Kode Pengajuan : ' . $kd_pnj . '
Pada : ' . $tgl_cair . '
Nominal : ' . rupiah($jml->nom_serap) . '
Penerima : ' . $penerima . '

*_telah dicairkan oleh Bendahara Bag. Admin Pencairan._*
Terimakasih';

        if ($this->db->affected_rows() > 0) {
            kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
            kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
            kirim_person($this->apiKey, '082264061060', $psn);
            kirim_person($this->apiKey, '085236924510', $psn);

            $this->session->set_flashdata('ok', 'Pengajuan sudah dicairkan');
            redirect('kasir/cairProses/' . $kd_pnj);
        } else {
            $this->session->set_flashdata('error', 'Pengajuan tidak bisa dicairkan');
            redirect('kasir/cairProses/' . $kd_pnj);
        }
    }

    public function pengajuanDisp()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['data'] = $this->model->getPengajuanDisp($this->tahun)->result();
        // $data['lembaga'] = $this->model->getBy2('lembaga', 'kode'$this->tahun)->result();
        // $data['pj'] = $this->model->getPjn('pengajuan', $this->lembaga, $this->tahun)->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/pengajuan', $data);
        $this->load->view('kasir/foot');
    }

    public function tanggungan()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['data'] = $this->model->getByJoin('tangg', 'tb_santri', 'nis', 'nis', 'tangg.tahun', $this->tahun)->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/tanggungan', $data);
        $this->load->view('kasir/foot');
    }

    public function delTanggungan($id)
    {
        $this->model->delete('tangg', 'id_tangg', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Tanggungan berhasil dihapus');
            redirect('kasir/tanggungan');
        } else {
            $this->session->set_flashdata('error', 'Tanggungan berhasil dihapus');
            redirect('kasir/tanggungan');
        }
    }

    public function discrb($nis)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['sn'] = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $data['tgn'] = $this->model->getBy2('tangg', 'nis', $nis, 'tahun', $this->tahun)->row();
        $data['masuk'] = $this->db->query("SELECT SUM(nominal) AS jml FROM pembayaran WHERE nis = '$nis' AND tahun = '$this->tahun' GROUP BY nis ")->row();
        $data['bayar'] = $this->model->getBy2('pembayaran', 'nis', $nis, 'tahun', $this->tahun)->result();

        $data['tmpKos'] = array("", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum");
        $data['kter'] = ["Bayar", "Ust/Usdtz", "Khaddam", "Gratis", "Berhenti"];


        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/discrb', $data);
        $this->load->view('kasir/foot');
    }

    public function addbayar()
    {
        $user = $this->Auth_model->current_user();

        $nominal = rmRp($this->input->post('nominal', true));
        $tgl = $this->input->post('tgl', true);
        $kasir = $user->nama;
        $nama = $this->input->post('nama', true);
        $nis = $this->input->post('nis', true);
        $tahun = $this->tahun;
        $dekos = $this->input->post('dekos', true);
        $bulan_bayar = $this->input->post('bulan', true);

        $dp = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $dpBr = $this->model->getBy2('tangg', 'nis', $nis, 'tahun', $this->tahun)->row();

        $by = $nominal + $this->input->post('masuk', true);
        $ttl = $this->input->post('ttl', true);
        $alm = $dp->desa . '-' . $dp->kec . '-' . $dp->kab;
        $hpNo = $dp->hp;
        $hpNo2 = '085236924510';

        $data = [
            'nis' => $nis,
            'nama' => $nama,
            'tgl' => $tgl,
            'nominal' => $nominal,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'kasir' => $kasir,
        ];
        $data2 = [
            'nis' => $nis,
            'nominal' => 300000,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'tgl' => $tgl,
            'penerima' => $kasir,
            'stts' => 1,
            'waktu' => date('Y-m-d H:i'),
        ];

        $pesan = '
*KWITANSI PEMBAYARAN ELEKTRONIK*
*PP DARUL LUGHAH WAL KAROMAH*
Bendahara Pondok Pesantren Darul Lughah Wal Karomah telah menerima pembayaran BP dari wali santri berikut :
    
No. BRIVA : *' . $dpBr->briva . '*
Nama : *' . $nama . '*
Alamat : *' . $alm . '* 
Nominal Pembayaran: *' . rupiah($nominal) . '*
Tanggal Bayar : *' . $tgl . '*
Pembayaran Untuk: *BP (Biaya Pendidikan) bulan ' . $this->bulan[$bulan_bayar] . '*
Penerima: *' . $kasir . '*

Bukti Penerimaan ini *DISIMPAN* oleh wali santri sebagai bukti pembayaran Biaya Pendidikan PP Darul Lughah Wal Karomah Tahun Pelajaran ' . $tahun . '.
*Hal – hal yang berkaitan dengan Teknis keuangan dapat menghubungi Contact Person Bendahara berikut :*
*https://wa.me/6287757777273*
*https://wa.me/6285235583647*

Terimakasih';

        if ($by > $ttl) {
            $this->session->set_flashdata('error', 'Maaf pembayaran melebihi');
            redirect('kasir/discrb/' . $nis);
        } else {
            $cek = $this->db->query("SELECT * FROM pembayaran WHERE nis = '$nis' AND bulan = '$bulan_bayar' AND tahun = '$tahun' ")->num_rows();
            if ($cek < 1) {
                if ($dekos == 'Y') {
                    $this->model->inputDb2('kos', $data2);
                    $this->model->input('pembayaran', $data);

                    if ($this->db->affected_rows() > 0) {
                        kirim_person($this->apiKey, $hpNo, $pesan);
                        kirim_person($this->apiKey, $hpNo2, $pesan);
                        $this->session->set_flashdata('ok', 'Tanggungan berhasil diinput');
                        redirect('kasir/discrb/' . $nis);
                    } else {
                        $this->session->set_flashdata('error', 'Tanggungan tidak berhasil diinput');
                        redirect('kasir/discrb/' . $nis);
                    }
                } else {
                    $this->model->input('pembayaran', $data);

                    if ($this->db->affected_rows() > 0) {
                        kirim_person($this->apiKey, $hpNo, $pesan);
                        kirim_person($this->apiKey, $hpNo2, $pesan);
                        $this->session->set_flashdata('ok', 'Tanggungan berhasil diinput');
                        redirect('kasir/discrb/' . $nis);
                    } else {
                        $this->session->set_flashdata('error', 'Tanggungan tidak berhasil diinput');
                        redirect('kasir/discrb/' . $nis);
                    }
                }
            } else {
                $this->session->set_flashdata('error', 'Maaf pembayaran ini bulan ini sudah ada');
                redirect('kasir/discrb/' . $nis);
            }
        }
    }

    public function delBayar($id)
    {
        $data = $this->model->getBy('pembayaran', 'id', $id)->row();

        // $sql = mysqli_query($conn, "DELETE FROM pembayaran WHERE id = '$id' AND tahun = '$tahun_ajaran' ");
        // $sql2 = mysqli_query($conn_dekos, "DELETE FROM kos WHERE nis = '$nis' AND bulan = '$buln' AND tahun = '$tahun' ");

        $this->model->deleteBayar($data->nis, $data->bulan, $data->tahun);
        $this->model->delete('pembayaran', 'id', $id);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Tanggungan berhasil dihapus');
            redirect('kasir/discrb/' . $data->nis);
        } else {
            $this->session->set_flashdata('error', 'Tanggungan tidak berhasil dihapus');
            redirect('kasir/discrb/' . $data->nis);
        }
    }

    public function bayar()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['rls'] = $this->model->getBayarAll()->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/bayar', $data);
        $this->load->view('kasir/foot');
    }

    public function mutasi()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['data'] = $this->model->getMutasi()->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/mutasi', $data);
        $this->load->view('kasir/foot');
    }

    public function mutasiDtl($nis)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['sn'] = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $data['tgn'] = $this->model->getBy2('tangg', 'nis', $nis, 'tahun', $this->tahun)->row();
        $data['masuk'] = $this->db->query("SELECT SUM(nominal) AS jml FROM pembayaran WHERE nis = '$nis' AND tahun = '$this->tahun' GROUP BY nis ")->row();
        $data['bayar'] = $this->model->getBy2('pembayaran', 'nis', $nis, 'tahun', $this->tahun)->result();

        $data['mts'] = $this->model->getByDb3('mutasi', 'nis', $nis)->row();
        $data['rc_byar'] = $this->model->getBy2('tangg', 'nis', $nis, 'tahun', $this->tahun)->row();

        if (date('m', strtotime($data['mts']->tgl_mutasi)) == 5 || date('m', strtotime($data['mts']->tgl_mutasi)) == 6) {
            $data['tgbyr'] = $data['rc_byar']->me_ju;
            $data['dekos'] = 0;
        } else {
            if ($data['rc_byar']->me_ju == $data['rc_byar']->ju_ap) {
                $data['dekos'] = 0;
            } else {
                $data['dekos'] = 300000;
            }
            $data['tgbyr'] = $data['rc_byar']->ju_ap - $data['dekos'];
        }

        $data['tglbr'] = date('d', strtotime($data['mts']->tgl_mutasi));

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/mutasi_dtl', $data);
        $this->load->view('kasir/foot');
    }

    public function bayarMutasi()
    {
        $user = $this->Auth_model->current_user();

        $nominal = rmRp($this->input->post('nominal_bp', true));
        $tgl = $this->input->post('tgl', true);
        $kasir = $user->nama;
        $nama = $this->input->post('nama', true);
        $nis = $this->input->post('nis', true);
        $tahun = $this->tahun;
        $dekos = rmRp($this->input->post('nominal_dks', true));
        $bulan_bayar = $this->input->post('bulan', true);

        $dp = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $dpBr = $this->model->getBy2('tangg', 'nis', $nis, 'tahun', $this->tahun)->row();

        $by = $nominal + $this->input->post('masuk', true);
        $ttl = $this->input->post('ttl', true);
        $alm = $dp->desa . '-' . $dp->kec . '-' . $dp->kab;
        // $hpNo = $dp->hp;
        $hpNo = '085236924510';

        $data = [
            'nis' => $nis,
            'nama' => $nama,
            'tgl' => $tgl,
            'nominal' => $nominal,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'kasir' => $kasir,
        ];
        $data2 = [
            'nis' => $nis,
            'nominal' => $dekos,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'tgl' => $tgl,
            'penerima' => $kasir,
            'stts' => 1,
            'waktu' => date('Y-m-d H:i'),
        ];

        $pesan = '
*KWITANSI PEMBAYARAN ELEKTRONIK*
*PP DARUL LUGHAH WAL KAROMAH*
Bendahara Pondok Pesantren Darul Lughah Wal Karomah telah menerima pembayaran BP dari wali santri berikut :
    
No. BRIVA : *' . $dpBr->briva . '*
Nama : *' . $nama . '*
Alamat : *' . $alm . '* 
Nominal Pembayaran: *' . rupiah($nominal) . '*
Tanggal Bayar : *' . $tgl . '*
Pembayaran Untuk: *BP (Biaya Pendidikan) bulan ' . $this->bulan[$bulan_bayar] . '*
Penerima: *' . $kasir . '*

Bukti Penerimaan ini *DISIMPAN* oleh wali santri sebagai bukti pembayaran Biaya Pendidikan PP Darul Lughah Wal Karomah Tahun Pelajaran ' . $tahun . '.
*Hal – hal yang berkaitan dengan Teknis keuangan dapat menghubungi Contact Person Bendahara berikut :*
*https://wa.me/6287757777273*
*https://wa.me/6285235583647*

Terimakasih';

        if ($by > $ttl) {
            $this->session->set_flashdata('error', 'Maaf pembayaran melebihi');
            redirect('kasir/mutasiDtl/' . $nis);
        } else {
            $cek = $this->db->query("SELECT * FROM pembayaran WHERE nis = '$nis' AND bulan = '$bulan_bayar' AND tahun = '$tahun' ")->num_rows();
            if ($cek < 1) {

                $this->model->inputDb2('kos', $data2);
                $this->model->input('pembayaran', $data);

                if ($this->db->affected_rows() > 0) {
                    kirim_person($this->apiKey, $hpNo, $pesan);
                    $this->session->set_flashdata('ok', 'Tanggungan berhasil diinput');
                    redirect('kasir/mutasiDtl/' . $nis);
                } else {
                    $this->session->set_flashdata('error', 'Tanggungan tidak berhasil diinput');
                    redirect('kasir/mutasiDtl/' . $nis);
                }
            } else {
                $this->session->set_flashdata('error', 'Maaf pembayaran ini bulan ini sudah ada');
                redirect('kasir/mutasiDtl/' . $nis);
            }
        }
    }

    public function vervalMutasi($id)
    {
        $mutasi = $this->model->getByDb3('mutasi', 'id_mutasi', $id)->row();
        $dts = $this->model->getBy('tb_santri', 'nis', $mutasi->nis)->row();

        // $sql = mysqli_query($conn_santri, "UPDATE mutasi SET status = 1 WHERE id_mutasi = '$id_mutasi' ");
        // $sql2 = mysqli_query($conn_sekretaris, "UPDATE mutasi SET status = 1 WHERE id_mutasi = '$id_mutasi' ");

        $this->model->updateDb3('mutasi', ['status' => 1], 'nis', $mutasi->nis);
        $this->model->updateDb4('mutasi', ['status' => 1], 'nis', $mutasi->nis);
        $hpNo = '085236924510';
        $psn = '*INFORMASI MUTASI*

*PENERBITAN SURAT BERHENTI*
    
Nama : ' . $dts->nama . '
Alamat : ' . $dts->desa . '-' . $dts->kec . '-' . $dts->kab . '
Sekolah : ' . $dts->k_formal . ' ' . $dts->t_formal . '
Tgl Mutasi : ' .  $mutasi->tgl_mutasi . '

*_telah diverifikasi oleh BENDAHARA PESANTREN. Untuk selanjutnya surat mutasi sudah bisa diterbitkan oleh SEKRETARIAT_*
Terimakasih';

        if ($this->db->affected_rows() > 0) {
            kirim_group($this->apiKey, '120363028015516743@g.us', $psn);
            // kirim_person($this->apiKey, $hpNo, $psn);
            $this->session->set_flashdata('ok', 'Mutasi berhasil diverval');
            redirect('kasir/mutasiDtl/' . $mutasi->nis);
        } else {
            $this->session->set_flashdata('error', 'Mutasi gagal diverval');
            redirect('kasir/mutasiDtl/' . $mutasi->nis);
        }
    }

    public function info()
    {
        $data['data'] = $this->model->getBy('info', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/info', $data);
        $this->load->view('kasir/foot');
    }

    public function setting()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/setting', $data);
        $this->load->view('kasir/foot');
    }

    public function updateAkun()
    {
        $id = $this->Auth_model->current_user('id_user');
        $id_user = $id->id_user;

        $nama = $this->input->post('nama', true);
        $username = $this->input->post('username', true);
        $password = $this->input->post('newpass', true);
        $password2 = $this->input->post('confir_newpass', true);
        $pass_lama = $this->input->post('pass_lama', true);
        $pass_baru = password_hash($password, PASSWORD_DEFAULT);

        if ($password == '' && $password2 = '') {

            $data = [
                'nama' => strtoupper($nama),
                'username' => $username
            ];
            $this->model->update('user', $data, 'id_user', $id_user);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'User akun berhasil diperbarui');
                redirect('kasir/setting');
            } else {
                $this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
                redirect('kasir/setting');
            }
        } else {
            if ($password != $password2) {
                $this->session->set_flashdata('error', 'Konfimasi password tidak sama');
                redirect('kasir/setting');
            } else {

                $data = [
                    'nama' => $nama,
                    'username' => $username,
                    'password' => $pass_baru
                ];
                $this->model->update('user', $data, 'id_user', $id_user);
                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('ok', 'User akun berhasil diperbarui');
                    redirect('kasir/setting');
                } else {
                    $this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
                    redirect('kasir/setting');
                }
            }
        }
    }

    public function updateLembaga()
    {
        $id_lm = $this->lembaga;
        $tahun = $this->tahun;

        $data = [
            'pj' => $this->input->post('pj', true),
            'hp' => $this->input->post('hp', true),
            'hp_kep' => $this->input->post('hp_kep', true),
            'waktu' => $this->input->post('waktu', true)
        ];

        $this->model->update2('lembaga', $data, 'kode', $id_lm, 'tahun', $tahun);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'User akun berhasil diperbarui');
            redirect('kasir/setting');
        } else {
            $this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
            redirect('kasir/setting');
        }
    }

    public function uploadFoto()
    {

        $user = $this->Auth_model->current_user();

        $file_name = 'PROFILE-' . rand(0, 99999999);
        $config['upload_path']          = FCPATH . '/vertical/assets/uploads/profile/';
        $config['allowed_types']        = 'jpg|jpeg|png';
        $config['file_name']            = $file_name;
        $config['overwrite']            = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $data['error'] = $this->upload->display_errors();
        } else {
            $uploaded_data = $this->upload->data();

            $new_data = [
                'foto' =>  $uploaded_data['file_name']
            ];
            $this->model->update('user', $new_data, 'id_user', $user->id_user);
            // unlink('./vertical/assets/uploads/honor/' . $file->files);

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'Upload foto sukses');
                redirect('kasir/setting');
            } else {
                $this->session->set_flashdata('error', 'Upload foto sukses');
                redirect('kasir/setting');
            }
        }
    }

    public function rekap()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;
        $data['hasil'] = $this->model->getByrGroup($this->tahun)->result();
        $data['total'] = $this->model->getBySum('pembayaran', 'tahun', $this->tahun, 'nominal')->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/rekap', $data);
        $this->load->view('kasir/foot');
    }

    public function dispen()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;
        $data['data'] = $this->model->getByJoin('dispensasi', 'tb_santri', 'nis', 'nis', 'dispensasi.tahun', $this->tahun)->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/dispen', $data);
        $this->load->view('kasir/foot');
    }

    public function dispenAdd()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;
        $data['santri'] = $this->model->getBy('tb_santri', 'aktif', 'Y')->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/dispenAdd', $data);
        $this->load->view('kasir/foot');
    }

    public function saveDispen()
    {
        $nis = $this->input->post('nis', true);
        $sandal = rmRp($this->input->post('sandal', true));
        $lomba = rmRp($this->input->post('lomba', true));
        $wilayah = rmRp($this->input->post('wilayah', true));

        $cek = $this->db->query("SELECT * FROM dispensasi WHERE nis = '$nis' ")->num_rows();
        $tangg = $this->db->query("SELECT ((ju_ap * 8) + (me_ju * 2)) AS tgnApr FROM tangg WHERE nis = '$nis' AND tahun = '$this->tahun' ")->row();
        $masuk = $this->db->query("SELECT SUM(nominal) AS byr FROM pembayaran WHERE nis = '$nis' AND tahun = '$this->tahun' ")->row();
        $bp = $tangg->tgnApr < $masuk->byr ? 0 : $tangg->tgnApr - $masuk->byr;

        $data = [
            'id_dispensasi' => $this->uuid->v4(),
            'nis' => $nis,
            'bp' => $bp,
            'sandal' => $sandal,
            'lomba' => $lomba,
            'wilayah' => $wilayah,
            'tahun' => $this->tahun,
        ];

        if ($cek > 0) {
            $this->session->set_flashdata('error', 'Maaf data sudah ada');
            redirect('kasir/dispenAdd');
        } else {
            $this->model->input('dispensasi', $data);

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'Data berhasil diinput');
                redirect('kasir/dispen');
            } else {
                $this->session->set_flashdata('error', 'Data tidak berhasil diinput');
                redirect('kasir/dispen');
            }
        }
    }

    public function delDispen($id)
    {
        $this->model->delete('dispensasi', 'id_dispensasi', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Data berhasil dihapus');
            redirect('kasir/dispen');
        } else {
            $this->session->set_flashdata('error', 'Data tidak berhasil dihapus');
            redirect('kasir/dispen');
        }
    }

    public function cetakDispen($nis)
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;
        $data['santri'] = $this->model->getBy2('tb_santri', 'nis', $nis, 'aktif', 'Y')->row();
        $data['dispn'] = $this->model->getBy2('dispensasi', 'nis', $nis, 'tahun', $this->tahun)->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/cetakDispen', $data);
        $this->load->view('kasir/foot');
    }

    public function printDispen()
    {
        $nis = $this->input->post('nis', true);
        $data['santri'] = $this->model->getBy2('tb_santri', 'nis', $nis, 'aktif', 'Y')->row();
        $data['dispn'] = $this->model->getBy2('dispensasi', 'nis', $nis, 'tahun', $this->tahun)->row();

        $data['bp'] = rmRp($this->input->post('bp', true));
        $data['bayar'] = rmRp($this->input->post('bayar', true));
        $data['janji'] = $this->input->post('janji', true);

        $datas = [
            'bayar' => $data['bayar'],
            'tgl_bayar' => date('Y-m-d'),
            'janji' => $data['janji'],
        ];

        $this->model->update('dispensasi', $datas, 'nis', $nis);

        if ($this->db->affected_rows() > 0) {
            $this->load->view('kasir/printDispen', $data);
        } else {
            $this->session->set_flashdata('error', 'Data tidak berhasil disipan');
            redirect('kasir/dispen');
        }
    }

    public function ifoDispen($nis)
    {
        $santri = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $dspn = $this->model->getBy('dispensasi', 'nis', $nis)->row();
        $tgn = $dspn->bp + $dspn->sandal + $dspn->wilayah + $dspn->lomba;
        $bln = $this->bulan;

        $pesan = '*Notifikasi Tagihan Perjanjian Pembayaran*
Yth *' . $santri->nama . '*, 
Dengan ini kami sampaikan Anda memiliki Tagihan Perjanjian Pembayaran di PP DARUL LUGHAH WAL KAROMAH sebesar *' . rupiah($tgn - $dspn->bayar) . '* 
dengan perjanjian pelunasan pada *Bulan ' . $bln[$dspn->janji] . '*

Terima kasih. 

Bendahara Pesantren 

_Jika sudah melakukan pelunasan abaikan pesan ini_';

        kirim_person($this->apiKey, $santri->hp, $pesan);
    }

    public function addOrderMitra()
    {
        $id_mitra = $this->input->post('id_mitra', true);
        $kode = $this->input->post('kode', true);
        $kode_pengajuan = $this->input->post('kode_pengajuan', true);

        $cekPjn = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode_pengajuan)->row();
        $cekPjn->cair == 1 ? $tblSelect = 'realis' : $tblSelect = 'real_sm';

        $pjnData = $this->model->getBy2($tblSelect, 'kode', $kode, 'kode_pengajuan', $kode_pengajuan)->row();

        $data = [
            'id_mitra' => $id_mitra,
            'kode' => $kode,
            'kode_pengajuan' => $kode_pengajuan,
            'tgl_order' => $pjnData->tgl,
            'tahun' => $pjnData->tahun,
            'status' => 'belum',
        ];

        $this->model->input('order_mitra', $data);
        if ($this->db->affected_rows() > 0) {
            // $this->session->set_flashdata('ok', 'Add Mitra Berhasil');
            // redirect('kasir/cairProses/' . $kode_pengajuan);
            echo '';
        } else {
            // $this->session->set_flashdata('error', 'Add Mitra Gagal');
            // redirect('kasir/cairProses/' . $kode_pengajuan);
            echo '';
        }
    }

    function delOrderMitra()
    {
        $id = $this->input->post('id_order', true);
        $kode_pengajuan = $this->model->getBy('order_mitra', 'id_order', $id)->row('kode_pengajuan');

        $this->model->delete('order_mitra', 'id_order', $id);
        if ($this->db->affected_rows() > 0) {
            // $this->session->set_flashdata('ok', 'Add Mitra Berhasil');
            // redirect('kasir/cairProses/' . $kode_pengajuan);
            echo '';
        } else {
            // $this->session->set_flashdata('error', 'Add Mitra Gagal');
            // redirect('kasir/cairProses/' . $kode_pengajuan);
            echo '';
        }
    }

    function notaMitra()
    {
        $data['kode_pj'] = $this->uri->segment(3);
        $id_mitra = $this->uri->segment(4);
        $kode_lj = $this->uri->segment(3);

        $sttsPj = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode_lj)->row();
        $sttsPj->cair == 1 ? $tblSelect = 'realis' : $tblSelect = 'real_sm';

        $data['mitra'] = $this->model->getBy('mitra', 'id_mitra', $id_mitra)->row();
        // $data['order_mitra'] = $this->model->getBy('order_mitra', 'kode_pengajuan', $data['kode_pj']);
        // $data['order_mitra'] = $this->model->getByJoin2('order_mitra', 'real_sm', 'kode', 'kode', 'order_mitra.kode_pengajuan', $data['kode_pj'], 'order_mitra.id_mitra', $id_mitra);

        $data['order_mitra'] = $this->db->query("SELECT order_mitra.*, $tblSelect.*, rab.nama, rab.satuan, rab.harga_satuan FROM order_mitra JOIN $tblSelect ON order_mitra.kode=$tblSelect.kode JOIN rab ON order_mitra.kode=rab.kode WHERE order_mitra.kode_pengajuan = '$kode_lj' AND order_mitra.id_mitra = '$id_mitra' ");

        $data['order_mitraTotal'] = $this->db->query("SELECT SUM($tblSelect.nominal) AS total FROM order_mitra JOIN $tblSelect ON order_mitra.kode=$tblSelect.kode WHERE order_mitra.kode_pengajuan = '$kode_lj' AND order_mitra.id_mitra = '$id_mitra' ")->row();


        $data['lembaga'] = $this->model->getBy2('lembaga', 'tahun', $this->tahun, 'kode', $data['order_mitra']->row('lembaga'));
        $data['kasir'] = $this->user;

        $this->load->view('kasir/cetakNota', $data);
    }

    public function pinjam()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['pinjam'] = $this->model->getBy('peminjaman', 'tahun', $this->tahun)->result();
        $data['sumPinjam'] = $this->model->getBySum('peminjaman', 'tahun', $this->tahun, 'nominal')->row();
        $data['sumCicil'] = $this->model->getBySum('cicilan', 'tahun', $this->tahun, 'nominal')->row();
        $data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/pinjam', $data);
        $this->load->view('kasir/foot');
    }

    public function savePinjam()
    {
        $data = [
            'id_pinjam' => $this->uuid->v4(),
            'kode_pinjam' => 'PINJAM-' . rand(0, 99999999),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'jml_cicil' => $this->input->post('jml_cicil', true),
            'peminjam' => $this->input->post('peminjam', true),
            'tgl_pinjam' => $this->input->post('tgl_pinjam', true),
            'tahun' => $this->tahun,
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->input('peminjaman', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/pinjam');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/pinjam');
        }
    }

    public function delPinjam($id)
    {
        $data = $this->model->getBy('peminjaman', 'id_pinjam', $id)->row();

        $this->model->delete('peminjaman', 'id_pinjam', $id);
        $this->model->delete('cicilan', 'kode_pinjam', $data->kode_pinjam);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/pinjam');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/pinjam');
        }
    }

    public function infoPinjam($id)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);

        $data['dataPinjam'] = $this->model->getBy('peminjaman', 'id_pinjam', $id)->row();
        $data['cicil'] = $this->model->getBy('cicilan', 'kode_pinjam', $data['dataPinjam']->kode_pinjam)->result();
        $data['sumPinjam'] = $this->model->getBySum('peminjaman', 'tahun', $this->tahun, 'nominal')->row();

        $data['sumCicil'] = $this->model->getBySum('cicilan', 'kode_pinjam', $data['dataPinjam']->kode_pinjam, 'nominal')->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/infoPinjam', $data);
        $this->load->view('kasir/foot');
    }

    public function addCicil()
    {
        $dataPinjam = $this->model->getBy('peminjaman', 'kode_pinjam', $this->input->post('kode_pinjam', true))->row();
        $data = [
            'id_cicilan' => $this->uuid->v4(),
            'kode_pinjam' =>  $this->input->post('kode_pinjam', true),
            'ket ' =>  $this->input->post('ket', true),
            'tgl_setor ' =>  $this->input->post('tgl_setor', true),
            'nominal ' =>  $dataPinjam->nominal / $dataPinjam->jml_cicil,
            'tahun' => $this->tahun,
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->input('cicilan', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/infoPinjam/' . $dataPinjam->id_pinjam);
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/infoPinjam/' . $dataPinjam->id_pinjam);
        }
    }

    public function delCicil($id)
    {
        $data = $this->model->getBy('cicilan', 'id_cicilan', $id)->row();
        $dataPinjam = $this->model->getBy('peminjaman', 'kode_pinjam', $data->kode_pinjam)->row();

        $this->model->delete('cicilan', 'id_cicilan', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/infoPinjam/' . $dataPinjam->id_pinjam);
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/infoPinjam/' . $dataPinjam->id_pinjam);
        }
    }

    public function pesantren()
    {
        $data['pes'] = $this->model->getBy('pesantren', 'tahun', $this->tahun)->result();
        $data['sumPes'] = $this->model->selectSum('pesantren', 'nominal', 'tahun', $this->tahun)->row();
        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/masukPes', $data);
        $this->load->view('kasir/foot');
    }

    public function pesAdd()
    {
        $data = [
            'id_pes' => $this->uuid->v4(),
            'lembaga' => $this->input->post('lembaga', true),
            'bidang' => $this->input->post('bidang', true),
            'kode' => $this->input->post('lembaga', true) . '.' . $this->input->post('bidang', true),
            'uraian' => $this->input->post('uraian', true),
            'periode' => $this->input->post('periode', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tgl_bayar' => $this->input->post('tgl_bayar', true),
            'tahun' => $this->input->post('tahun', true),
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->input('pesantren', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input Pemasukan Pesantren Berhasil');
            redirect('kasir/pesantren');
        } else {
            $this->session->set_flashdata('error', 'Input Pemasukan Pesantren Gagal');
            redirect('kasir/pesantren');
        }
    }

    public function editPes($id)
    {
        $data['pes'] = $this->model->getBy('pesantren', 'id_pes', $id)->row();
        $data['sumPes'] = $this->model->selectSum('pesantren', 'nominal', 'tahun', $this->tahun)->row();
        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/editPes', $data);
        $this->load->view('kasir/foot');
    }

    public function saveEditPes()
    {
        $id = $this->input->post('id_pes', true);
        $data = [
            'lembaga' => $this->input->post('lembaga', true),
            'bidang' => $this->input->post('bidang', true),
            'kode' => $this->input->post('lembaga', true) . '.' . $this->input->post('bidang', true),
            'uraian' => $this->input->post('uraian', true),
            'periode' => $this->input->post('periode', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tgl_bayar' => $this->input->post('tgl_bayar', true),
            'tahun' => $this->input->post('tahun', true),
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->update('pesantren', $data, 'id_pes', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Edit Pemasukan Pesantren Berhasil');
            redirect('kasir/pesantren');
        } else {
            $this->session->set_flashdata('error', 'Edit Pemasukan Pesantren Gagal');
            redirect('kasir/pesantren');
        }
    }

    public function delPes($id)
    {
        $this->model->delete('pesantren', 'id_pes', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus Pemasukan Pesantren Berhasil');
            redirect('kasir/pesantren');
        } else {
            $this->session->set_flashdata('error', 'Hapus Pemasukan Pesantren Gagal');
            redirect('kasir/pesantren');
        }
    }

    public function bos()
    {
        $data['bos'] = $this->model->getBy('bos', 'tahun', $this->tahun)->result();
        $data['sumBos'] = $this->model->selectSum('bos', 'nominal', 'tahun', $this->tahun)->row();
        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/masukBos', $data);
        $this->load->view('kasir/foot');
    }

    public function bosAdd()
    {
        $data = [
            'id_bos' => $this->uuid->v4(),
            'lembaga' => $this->input->post('lembaga', true),
            'kode' => $this->input->post('lembaga', true) . '.' . $this->input->post('bidang', true),
            'uraian' => $this->input->post('uraian', true),
            'periode' => $this->input->post('periode', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tgl_setor' => $this->input->post('tgl_setor', true),
            'tahun' => $this->input->post('tahun', true),
            'kasir' => $this->input->post('kasir', true),
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->input('bos', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input Pemasukan BOS Berhasil');
            redirect('kasir/bos');
        } else {
            $this->session->set_flashdata('error', 'Input Pemasukan BOS Gagal');
            redirect('kasir/bos');
        }
    }

    public function editBos($id)
    {
        $data['pes'] = $this->model->getBy('bos', 'id_bos', $id)->row();
        $data['sumBos'] = $this->model->selectSum('bos', 'nominal', 'tahun', $this->tahun)->row();
        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/editBos', $data);
        $this->load->view('kasir/foot');
    }

    public function saveEditBos()
    {
        $id = $this->input->post('id_bos', true);
        $data = [
            'lembaga' => $this->input->post('lembaga', true),
            'kode' => $this->input->post('lembaga', true),
            'uraian' => $this->input->post('uraian', true),
            'periode' => $this->input->post('periode', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tgl_setor' => $this->input->post('tgl_setor', true),
            'tahun' => $this->input->post('tahun', true),
        ];

        $this->model->update('bos', $data, 'id_bos', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Edit Pemasukan Bos Berhasil');
            redirect('kasir/bos');
        } else {
            $this->session->set_flashdata('error', 'Edit Pemasukan Bos Gagal');
            redirect('kasir/bos');
        }
    }

    public function delBos($id)
    {
        $this->model->delete('bos', 'id_bos', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus Pemasukan Bos Berhasil');
            redirect('kasir/bos');
        } else {
            $this->session->set_flashdata('error', 'Hapus Pemasukan Bos Gagal');
            redirect('kasir/bos');
        }
    }

    public function bpMasuk()
    {
        $data['data'] = $this->model->getBy('pembayaran', 'tahun', $this->tahun)->result();
        $data['sumBp'] = $this->model->selectSum('pembayaran', 'nominal', 'tahun', $this->tahun)->row();
        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan_cal'] = $this->bulan;

        for ($i = 1; $i <= 12; $i++) {
            $i !== 5 && $i !== 6 ? $field = 'ju_ap' : $field = 'me_ju';

            $tangg_perbulan = $this->model->getBySum('tangg', 'tahun', $this->tahun, $field)->row();
            $bayar_perbulan = $this->model->getBySum2('pembayaran', 'tahun', $this->tahun, 'bulan', $i, 'nominal')->row();
            $jml_tangg[] = array(
                'bulan' => $i,
                'tangg' => $tangg_perbulan->jml,
                'bayar' => $bayar_perbulan->jml,
                'bayar_prsn' => $bayar_perbulan->jml / $tangg_perbulan->jml * 100,
                'kurang' => $tangg_perbulan->jml - $bayar_perbulan->jml,
                'kurang_prsn' => ($tangg_perbulan->jml - $bayar_perbulan->jml) / $tangg_perbulan->jml * 100,
            );
        }

        $data['jml_tangg'] = $jml_tangg;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/masukBp', $data);
        $this->load->view('kasir/foot');
    }

    public function lain()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['keluar'] = $this->model->getBy('keluar', 'tahun', $this->tahun)->result();
        $data['sumKeluar'] = $this->model->getBySum('keluar', 'tahun', $this->tahun, 'nominal')->row();
        $data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/keluar', $data);
        $this->load->view('kasir/foot');
    }

    public function saveOut()
    {
        $data = [
            'id_keluar' => $this->uuid->v4(),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tanggal' => $this->input->post('tanggal', true),
            'pj' => $this->input->post('pj', true),
            'ket' => $this->input->post('ket', true),
            'tahun' => $this->tahun,
            'kasir' => $this->user,
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->input('keluar', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/lain');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/lain');
        }
    }

    public function delLain($id)
    {
        $this->model->delete('keluar', 'id_keluar', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/lain');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/lain');
        }
    }

    function sendNota()
    {
        $kode_pengajuan = $this->uri->segment(3);
        $id_mitra = $this->uri->segment(4);

        $mitra = $this->model->getBy('mitra', 'id_mitra', $id_mitra)->row();
        $url_file = base_url('vertical/assets/nota/' . $kode_pengajuan . '_' . $id_mitra . '.jpg');
        $url_file = base_url('246708021.jpg');
        $caption = 'Kpd, Yth.

' . $mitra->nama . '
Berikut ada order baru dengan Kode Order ' . $kode_pengajuan . '

Terimkasih
TTD


Bendahara PPDWK
';

        // echo $url_file;
        // kirim_nota($this->apiKey, $mitra->hp, $url_file, '0', $caption);
    }

    function notaKPA($kodePj)
    {
        $data['kode_pj'] = $kodePj;

        $data['ajuanData'] = $this->db->query("SELECT order_mitra.*, real_sm.*, rab.nama, rab.satuan, mitra.nama AS namaMitra FROM order_mitra JOIN real_sm ON order_mitra.kode=real_sm.kode JOIN rab ON order_mitra.kode=rab.kode JOIN mitra ON order_mitra.id_mitra=mitra.id_mitra WHERE order_mitra.kode_pengajuan = '$kodePj' ORDER BY order_mitra.id_mitra ");

        $data['lembaga'] = $this->model->getBy2('lembaga', 'tahun', $this->tahun, 'kode', $data['ajuanData']->row('lembaga'));
        $data['kasir'] = $this->user;

        $this->load->view('kasir/cetakNotaKPA', $data);
    }

    public function outRutin()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->db->query("SELECT pengeluaran_rutin.*, lembaga.nama AS nmLembaga, bidang.nama AS nmBidang FROM lembaga JOIN pengeluaran_rutin ON pengeluaran_rutin.lembaga=lembaga.kode JOIN bidang ON pengeluaran_rutin.lembaga=bidang.kode WHERE pengeluaran_rutin.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' AND bidang.tahun = '$this->tahun' ORDER BY pengeluaran_rutin.tanggal DESC ")->result();

        $data['sumData'] = $this->model->getBySum('pengeluaran_rutin', 'tahun', $data['tahun'], 'nominal')->row();

        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $data['tahun'])->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $data['tahun'])->result();


        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/outRutin', $data);
        $this->load->view('kasir/foot');
    }

    public function saveOutRutin()
    {
        $data = [
            "id_pengeluaran_rutin" => $this->uuid->v4(),
            "langganan" => $this->input->post('langganan', true),
            "lembaga" => $this->input->post('lembaga', true),
            "bidang" => $this->input->post('bidang', true),
            "ket" => $this->input->post('ket', true),
            "nominal" => rmRp($this->input->post('nominal', true)),
            "tanggal" => $this->input->post('tanggal', true),
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "at" => date('Y-m-d H:i:s')
        ];

        $this->model->input('pengeluaran_rutin', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/outRutin');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/outRutin');
        }
    }

    public function delOutRutin($id)
    {
        $this->model->delete('pengeluaran_rutin', 'id_pengeluaran_rutin', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/outRutin');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/outRutin');
        }
    }

    public function outHarian()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->db->query("SELECT pengeluaran_harian.*, lembaga.nama AS nmLembaga, bidang.nama AS nmBidang FROM lembaga JOIN pengeluaran_harian ON pengeluaran_harian.lembaga=lembaga.kode JOIN bidang ON pengeluaran_harian.lembaga=bidang.kode WHERE pengeluaran_harian.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' AND bidang.tahun = '$this->tahun' ")->result();

        $data['sumData'] = $this->model->getBySum('pengeluaran_harian', 'tahun', $data['tahun'], 'nominal')->row();

        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $data['tahun'])->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $data['tahun'])->result();
        $data['pagu'] = $this->model->getBy('pagu', 'tahun', $data['tahun'])->result();


        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/outHarian', $data);
        $this->load->view('kasir/foot');
    }

    public function saveOutHarian()
    {
        $data = [
            "id_harian" => $this->uuid->v4(),
            "lembaga" => $this->input->post('lembaga', true),
            "bidang" => $this->input->post('bidang', true),
            "pagu" => $this->input->post('pagu', true),
            "jenis" => $this->input->post('jenis', true),
            "nominal" => rmRp($this->input->post('nominal', true)),
            "tanggal" => $this->input->post('tanggal', true),
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "at" => date('Y-m-d H:i:s')
        ];

        $this->model->input('pengeluaran_harian', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/outHarian');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/outHarian');
        }
    }

    public function delOutHarian($id)
    {
        $this->model->delete('pengeluaran_harian', 'id_harian', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/outHarian');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/outHarian');
        }
    }

    public function sarpras()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->model->getBy2('sarpras', 'tahun', $this->tahun, 'status', 'disetujui')->result();

        $data['bulan'] = $this->bulan;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/sarpras', $data);
        $this->load->view('kasir/foot');
    }

    function sarprasDetail($kode)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
        $data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

        $data['data'] = $this->db->query("SELECT sarpras_detail.*, lembaga.nama FROM sarpras_detail JOIN lembaga ON sarpras_detail.lembaga=lembaga.kode WHERE kode_pengajuan = '$kode' AND lembaga.tahun = '$this->tahun' AND sarpras_detail.tahun = '$this->tahun' ")->result();

        $data['dataSum'] = $this->db->query("SELECT SUM(qty * harga_satuan) AS jml FROM sarpras_detail WHERE kode_pengajuan = '$kode' ")->row();

        $data['pj'] = $this->db->query("SELECT * FROM sarpras WHERE kode_pengajuan = '$kode'")->row();

        $data['lembagaData'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/sarprasInput', $data);
        $this->load->view('kasir/foot');
    }

    function cairSarpras()
    {
        $kd_pnj = $this->input->post('kode_pengajuan', true);
        $penerima = $this->input->post('penerima', true);
        $tgl_cair = $this->input->post('tgl_cair', true);
        $total = $this->input->post('total', true);
        $dataPj = $this->model->getBy('sarpras', 'kode_pengajuan', $kd_pnj)->row();
        $dataSum = $this->db->query("SELECT SUM(qty * harga_satuan) AS jml FROM sarpras_detail WHERE kode_pengajuan = '$kd_pnj' ")->row();

        $psn = '*INFORMASI PENCAIRAN PENGAJUAN SARPRAS*

pengajuan dari :
    
Lembaga : Biro Umum - Sarpras
Kode Pengajuan : ' . $kd_pnj . '
Nominal : _*' . rupiah($dataSum->jml) . '*_
Penerima : ' . $penerima . '
*telah dicairkan pada ' . $tgl_cair . '*

*_dimohon kepada KPA untuk menyelesaikan SPJ untuk melakukan pengajuan berikutnya_*

Terimakasih';

        $data = ['status' => 'dicairkan'];

        $this->model->update('sarpras', $data, 'kode_pengajuan', $kd_pnj);

        if ($this->db->affected_rows() > 0) {
            kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
            kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
            // kirim_person($this->apiKey, '085235583647', $psn);
            kirim_person($this->apiKey, '085236924510', $psn);

            $this->session->set_flashdata('ok', 'Pencairan Pengajuan berhasil');
            redirect('kasir/sarpras');
        } else {
            $this->session->set_flashdata('error', 'Pencairan Pengajuan tidak bisa');
            redirect('kasir/sarpras');
        }
    }

    public function inHarian()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->db->query("SELECT pemasukan_harian.*, lembaga.nama AS nmLembaga, bidang.nama AS nmBidang FROM lembaga JOIN pemasukan_harian ON pemasukan_harian.lembaga=lembaga.kode JOIN bidang ON pemasukan_harian.lembaga=bidang.kode WHERE pemasukan_harian.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' AND bidang.tahun = '$this->tahun' ")->result();

        $data['sumData'] = $this->model->getBySum('pemasukan_harian', 'tahun', $data['tahun'], 'nominal')->row();

        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $data['tahun'])->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $data['tahun'])->result();
        $data['pagu'] = $this->model->getBy('pagu', 'tahun', $data['tahun'])->result();


        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/inHarian', $data);
        $this->load->view('kasir/foot');
    }

    public function saveInHarian()
    {
        $data = [
            "id_masukan" => $this->uuid->v4(),
            "lembaga" => $this->input->post('lembaga', true),
            "bidang" => $this->input->post('bidang', true),
            "jenis" => $this->input->post('jenis', true),
            "nominal" => rmRp($this->input->post('nominal', true)),
            "tanggal" => $this->input->post('tanggal', true),
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "at" => date('Y-m-d H:i:s')
        ];

        $this->model->input('pemasukan_harian', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/inHarian');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/inHarian');
        }
    }

    public function delInHarian($id)
    {
        $this->model->delete('pemasukan_harian', 'id_masukan', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/inHarian');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/inHarian');
        }
    }

    public function rekapTabungan()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->db->query("SELECT rekap_tabungan.*, tb_santri.nama AS nmSantri FROM tb_santri JOIN rekap_tabungan ON rekap_tabungan.nis=tb_santri.nis WHERE rekap_tabungan.tahun = '$this->tahun' AND tb_santri.aktif = 'Y' ORDER BY rekap_tabungan.at DESC  ")->result();

        $data['sumData'] = $this->model->getBySum('rekap_tabungan', 'tahun', $data['tahun'], 'jumlah')->row();

        $data['santri'] = $this->model->getBy('tb_santri', 'aktif', 'Y')->result();


        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/tabungan', $data);
        $this->load->view('kasir/foot');
    }

    public function saveTabungan()
    {
        $data = [
            "id_tabungan" => $this->uuid->v4(),
            "nis" => $this->input->post('nis', true),
            "jumlah" => rmRp($this->input->post('jumlah', true)),
            "tanggal" => $this->input->post('tanggal', true),
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "at" => date('Y-m-d H:i:s')
        ];

        $this->model->input('rekap_tabungan', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/rekapTabungan');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/rekapTabungan');
        }
    }

    public function delTabungan($id)
    {
        $this->model->delete('rekap_tabungan', 'id_tabungan', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/rekapTabungan');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/rekapTabungan');
        }
    }

    public function pajak()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->model->getBy('pajak', 'tahun', $this->tahun)->result();

        $data['sumData'] = $this->model->getBySum('pajak', 'tahun', $data['tahun'], 'nominal')->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/pajak', $data);
        $this->load->view('kasir/foot');
    }

    function savePajak()
    {
        $data = [
            'id_pajak' => $this->uuid->v4(),
            'jenis' => $this->input->post('jenis', true),
            "ket" => $this->input->post('ket', true),
            "nominal" => rmRp($this->input->post('nominal', true)),
            "tanggal" => $this->input->post('tanggal', true),
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "at" => date('Y-m-d H:i:s')
        ];

        $this->model->input('pajak', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/pajak');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/pajak');
        }
    }

    public function delPajak($id)
    {
        $this->model->delete('pajak', 'id_pajak', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/pajak');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/pajak');
        }
    }

    public function changeAkses()
    {
        $id = $this->input->post('id', true);
        $lembaga = $this->input->post('lembaga', true);
        $level = $this->input->post('level', true);

        $cek = $this->model->getBy('user', 'id_user', $id)->row();
        if ($cek->level != 'admin') {
            redirect('login/logout');
        } else {
            if ($level === 'lembaga') {
                $this->model->update('user', ['lembaga' => $lembaga], 'id_user', $id);
                if ($this->db->affected_rows() > 0) {
                    redirect('lembaga');
                }
            } else {
                redirect($level);
            }
        }
    }


    public function cekTanggungan()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['lmbFr'] = $this->model->getLembagaFr()->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/cekTgn', $data);
        $this->load->view('kasir/foot');
    }

    function getKelas()
    {
        $t_formal = $this->input->post('t_formal', true);

        echo "<option value=''>Pilih Kelas</option>";

        $kls = $this->model->getByDb4('kl_formal', 'lembaga', $t_formal)->result();

        foreach ($kls as $row) {
            echo "<option value='" . $row->nm_kelas . "'>" . $row->nm_kelas . "</option>";
        }
    }

    function cekKelas()
    {
        $t_formal = $this->input->post('t_formal', true);
        $k_formal = $this->input->post('k_formal', true);
        $tahun = $this->input->post('tahun', true);

        $data['tahun'] = $tahun;

        if ($k_formal != '') {

            $kls_pch = explode('-', $k_formal);
            $kls = $kls_pch[0];
            $jur = $kls_pch[1];
            $rmb = $kls_pch[2];
            $tingkat = $kls_pch[3];

            $data['dt1'] = $this->db->query("SELECT * FROM pembayaran a JOIN tb_santri b ON a.nis=b.nis WHERE b.k_formal = '$kls' AND b.t_formal = '$tingkat' AND b.r_formal = '$rmb' AND b.jurusan = '$jur' AND a.tahun = '$tahun' AND b.aktif = 'Y' GROUP BY a.nis ORDER BY b.nama")->result();

            $data['dt_null'] = $this->db->query("SELECT * FROM tb_santri WHERE k_formal = '$kls' AND t_formal = '$tingkat' AND r_formal = '$rmb' AND jurusan = '$jur' AND aktif = 'Y'  AND  NOT EXISTS (SELECT * FROM pembayaran WHERE tb_santri.nis = pembayaran.nis AND tahun = '$tahun') ")->result();
        } else {
            $data['dt1'] = $this->db->query("SELECT * FROM pembayaran a JOIN tb_santri b ON a.nis=b.nis WHERE b.t_formal = '$t_formal' AND a.tahun = '$tahun' AND b.aktif = 'Y' GROUP BY a.nis ORDER BY b.nama")->result();

            $data['dt_null'] = $this->db->query("SELECT * FROM tb_santri WHERE t_formal = '$t_formal' AND aktif = 'Y' AND  NOT EXISTS (SELECT * FROM pembayaran WHERE tb_santri.nis = pembayaran.nis AND tahun = '$tahun') ")->result();
        }
        $this->load->view('kasir/hasilCekKelas', $data);

        // var_dump($data['dt1']);
        // var_dump($jur);
    }

    public function sisa()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->model->getBy('real_sisasm', 'tahun', $this->tahun)->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/sisaReal', $data);
        $this->load->view('kasir/foot');
    }

    public function tarikSisa($id)
    {
        $sisa = $this->model->getBy('real_sisasm', 'id_sisa', $id)->row();
        $data = [
            'id_sisa' => $sisa->id_sisa,
            'kode_pengajuan' => $sisa->kode_pengajuan,
            'dana_cair' => $sisa->dana_cair,
            'dana_serap' => $sisa->dana_serap,
            'sisa' => $sisa->sisa,
            'tgl_setor' => date('Y-m-d'),
            'kasir' => $sisa->kasir,
            'tahun' => $sisa->tahun,
        ];

        $kode = $sisa->kode_pengajuan;
        $lmb = $this->db->query("SELECT pengajuan.*, lembaga.nama FROM pengajuan JOIN lembaga ON pengajuan.lembaga=lembaga.kode WHERE kode_pengajuan = '$kode'")->row();

        if (preg_match("/DISP./i", $kode)) {
            $rt = "*(DISPOSISI)*";
        } else {
            $rt = '';
        }

        $psn = '
*INFORMASI VERIFIKASI BERKAS SPJ* ' . $rt . '

Ada pelaporan SPJ dari :
    
Lembaga : ' . $lmb->nama . '
Kode Pengajuan : ' . $kode . '
Pada : ' . date('d-m-Y H:i:s') . '

*_Hard copy SPJ dan sisa belanja anggaran telah disetor kepada KASIR. Untuk pengajuan berikutnya sudah bisa dilakukan._*

Terimakasih
https://simkupaduka.ppdwk.com/';

        $data1 = ['stts' => '3'];
        $data2 = ['spj' => '3'];

        $this->model->input('real_sisa', $data);

        if ($this->db->affected_rows() > 0) {
            $this->model->delete('real_sisasm', 'id_sisa', $id);
            $this->model->update('spj', $data1, 'kode_pengajuan', $kode);
            $this->model->update('pengajuan', $data2, 'kode_pengajuan', $kode);

            kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
            kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
            // kirim_person($this->apiKey, $hp, $psn);
            kirim_person($this->apiKey, '085236924510', $psn);
            $this->session->set_flashdata('ok', 'Update data sukses');
            redirect('kasir/sisa');
        } else {
            $this->session->set_flashdata('error', 'Update data gagal');
            redirect('kasir/sisa');
        }
    }

    public function rekom()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/rekom', $data);
        $this->load->view('kasir/foot');
    }

    public function loadSantri()
    {
        $data['santri'] = $this->db->query("SELECT * FROM tb_santri WHERE NOT EXISTS (SELECT * FROM rekom WHERE tb_santri.nis=rekom.nis AND rekom.tahun = '$this->tahun') AND aktif = 'Y' ORDER BY t_formal DESC, k_formal ASC, nama ASC ")->result();
        $this->load->view('kasir/loadSantri', $data);
    }
    public function loadRekom()
    {
        $data['data'] = $this->db->query("SELECT * FROM rekom JOIN tb_santri ON rekom.nis=tb_santri.nis WHERE aktif = 'Y' ORDER BY t_formal DESC, k_formal ASC, nama ASC ")->result();
        $this->load->view('kasir/loadRekom', $data);
    }

    public function addRekom()
    {
        $data = array(
            'nis' => $this->input->post('nis'),
            'ket' => 'maulid',
            'tahun' => $this->tahun,
        );

        $result = $this->model->input('rekom', $data);

        if ($result) {
            echo "Data berhasil ditambahkan.";
        } else {
            echo "Terjadi kesalahan saat menambahkan data.";
        }
    }
    public function delRekom()
    {
        $nis = $this->input->post('nis');

        $result = $this->model->delete('rekom', 'nis', $nis);

        if ($result) {
            echo "Data berhasil dihapus.";
        } else {
            echo "Terjadi kesalahan saat menghapus data.";
        }
    }
}
