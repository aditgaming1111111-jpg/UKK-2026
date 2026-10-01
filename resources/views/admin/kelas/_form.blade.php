@csrf
<div class="grid gap-5 sm:grid-cols-2"><x-ui.input label="Nama kelas" name="nama_kelas" :value="$kelas->nama_kelas ?? null" required
        maxlength="50" /><x-ui.input label="Tingkat" name="tingkat" :value="$kelas->tingkat ?? null" required maxlength="20" />
    <div class="sm:col-span-2"><x-ui.input label="Jurusan" name="jurusan" :value="$kelas->jurusan ?? null"
            hint="Boleh dikosongkan jika kelas tidak menggunakan jurusan." /></div>
</div>
<div class="mt-6 flex justify-end gap-3"><x-ui.button variant="secondary"
        :href="isset($kelas) ? route('admin.classes.show', $kelas) : route('admin.classes.index')">Batal</x-ui.button><x-ui.button type="submit">{{ $submitLabel }}</x-ui.button></div>
