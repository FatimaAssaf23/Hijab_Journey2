@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-rose-50 to-cyan-50">
    <!-- Header - Matches Navbar -->
    <div class="relative bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-black text-white mb-1 drop-shadow-lg tracking-tight">
                        🎯 Quiz Analytics Hub
                    </h1>
                    <p class="text-white/90 text-sm font-medium">Comprehensive insights into student quiz performance</p>
                </div>
                <div class="bg-white/20 backdrop-blur-xl rounded-xl px-6 py-3 border border-white/30 shadow-lg">
                    <div class="text-center">
                        <p class="text-white/90 text-xs font-semibold mb-1">Total Quizzes</p>
                        <p class="text-white text-3xl font-black">{{ $quizzes->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="bg-gradient-to-r from-cyan-400 to-teal-500 text-white rounded-2xl p-4 flex items-center gap-3 shadow-xl border-2 border-cyan-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Charts Section -->
        <div class="mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Overall Submission Status Pie Chart -->
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border-2 border-pink-200">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                        <span class="text-3xl">🥧</span>
                        Overall Submission Status
                    </h3>
                    <p class="text-sm text-gray-600 mb-6 ml-11">Across all classes</p>
                    <div id="statusChart" style="min-height: 350px;"></div>
                </div>

                <!-- Class Performance Comparison - Horizontal Bar Chart -->
                @if(!empty($classStats))
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border-2 border-cyan-200">
                    <h3 class="text-2xl font-bold text-gray-800 mb-8 flex items-center gap-3">
                        <span class="text-3xl">🏫</span>
                        Class Performance Comparison
                    </h3>
                    <div id="classComparisonChart" style="min-height: 350px;"></div>
                </div>
                @endif
            </div>
        </div>

        <!-- By Classes Section -->
        <div class="mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border-2 border-pink-200 min-h-[600px]">
                <div class="p-8">
                    @if(empty($classStats))
                    <div class="text-center py-20">
                        <div class="w-32 h-32 bg-gradient-to-br from-pink-200 to-cyan-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                            <span class="text-6xl">🎓</span>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">No Class Data</h3>
                        <p class="text-gray-600 text-lg">No quizzes have been assigned to classes yet.</p>
                    </div>
                    @else
                    <div class="space-y-6">
                        @foreach($classStats as $index => $class)
                        <div x-data="{ expanded: false }" 
                             class="bg-gradient-to-r {{ $index % 2 == 0 ? 'from-pink-50 via-rose-50 to-pink-50' : 'from-cyan-50 via-teal-50 to-cyan-50' }} rounded-2xl p-6 shadow-xl border-2 {{ $index % 2 == 0 ? 'border-pink-300' : 'border-cyan-300' }} hover:shadow-2xl transition-all transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br {{ $index % 2 == 0 ? 'from-pink-300 to-rose-400' : 'from-cyan-300 to-teal-400' }} flex items-center justify-center text-gray-800 font-black text-2xl shadow-lg">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <h4 class="text-2xl font-black text-gray-800">{{ $class['class_name'] }}</h4>
                                        <p class="text-gray-600 font-semibold">Teacher: {{ $class['teacher'] }}</p>
                                    </div>
                                </div>
                                <button @click="expanded = !expanded" 
                                        class="px-6 py-3 bg-gradient-to-r {{ $index % 2 == 0 ? 'from-pink-300 to-rose-300' : 'from-cyan-300 to-teal-300' }} text-gray-800 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                    <span x-text="expanded ? 'Hide' : 'Show'"></span>
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                                <div class="bg-white/80 rounded-xl p-4 text-center border border-pink-200 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Quizzes</p>
                                    <p class="text-2xl font-black text-pink-600">{{ $class['total_quizzes'] }}</p>
                                </div>
                                <div class="bg-white/80 rounded-xl p-4 text-center border border-cyan-200 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Submissions</p>
                                    <p class="text-2xl font-black text-cyan-600">{{ $class['total_submissions'] }}</p>
                                </div>
                                <div class="bg-white/80 rounded-xl p-4 text-center border border-green-200 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Passed</p>
                                    <p class="text-2xl font-black text-green-600">{{ $class['total_passed'] }}</p>
                                </div>
                                <div class="bg-white/80 rounded-xl p-4 text-center border border-red-200 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Failed</p>
                                    <p class="text-2xl font-black text-red-600">{{ $class['total_failed'] }}</p>
                                </div>
                                <div class="bg-white/80 rounded-xl p-4 text-center border border-purple-200 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Avg Score</p>
                                    <p class="text-2xl font-black text-purple-600">{{ number_format($class['average_percentage'], 1) }}%</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white/80 rounded-xl p-4 border border-pink-200">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-semibold text-gray-700">Completion</span>
                                        <span class="font-black text-pink-600">{{ $class['completion_rate'] }}%</span>
                                    </div>
                                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-pink-300 to-rose-300 rounded-full transition-all duration-1000" style="width: {{ min($class['completion_rate'], 100) }}%"></div>
                                    </div>
                                </div>
                                <div class="bg-white/80 rounded-xl p-4 border border-cyan-200">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-semibold text-gray-700">Pass Rate</span>
                                        <span class="font-black text-cyan-600">{{ $class['pass_rate'] }}%</span>
                                    </div>
                                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-cyan-300 to-teal-300 rounded-full transition-all duration-1000" style="width: {{ min($class['pass_rate'], 100) }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="expanded" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="mt-6 pt-6 border-t-2 border-gray-300">
                                
                                @php
                                    $classQuizzes = $quizzes->where('class_id', $class['class_id']);
                                @endphp
                                @if($classQuizzes->count() > 0)
                                <h5 class="font-bold text-gray-800 mb-6 text-xl flex items-center gap-2">
                                    <span class="text-2xl">📚</span>
                                    Class Quizzes ({{ $classQuizzes->count() }})
                                </h5>
                                <div class="space-y-6">
                                    @foreach($classQuizzes as $quizIndex => $quizItem)
                                    <div x-data="{ showQuizContent: false, showQuizDetails: false }" 
                                         class="bg-white/90 rounded-2xl p-6 border-2 {{ $index % 2 == 0 ? 'border-pink-200' : 'border-cyan-200' }} shadow-lg hover:shadow-xl transition-all">
                                        <!-- Quiz Header -->
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-start gap-4 flex-1">
                                                <div class="w-16 h-16 rounded-xl flex items-center justify-center text-3xl shadow-lg bg-gradient-to-br {{ $index % 2 == 0 ? 'from-pink-300 to-rose-400' : 'from-cyan-300 to-teal-400' }}" 
                                                     style="background: linear-gradient(135deg, {{ $quizItem->background_color ?? ($index % 2 == 0 ? '#F9A8D4' : '#67E8F9') }}, {{ $quizItem->background_color ?? ($index % 2 == 0 ? '#F472B6' : '#22D3EE') }});">
                                                    ❓
                                                </div>
                                                <div class="flex-1">
                                                    <h6 class="text-xl font-bold text-gray-800 mb-2">{{ $quizItem->title }}</h6>
                                                    @if($quizItem->description)
                                                    <p class="text-sm text-gray-600 mb-3">{{ $quizItem->description }}</p>
                                                    @endif
                                                    <div class="flex flex-wrap gap-2 text-xs mb-3">
                                                        <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full font-semibold border border-pink-200">
                                                            {{ $quizItem->questions->count() }} Questions
                                                        </span>
                                                        @if($quizItem->max_score)
                                                        <span class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full font-semibold border border-cyan-200">
                                                            Max: {{ $quizItem->max_score }} pts
                                                        </span>
                                                        @endif
                                                        @if($quizItem->passing_score)
                                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full font-semibold border border-green-200">
                                                            Pass: {{ $quizItem->passing_score }} pts
                                                        </span>
                                                        @endif
                                                        @if($quizItem->timer_minutes)
                                                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full font-semibold border border-orange-200">
                                                            ⏱️ {{ $quizItem->timer_minutes }}min
                                                        </span>
                                                        @endif
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-2 mt-3">
                                                        <div class="text-center p-2 bg-pink-50 rounded-lg border border-pink-200">
                                                            <p class="text-xs text-gray-600 mb-0.5">Submissions</p>
                                                            <p class="text-lg font-black text-pink-600">{{ $quizItem->submissions_count }}</p>
                                                        </div>
                                                        <div class="text-center p-2 bg-cyan-50 rounded-lg border border-cyan-200">
                                                            <p class="text-xs text-gray-600 mb-0.5">Passed</p>
                                                            <p class="text-lg font-black text-cyan-600">{{ $quizItem->passed_count }}</p>
                                                        </div>
                                                        <div class="text-center p-2 bg-rose-50 rounded-lg border border-rose-200">
                                                            <p class="text-xs text-gray-600 mb-0.5">Failed</p>
                                                            <p class="text-lg font-black text-rose-600">{{ $quizItem->failed_count }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 text-center p-2 bg-purple-50 rounded-lg border border-purple-200">
                                                        <p class="text-xs text-gray-600 mb-0.5">Average Score</p>
                                                        <p class="text-lg font-black text-purple-600">{{ number_format($quizItem->average_score_percentage ?? 0, 1) }}%</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <button @click="showQuizContent = !showQuizContent" 
                                                        class="px-5 py-2.5 bg-gradient-to-r {{ $index % 2 == 0 ? 'from-cyan-300 to-teal-300' : 'from-pink-300 to-rose-300' }} text-gray-800 rounded-xl font-bold shadow-md hover:shadow-lg transform hover:scale-105 transition-all text-sm">
                                                    <span x-text="showQuizContent ? 'Hide Content' : 'View Content'"></span>
                                                </button>
                                                <button @click="showQuizDetails = !showQuizDetails" 
                                                        class="px-5 py-2.5 bg-gradient-to-r {{ $index % 2 == 0 ? 'from-pink-300 to-rose-300' : 'from-cyan-300 to-teal-300' }} text-gray-800 rounded-xl font-bold shadow-md hover:shadow-lg transform hover:scale-105 transition-all text-sm">
                                                    <span x-text="showQuizDetails ? 'Hide Details' : 'View Details'"></span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Quiz Content Section -->
                                        <div x-show="showQuizContent" 
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 transform -translate-y-4"
                                             x-transition:enter-end="opacity-100 transform translate-y-0"
                                             class="mt-6 pt-6 border-t-2 {{ $index % 2 == 0 ? 'border-cyan-200' : 'border-pink-200' }}">
                                            <div class="bg-gradient-to-r {{ $index % 2 == 0 ? 'from-cyan-50 to-teal-50' : 'from-pink-50 to-rose-50' }} rounded-2xl p-6 border-2 {{ $index % 2 == 0 ? 'border-cyan-200' : 'border-pink-200' }} shadow-lg">
                                                <h6 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                                    <span class="text-2xl">📝</span>
                                                    Quiz Content - {{ $quizItem->title }}
                                                </h6>
                                                
                                                @if($quizItem->questions->isEmpty())
                                                <div class="text-center py-12 bg-white/70 rounded-xl border border-gray-200">
                                                    <p class="text-gray-600 font-semibold">No questions added to this quiz yet.</p>
                                                </div>
                                                @else
                                                <div class="space-y-5">
                                                    @foreach($quizItem->questions->sortBy('question_order') as $qIndex => $question)
                                                    <div class="bg-white/90 rounded-xl p-5 border-2 border-pink-200 shadow-md hover:shadow-lg transition-all">
                                                        <div class="flex items-start gap-3 mb-3">
                                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-pink-300 to-rose-300 flex items-center justify-center text-white font-black text-base shadow-md flex-shrink-0">
                                                                {{ $qIndex + 1 }}
                                                            </div>
                                                            <div class="flex-1">
                                                                <h7 class="text-base font-bold text-gray-800">
                                                                    {{ $question->question_text }}
                                                                </h7>
                                                                @if($question->points)
                                                                <span class="inline-block mt-2 px-2 py-1 bg-cyan-100 text-cyan-700 rounded-lg text-xs font-semibold border border-cyan-200">
                                                                    {{ $question->points }} point{{ $question->points != 1 ? 's' : '' }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        @if($question->options->isEmpty())
                                                        <div class="ml-12 bg-gray-50 rounded-lg p-3 border border-gray-200">
                                                            <p class="text-gray-600 text-xs italic">No options available for this question.</p>
                                                        </div>
                                                        @else
                                                        <div class="ml-12 space-y-2">
                                                            <p class="text-xs font-semibold text-gray-700 mb-2">Options:</p>
                                                            @foreach($question->options->sortBy('option_order') as $optIndex => $option)
                                                            <div class="flex items-center gap-2 p-2.5 rounded-lg border-2 {{ $option->is_correct ? 'bg-green-50 border-green-300' : 'bg-white border-gray-200' }} hover:shadow-sm transition-all">
                                                                <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs shadow-sm {{ $option->is_correct ? 'bg-green-400 text-white' : 'bg-gray-300 text-gray-700' }}">
                                                                    {{ chr(65 + $optIndex) }}
                                                                </div>
                                                                <div class="flex-1">
                                                                    <p class="text-sm text-gray-800 font-medium">{{ $option->option_text }}</p>
                                                                </div>
                                                                @if($option->is_correct)
                                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold border border-green-300">
                                                                    ✓ Correct
                                                                </span>
                                                                @endif
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Quiz Details Section -->
                                        <div x-show="showQuizDetails" 
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 transform -translate-y-4"
                                             x-transition:enter-end="opacity-100 transform translate-y-0"
                                             class="mt-6 pt-6 border-t-2 {{ $index % 2 == 0 ? 'border-pink-200' : 'border-cyan-200' }}">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="bg-white/70 rounded-xl p-4 border border-pink-200">
                                                    <h7 class="font-bold text-gray-800 mb-2 text-sm">Quiz Information</h7>
                                                    <div class="space-y-1.5 text-xs mt-2">
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">Created:</span>
                                                            <span class="font-semibold">{{ $quizItem->created_at ? $quizItem->created_at->format('M d, Y') : 'N/A' }}</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">Max Score:</span>
                                                            <span class="font-semibold">{{ $quizItem->max_score ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">Passing Score:</span>
                                                            <span class="font-semibold text-cyan-600">{{ $quizItem->passing_score ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">Status:</span>
                                                            <span class="font-semibold {{ $quizItem->is_active ? 'text-green-600' : 'text-gray-600' }}">
                                                                {{ $quizItem->is_active ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-white/70 rounded-xl p-4 border border-cyan-200">
                                                    <h7 class="font-bold text-gray-800 mb-2 text-sm">Performance</h7>
                                                    <div class="space-y-1.5 text-xs mt-2">
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">Completion:</span>
                                                            <span class="font-semibold text-pink-600">
                                                                {{ $quizItem->total_students > 0 ? round(($quizItem->submissions_count / $quizItem->total_students) * 100) : 0 }}%
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">Pass Rate:</span>
                                                            <span class="font-semibold text-cyan-600">
                                                                {{ $quizItem->submissions_count > 0 ? round(($quizItem->passed_count / $quizItem->submissions_count) * 100) : 0 }}%
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">Total Students:</span>
                                                            <span class="font-semibold">{{ $quizItem->total_students }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Submission Status Pie Chart - Pink & Turquoise Theme
    const statusOptions = {
        series: [{{ $totalPassed }}, {{ $totalFailed }}],
        chart: {
            type: 'pie',
            height: 350
        },
        labels: ['Passed', 'Failed'],
        colors: ['#F9A8D4', '#67E8F9'],
        legend: {
            position: 'bottom'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    new ApexCharts(document.querySelector("#statusChart"), statusOptions).render();

    @if(!empty($classStats))
    // Class Comparison Horizontal Bar Chart - Pink & Turquoise Gradient
    const classComparisonOptions = {
        series: [{
            name: 'Average Score %',
            data: [@foreach($classStats as $class){{ number_format($class['average_percentage'], 1) }},@endforeach]
        }],
        chart: {
            type: 'bar',
            height: 400,
            toolbar: { show: false }
        },
        colors: ['#F9A8D4'],
        plotOptions: {
            bar: {
                borderRadius: 10,
                horizontal: true,
                barHeight: '60%',
                dataLabels: {
                    position: 'top',
                },
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val + "%";
            },
            offsetX: 0,
            style: {
                fontSize: '12px',
                colors: ['#fff']
            }
        },
        xaxis: {
            categories: [@foreach($classStats as $class)'{{ $class['class_name'] }}',@endforeach],
            max: 100
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'horizontal',
                shadeIntensity: 0.25,
                gradientToColors: ['#67E8F9'],
                inverseColors: false,
                opacityFrom: 0.85,
                opacityTo: 0.85,
                stops: [0, 100]
            }
        }
    };
    new ApexCharts(document.querySelector("#classComparisonChart"), classComparisonOptions).render();
    @endif
});
</script>
@endpush
@endsection
