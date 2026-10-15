<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin IoT Simulator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-800 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Dasbor Simulator Sensor IoT</h1>

        <!-- Notifikasi jika berhasil update -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            @foreach($haltes as $halte)
            <div class="border p-5 rounded-lg bg-gray-50 shadow-sm">
                <h3 class="font-bold text-lg mb-1 text-blue-700">{{ $halte->nama_halte }}</h3>
                <p class="text-sm text-gray-600 mb-4">Kapasitas Maksimal Halte: <strong>{{ $halte->kapasitas_maksimal }}</strong></p>
                
                <!-- Formulir Input Simulasi Sensor -->
                <form action="{{ route('admin.update', $halte->id) }}" method="POST" class="flex flex-col gap-2">
                    @csrf
                    <label class="text-sm font-semibold">Kirim Data Jumlah Penumpang (Simulasi):</label>
                    <div class="flex gap-2">
                        <input type="number" name="jumlah_sekarang" value="{{ $halte->jumlah_sekarang }}" min="0" max="{{ $halte->kapasitas_maksimal }}" class="border-2 border-gray-300 p-2 rounded w-full focus:outline-none focus:border-blue-500" required>
                        <button type="submit" class="bg-blue-600 text-white font-bold px-4 py-2 rounded hover:bg-blue-700 transition">Kirim</button>
                    </div>
                </form>
            </div>
            @endforeach
        </div>

        <!-- Tabel Daftar Feedback Pengguna -->
        <div class="mt-8 bg-slate-900 border border-gray-700 rounded-lg p-6 text-white shadow-md">
            <h3 class="text-lg font-bold mb-4 flex items-center space-x-2">
                <span>💬 Pesan & Feedback Pengguna</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-700 text-gray-400 text-sm">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Pesan / Masukan</th>
                            <th class="py-3 px-4">Waktu Kirim</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 text-sm">
                        @forelse($feedbacks as $index => $fb)
                            <tr class="hover:bg-slate-800 transition-colors">
                                <td class="py-3 px-4 text-gray-400">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-medium text-gray-200">{{ $fb->pesan }}</td>
                                <td class="py-3 px-4 text-gray-400 text-xs">{{ $fb->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-gray-500">Belum ada feedback yang masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>