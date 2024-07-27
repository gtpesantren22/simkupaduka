<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AppModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'tangg';

        $this->db2 = $this->load->database('dekos', true);
        $this->db5 = $this->load->database('nikmus', true);
        $this->db6 = $this->load->database('psb24', true);
    }

    function getBySum($table, $where, $dtwhere, $sum)
    {
        $this->db->select_sum($sum, 'jml');
        $this->db->where($where, $dtwhere);
        return $this->db->get($table);
    }

    function getBySum2($table, $where, $dtwhere, $where1, $dtwhere1, $sum)
    {
        $this->db->select_sum($sum, 'jml');
        $this->db->where($where, $dtwhere);
        $this->db->where($where1, $dtwhere1);
        return $this->db->get($table);
    }

    public function getDekosSum($tahun)
    {
        $this->db2->select_sum('nominal', 'nominal');
        $this->db2->where('tahun', $tahun);
        $this->db2->from('setor');
        return $this->db2->get();
    }

    public function getNikmusSum($tahun)
    {
        $this->db5->select_sum('nom_kriteria', 'nom_kriteria');
        $this->db5->select_sum('transport', 'transport');
        $this->db5->select_sum('sopir', 'sopir');
        $this->db5->where('tahun', $tahun);
        $this->db5->from('pengajuan');
        return $this->db5->get();
    }

    function getBySumPsb($table, $where, $dtwhere, $sum)
    {
        $this->db6->select('*');
        $this->db6->select_sum($sum, 'jml');
        $this->db6->where($where, $dtwhere);
        return $this->db6->get($table);
    }

    function pengajuanPsb()
    {
        $penajuan = $this->db6->query("SELECT SUM(qty * harga_satuan) as jml FROM pengajuan JOIN pengajuan_detail ON pengajuan.kode_pengajuan=pengajuan_detail.kode_pengajuan WHERE status = 'dicairkan' OR status = 'selesai'")->row('jml');
        $keluar = $this->db6->query("SELECT SUM(nominal) as jml FROM keluar ")->row('jml');
        return $penajuan + $keluar;
    }

    function masuk($tahun)
    {
        // $realSisa = $this->getBySum('real_sisa', 'tahun', $tahun, 'sisa')->row();
        // $pesantren = $this->getBySum('pesantren', 'tahun', $tahun, 'nominal')->row();
        // $cadangan = $this->getBySum('cadangan', 'tahun', $tahun, 'nominal')->row();
        $pembayaran = $this->getBySum('pembayaran', 'tahun', $tahun, 'nominal')->row();
        $bos = $this->getBySum('bos', 'tahun', $tahun, 'nominal')->row();
        $talangan = $this->getBySum('talangan', 'tahun', $tahun, 'nominal')->row();
        $sumCicil = $this->getBySum('cicilan', 'tahun', $tahun, 'nominal')->row();

        $daftar = $this->getBySumPsb('bp_daftar', 'nominal <>', '', 'nominal')->row();
        $regist = $this->getBySumPsb('regist', 'nominal <>', '', 'nominal')->row();
        $pemasukanPSB = $this->getBySumPsb('masuk', 'nominal <>', '', 'nominal')->row();

        $masuk = $bos->jml + $pembayaran->jml +  $sumCicil->jml + $daftar->jml + $regist->jml + $talangan->jml + $pemasukanPSB->jml;
        // $masuk = $bos->jml + $pembayaran->jml + $pesantren->jml + $sumCicil->jml + $realSisa->jml + $cadangan->jml + $daftar->jml + $regist->jml + $talangan->jml;

        return $masuk;
    }

    function keluar($tahun)
    {
        $kebijakan = $this->getBySum('kebijakan', 'tahun', $tahun, 'nominal')->row();
        $realis = $this->getBySum('realis', 'tahun', $tahun, 'nom_serap')->row();
        $keluarLain = $this->getBySum('keluar', 'tahun', $tahun, 'nominal')->row();
        $dekos = $this->getDekosSum($tahun)->row();
        $nikmus = $this->getNikmusSum($tahun)->row();
        $sumPinjam = $this->getBySum('peminjaman', 'tahun', $tahun, 'nominal')->row();
        $panjar = $this->getBySum('panjar', 'tahun', $tahun, 'nominal')->row();
        $pengajuanPsb = $this->pengajuanPsb();

        $outRutin = $this->getBySum('pengeluaran_rutin', 'tahun', $tahun, 'nominal')->row();
        $sarpras = $this->db->query("SELECT SUM(qty*harga_satuan) as jml FROM sarpras_detail JOIN sarpras ON sarpras_detail.kode_pengajuan=sarpras.kode_pengajuan WHERE sarpras_detail.tahun = '$tahun' AND sarpras.status = 'dicairkan' ")->row();
        $haflah = $this->db->query("SELECT SUM(qty*harga_satuan) as jml FROM haflah_detail JOIN sarpras ON haflah_detail.kode_pengajuan=sarpras.kode_pengajuan WHERE haflah_detail.tahun = '$tahun' AND sarpras.status = 'dicairkan' ")->row();
        $keluarPsb = $this->getBySumPsb('keluar', 'nominal <>', '', 'nominal')->row();

        $keluar = $kebijakan->jml + $realis->jml + $dekos->nominal + $nikmus->nom_kriteria + $nikmus->transport + $nikmus->sopir + $keluarLain->jml + $sumPinjam->jml + $panjar->jml + $pengajuanPsb + $outRutin->jml + $sarpras->jml + $keluarPsb->jml;

        return $keluar;
    }

    function  cadangan($tahun)
    {
        $cadangan = $this->getBySum2('cadangan', 'tahun', $tahun, 'jenis', 'masuk', 'nominal')->row();
        $cadanganKeluar = $this->getBySum2('cadangan', 'tahun', $tahun, 'jenis', 'keluar', 'nominal')->row();
        $pesantren = $this->getBySum('pesantren', 'tahun', $tahun, 'nominal')->row();
        $realSisa = $this->getBySum('real_sisa', 'tahun', $tahun, 'sisa')->row();

        return ($pesantren->jml + $realSisa->jml + $cadangan->jml) - $cadanganKeluar->jml;
    }
}
