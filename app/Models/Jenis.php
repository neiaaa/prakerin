<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RealRashid\SweetAlert\Facades\Alert;

class Jenis extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jenis',
    ];

    public $timestamps = true;

    public function barang()
    {
        return $this->hasMany('App\Models\Barang', 'id_jenis');
    }

    public static function code()
    {
        $kode = DB::table('jenis')->max('kode');
        $addNol = '';
        $kode = str_replace("JNS", "", $kode);
        $kode = (int) $kode + 1;
        $incrementKode = $kode;

        if (strlen($kode) == 1) {
            $addNol = "000";
        } elseif (strlen($kode) == 2) {
            $addNol = "00";
        } elseif (strlen($kode == 3)) {
            $addNol = "0";
        }

        $kodeBaru = "JNS" . $addNol . $incrementKode;
        return $kodeBaru;
    }
    public static function boot()
    {
        parent::boot();
        self::deleting(function($barangMasuk) {
          if ($barangMasuk->barang->count() > 0) {
            Alert::error('Gagal menghapus data '. $barang->nama_jenis. ' karena masih memiliki data barang');
            return false;
          }
        });
    }
}
