
'use strict';

const handleHover = (evt, item, legend) => {
   legend.chart.data.datasets[0].backgroundColor.forEach((color, index, colors) => {
      colors[index] = index === item.index || color.length === 9 ? color : color + '4D';
   });
   legend.chart.update();
}

const handleLeave = (evt, item, legend) => {
   legend.chart.data.datasets[0].backgroundColor.forEach((color, index, colors) => {
      colors[index] = color.length === 9 ? color.slice(0, -2) : color;
   });
   legend.chart.update();
}

// datos del usuario graficados
const dataUserChart = async (res) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let canvas_div = document.getElementById('users-chart-div');
            document.getElementById('count-card-users').innerHTML = res.count;
            canvas_div.innerHTML = '';
            canvas_div.innerHTML += /* html */ `<canvas id="users-chart" width="300" height="150" aria-label="USER ARIA" role="img"></canvas>`;
            // grafico
            let canvas = document.getElementById('users-chart');
            const activateUsersData = res.data_user.filter((user) => parseInt(user.esta_cod) === 1);
            const inactiveUsersData = res.data_user.filter((user) => parseInt(user.esta_cod) === 2);
            const deleteUsersData = res.data_user.filter((user) => parseInt(user.esta_cod) === 3);
            const data = {
               labels: [
                  `${activateUsersData.length} Activos`,
                  `${inactiveUsersData.length} Inactivos`,
                  `${deleteUsersData.length} Eliminados`,
               ],
               datasets: [{
                  label: '# Cantidad',
                  data: [
                     activateUsersData.length,
                     inactiveUsersData.length,
                     deleteUsersData.length
                  ],
                  borderWidth: 1,
                  backgroundColor: ['#00A60F', '#FFEB00', '#0055FF']
               }],
            };
            const config = {
               type: 'pie',
               data: data,
               options: {
                  plugins: {
                     legend: {
                        onHover: handleHover,
                        onLeave: handleLeave
                     }
                  },
                  onClick: (event, legendItem, legend) => {
                     if (legendItem[0]) {
                        const i = legendItem[0].index;
                        const label = legend.data.labels[i];
                        if (label === `${activateUsersData.length} Activos`) {
                           dataUserStatus(activateUsersData, res.dominio_usuario);
                        } else if (label === `${inactiveUsersData.length} Inactivos`) {
                           dataUserStatus(inactiveUsersData, res.dominio_usuario);
                        } else if (label === `${deleteUsersData.length} Eliminados`) {
                           dataUserStatus(deleteUsersData, res.dominio_usuario);
                        }
                     }
                  },
                  responsive: true,
                  maintainAspectRatio: false,
               }
            };
            new Chart(canvas, config);
            resolve(true);
         }, 200);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      toast.fire({
         icon: 'error',
         title: 'Error obteniendo alertas'
      });
      showToastError('Error obteniendo operadores');
   }
}

// data operador
const dataOperatorChart = (res) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let canvas_div = document.getElementById('operators-chart-div');
            document.getElementById('count-card-operators').innerHTML = res.total_oper;
            canvas_div.innerHTML = '';
            canvas_div.innerHTML += /* html */ `<canvas id="operators-chart" width="300" height="150" aria-label="OPERATOR ARIA" role="img"></canvas>`;
            // grafico
            let canvas = document.getElementById('operators-chart');
            const activateOperatorsData = res.data_oper.filter((operator) => parseInt(operator.esta_cod) === 1);
            const inactiveOperatorsData = res.data_oper.filter((operator) => parseInt(operator.esta_cod) === 2);
            const deleteOperatorsData = res.data_oper.filter((operator) => parseInt(operator.esta_cod) === 3);
            const data = {
               labels: [
                  `${activateOperatorsData.length} Activos`,
                  `${inactiveOperatorsData.length} Inactivos`,
                  `${deleteOperatorsData.length} Eliminados`,
               ],
               datasets: [{
                  label: '# Cantidad',
                  data: [
                     activateOperatorsData.length,
                     inactiveOperatorsData.length,
                     deleteOperatorsData.length
                  ],
                  borderWidth: 1,
                  backgroundColor: ['#00A60F', '#FFEB00', '#0055FF']
               }],
            };
            const config = {
               type: 'pie',
               data: data,
               options: {
                  plugins: {
                     legend: {
                        onHover: handleHover,
                        onLeave: handleLeave
                     }
                  },
                  onClick: (event, legendItem, legend) => {
                     if (legendItem[0]) {
                        const i = legendItem[0].index;
                        const label = legend.data.labels[i];
                        if (label === `${activateOperatorsData.length} Activos`) {
                           dataOperatorStatus(activateOperatorsData, res.dominio_usuario);
                        } else if (label === `${inactiveOperatorsData.length} Inactivos`) {
                           dataOperatorStatus(inactiveOperatorsData, res.dominio_usuario);
                        } else if (label === `${deleteOperatorsData.length} Eliminados`) {
                           dataOperatorStatus(deleteOperatorsData, res.dominio_usuario);
                        }
                     }
                  },
                  responsive: true,
                  maintainAspectRatio: false,
               }
            };
            new Chart(canvas, config);
            resolve(true);
         }, 200);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError('Error obteniendo operadores');
   }
}

