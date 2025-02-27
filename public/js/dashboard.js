document.addEventListener("DOMContentLoaded", function () {
    const today = new Date();
    const startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - today.getDay() + 1); // Senin minggu ini
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 4); // Jumat minggu ini


    // ✅ **Chart Harian (Senin - Jumat)**
    const optionsHarian = {
        series: [{
                name: "Hadir",
                color: "#2c6e49",
                data: [{
                        x: "Senin",
                        y: hadirPerHari[0] ?? 0
                    },
                    {
                        x: "Selasa",
                        y: hadirPerHari[1] ?? 0
                    },
                    {
                        x: "Rabu",
                        y: hadirPerHari[2] ?? 0
                    },
                    {
                        x: "Kamis",
                        y:  hadirPerHari[3] ?? 0
                    },
                    {
                        x: "Jumat",
                        y:  hadirPerHari[4] ?? 0
                    },
                ],
            },
            {
                name: "Tidak Hadir",
                color: "#bc4749",
                data: [{
                        x: "Senin",
                        y: tidakHadirPerHari[0] ?? 0
                    },
                    {
                        x: "Selasa",
                        y: tidakHadirPerHari[1] ?? 0
                    },
                    {
                        x: "Rabu",
                        y: tidakHadirPerHari[2] ?? 0
                    },
                    {
                        x: "Kamis",
                        y: tidakHadirPerHari[3] ?? 0
                    },
                    {
                        x: "Jumat",
                        y: tidakHadirPerHari[4] ?? 0
                    },
                ],
            },
        ],
        chart: {
            type: "bar",
            height: "300px",
            toolbar: {
                show: false
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "80%",
                borderRadius: 4,
            },
        },
        xaxis: {
            labels: {
                style: {
                    fontSize: "12px",
                    fontWeight: "semibold",
                    fontFamily: "Sofia Pro, sans-serif",
                    color: "#ffffff",
                }
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
        },
        yaxis: {
            show: false
        },
        fill: {
            opacity: 1
        },
    };

    // ✅ **Chart Bulanan (Januari - desember)**
    // const monthlyLabels = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    const optionsMingguan = {
        series: [{
                name: "Hadir",
                color: "#2c6e49",
                data: monthlyLabels.map((month, index) => ({
                    x: month,
                    y: hadirPerBulan[index] || 0
                })),
            },
            {
                name: "Tidak Hadir",
                color: "#bc4749",
                data: monthlyLabels.map((month, index) => ({
                    x: month,
                    y: tidakHadirPerBulan[index] || 0
                }))
            },
        ],
        chart: {
            type: "bar",
            height: "300px",
            toolbar: {
                show: false
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "80%",
                borderRadius: 4,
            },
        },
        xaxis: {
            categories: monthlyLabels,
            labels: {
                style: {
                    fontSize: "12px",
                    fontWeight: "500",
                    fontFamily: "Sofia Pro, sans-serif",
                    color: "#ffffff",
                }
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
        },
        yaxis: {
            show: false
        },
        fill: {
            opacity: 1
        },
    };


    
    // ✅ **Chart Pie**
    const getChartOptions = () => {
        return {
            series: [tepat, terlambat],
            colors: ["#6a994e", "#f25c54"],
            chart: {
                height: 420,
                width: "100%",
                type: "pie",
            },
            stroke: {
                colors: ["white"],
                lineCap: ""
            },
            plotOptions: {
                pie: {
                    labels: {
                        show: true
                    },
                    size: "100%",
                    dataLabels: {
                        offset: -25
                    },
                },
            },
            labels: ["Tepat Waktu", "Terlambat"],
            dataLabels: {
                enabled: true,
                style: {
                    fontFamily: "Sofia Pro, sans-serif",
                },
            },
            legend: {
                position: "bottom",
                fontFamily: "Sofia Pro, sans-serif",
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return value + " Orang";
                    }
                },
            },
            xaxis: {
                labels: {
                    formatter: function (value) {
                        return value + " Orang";
                    }
                },
                axisTicks: {
                    show: true
                },
                axisBorder: {
                    show: true
                },
            },
        };
    };

    

    // ✅ **Render Chart Harian**
    const chartHarianElement = document.getElementById("chart-harian");
    if (chartHarianElement && typeof ApexCharts !== "undefined") {
        const chartHarian = new ApexCharts(chartHarianElement, optionsHarian);
        chartHarian.render();
    } else {
        console.error("ApexCharts atau elemen chart harian tidak ditemukan.");
    }

    // ✅ **Render Chart Bulanan**
    const chartMingguanElement = document.getElementById("chart-mingguan");
    if (chartMingguanElement && typeof ApexCharts !== "undefined") {
        const chartMingguan = new ApexCharts(chartMingguanElement, optionsMingguan);
        chartMingguan.render();
    } else {
        console.error("ApexCharts atau elemen chart mingguan tidak ditemukan.");
    }

    // ✅ **Render Chart Pie**
    const pieChartElement = document.getElementById("pie-chart");
    if (pieChartElement && typeof ApexCharts !== "undefined") {
        const chart = new ApexCharts(pieChartElement, getChartOptions());
        chart.render();
    } else {
        console.error("ApexCharts atau elemen pie chart tidak ditemukan.");
    }

    // ✅ **Menambahkan CSS untuk warna teks chart**
    const style = document.createElement("style");
    style.innerHTML = `
        .apexcharts-xaxis text {
            fill: #ffffff !important; /* Warna putih */
        }
    `;
    document.head.appendChild(style);
});