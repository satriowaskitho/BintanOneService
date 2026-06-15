@extends('kiosk.master')

@section('content')
<div class="max-w-xl mx-auto py-6">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Buat Janji Temu</h2>
        <p class="text-sm text-gray-500 mt-2">Pilih jenis layanan, tanggal, dan waktu kunjungan Anda.</p>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl font-bold" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('kiosk.appointment.store', $visitor->id) }}" method="POST" class="space-y-6 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        @csrf
        <input type="hidden" name="visitor_id" value="{{ $visitor->id }}">

        <!-- SERVICE TYPE -->
        <div>
            <label class="block text-gray-700 font-bold mb-2">Jenis Layanan</label>
            <select name="service_type_id" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 outline-none">
                <option value="">-- Pilih Layanan --</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ old('service_type_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- DATE -->
        <div>
            <label class="block text-gray-700 font-bold mb-2">Tanggal Kunjungan</label>
            <input type="date" name="date" id="appointment_date" min="{{ date('Y-m-d') }}" value="{{ old('date', date('Y-m-d')) }}" required 
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 outline-none">
        </div>

        <!-- TIME SLOTS GRID -->
        <div>
            <label class="block text-gray-700 font-bold mb-2">Pilih Waktu Kunjungan (WIB)</label>
            <div class="grid grid-cols-4 gap-2">
                @php
                    $slots = [
                        '08:00', '08:15', '08:30', '08:45',
                        '09:00', '09:15', '09:30', '09:45',
                        '10:00', '10:15', '10:30', '10:45',
                        '11:00', '11:15', '11:30', '11:45',
                        '13:00', '13:15', '13:30', '13:45',
                        '14:00', '14:15', '14:30', '14:45',
                        '15:00', '15:15', '15:30'
                    ];
                @endphp
                @foreach($slots as $slot)
                    <label class="block text-center">
                        <input type="radio" name="time" value="{{ $slot }}" required class="sr-only peer" {{ old('time') === $slot ? 'checked' : '' }}>
                        <div class="py-2.5 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer font-semibold text-sm text-gray-700 hover:bg-gray-100 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 peer-disabled:bg-gray-100 peer-disabled:text-gray-400 peer-disabled:border-gray-200 peer-disabled:cursor-not-allowed peer-disabled:hover:bg-gray-100 transition-all">
                            {{ $slot }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- PURPOSE -->
        <div>
            <label class="block text-gray-700 font-bold mb-2">Keperluan Kunjungan</label>
            <textarea name="purpose" rows="3" required placeholder="Jelaskan kebutuhan pelayanan Anda secara detail (min. 10 karakter)"
                      class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 outline-none resize-none">{{ old('purpose') }}</textarea>
        </div>



        <!-- NOTIFICATION EMAIL -->
        <div>
            <label class="block text-gray-700 font-bold mb-2">Email Penerima Tiket</label>
            <input type="email" name="email" value="{{ old('email', $visitor->email) }}" required placeholder="akun@domain.com"
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 outline-none">
        </div>

        <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-colors mt-6 text-lg">
            📅 Buat Jadwal Janji Temu
        </button>
    </form>

    <div class="mt-8 text-center">
        <a href="{{ route('kiosk.options', $visitor->id) }}" class="text-sm text-gray-400 hover:text-gray-600 font-semibold">&larr; Kembali ke Pilihan</a>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('appointment_date');
        const timeInputs = document.querySelectorAll('input[name="time"]');

        function updateAvailableSlots() {
            const selectedDateStr = dateInput.value;
            if (!selectedDateStr) return;

            const selectedDate = new Date(selectedDateStr);
            const today = new Date();
            
            // Compare dates without time
            const isToday = selectedDate.toDateString() === today.toDateString();

            const currentHour = today.getHours();
            const currentMinute = today.getMinutes();

            timeInputs.forEach(input => {
                const slotValue = input.value; // e.g. "08:15"
                const [slotHour, slotMinute] = slotValue.split(':').map(Number);
                
                let disable = false;
                if (isToday) {
                    if (slotHour < currentHour || (slotHour === currentHour && slotMinute <= currentMinute)) {
                        disable = true;
                    }
                }

                input.disabled = disable;
                
                if (disable && input.checked) {
                    input.checked = false;
                }
            });
        }

        dateInput.addEventListener('change', updateAvailableSlots);
        updateAvailableSlots();
    });
</script>
@endpush
@endsection
