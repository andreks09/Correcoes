<!-- Gráfico de encerramentos diarios -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Área do gráfico (pode estar dentro de um card, por exemplo) -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Encerramento Diário (esta semana)</h3>
  </div>
  <div class="card-body">
    <canvas id="graficodeBarras1" style="min-height: 250px; height: 250px;"></canvas>
  </div>
</div>

<!-- Script para renderizar o gráfico -->
<script>
  const ctx1 = document.getElementById('graficodeBarras1').getContext('2d');
  const graficodeBarras1 = new Chart(ctx1, {
    type: 'bar',
    data: {
      labels: ['domingo','segunda','terça','quarta','quinta','sexta','sabado'],
      datasets: [{
        label: 'Tickets Encerrados',
        data: [0,0,0,0,0,8,0,25],
        backgroundColor: 'rgba(60,141,188,0.9)', // azul padrão do AdminLTE
        borderColor: 'rgba(60,141,188,1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      escalas: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

<!-- Gráfico de encerramentos semanais -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Área do gráfico (pode estar dentro de um card, por exemplo) -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Encerramentos Semanais (este mês)</h3>
  </div>
  <div class="card-body">
    <canvas id="graficodeBarras2" style="min-height: 250px; height: 250px;"></canvas>
  </div>
</div>

<!-- Script para renderizar o gráfico -->
<script>
  const ctx2 = document.getElementById('graficodeBarras2').getContext('2d');
  const graficodeBarras2 = new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: ['1ªsemana','2ªsemana','3ªsemana','4ªsemana','5ªsemana'],
      datasets: [{
        label: 'Tickets Encerrados',
        data: [23,65,0,0,0,0,25],
        backgroundColor: 'rgba(60,141,188,0.9)', // azul padrão do AdminLTE
        borderColor: 'rgba(60,141,188,1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>   

<!-- Gráfico de encerramentos mensais -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Área do gráfico (pode estar dentro de um card, por exemplo) -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Encerramentos Mensais (este ano)</h3>
  </div>
  <div class="card-body">
    <canvas id="graficodeBarras3" style="min-height: 250px; height: 250px;"></canvas>
  </div>
</div>

<!-- Script para renderizar o gráfico -->
<script>
  const ctx3 = document.getElementById('graficodeBarras3').getContext('2d');
  const graficodeBarras3 = new Chart(ctx3, {
    type: 'bar',
    data: {
      labels: ['janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'],
      datasets: [{
        label: 'Tickets Encerrados',
        data: [0,88,0,0,0,0,0,0,0,0,0,0],
        backgroundColor: 'rgba(60,141,188,0.9)', // azul padrão do AdminLTE
        borderColor: 'rgba(60,141,188,1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
<!-- Gráfico de encerramentos anuais -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Área do gráfico (pode estar dentro de um card, por exemplo) -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Encerramentos anuais (últimos dois anos e próximos dois anos)</h3>
  </div>
  <div class="card-body">
    <canvas id="graficodeBarras4" style="min-height: 250px; height: 250px;"></canvas>
  </div>
</div>

<!-- Script para renderizar o gráfico -->
<script>
  const ctx4 = document.getElementById('graficodeBarras4').getContext('2d');
  const graficodeBarras4 = new Chart(ctx4, {
    type: 'bar',
    data: {
      labels: ['2023','2024','2025','2026','2027'],
      datasets: [{
        label: 'Tickets Encerrados',
        data: [0,0,88,0,0,0,0,0,0,0,0,0],
        backgroundColor: 'rgba(60,141,188,0.9)', // azul padrão do AdminLTE
        borderColor: 'rgba(60,141,188,1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>