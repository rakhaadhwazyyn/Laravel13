@php
    // foto: file ada di public/foto-cv ?
    $urlFoto = ($cv->foto && file_exists(public_path('foto-cv/'.$cv->foto)))
        ? asset('foto-cv/'.$cv->foto)
        : null;

    // keahlian: satu per baris (atau dipisah koma) -> array
    $daftarKeahlian = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', (string) $cv->keahlian))));
    $adaFoto = $cv->tampilkan_foto && $urlFoto;

    // ikon garis sederhana (inline SVG, tanpa library tambahan)
    $ikon = [
        'email'  => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
        'telp'   => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
        'alamat' => '<path d="M12 21s7-6.2 7-11a7 7 0 0 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/>',
    ];
    $kontak = array_filter([
        ['email',  $cv->email],
        ['telp',   $cv->telepon],
        ['alamat', $cv->alamat],
    ], fn ($k) => filled($k[1]));
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CV - {{ $cv->nama_lengkap }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@500;600;700&family=Mulish:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --hijau: #0aa75e; --hijau-tua: #08874b; --teks: #4b4b4b; }
        * { box-sizing: border-box; }
        html { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        body {
            margin: 0; font-family: 'Mulish', 'Segoe UI', Arial, sans-serif;
            color: var(--teks); background: #f5f5f5;
        }
        .latar { height: 180px; background: linear-gradient(120deg, #2b2f4a, #4a3f6b 55%, #7a5a6a); }
        .halaman { max-width: 900px; margin: -140px auto 40px; padding: 0 16px; }

        /* ---------- header hijau + foto ---------- */
        .header {
            position: relative; display: flex; min-height: 265px; overflow: hidden;
            background: #d9d9d9; box-shadow: 0 2px 10px rgba(0,0,0,.25);
        }
        .foto { position: absolute; inset: 0 0 0 auto; width: 48%; background-size: cover; background-position: center top; }
        .info {
            position: relative; z-index: 2; flex: 0 0 66%; background: var(--hijau); color: #fff;
            padding: 28px 30px 28px 30px; clip-path: polygon(0 0, 100% 0, 88% 100%, 0 100%);
            font-family: 'Raleway', 'Segoe UI', Arial, sans-serif;
        }
        .tanpa-foto .info { flex: 1 1 auto; clip-path: none; }
        .info h1 { margin: 0; font-size: 28px; letter-spacing: .5px; text-transform: uppercase; font-weight: 600; }
        .info .jabatan { margin: 2px 0 14px; font-size: 16px; font-weight: 700; }
        .kontak { list-style: none; margin: 0; padding: 0; font-size: 13.5px; font-weight: 600; }
        .kontak li { display: flex; align-items: center; gap: 10px; margin: 5px 0; word-break: break-word; }
        .kontak svg { flex: 0 0 18px; width: 18px; height: 18px; fill: none; stroke: #fff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .sosial { display: flex; gap: 10px; margin-top: 16px; }
        .sosial a {
            width: 28px; height: 28px; border-radius: 50%; color: #fff; text-decoration: none;
            display: flex; align-items: center; justify-content: center;
            font: 700 11px Arial, sans-serif; box-shadow: 0 2px 4px rgba(0,0,0,.35);
        }

        /* ---------- kartu ---------- */
        .kartu { background: #fff; margin-top: 36px; box-shadow: 0 1px 6px rgba(0,0,0,.18); }
        .kartu-isi { padding: 26px 32px; }
        .ringkasan { text-align: center; line-height: 1.6; font-size: 14px; margin: 0; white-space: pre-line; }
        .aksi { border-top: 1px solid #eee; padding: 22px; text-align: center; }
        .tombol {
            display: inline-block; padding: 9px 26px; margin: 0 8px; background: var(--hijau); color: #fff;
            border: 0; font: 700 13px 'Mulish', Arial, sans-serif; letter-spacing: .5px; text-transform: uppercase;
            text-decoration: none; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,.35);
        }
        .tombol:hover { background: var(--hijau-tua); }

        h2.judul {
            margin: 0 0 16px; padding-bottom: 8px; font: 700 16px 'Raleway', Arial, sans-serif;
            color: var(--hijau); text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid var(--hijau);
        }
        .item { margin-bottom: 16px; page-break-inside: avoid; }
        .item .atas { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .item .nama { font-weight: 700; font-size: 15px; color: #222; }
        .item .periode { font-size: 13px; color: var(--hijau-tua); font-weight: 700; }
        .item .sub { font-size: 13.5px; color: #666; }
        .item .desk { font-size: 13.5px; margin-top: 4px; line-height: 1.55; }

        .chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .chip { background: var(--hijau); color: #fff; padding: 6px 14px; border-radius: 16px; font-size: 13px; font-weight: 700; }

        @media (max-width: 680px) {
            .header { flex-direction: column; }
            .foto { position: relative; width: 100%; height: 240px; order: -1; }
            .info, .tanpa-foto .info { flex: 1 1 auto; clip-path: none; }
            .tombol { margin: 4px; }
        }

        /* ---------- cetak ---------- */
        @page { size: A4; margin: 10mm; }
        @media print {
            body { background: #fff; }
            .latar, .aksi { display: none !important; }
            .halaman { margin: 0 auto; max-width: none; padding: 0; }
            .header, .kartu { box-shadow: none; }
            .kartu { margin-top: 20px; border: 1px solid #eee; }
        }
    </style>
</head>

<body>
    <div class="latar"></div>

    <div class="halaman">
        {{-- ===== HEADER ===== --}}
        <div class="header {{ $adaFoto ? '' : 'tanpa-foto' }}">
            @if ($adaFoto)
                <div class="foto" style="background-image:url('{{ $urlFoto }}')"></div>
            @endif

            <div class="info">
                <h1>{{ $cv->nama_lengkap }}</h1>
                <div class="jabatan">{{ $cv->jabatan }}</div>

                <ul class="kontak">
                    @foreach ($kontak as [$tipe, $isi])
                        <li>
                            <svg viewBox="0 0 24 24">{!! $ikon[$tipe] !!}</svg>
                            <span>{{ $isi }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- ===== RINGKASAN + TOMBOL ===== --}}
        <div class="kartu">
            @if (filled($cv->ringkasan))
                <div class="kartu-isi">
                    <p class="ringkasan">{{ $cv->ringkasan }}</p>
                </div>
            @endif
            <div class="aksi">
                <button type="button" class="tombol" onclick="window.print()">Cetak / Unduh CV</button>
                <a href="mailto:{{ $cv->email }}" class="tombol">Hubungi Saya</a>
                <a href="{{ route('cv.index') }}" class="tombol" style="background:#6b7280">Kembali</a>
            </div>
        </div>

        {{-- ===== PENDIDIKAN ===== --}}
        @if (filled($cv->institusi))
            <div class="kartu">
                <div class="kartu-isi">
                    <h2 class="judul">Pendidikan</h2>
                    <div class="item">
                        <div class="atas">
                            <span class="nama">{{ $cv->institusi }}</span>
                            <span class="periode">{{ $cv->tahun_lulus }}</span>
                        </div>
                        @if (filled($cv->jurusan))
                            <div class="sub">{{ $cv->jurusan }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== KEAHLIAN ===== --}}
        @if (count($daftarKeahlian))
            <div class="kartu">
                <div class="kartu-isi">
                    <h2 class="judul">Keahlian</h2>
                    <div class="chips">
                        @foreach ($daftarKeahlian as $k)
                            <span class="chip">{{ $k }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>

</html>
