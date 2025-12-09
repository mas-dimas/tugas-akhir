<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-sky-300 via-sky-100 to-amber-100">
        <div class="max-w-5xl mx-auto p-6">
            <div class="bg-white/70 backdrop-blur rounded-2xl shadow p-6">
                <h1 class="text-2xl font-bold">Dashboard Peserta 🌞🏖️</h1>
                <p class="mt-2 text-gray-700">
                    Halo, {{ auth()->user()->name }}! Kamu bisa lihat lomba dan daftar dari sini.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
