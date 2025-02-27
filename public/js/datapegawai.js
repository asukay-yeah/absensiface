document.addEventListener("DOMContentLoaded", function () {
    const tableElement = document.getElementById("export-table-pegawai");
    if (!tableElement) return;

    // === Inisialisasi DataTable TANPA fitur search & pagination bawaan ===
    const table = new simpleDatatables.DataTable("#export-table-pegawai", {
        searchable: false,   // Nonaktifkan search bawaan
        paging: false,       // Nonaktifkan pagination bawaan
        fixedHeight: true,   // Agar tabel tidak memanjang
        perPage: 10,         // Default jumlah data per halaman (jika nanti diaktifkan)
        perPageSelect: false // Hilangkan opsi dropdown jumlah data per halaman
    });

    // === Manual Search Bar dengan pesan "pegawai tidak di temukan" ===
    document.getElementById("searchInputPegawai").addEventListener("keyup", function () {
        const searchValue = this.value.toLowerCase();
        const tbody = tableElement.querySelector("tbody");
        // Ambil semua baris kecuali baris pesan "no results"
        const rows = tbody.querySelectorAll("tr:not(.no-results)");
        let visibleCount = 0;

        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchValue)) {
                row.style.display = "";
                visibleCount++;
            } else {
                row.style.display = "none";
            }
        });

        let noResultsRow = tbody.querySelector("tr.no-results");
        if (visibleCount === 0) {
            if (!noResultsRow) {
                // Hitung jumlah kolom dari header tabel
                const colCount = tableElement.querySelector("thead tr").children.length;
                noResultsRow = document.createElement("tr");
                noResultsRow.classList.add("no-results");
                const cell = document.createElement("td");
                cell.setAttribute("colspan", colCount);
                cell.textContent = "pegawai tidak di temukan";
                cell.style.textAlign = "center";
                noResultsRow.appendChild(cell);
                tbody.appendChild(noResultsRow);
            } else {
                noResultsRow.style.display = "";
            }
        } else {
            if (noResultsRow) {
                noResultsRow.style.display = "none";
            }
        }
    });
});
