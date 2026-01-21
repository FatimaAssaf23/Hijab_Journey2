<div class="bg-white rounded-xl shadow-lg p-6 w-full">
    <h3 class="text-xl font-semibold text-gray-800 mb-6">Assignment Grade Distribution</h3>
    
    <div class="w-full" style="position: relative; height: 250px;">
        <canvas id="assignmentStatsChart"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('assignmentStatsChart');
    if (!ctx) return;
    
    const gradeRangeLabels = @json($gradeRangeLabels);
    const gradeRangeCounts = @json($gradeRangeCounts);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: gradeRangeLabels,
            datasets: [{
                label: 'Number of Students',
                data: gradeRangeCounts,
                backgroundColor: '#6EC6C5',
                borderColor: '#197D8C',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 5,
                    right: 5,
                    top: 10,
                    bottom: 5
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 11
                        },
                        padding: 10
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Students: ' + context.parsed.x;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Students',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        stepSize: 1,
                        precision: 0,
                        font: {
                            size: 10
                        },
                        padding: 5
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Grade Range (%)',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        },
                        padding: 5
                    }
                }
            }
        }
    });
});
</script>
@endpush
