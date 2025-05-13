@extends('layouts.main')

@section('title', 'Dashboard')

@section('contentAdmin')

<link rel="stylesheet" href="{{ asset('css/financeiro.css') }}">

<div class="main p-3">
    <div class="backdrop">
        <div class="comeco">
            <div class="title">
                <p>Controle de Informações</p>
                <h2>|</h2>
                <h4>Dados atualizados em:</h4>
            </div>
        </div>

        <div class="tudo">
            <div class="primeiracoluna">
                <div class="nome"><p class="nome">Serviços mais procurados no mês de:<p class="agosto">Agosto</p></p></div>
                <div class="fazL">
                    <canvas id="frankocean" width="500" height="250"></canvas>
                </div>

                <div class="mediaval">
                    <div>
                        <div class="nome"><p class="nome">Média de valores por serviços</p></div>
                    </div> 
                    <div class="fazL">
                     <canvas id="celine" width="500" height="250"></canvas>
                    </div>
                </div>

            </div>

            <div class="segundacoluna">

                <div class="chartusers">
                    <div class="nome"><p class="nome">Usuários Premium</p></div>
                    <canvas id="cachorro" width="350" height="300"></canvas>
                </div>
                
             
                <div class="crescimentousers">
                <div class="nome"><p class="nome">Crescimento de usuários</p></div>
                    <canvas id="vsfd" width="500" height="250"></canvas>
                </div>
            </div>

            <div class="terceiracoluna">
                <div class="despesareceita">
                <div class="nome"><p class="nome">Receita e Despesas</p></div>
                    <canvas id="goodnight" width="500" height="350"></canvas>
                </div>

                <div class="soliconclu">
                    <div class="metodosdepag"><div class="palavrinha">Serviços solicitados</div></div>
                    <div class="nseiainda"><div class="palavrinha">Serviços concluidos</div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclua o script do Chart.js -->
<div class="chart">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Primeiro gráfico (frankocean)
            const ctx1 = document.getElementById('frankocean').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Média de valores dos serviços',
                        data: [65, 59, 80, 81, 56, 55, 40],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)',
                            'rgb(201, 203, 207)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Segundo gráfico (celine)
            const ctx4 = document.getElementById('celine').getContext('2d');
            new Chart(ctx4, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Média de valores dos serviços',
                        data: [65, 59, 80, 81, 56, 55, 40],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)',
                            'rgb(201, 203, 207)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Gráfico de Pizza (cachorro)
            const ctx5 = document.getElementById('cachorro').getContext('2d');
            new Chart(ctx5, {
                type: 'pie',
                data: {
                    labels: ['Blue', 'Yellow'],
                    datasets: [{
                        label: 'My First Dataset',
                        data: [65, 35],
                        backgroundColor: [
                            '#0c95f7',
                            '#f2f542',
                            
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de linha (vsfd)
            const ctx2 = document.getElementById('vsfd').getContext('2d');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Crescimento de Usuários',
                        data: [0, 10, 20, 30, 40, 50],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });

            // Terceiro gráfico (goodnight)
            const ctx3 = document.getElementById('goodnight').getContext('2d');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: ['Receita', 'Despesas'],
                    datasets: [{
                        label: 'Valores',
                        data: [12, 19],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</div>

@endsection