// obtiene cantidad de alertas
const countAlertMonth = (data) => {
   try {
      let data_month = { 1: [], 2: [], 3: [], 4: [], 5: [], 6: [], 7: [], 8: [], 9: [], 10: [], 11: [], 12: [] }
      for (let i = 0; i < data.data_alert.length; i++) {
         const element = data.data_alert[i];
         const createAt = new Date(element.fecha_creacion);
         const month = createAt.getMonth() + 1;
         data_month[month].push(element);
      }
      return [
         data_month[1].length,
         data_month[2].length,
         data_month[3].length,
         data_month[4].length,
         data_month[5].length,
         data_month[6].length,
         data_month[7].length,
         data_month[8].length,
         data_month[9].length,
         data_month[10].length,
         data_month[11].length,
         data_month[12].length,
      ];
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError('Error obteniendo operadores');
   }
}

// chart alertas
const cardDataAlertsChart = async (res) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let canvas_div = document.getElementById('alerts-chart-div');
            canvas_div.innerHTML = '';
            canvas_div.innerHTML = /* html */ `<canvas id="alerts-chart" width="auto" height="250" aria-label="Alerts AIRA" role="img"></canvas>`;
            let canvas = document.getElementById('alerts-chart');
            const labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            const data_month = countAlertMonth(res);
            const data = {
               labels: labels,
               datasets: [{
                  label: '# Cantidad',
                  data: data_month,
                  backgroundColor: [
                     'rgba(255, 99, 132, 0.2)',
                     'rgba(255, 159, 64, 0.2)',
                     'rgba(227, 0, 242, 0.2)',
                     'rgba(75, 192, 192, 0.2)',
                     'rgba(54, 162, 235, 0.2)',
                     'rgba(27, 196, 0, 0.2)',
                     'rgba(6, 135, 224, 0.2)',
                     'rgba(210, 102, 255, 0.2)',
                     'rgba(204, 251, 95, 0.2)',
                     'rgba(178, 0, 255, 0.2)',
                     'rgba(255, 0, 85, 0.2)',
                     'rgba(153, 102, 255, 0.2)',
                  ],
                  borderColor: [
                     'rgb(255, 99, 132)',
                     'rgb(255, 159, 64)',
                     'rgb(227, 0, 242)',
                     'rgb(75, 192, 192)',
                     'rgb(54, 162, 235)',
                     'rgb(27, 196, 0)',
                     'rgb(6, 135, 224)',
                     'rgb(210, 162, 235)',
                     'rgb(204, 251, 95)',
                     'rgb(178, 0, 255)',
                     'rgb(255, 0, 85)',
                     'rgb(54, 162, 235)',
                  ],
                  borderWidth: 1,
                  elements: {
                     bar: {
                        borderWidth: 1,
                     }
                  },
               }]
            };
            const config = {
               type: 'bar',
               data: data,
               options: {
                  y: {
                     beginAtZero: true
                  },
                  responsive: true,
                  maintainAspectRatio: false,
                  elements: {
                     bar: {
                        borderWidth: 1,
                     }
                  },
                  plugins: {
                     legend: {
                        display: false
                     },
                  },
                  onClick: (event, legendItem, legend) => {
                     if (legendItem.length === 0) {
                        return false;
                     }
                     infoAlertsDiv(res.data_alert, (legendItem[0].index + 1), legend.data.labels[legendItem[0].index]);
                  },
               },
            }
            new Chart(canvas, config);
            resolve(true);
         }, 200)
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError('Error obteniendo alertas');
   }
}

