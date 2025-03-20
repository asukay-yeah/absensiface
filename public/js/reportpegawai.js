if (document.getElementById("export-table") && typeof simpleDatatables.DataTable !== 'undefined') {
    // const table = new simpleDatatables.DataTable("#export-table", {
    //     perPageSelect: false,
    //     template: (options, dom) => `
    //         <div class='${options.classes.container}' ${options.scrollY.length ? `style='height: ${options.scrollY}; overflow-Y: auto;'` : ""}></div>
    //         <div class='${options.classes.bottom}'>
    //             <nav class='${options.classes.pagination}'>
    //                 <button class='pagination-prev'>Previous</button>
    //                 <button class='pagination-next'>Next</button>
    //             </nav>
    //         </div>
    //     `
    // });

    const table = new simpleDatatables.DataTable("#export-table", {
        perPage: 0, // Menampilkan semua data tanpa pagination
        perPageSelect: false,
        template: (options, dom) => `
            <div class='${options.classes.container}' ${options.scrollY.length ? `style='height: ${options.scrollY}; overflow-Y: auto;'` : ""}></div>
        `
    });
    

    // 📂 Toggle Dropdown Export
    document.getElementById("exportDropdownButton").addEventListener("click", function () {
        document.getElementById("exportDropdown").classList.toggle("hidden");
    });

    // Function to generate report title based on search
    function generateReportTitle() {
        const searchInput = document.getElementById("search-input").value.trim().toLowerCase();
        let title = "Data Laporan Kehadiran Balmon Jakarta";
        
        // Mapping nama bulan untuk validasi dan format
        const bulanMap = {
            'januari': 'Januari', 'februari': 'Februari', 'maret': 'Maret', 'april': 'April',
            'mei': 'Mei', 'juni': 'Juni', 'juli': 'Juli', 'agustus': 'Agustus',
            'september': 'September', 'oktober': 'Oktober', 'november': 'November', 'desember': 'Desember'
        };
        
        // Pattern untuk <tanggal> <bulan> <tahun>
        if (/^(\d{1,2})\s+([a-zA-Z]+)(?:\s+(\d{4}))?$/.test(searchInput)) {
            const matches = searchInput.match(/^(\d{1,2})\s+([a-zA-Z]+)(?:\s+(\d{4}))?$/);
            const tanggal = matches[1];
            const bulanText = matches[2];
            const tahun = matches[3] || new Date().getFullYear();
            
            if (bulanMap[bulanText]) {
                return `${title}, ${tanggal} ${bulanMap[bulanText]} ${tahun}`;
            }
        } 
        // Pattern untuk <bulan> <tahun>
        else if (/^([a-zA-Z]+)\s+(\d{4})$/.test(searchInput)) {
            const matches = searchInput.match(/^([a-zA-Z]+)\s+(\d{4})$/);
            const bulanText = matches[1];
            const tahun = matches[2];
            
            if (bulanMap[bulanText]) {
                return `${title}, bulan ${bulanMap[bulanText]} ${tahun}`;
            }
        }
        // Pattern untuk <bulan> saja
        else if (bulanMap[searchInput]) {
            return `${title}, bulan ${bulanMap[searchInput]}`;
        }
        // Pattern untuk <tahun> saja
        else if (/^\d{4}$/.test(searchInput)) {
            return `${title}, tahun ${searchInput}`;
        }
        
        // Default jika tidak ada kriteria search
        return title;
    }

    // 📊 Export ke Excel
    document.getElementById("export-excel").addEventListener("click", () => {
        const reportTitle = generateReportTitle();
        
        // Create workbook with sheet
        const wb = XLSX.utils.book_new();
        const ws_data = [
            [reportTitle],
            [], // Empty row for spacing
        ];
        
        // Add headers
        const tableHeaders = [];
        document.querySelectorAll("#export-table thead th").forEach(th => {
            // Remove sorting icons from header text
            const headerText = th.textContent.trim().replace(/[\n\r]+|[\s]{2,}/g, ' ');
            tableHeaders.push(headerText);
        });
        ws_data.push(tableHeaders);
        
        // Add data rows
        document.querySelectorAll("#export-table tbody tr").forEach(tr => {
            const rowData = [];
            tr.querySelectorAll("td").forEach(td => {
                rowData.push(td.textContent.trim());
            });
            ws_data.push(rowData);
        });
        
        // Create worksheet from data
        const ws = XLSX.utils.aoa_to_sheet(ws_data);
        
        // Style title row
        ws['!merges'] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: tableHeaders.length - 1 } }];
        
        // Adjust column widths
        const colWidths = [];
        tableHeaders.forEach(() => colWidths.push({ wch: 20 }));
        ws['!cols'] = colWidths;
        
        // Add worksheet to workbook and save
        XLSX.utils.book_append_sheet(wb, ws, "Report");
        XLSX.writeFile(wb, "laporan_kehadiran_pegawai.xlsx");
    });

    // 📝 Export ke PDF
    document.getElementById("export-pdf").addEventListener("click", () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const tableElement = document.getElementById("export-table");
        
        // Get dynamic report title
        const reportTitle = generateReportTitle();
        
        // Set title with font bold
        doc.setFontSize(14);
        doc.setFont("helvetica", "bold");
        doc.text(reportTitle, 15, 10);
        doc.setFont("helvetica", "normal");
        
        // Konversi tabel HTML ke JSON menggunakan autoTableHtmlToJson
        const data = doc.autoTableHtmlToJson(tableElement);
        
        // Cari indeks kolom dengan header "no meja" (case-insensitive)
        const noMejaIndex = data.columns.findIndex(col => 
            col.toLowerCase().includes("no meja") || col.toLowerCase().includes("nomor meja"));
        
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
            startY: 20,
            headStyles: { fillColor: [37, 44, 88], textColor: [255, 255, 255] },
            alternateRowStyles: { fillColor: [245, 247, 250] },
            margin: { top: 20 }
        });
        
        doc.save("laporan_kehadiran_pegawai.pdf");
    });
}