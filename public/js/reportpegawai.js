if (document.getElementById("export-table") && typeof simpleDatatables.DataTable !== 'undefined') {
    const table = new simpleDatatables.DataTable("#export-table", {
        perPageSelect: false,
        template: (options, dom) => `
            <div class='${options.classes.container}' ${options.scrollY.length ? `style='height: ${options.scrollY}; overflow-Y: auto;'` : ""}></div>
            <div class='${options.classes.bottom}'>
                <nav class='${options.classes.pagination}'>
                    <button class='pagination-prev'>Previous</button>
                    <button class='pagination-next'>Next</button>
                </nav>
            </div>
        `
    });

    

    // 📂 Toggle Dropdown Export
    document.getElementById("exportDropdownButton").addEventListener("click", function () {
        document.getElementById("exportDropdown").classList.toggle("hidden");
    });

    // 📊 Export ke Excel
    document.getElementById("export-excel").addEventListener("click", () => {
        const wb = XLSX.utils.table_to_book(document.getElementById("export-table"));
        XLSX.writeFile(wb, "export_table.xlsx");
    });

    // 📝 Export ke PDF dengan header bold dan menghapus kolom "no meja"
    document.getElementById("export-pdf").addEventListener("click", () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const tableElement = document.getElementById("export-table");

        // Tentukan header report (nilai bisa diganti atau dibuat dinamis)
        const startMonth = "Januari";
        const endMonth = "Desember";
        const headerText = `Data report kehadiran pegawai dari ${startMonth} sampai ${endMonth}`;

        // Set header dengan font bold
        doc.setFontSize(14);
        doc.setFont("helvetica", "bold");
        doc.text(headerText, 15, 10);
        doc.setFont("helvetica", "normal");

        // Konversi tabel HTML ke JSON menggunakan autoTableHtmlToJson
        const data = doc.autoTableHtmlToJson(tableElement);

        // Cari indeks kolom dengan header "no meja" (case-insensitive)
        const noMejaIndex = data.columns.findIndex(col => col.toLowerCase() === "no meja");
        let filteredColumns, filteredData;
        if (noMejaIndex !== -1) {
            filteredColumns = data.columns.filter((col, index) => index !== noMejaIndex);
            filteredData = data.data.map(row => row.filter((cell, index) => index !== noMejaIndex));
        } else {
            filteredColumns = data.columns;
            filteredData = data.data;
        }

        // Render tabel ke PDF mulai dari y = 20 agar tidak bertabrakan dengan header
        doc.autoTable({
            head: [filteredColumns],
            body: filteredData,
            startY: 17
        });
        doc.save("report_absensi_pegawai.pdf");
    });

    
}
