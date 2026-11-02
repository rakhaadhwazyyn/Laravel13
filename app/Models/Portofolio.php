<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portofolio extends Model
{
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $table = 'Portofolio_1164';
    protected $primaryKey = 'id_portofolio';

    protected $fillable = [
        'nama_lengkap', 'jabatan', 'email', 'telepon',
        'alamat', 'ringkasan', 'foto', 'tampilkan_foto',
    ];

    protected $casts = ['tampilkan_foto' => 'boolean'];

    public function pengalaman(): HasMany
    {
        return $this->hasMany(PengalamanKerja::class, 'id_portofolio', 'id_portofolio');
    }

    public function pendidikan(): HasMany
    {
        return $this->hasMany(Pendidikan::class, 'id_portofolio', 'id_portofolio');
    }

    /** Alamat file foto di /public/foto-cv, atau null jika kosong / file tidak ada. */
    public function getUrlFotoAttribute(): ?string
    {
        if (! $this->foto || ! file_exists(public_path('foto-cv/'.$this->foto))) {
            return null;
        }
        return asset('foto-cv/'.$this->foto);
    }
}
