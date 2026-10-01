@csrf
<div class="grid gap-5 sm:grid-cols-2">
    <x-ui.input label="NISN" name="nisn" inputmode="numeric" maxlength="10" :value="$student->nisn ?? null" required />
    <x-ui.input label="Nama lengkap" name="nama" :value="$student->nama ?? null" required />
    <label class="space-y-1.5"><span class="block text-sm font-medium text-gray-700">Jenis kelamin <span
                class="text-gray-500">*</span></span><select name="jenis_kelamin" required
            class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
            <option value="">Pilih jenis kelamin</option>
            <option value="L" @selected(old('jenis_kelamin', $student->jenis_kelamin ?? '') === 'L')>Laki-laki</option>
            <option value="P" @selected(old('jenis_kelamin', $student->jenis_kelamin ?? '') === 'P')>Perempuan</option>
        </select><x-ui.form-error name="jenis_kelamin" /></label>
    <x-ui.input label="Tahun masuk" name="tahun_masuk" type="number" min="1900" max="2100" :value="$student->tahun_masuk ?? now()->year"
        required />
    <div class="sm:col-span-2"><x-ui.textarea label="Alamat" name="alamat"
            rows="3">{{ $student->alamat ?? '' }}</x-ui.textarea></div>
</div>
<div class="mt-6 flex flex-wrap justify-end gap-3"><x-ui.button variant="secondary"
        :href="isset($student) ? route('admin.students.show', $student) : route('admin.students.index')">Batal</x-ui.button><x-ui.button type="submit">{{ $submitLabel }}</x-ui.button></div>
