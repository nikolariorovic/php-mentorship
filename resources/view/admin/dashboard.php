<?php include __DIR__ . '/navigation.php'; ?>
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
        .filters-bar { display: flex; gap: 16px; align-items: center; margin-bottom: 24px; flex-wrap: wrap; }
        .filters-bar label { font-weight: 500; color: #444; }
        .filters-bar input, .filters-bar select { padding: 7px 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 1rem; }
        .filters-bar button { padding: 7px 18px; border: none; border-radius: 5px; background: #007bff; color: #fff; font-weight: 500; cursor: pointer; transition: background 0.2s; }
        .filters-bar button:hover { background: #0056b3; }
        .top-mentors-table { width: 100%; border-collapse: collapse; background: #f8fafc; border-radius: 10px; overflow: hidden; }
        .top-mentors-table th, .top-mentors-table td { padding: 14px 10px; text-align: left; }
        .top-mentors-table th { background: #e9ecef; color: #333; font-weight: 700; }
        .top-mentors-table tr:nth-child(even) { background: #f4f6f8; }
        .top-mentors-table td { color: #444; }
        .star { color: #ffc107; }
        @media (max-width: 800px) {
            .dashboard-cards { flex-direction: column; gap: 16px; }
            .filters-bar { flex-direction: column; align-items: stretch; gap: 10px; }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-title"><i class="fas fa-chart-line"></i> Dashboard</div>
        <div class="dashboard-cards">
            <div class="card">
                <div class="card-label">Ukupna zarada</div>
                <div class="card-value" id="totalEarnings">
                    <?php
                        $totalProfit = isset($profit[0]['total_profit']) ? (float)$profit[0]['total_profit'] : 0;
                        echo number_format($totalProfit, 2, ',', '.') . ' €';
                    ?>
                </div>
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
                    <?php if (!empty($mentors)): ?>
                        <?php foreach ($mentors as $mentor): ?>
                            <tr>
                                <td><?= htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']) ?></td>
                                <td><?= (int)$mentor['session_count'] ?></td>
                                <td>
                                    <?= number_format((float)$mentor['avg_rating'], 2, ',', '.') ?>
                                    <span class="star"><i class="fas fa-star"></i></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="text-align:center;color:#888;">Nema rezultata za ovaj mesec.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="/public/js/admin-navigation.js"></script>
    <script>
        // PHP šalje appointments podatke u JS
        const appointments = <?= json_encode($appointments) ?>;

        // Priprema podataka za chart
        const sessionsPerMonth = {
            labels: appointments.map(a => a.yearMonth),
            data: appointments.map(a => Number(a.session_count))
        };

        // Pronađi trenutni mesec (format YYYY-MM)
        const now = new Date();
        const currentYearMonth = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
        // Pronađi broj sesija za trenutni mesec
        const currentMonthSessions = (() => {
            const found = appointments.find(a => a.yearMonth === currentYearMonth);
            return found ? Number(found.session_count) : 0;
        })();

        // Ispis u dashboard
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
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });

        // Ostatak JS za filtere mentora ostaje isti...
    </script>
</body>
</html> 