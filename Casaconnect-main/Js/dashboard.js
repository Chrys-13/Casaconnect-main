// Mock data for demonstration purposes
const recentPurchasesData = [10, 20, 15, 5, 12]; // Example data for recent purchases
const recentSignupsData = [5, 10, 8, 3, 7]; // Example data for recent sign-ups
const topBrokersData = [25, 18, 22, 30, 17]; // Example data for top brokers
const citiesData = [12, 19, 3, 5, 2]; // Example data for cities
const citiesLabels = ['Rockstar Hill', 'Brentwood', 'Vespucci Beach', 'Ashingale', 'Glandston']; // Labels for pie chart

// Initialize Chart.js charts
function createBarChart(ctx, label, data) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
            datasets: [{
                label: label,
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
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
}

function createPieChart(ctx, labels, data) {
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    createBarChart(document.getElementById('purchasesChart'), 'Recent Purchases', recentPurchasesData);
    createBarChart(document.getElementById('signupsChart'), 'Recent Sign-Ups', recentSignupsData);
    createBarChart(document.getElementById('brokersChart'), 'Top Brokers', topBrokersData);
    createPieChart(document.getElementById('citiesChart'), citiesLabels, citiesData);
});
