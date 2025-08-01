window.addEventListener('DOMContentLoaded', () => {
  console.log('DOM carregado, iniciando timer para esconder alertas');
  setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length === 0) {
      console.log('Nenhum alerta encontrado');
    }
    alerts.forEach(alert => {
      alert.style.display = 'none';
      console.log('Alerta escondido:', alert);
    });
  }, 5000);
});
