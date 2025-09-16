<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LembagaModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->flat = $this->load->database('flat', true);
    }

    function apikey()
    {
        $this->db->select('*');
        $this->db->from('api');
        $this->db->where('nama', 'Bendahara');
        return $this->db->get();
    }

    public function get($where = 0)
    {
        if ($where)
            $this->db->where($where);
        $query = $this->db->get('rab_sm24');
        return $query->row();
    }


    function input($tbl, $data)
    {
        $this->db->insert($tbl, $data);
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

    public function update2($table, $data, $where1, $dtwhere1, $where2, $dtwhere2)
    {
        $this->db->where($where1, $dtwhere1);
        $this->db->where($where2, $dtwhere2);
        $this->db->update($table, $data);
    }

    public function delete($table, $where, $dtwhere)
    {
        $this->db->where($where, $dtwhere);
        $this->db->delete($table);
    }
    public function delete2($table, $where, $dtwhere, $where2, $dtwhere2)
    {
        $this->db->where($where, $dtwhere);
        $this->db->delete($table);
    }

    function getBy($table, $where, $dtwhere)
    {
        $this->db->where($where, $dtwhere);
        return $this->db->get($table);
    }

    function getByOrd($table, $where, $dtwhere, $ord)
    {
        $this->db->where($where, $dtwhere);
        $this->db->order_by($ord, 'ASC');
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

    function getBySum3($table, $where1, $dtwhere1, $where2, $dtwhere2, $where3, $dtwhere3, $sum)
    {
        // $this->db->select('*');
        $this->db->select_sum($sum, 'jml3');
        $this->db->where($where1, $dtwhere1);
        $this->db->where($where2, $dtwhere2);
        $this->db->where($where3, $dtwhere3);
        return $this->db->get($table);
    }

    public function getPjn($table, $lembaga, $tahun)
    {
        $this->db->from($table);
        $this->db->where('lembaga', $lembaga);
        $this->db->where('tahun', $tahun);
        $this->db->order_by('no_urut', 'DESC');
        $this->db->limit('1');
        return $this->db->get();
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

    function getBy2($table, $where1, $dtwhere1, $where2, $dtwhere2)
    {
        $this->db->where($where1, $dtwhere1);
        $this->db->where($where2, $dtwhere2);
        return $this->db->get($table);
    }

    function getBySum($table, $where, $dtwhere, $sum)
    {
        $this->db->select('*');
        $this->db->select_sum($sum, 'jml');
        $this->db->where($where, $dtwhere);
        return $this->db->get($table);
    }

    public function getPengajuan($lembaga, $tahun)
    {
        $this->db->where('lembaga', $lembaga);
        $this->db->where('tahun', $tahun);
        $this->db->not_like('kode_pengajuan', 'DISP.');
        $this->db->order_by('no_urut', 'DESC');
        return $this->db->get('pengajuan');
    }
    public function getPengajuan2($lembaga, $tahun)
    {
        $this->db->where('lembaga', $lembaga);
        $this->db->where('tahun', $tahun);
        $this->db->like('kode_pengajuan', 'DISP.');
        $this->db->order_by('no_urut', 'DESC');
        return $this->db->get('pengajuan');
    }

    public function getSpj($lembaga, $tahun)
    {
        $this->db->select('spj.*,  pengajuan.cair as b_cair, pengajuan.kode_pengajuan as b_kode');
        $this->db->from('spj');
        $this->db->join('pengajuan', 'ON  spj.kode_pengajuan=pengajuan.kode_pengajuan');
        $this->db->where('spj.lembaga', $lembaga);
        $this->db->where('spj.tahun', $tahun);
        return $this->db->get();
    }

    public function add_batch($data)
    {
        return $this->db->insert_batch('rab_sm24', $data);
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

    function getBy2JoinSum($tbl1, $tbl2,  $where1, $dtwhere1, $where2, $dtwhere2, $on, $sum)
    {
        $this->db->select_sum($sum, 'jml');
        $this->db->from($tbl1);
        $this->db->join($tbl2, 'ON ' . $tbl1 . '.' . $on . '=' . $tbl2 . '.' . $on);
        $this->db->where($where1, $dtwhere1);
        $this->db->where($where2, $dtwhere2);
        return $this->db->get();
    }

    function flat_getBy($table, $where, $dtwhere)
    {
        $this->flat->where($where, $dtwhere);
        return $this->flat->get($table);
    }
    function flat_getBy2($table, $where, $dtwhere, $where2, $dtwhere2)
    {
        $this->flat->where($where, $dtwhere);
        $this->flat->where($where2, $dtwhere2);
        return $this->flat->get($table);
    }
    function flat_getBy3($table, $where, $dtwhere, $where2, $dtwhere2, $where3, $dtwhere3)
    {
        $this->flat->where($where, $dtwhere);
        $this->flat->where($where2, $dtwhere2);
        $this->flat->where($where3, $dtwhere3);
        return $this->flat->get($table);
    }
    function flat_getBy2Ord($table, $where, $dtwhere, $where2, $dtwhere2, $ord, $sort)
    {
        $this->flat->where($where, $dtwhere);
        $this->flat->where($where2, $dtwhere2);
        $this->flat->order_by($ord, $sort);
        return $this->flat->get($table);
    }
    function flat_getData($table)
    {
        return $this->flat->get($table);
    }
    function getHonor()
    {
        $this->flat->from('honor');
        $this->flat->group_by('honor_id');
        $this->flat->order_by('tahun', 'DESC');
        $this->flat->order_by('bulan', 'DESC');
        return $this->flat->get();
    }
    function getKehadiran()
    {
        $this->flat->from('kehadiran');
        $this->flat->group_by('kehadiran_id');
        $this->flat->order_by('tahun', 'DESC');
        $this->flat->order_by('bulan', 'DESC');
        return $this->flat->get();
    }
    function getPotongan()
    {
        $this->flat->from('potongan');
        $this->flat->group_by('potongan_id');
        $this->flat->order_by('tahun', 'DESC');
        $this->flat->order_by('bulan', 'DESC');
        return $this->flat->get();
    }
    function getHonorRinci($id, $lembaga)
    {
        $this->flat->select('honor.*, guru.nama, guru.santri');
        $this->flat->from('honor');
        $this->flat->join('guru', 'honor.guru_id=guru.guru_id');
        $this->flat->where('honor.lembaga', $lembaga);
        $this->flat->where('honor_id', $id);
        $this->flat->order_by('guru.nama', 'ASC');
        return $this->flat->get();
    }
    function getKehadiranRinci($id, $lembaga)
    {
        $this->flat->select('kehadiran.*, guru.nama, guru.santri');
        $this->flat->from('kehadiran');
        $this->flat->join('guru', 'kehadiran.guru_id=guru.guru_id');
        $this->flat->where('guru.satminkal', $lembaga);
        $this->flat->where('guru.kategori', 5);
        $this->flat->where('kehadiran_id', $id);
        $this->flat->order_by('guru.nama', 'ASC');
        return $this->flat->get();
    }
    function getPotonganRinci($id, $lembaga)
    {
        $this->flat->select('potongan.*, guru.nama, SUM(nominal) as total');
        $this->flat->from('potongan');
        $this->flat->join('guru', 'potongan.guru_id=guru.guru_id');
        $this->flat->where('guru.satminkal', $lembaga);
        $this->flat->where('potongan_id', $id);
        $this->flat->group_by('guru.guru_id', $id);
        $this->flat->order_by('guru.nama', 'ASC');
        return $this->flat->get();
    }
    public function flat_edit($table, $data, $where, $dtwhere)
    {
        $this->flat->where($where, $dtwhere);
        $this->flat->update($table, $data);
        return $this->flat->affected_rows();
    }
    function flat_input($tbl, $data)
    {
        $this->flat->insert($tbl, $data);
        return $this->flat->affected_rows();
    }
    public function flat_delete($table, $where, $dtwhere)
    {
        $this->flat->where($where, $dtwhere);
        $this->flat->delete($table);
        return $this->flat->affected_rows();
    }
    public function flat_delete2($table, $where, $dtwhere, $where2, $dtwhere2)
    {
        $this->flat->where($where, $dtwhere);
        $this->flat->where($where2, $dtwhere2);
        $this->flat->delete($table);
        return $this->flat->affected_rows();
    }

    public function flat_totoalPotongan($potonganID, $guruID)
    {
        $this->flat->select('SUM(nominal) as total');
        $this->flat->where('potongan_id', $potonganID);
        $this->flat->where('guru_id', $guruID);
        $this->flat->from('potongan');
        return $this->flat->get();
    }

    public function allPtty($lembaga)
    {
        $this->flat->select('guru_id, guru.nama AS nama, satminkal.nama AS lembaga');
        $this->flat->from('guru');
        $this->flat->join('satminkal', 'guru.satminkal=satminkal.id');
        // $this->flat->where('satminkal !=', $lembaga);
        $this->flat->where('sik', 'PTTY');
        $this->flat->order_by('guru.nama', 'ASC');
        return $this->flat->get();
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
    function getByJoin3($table1, $table2, $on1, $on2, $where1, $where2, $dtwhere1, $dtwhere2)
    {
        $this->db->from($table1);
        $this->db->join($table2, 'ON ' . $table1 . '.' . $on1 . '=' . $table2 . '.' . $on2);
        $this->db->where($where1, $dtwhere1);
        $this->db->where($where2, $dtwhere2);
        return $this->db->get();
    }
    public function getSPJAll($tahun)
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
}
