@props(['class' => '', 'id' => null, 'type', 'labels', 'data', 'titulo'])

<div>
    <canvas id="chart-{{ $id }}" class="{{ $class }}"></canvas>
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('chart-{{ $id }}').getContext('2d');

            const colorPalette = [
                'rgba(75, 192, 192, 1)', 
                'rgba(153, 102, 255, 1)', 
                'rgba(255, 159, 64, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(231, 76, 60, 1)',
                'rgba(241, 196, 15, 1)',
                'rgba(46, 204, 113, 1)',
                'rgba(52, 152, 219, 1)',
                'rgba(155, 89, 182, 1)',
                'rgba(142, 68, 173, 1)',
                'rgba(26, 188, 156, 1)',
                'rgba(52, 152, 219, 1)',
                'rgba(41, 128, 185, 1)',
                'rgba(211, 84, 0, 1)',
            ];

            function getRandomColors() {
                let colors = [];
                let colorPaletteCopy = [...colorPalette]; 
                @json($data).forEach(() => {
                    let randomIndex = Math.floor(Math.random() * colorPaletteCopy.length);
                    colors.push(colorPaletteCopy[randomIndex]);
                    colorPaletteCopy.splice(randomIndex, 1); 
                });

                return colors;
            }

            const borderColors = getRandomColors();
            const backgroundColors = borderColors.map(color => color.replace('1)', '0.2)'));  

            const myChart = new Chart(ctx, {
                type: '{{ $type }}',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: '{{ $titulo }}',
                        data: @json($data),
                        borderColor: borderColors,
                        backgroundColor: backgroundColors,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
