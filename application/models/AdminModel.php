<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdminModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'tangg';

        $this->db2 = $this->load->database('dekos', true);
        $this->db5 = $this->load->database('nikmus', true);
        $this->db6 = $this->load->database('psb24', true);
    }

    public function add($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function getAll($table)
    {
        return $this->db->get($table);
    }


    public function update($table, $data, $where, $dtwhere)
    {
        $this->db->where($where, $dtwhere);
        $this->db->update($table, $data);
    }

    public function update2($table, $data, $where, $dtwhere, $where2, $dtwhere2)
    {
        $this->db->where($where, $dtwhere);
        $this->db->where($where2, $dtwhere2);
        $this->db->update($table, $data);
    }

    public function delete($table, $where, $dtwhere)
    {
        $this->db->where($where, $dtwhere);
        $this->db->delete($table);
    }

    public function get($where = 0)
    {
        if ($where)
            $this->db->where($where);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function get_all($where = 0, $order_by_column = 0, $order_by = 0)
    {
        if ($where)
            $this->db->where($where);
        if ($order_by_column and $order_by)
            $this->db->order_by($order_by_column, $order_by);
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function get_num_rows($where = 0)
    {
        if ($where)
            $this->db->where($where);
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function add_batch($data)
    {
        return $this->db->insert_batch('tangg', $data);
    }

    function apikey()
    {
        $this->db->select('*');
        $this->db->from('api');
        $this->db->where('nama', 'Bendahara');
        return $this->db->get();
    }

    public function total()
    {
        $this->db->from('tb_santri');
        return $this->db->get();
    }
    public function totalPa()
    {
        $this->db->from('tb_santri');
        $this->db->where('jkl', 'Laki-laki');
        return $this->db->get();
    }
    public function totalPi()
    {
        $this->db->from('tb_santri');
        $this->db->where('jkl', 'Perempuan');
        return $this->db->get();
    }

    public function jmlLem($lm, $jkl)
    {
        $this->db->from('tb_santri');
        $this->db->where('jkl', $jkl);
        $this->db->where('lembaga', $lm);
        return $this->db->get();
    }

    public function lama()
    {
        $this->db->from('tb_lama');
        $this->db->where('NOT EXISTS (SELECT * FROM tb_santri WHERE tb_lama.nis=tb_santri.nis)', '', false);
        $this->db->where('k_formal', 'IX');
        return $this->db->get();
    }

    function getBy($table, $where, $dtwhere)
    {
        $this->db->where($where, $dtwhere);
        return $this->db->get($table);
    }

    function getBySum($table, $where, $dtwhere, $sum)
    {
        $this->db->select('*');
        $this->db->select_sum($sum, 'jml');
        $this->db->where($where, $dtwhere);
        return $this->db->get($table);
    }
    function getBySum2($table, $where1, $dtwhere1, $where2, $dtwhere2, $sum)
    {
        $this->db->select('*');
        $this->db->select_sum($sum, 'jml');
        $this->db->where($where1, $dtwhere1);
        $this->db->where($where2, $dtwhere2);
        return $this->db->get($table);
    }

    function getBy2($table, $where1, $dtwhere1, $where2, $dtwhere2)
    {
        $this->db->where($where1, $dtwhere1);
        $this->db->where($where2, $dtwhere2);
        return $this->db->get($table);
    }
    function getBy3($table, $where1, $dtwhere1, $where2, $dtwhere2, $where3, $dtwhere3)
    {
        $this->db->where($where1, $dtwhere1);
        $this->db->where($where2, $dtwhere2);
        $this->db->where($where3, $dtwhere3);
        return $this->db->get($table);
    }

    function getByJoin($table1, $table2, $on, $where, $dtwhere)
    {
        $this->db->from($table1);
        $this->db->join($table2, 'ON ' . $table1 . '.' . $on . '=' . $table2 . '.' . $on);
        $this->db->where($table1 . '.' . $where, $dtwhere);
        return $this->db->get();
    }
    function getByJoin2($table1, $table2, $on1, $on2, $where, $dtwhere)
    {
        $this->db->from($table1);
        $this->db->join($table2, 'ON ' . $table1 . '.' . $on1 . '=' . $table2 . '.' . $on2);
        $this->db->where($table1 . '.' . $where, $dtwhere);
        $this->db->where($table2 . '.' . $where, $dtwhere);
        return $this->db->get();
    }

    function getByJoin3($table1, $table2, $on1, $on2, $where1, $where2, $dtwhere1, $dtwhere2)
    {
        $this->db->from($table1);
        $this->db->join($table2, 'ON ' . $table1 . '.' . $on1 . '=' . $table2 . '.' . $on2);
        $this->db->where($where1, $dtwhere1);
        $this->db->where($where2, $dtwhere2);
        return $this->db->get();
    }

    function input($tbl, $data)
    {
        $this->db->insert($tbl, $data);
    }

    public function dataSantri()
    {
        $this->db->where('aktif', 'Y');
        return $this->db->get('tb_santri');
    }
    public function dataBp($tahun)
    {
        $this->db->from('tangg');
        $this->db->join('tb_santri', 'ON tangg.nis=tb_santri.nis');
        $this->db->where('tangg.tahun', $tahun);
        return $this->db->get();
    }

    public function selectMax($table, $max)
    {
        $this->db->select_max($max);
        return $this->db->get($table);
    }
    public function selectSum($table, $sum, $where, $dtwhere)
    {
        $this->db->select_sum($sum);
        $this->db->where($where, $dtwhere);
        return $this->db->get($table);
    }

    public function getTotalRabJenis($jenis, $lm, $tahun)
    {
        $this->db->select_sum('total');
        $this->db->where('jenis', $jenis);
        $this->db->where('lembaga', $lm);
        $this->db->where('tahun', $tahun);
        return $this->db->get('rab');
    }

    public function getTotalRealJenis($jenis, $lm, $tahun)
    {
        $this->db->select_sum('nominal');
        $this->db->where('jenis', $jenis);
        $this->db->where('lembaga', $lm);
        $this->db->where('tahun', $tahun);
        $this->db->not_like('kode_pengajuan', 'DISP.');
        return $this->db->get('realis');
    }

    public function getTotalRab($lm, $tahun)
    {
        $this->db->select_sum('total');
        $this->db->where('lembaga', $lm);
        $this->db->where('tahun', $tahun);
        return $this->db->get('rab');
    }

    public function rabPak($kode)
    {
        $this->db->select('pak_detail.*, rab.nama AS nm');
        $this->db->from('pak_detail');
        $this->db->join('rab', 'ON pak_detail.kode_rab=rab.kode');
        $this->db->where('kode_pak', $kode);
        return $this->db->get();
    }

    public function getPengajuan($tahun)
    {
        $this->db->from('pengajuan');
        $this->db->join('lembaga', 'ON pengajuan.lembaga=lembaga.kode');
        $this->db->where('pengajuan.spj !=', '2');
        $this->db->where('pengajuan.tahun', $tahun);
        $this->db->where('lembaga.tahun', $tahun);
        $this->db->not_like('pengajuan.kode_pengajuan', 'DISP.');
        $this->db->order_by('pengajuan.at', 'DESC');
        return $this->db->get();
    }

    public function getPengajuanAll($tahun)
    {
        $this->db->from('pengajuan');
        $this->db->join('lembaga', 'ON pengajuan.lembaga=lembaga.kode');
        $this->db->where('pengajuan.tahun', $tahun);
        $this->db->where('lembaga.tahun', $tahun);
        $this->db->order_by('pengajuan.at', 'DESC');
        return $this->db->get();
    }

    public function getSPJ($tahun)
    {
        $this->db->select('spj.*, lembaga.nama, lembaga.hp, pengajuan.cair');
        $this->db->from('spj');
        $this->db->join('lembaga', 'ON spj.lembaga=lembaga.kode');
        $this->db->join('pengajuan', 'ON spj.kode_pengajuan=pengajuan.kode_pengajuan');
        $this->db->where('spj.file_spj !=', '');
        $this->db->where('spj.tahun', $tahun);
        $this->db->where('pengajuan.tahun', $tahun);
        $this->db->where('lembaga.tahun', $tahun);
        $this->db->order_by('spj.tgl_upload', 'DESC');
        return $this->db->get();
    }

    public function getSPJSarpras($tahun)
    {
        $this->db->select('spj.*, lembaga.nama, lembaga.hp, pengajuan.cair');
        $this->db->from('spj');
        $this->db->join('lembaga', 'ON spj.lembaga=lembaga.kode');
        $this->db->join('pengajuan', 'ON spj.kode_pengajuan=pengajuan.kode_pengajuan');
        $this->db->where('spj.file_spj !=', '');
        $this->db->where('spj.tahun', $tahun);
        $this->db->where('pengajuan.tahun', $tahun);
        $this->db->where('lembaga.tahun', $tahun);
        $this->db->where('spj.kode_pengajuan', 'LIKE %SRPS%');
        $this->db->order_by('spj.tgl_upload', 'DESC');
        return $this->db->get();
    }

    public function getDispo($tahun)
    {
        $this->db->select('pengajuan.*, lembaga.nama');
        $this->db->from('pengajuan');
        $this->db->join('lembaga', 'ON pengajuan.lembaga=lembaga.kode');
        $this->db->where('pengajuan.tahun', $tahun);
        $this->db->where('lembaga.tahun', $tahun);
        $this->db->like('pengajuan.kode_pengajuan', 'DISP.');
        return $this->db->get();
    }

    public function dispPakai($tahun)
    {
        $this->db->select_sum('nom_cair', 'total');
        $this->db->from('realis');
        $this->db->like('kode_pengajuan', 'DISP.');
        $this->db->where('tahun', $tahun);
        return $this->db->get();
    }

    function getUser($dtwhere2)
    {
        $this->db->select('user.*, lembaga.nama AS nm_lm, lembaga.hp_kep, lembaga.hp');
        $this->db->from('user');
        $this->db->join('lembaga', 'ON user.lembaga=lembaga.kode');
        $this->db->where('lembaga.tahun', $dtwhere2);
        return $this->db->get();
    }

    public function getDekosSum($tahun)
    {
        $this->db2->select_sum('nominal', 'nominal');
        $this->db2->where('tahun', $tahun);
        $this->db2->from('setor');
        return $this->db2->get();
    }

    public function getSetor($tahun)
    {
        $this->db2->where('tahun', $tahun);
        $this->db2->from('setor');
        $this->db2->order_by('tgl', 'DESC');
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

    public function getSisaOrder($tahun)
    {
        $this->db->where('real_sisa.tahun', $tahun);
        $this->db->where('lembaga.tahun', $tahun);
        $this->db->from('real_sisa');
        $this->db->join('pengajuan', 'ON real_sisa.kode_pengajuan=pengajuan.kode_pengajuan');
        $this->db->join('lembaga', 'ON pengajuan.lembaga=lembaga.kode');
        $this->db->order_by('real_sisa.tgl_setor', 'DESC');
        return $this->db->get();
    }

    public function getRabByDppk($lembaga, $tahun)
    {
        $this->db->select('*');
        $this->db->from('rab_sm24');
        $this->db->where('lembaga', $lembaga);
        $this->db->where('tahun', $tahun);
        $this->db->group_by('kode_pak');
        return $this->db->get();
    }

    function getBySum3($table, $where1, $dtwhere1, $where2, $dtwhere2, $where3, $dtwhere3, $sum)
    {
        // $this->db->select('*');
        $this->db->select_sum($sum, 'jml3');
        $this->db->where($where1, $dtwhere1);
        $this->db->where($where2, $dtwhere2);
        $this->db->where($where3, $dtwhere3);
        return $this->db->get($table);
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
        return $this->db6->query("SELECT SUM(qty * harga_satuan) as jml FROM pengajuan JOIN pengajuan_detail ON pengajuan.kode_pengajuan=pengajuan_detail.kode_pengajuan WHERE status = 'dicairkan' OR status = 'selesai' ");
    }

    function pemsukanPsb()
    {
        $daftar = $this->db6->query("SELECT SUM(nominal) as jml FROM bp_daftar")->row();
        $regist = $this->db6->query("SELECT SUM(nominal) as jml FROM regist")->row();

        return $daftar->jml + $regist->jml;
    }

    function pengeluaranPsb()
    {
        $daftar = $this->db6->query("SELECT SUM(qty*harga_satuan) as jml FROM pengajuan_detail JOIN pengajuan ON pengajuan.kode_pengajuan=pengajuan_detail.kode_pengajuan WHERE status = 'dicairkan' OR status = 'selesai' ")->row();

        return $daftar->jml;
    }

    function dataPengeluaranPsb()
    {
        return $this->db6->query("SELECT *, SUM(harga_satuan*qty) as total, jabatan.nama as nmbidang FROM pengajuan JOIN pengajuan_detail  ON pengajuan.kode_pengajuan=pengajuan_detail.kode_pengajuan JOIN jabatan ON pengajuan.bidang=jabatan.kode GROUP BY pengajuan.kode_pengajuan");
    }
}
