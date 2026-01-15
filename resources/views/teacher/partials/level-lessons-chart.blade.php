<div class="bg-white rounded-xl shadow-lg p-6 w-full">
    <h3 class="text-xl font-semibold text-gray-800 mb-6">Lessons by Level</h3>
    
    <div class="w-full" style="position: relative; height: 400px;">
        <canvas id="levelLessonsChart"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('levelLessonsChart');
    if (!ctx) return;
    
    const levelNames = @json($levelNames);
    const lessonCounts = @json($lessonCounts);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: levelNames,
            datasets: [{
                label: 'Number of Lessons',
                data: lessonCounts,
                borderColor: '#6EC6C5',
                backgroundColor: 'rgba(110, 198, 197, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#6EC6C5',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#197D8C',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 12
                        }
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
                            return 'Lessons: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Lessons',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Level',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45,
                        autoSkip: false
                    }
                }
            }
        }
    });
});
</script>
@endpush
