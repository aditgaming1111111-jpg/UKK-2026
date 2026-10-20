@csrf
<div class="grid gap-5 sm:grid-cols-2">
    <x-ui.input label="NIP" name="nip" :value="$teacher->nip ?? null" required />
    <x-ui.input label="Nama lengkap" name="nama" :value="$teacher->nama ?? null" required />
    <div class="sm:col-span-2"><x-ui.input label="Email" name="email" type="email" :value="$teacher->email ?? null"
            hint="Email ini digunakan untuk identitas Guru sekaligus login." required /></div>
</div>
<div class="mt-6 flex justify-end gap-3"><x-ui.button variant="secondary"
        :href="isset($teacher) ? route('admin.teachers.show', $teacher) : route('admin.teachers.index')">Batal</x-ui.button><x-ui.button type="submit">{{ $submitLabel }}</x-ui.button></div>
