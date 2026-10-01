@csrf
<div class="grid gap-5 sm:grid-cols-2"><x-ui.input label="Nama tahun ajaran" name="nama_tahun_ajaran" :value="$academicYear->nama_tahun_ajaran ?? null"
        required maxlength="20" placeholder="2026/2027" /><x-ui.input label="Tanggal mulai" name="tanggal_mulai"
        type="date" :value="isset($academicYear) ? $academicYear->tanggal_mulai->format('Y-m-d') : null" required /><x-ui.input label="Tanggal selesai" name="tanggal_selesai"
        type="date" :value="isset($academicYear) ? $academicYear->tanggal_selesai->format('Y-m-d') : null" required /><label
        class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 text-sm text-gray-700"><input
            type="checkbox" name="status_aktif" value="1" @checked(old('status_aktif', $academicYear->status_aktif ?? false))
            class="rounded border-gray-300 text-gray-900 focus:ring-gray-500"> Jadikan tahun ajaran aktif <span
            class="text-xs text-gray-500">Tahun aktif sebelumnya akan dinonaktifkan.</span></label></div>
<div class="mt-6 flex justify-end gap-3"><x-ui.button variant="secondary"
        :href="isset($academicYear)
            ? route('admin.academic-years.show', $academicYear)
            : route('admin.academic-years.index')">Batal</x-ui.button><x-ui.button type="submit">{{ $submitLabel }}</x-ui.button></div>