// alerta de usuarios graficado
const dataAlertChart = (res, du, dataf) => {
   try {
      setTimeout(() => {
         let canvas_div = document.getElementById('ranking-alerts-chart-div');
         canvas_div.innerHTML = '';
         canvas_div.innerHTML = /* html */ `<canvas id="ranking-alerts-chart" width="300" height="150" aria-label="Alerts AIRA" role="img"></canvas>`;
         let canvas = document.getElementById('ranking-alerts-chart');
         const labels = [];
         const countAlert = [];
         let i = 0;
         res.sort((a, b) => a.count - b.count).reverse().forEach((user, index) => {
            if (index < 5) {
               labels.push(`${user.cloud_username}@${du}`);
            }
            i++;
         });
         res.sort((a, b) => a.count - b.count).reverse().forEach((user, index) => {
            if (index < 5) {
               countAlert.push(user.count);
            }
         });
         const data = {
            labels: labels,
            datasets: [{
               label: '# Cantidad',
               data: countAlert,
               backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(90, 205, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(54, 162, 235, 0.2)'
               ],
               borderColor: [
                  'rgb(255, 99, 132)',
                  'rgb(255, 159, 64)',
                  'rgb(255, 205, 86)',
                  'rgb(90, 192, 192)',
                  'rgb(54, 162, 235)'
               ],
               borderWidth: 1
            }]
         };
         const config = {
            type: 'bar',
            data: data,
            options: {
               indexAxis: 'y',
               // Elements options apply to all of the options unless overridden in a dataset
               // In this case, we are setting the border of each horizontal bar to be 2px wide
               elements: {
                  bar: {
                     borderWidth: 1,
                  }
               },
               plugins: {
                  legend: {
                     position: 'right',
                     display: false
                  },
                  title: {
                     display: true,
                     text: `Ranking - Primeros ${i > 5 ? '5' : i}:`,
                     align: 'start'
                  }
               },
               onClick: (event, legendItem, legend) => {
                  if (legendItem[0]) {
                     const i = legendItem[0].index;
                     const label = legend.data.labels[i];
                     dataAlertsUser(dataf, label);
                  }
               },
               responsive: true,
               maintainAspectRatio: false,
            }
         }
         new Chart(canvas, config);
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError('Error obteniendo alertas');
   }
}

// data alert chart
const dataAlertChartAll = (res, du, dataf) => {
   try {
      setTimeout(() => {
         let canvas_div = document.getElementById('alerts-all-chart-div');
         canvas_div.innerHTML = '';
         canvas_div.innerHTML = /* html */ `<canvas id="alerts-all-chart" width="400" height="200" aria-label="Alerts AIRA" role="img"></canvas>`;
         let canvas = document.getElementById('alerts-all-chart');
         const labels = [];
         const countAlert = [];
         res.sort((a, b) => a.count - b.count).reverse().forEach((user, index) => {
            labels.push(`${user.cloud_username}@${du}`);
         });
         res.sort((a, b) => a.count - b.count).reverse().forEach((user, index) => {
            countAlert.push(user.count);
         });
         const data = {
            labels: labels,
            datasets: [{
               label: '# Cantidad',
               data: countAlert,
               backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(90, 205, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(54, 162, 235, 0.2)'
               ],
               borderColor: [
                  'rgb(255, 99, 132)',
                  'rgb(255, 159, 64)',
                  'rgb(255, 205, 86)',
                  'rgb(90, 192, 192)',
                  'rgb(54, 162, 235)'
               ],
               borderWidth: 1
            }]
         };
         const config = {
            type: 'bar',
            data: data,
            options: {
               indexAxis: 'y',
               // Elements options apply to all of the options unless overridden in a dataset
               // In this case, we are setting the border of each horizontal bar to be 2px wide
               elements: {
                  bar: {
                     borderWidth: 1,
                  }
               },
               plugins: {
                  legend: {
                     position: 'right',
                     display: false
                  },
                  title: {
                     display: true,
                     text: 'Ranking:',
                     align: 'start'
                  }
               },
               onClick: (event, legendItem, legend) => {
                  if (legendItem[0]) {
                     const i = legendItem[0].index;
                     const label = legend.data.labels[i];
                     dataAlertsUser(dataf, label);
                  }
               },
               responsive: true,
               maintainAspectRatio: false,
            }
         };
         new Chart(canvas, config);
         spiner_menu_close(`spinner-filter-alert-all`);
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError('Error obteniendo alertas');
   }
}