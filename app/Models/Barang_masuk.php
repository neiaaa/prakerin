<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class barang_masuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang',
        'id_supplier',
        'jenis_barang',
        'jumlah_penerimaan',
        'tgl_masuk'
    ];
    public $timestamps = true;

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'id_supplier');
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($supplier) {
            if ($supplier->supplier->count() > 0) {
                $msg = 'Data tidak bisa dihapus karena masih ada barang : ';
                $msg .= '<ul>';
                foreach ($supplier->supplier as $data) {
                    $msg .= "<li>$data->nama_supplier</li>";
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
