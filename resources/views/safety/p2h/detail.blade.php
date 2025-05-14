{
    data: null,
    render: function(data, type, row) {
        // Pastikan row valid
        if (!row) return '';

        // Cek apakah keduanya null (VERIFIED_FOREMAN dan VERIFIED_SUPERVISOR)
        if (!row.VERIFIED_FOREMAN && !row.VERIFIED_SUPERVISOR) {
            // Tampilkan tombol Detail jika keduanya null
            let editUrl = "{{ route('p2h.detail') }}" +
                "?VHC_ID=" + encodeURIComponent(row.VHC_ID) +
                "&OPR_REPORTTIME=" + encodeURIComponent(row.OPR_REPORTTIME) +
                "&OPR_NRP=" + encodeURIComponent(row.OPR_NRP);

            return `
                <a href="${editUrl}">
                    <span class="badge w-100" style="font-size:14px;background-color:#001932">
                        Detail
                    </span>
                </a>
            `;
        } else {
            return ''; // Kosongkan jika salah satu atau keduanya tidak null
        }
    }
}
