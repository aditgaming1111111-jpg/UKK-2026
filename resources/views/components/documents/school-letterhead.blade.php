@php
    $configuredLogoPath = config('school.logo_path');
    $schoolLogoPath = $configuredLogoPath ? public_path($configuredLogoPath) : null;
@endphp

<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-bottom: 3px double #111827; margin-bottom: 16px;">
    <tr>
        <td width="78" style="padding: 0 12px 12px 0; vertical-align: middle;">
            @if ($schoolLogoPath && is_file($schoolLogoPath))
                <img src="{{ $schoolLogoPath }}" alt="Logo sekolah" style="width: 64px; height: 64px; object-fit: contain;">
            @endif
        </td>
        <td style="padding: 0 0 12px; text-align: center; vertical-align: middle;">
            @if (config('school.organization'))
                <div style="font-size: 9px; text-transform: uppercase;">{{ config('school.organization') }}</div>
            @endif
            <div style="font-size: 17px; font-weight: bold; text-transform: uppercase;">{{ config('school.name', 'SekolahKu') }}</div>
            @if (config('school.address'))
                <div style="margin-top: 4px; font-size: 9px;">{{ config('school.address') }}</div>
            @else
                <div style="margin-top: 4px; color: #6b7280; font-size: 9px;">Alamat sekolah belum dikonfigurasi</div>
            @endif
        </td>
        <td width="78" style="padding: 0 0 12px 12px;"></td>
    </tr>
</table>
