@extends('admin.layout')

@section('title', 'Detail Pengiriman')

@section('content')
<h1 class="text-xl font-bold mb-4">Detail Pengiriman</h1>

<table class="w-full border">
    <tr class="bg-gray-100">
        <th class="p-2">Pelanggan</th>
        <th class="p-2">Jumlah</th>
        <th class="p-2">Bukti</th>
    </tr>

    @foreach($pengiriman as $p)
    <tr>
        <td class="p-2">{{ $p->pesanan->pelanggan->nama }}</td>
        <td class="p-2 text-center">{{ $p->jumlah_terkirim }}</td>
        <td class="p-2 text-center">
            @if($p->bukti_foto)
                <img src="{{ asset('storage/' . $p->bukti_foto) }}" width="100">
            @endif
        </td>
    </tr>
    @endforeach
</table>
@endsection