<?php include __DIR__ . '/../admin/navigation.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/public/css/admin-navigation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f8; margin: 0; padding-top: 70px; }
        .dashboard-container { max-width: 1100px; margin: 40px auto; padding: 24px; background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07);}
        .dashboard-title { font-size: 2rem; font-weight: bold; margin-bottom: 32px; color: #333; }
        .dashboard-cards { display: flex; gap: 24px; margin-bottom: 36px; }
        .card { flex: 1; background: #f8fafc; border-radius: 10px; padding: 28px 20px; box-shadow: 0 1px 6px rgba(0,0,0,0.04); display: flex; flex-direction: column; align-items: center; }
        .card .card-label { font-size: 1.1rem; color: #666; margin-bottom: 10px; }
        .card .card-value { font-size: 2.2rem; font-weight: bold; color: #007bff; }
        .chart-section { margin-bottom: 40px; }
        .section-title { font-size: 1.2rem; font-weight: 600; color: #444; margin-bottom: 18px; }
        .top-mentors-table { width: 100%; border-collapse: collapse; background: #f8fafc; border-radius: 10px; overflow: hidden; }
        .top-mentors-table th, .top-mentors-table td { padding: 14px 10px; text-align: left; }
        .top-mentors-table th { background: #e9ecef; color: #333; font-weight: 700; }
        .top-mentors-table tr:nth-child(even) { background: #f4f6f8; }
        .top-mentors-table td { color: #444; }
        .star { color: #ffc107; }
        @media (max-width: 800px) {
            .dashboard-cards { flex-direction: column; gap: 16px; }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-title"><i class="fas fa-chart-line"></i> Dashboard</div>
        <div class="dashboard-cards">
            <div class="card">
                <div class="card-label">Ukupna zarada</div>
                <div class="card-value" id="totalEarnings">0 €</div>
            </div>
            <div class="card">
                <div class="card-label">Broj sesija ovog meseca</div>
                <div class="card-value" id="currentMonthSessions">0</div>
            </div>
        </div>
        <div class="chart-section">
            <div class="section-title"><i class="fas fa-calendar-alt"></i> Broj sesija po mesecu</div>
            <canvas id="sessionsChart" height="80"></canvas>
        </div>
        <div>
            <div class="section-title"><i class="fas fa-user-tie"></i> Najaktivniji i najbolje ocenjeni mentori</div>
            <table class="top-mentors-table">
                <thead>
                    <tr>
                        <th>Mentor</th>
                        <th>Broj sesija</th>
                        <th>Prosečna ocena</th>
                    </tr>
                </thead>
                <tbody id="mentorsTableBody">
                    <!-- Popunjava JS -->
                </tbody>
            </table>
        </div>
    </div>
    <script src="/public/js/admin-navigation.js"></script>
    <script>
        // --- OVO OČEKUJEŠ OD BACKENDA ---
        // sessionsPerMonth: { labels: [...], data: [...] }
        // totalEarnings: broj
        // topMentors: [{ name, sessions, avg_rating }]
        // currentMonthSessions: broj

        // DEMO PODACI (ZAMENIĆEŠ SA BACKENDOM)
        const sessionsPerMonth = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'Avg', 'Sep', 'Okt', 'Nov', 'Dec'],
            data: [12, 18, 25, 30, 22, 28, 35, 40, 32, 27, 20, 15]
        };
        const totalEarnings = 12345.67;
        const currentMonthSessions = 40;
        const topMentors = [
            { name: 'Ana Anić', sessions: 42, avg_rating: 4.9 },
            { name: 'Marko Marković', sessions: 38, avg_rating: 4.8 },
            { name: 'Jovana Jović', sessions: 35, avg_rating: 4.7 }
        ];

        // --- KRAJ DEMO PODATAKA ---

        // Zarada
        document.getElementById('totalEarnings').textContent = totalEarnings.toLocaleString('sr-RS', { style: 'currency', currency: 'EUR', minimumFractionDigits: 2 });
        // Broj sesija ovog meseca
        document.getElementById('currentMonthSessions').textContent = currentMonthSessions;

        // Chart.js za broj sesija po mesecu
        new Chart(document.getElementById('sessionsChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: sessionsPerMonth.labels,
                datasets: [{
                    label: 'Broj sesija',
                    data: sessionsPerMonth.data,
                    backgroundColor: '#007bff88',
                    borderColor: '#007bff',
                    borderWidth: 2,
                    borderRadius: 6,
                    maxBarThickness: 36
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 5 } }
                }
            }
        });

        // Najbolji mentori
        const mentorsTableBody = document.getElementById('mentorsTableBody');
        mentorsTableBody.innerHTML = topMentors.map(m => `
            <tr>
                <td>${m.name}</td>
                <td>${m.sessions}</td>
                <td>
                    ${m.avg_rating.toFixed(2)}
                    <span class="star"><i class="fas fa-star"></i></span>
                </td>
            </tr>
        `).join('');
    </script>
</body>
</html> 