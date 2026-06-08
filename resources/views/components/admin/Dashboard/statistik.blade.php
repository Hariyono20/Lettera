<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

    {{-- Card: Jumlah Pengajuan per Bulan --}}
    <div class="bg-white p-5 rounded-xl shadow-md">
        <h2 class="text-base font-bold mb-3">Jumlah Pengajuan Per Bulan</h2>

        <div class="h-56">
            <canvas id="chartPengajuan"></canvas>
        </div>
    </div>

    {{-- Card: Jenis Surat Terbanyak --}}
    <div class="bg-white p-5 rounded-xl shadow-md">
        <h2 class="text-base font-bold mb-3">Jenis Surat Terbanyak</h2>

        <div class="h-56">
            <canvas id="chartJenisSurat"></canvas>
        </div>
    </div>

</div>

{{-- CDNs Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    // -------------------------------------------------------------
    // Chart 1 — Pengajuan Per Bulan (Dinamis dari Database)
    // -------------------------------------------------------------
    const dataBulanan = {!! json_encode($chartBulananData) !!};
    const labelsBulanan = {!! json_encode($chartBulananLabels) !!};

    new Chart(document.getElementById('chartPengajuan'), {
        type: 'bar',
        data: {
            labels: labelsBulanan,
            datasets: [{
                data: dataBulanan,
                backgroundColor: '#3B82F6',
                borderRadius: 8,
                barThickness: 48,
                maxBarThickness: 48,
                categoryPercentage: 0.5,   
                barPercentage: 0.7      
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { 
                        font: { size: 12 },
                        precision: 0 // Menghindari angka desimal pada skala jumlah surat
                    }
                },
                x: { 
                    ticks: { font: { size: 12 } }
                },
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // -------------------------------------------------------------
    // Chart 2 — Jenis Surat Terbanyak (PERSENTASE DI DALAM DONUT RIIL)
    // -------------------------------------------------------------
    const jenisData = {!! json_encode($chartJenisData) !!};
    const jenisLabels = {!! json_encode($chartJenisLabels) !!};
    const total = jenisData.reduce((a, b) => a + b, 0);

    new Chart(document.getElementById('chartJenisSurat'), {
        type: 'doughnut',
        data: {
            labels: jenisLabels,
            datasets: [{
                data: jenisData,
                backgroundColor: [
                    '#3B82F6',
                    '#F59E0B',
                    '#10B981',
                    '#8B5CF6',
                    '#EF4444'
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: { 
                        boxWidth: 12,
                        font: { size: 12 }
                    }
                },
                // Persentase dinamis berdasarkan kalkulasi total data di database
                datalabels: {
                    color: '#ffffff',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: (value) => {
                        if (total === 0) return '0%';
                        const percentage = ((value / total) * 100).toFixed(0);
                        return percentage + '%';
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>