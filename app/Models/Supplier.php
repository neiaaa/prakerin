<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'no_telp',
        'email',
        'nama_perusahaan',
    ];

    public $timestamps = true;

    public function barangMasuk()
    {
        return $this->hasMany('App\Models\Barang_masuk', 'id_supplier');
    }

    public static function code()
    {
        $kode = DB::table('suppliers')->max('kode');
        $addNol = '';
        $kode = str_replace("SPR", "", $kode);
        $kode = (int) $kode + 1;
        $incrementKode = $kode;

        if (strlen($kode) == 1) {
            $addNol = "000";
        } elseif (strlen($kode) == 2) {
            $addNol = "00";
        } elseif (strlen($kode == 3)) {
            $addNol = "0";
        }

        $kodeBaru = "SPR" . $addNol . $incrementKode;
        return $kodeBaru;
    }
    public static function boot()
    {
        parent::boot();
        self::deleting(function ($supplier) {
            if ($supplier->barangMasuk->count() > 0) {
                $msg = 'Data tidak bisa dihapus karena masih ada barang : ';
                $msg .= '<ul>';
                foreach ($supplier->barangMasuk as $data) {
                    $msg .= "<li>$data->nama_barang</li>";
                }
                $msg .= '</ul>';
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => $msg,
                ]);
                return false;
            }
        });
    }
}
