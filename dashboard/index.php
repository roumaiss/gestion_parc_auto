<?php
require_once "../config/db.php";
include "header.php";
include "sidebar.php";

// ── KPI counts ───────────────────────────────────────────────────────
$vehicles        = $pdo->query("SELECT COUNT(*) FROM vehicule")->fetchColumn();
$drivers         = $pdo->query("SELECT COUNT(*) FROM employe")->fetchColumn();
$missions_total  = $pdo->query("SELECT COUNT(*) FROM mission")->fetchColumn();
$active_missions = $pdo->query("SELECT COUNT(*) FROM mission WHERE date_retour IS NULL OR date_retour = '0000-00-00'")->fetchColumn();
$maintenance     = $pdo->query("SELECT COUNT(*) FROM entretien")->fetchColumn();
$incidents       = $pdo->query("SELECT COUNT(*) FROM incident")->fetchColumn();

// ── Vehicle states (pie chart) ───────────────────────────────────────
$etats_raw = $pdo->query("SELECT etat, COUNT(*) as total FROM vehicule GROUP BY etat")->fetchAll();
$etat_labels = array_column($etats_raw, 'etat');
$etat_values = array_column($etats_raw, 'total');

// ── Missions per month (bar chart, last 6 months) ────────────────────
$missions_per_month = $pdo->query("
    SELECT DATE_FORMAT(date_sortie,'%b %Y') as mois,
           YEAR(date_sortie) as yr,
           MONTH(date_sortie) as mo,
           COUNT(*) as total
    FROM mission
    WHERE date_sortie >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY yr, mo, mois
    ORDER BY yr ASC, mo ASC
")->fetchAll();
$month_labels = array_column($missions_per_month, 'mois');
$month_values = array_column($missions_per_month, 'total');

// ── Carburant spend per month (line chart) ───────────────────────────
$carburant_months = $pdo->query("
    SELECT DATE_FORMAT(c.date_delivrance,'%b %Y') as mois,
           YEAR(c.date_delivrance) as yr,
           MONTH(c.date_delivrance) as mo,
           SUM(cd.montant) as total
    FROM carburant c
    JOIN carburant_detail cd ON c.num_carburant = cd.num_carburant
    WHERE c.date_delivrance >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY yr, mo, mois
    ORDER BY yr ASC, mo ASC
")->fetchAll();
$carb_labels = array_column($carburant_months, 'mois');
$carb_values = array_column($carburant_months, 'total');

// ── Missions by status (doughnut) ────────────────────────────────────
$missions_en_cours  = $pdo->query("SELECT COUNT(*) FROM mission WHERE date_retour IS NULL OR date_retour = '0000-00-00'")->fetchColumn();
$missions_terminees = $pdo->query("SELECT COUNT(*) FROM mission WHERE date_retour IS NOT NULL AND date_retour != '0000-00-00'")->fetchColumn();

// ── Recent missions ──────────────────────────────────────────────────
$recent_missions = $pdo->query("
    SELECT m.num_mission, m.date_sortie, m.date_retour, v.marque, v.matricule, e.nom, e.prenom
    FROM mission m
    JOIN vehicule v ON m.id_vehicule = v.id_vehicule
    JOIN employe  e ON m.id_employe  = e.id_employe
    ORDER BY m.num_mission DESC
    LIMIT 5
")->fetchAll();

// ── Recent incidents ─────────────────────────────────────────────────
$recent_incidents = $pdo->query("
    SELECT i.num_incident, i.gravite, i.date_incident, v.marque, v.matricule
    FROM incident i
    LEFT JOIN mission m ON i.num_mission = m.num_mission
    LEFT JOIN vehicule v ON m.id_vehicule = v.id_vehicule
    ORDER BY i.num_incident DESC
    LIMIT 5
")->fetchAll();
?>

<div class="content">

    <!-- Welcome -->
    <div style="margin-bottom:28px;">
        <h1 style="font-size:22px;font-weight:700;color:var(--green-dark);">
            Bienvenue, <?= htmlspecialchars($username) ?> 👋
        </h1>
        <p style="font-size:14px;color:#888;">Tableau de bord — vue d'ensemble du parc automobile</p>
    </div>

    <!-- ── KPI CARDS ──────────────────────────────────────────────── -->
    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-icon" style="background:#e8f5e9;color:#2e8b57;">
                <i class="fas fa-car"></i>
            </div>
            <div class="kpi-body">
                <div class="kpi-value"><?= $vehicles ?></div>
                <div class="kpi-label">Véhicules</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background:#e3f2fd;color:#1565c0;">
                <i class="fas fa-id-card"></i>
            </div>
            <div class="kpi-body">
                <div class="kpi-value"><?= $drivers ?></div>
                <div class="kpi-label">Chauffeurs</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background:#fff8e1;color:#f9a825;">
                <i class="fas fa-route"></i>
            </div>
            <div class="kpi-body">
                <div class="kpi-value"><?= $active_missions ?></div>
                <div class="kpi-label">Missions en cours</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background:#e8f5e9;color:#2e8b57;">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="kpi-body">
                <div class="kpi-value"><?= $missions_total ?></div>
                <div class="kpi-label">Missions totales</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background:#fce4ec;color:#c62828;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="kpi-body">
                <div class="kpi-value"><?= $incidents ?></div>
                <div class="kpi-label">Incidents</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background:#f3e5f5;color:#6a1b9a;">
                <i class="fas fa-tools"></i>
            </div>
            <div class="kpi-body">
                <div class="kpi-value"><?= $maintenance ?></div>
                <div class="kpi-label">Maintenances</div>
            </div>
        </div>

    </div>

    <!-- ── CHARTS ROW 1 ──────────────────────────────────────────── -->
    <div class="chart-grid">

        <div class="chart-card">
            <div class="chart-title">Missions par mois</div>
            <canvas id="missionsChart" height="220"></canvas>
        </div>

        <div class="chart-card">
            <div class="chart-title">État des véhicules</div>
            <canvas id="etatsChart" height="220"></canvas>
        </div>

    </div>

    <!-- ── CHARTS ROW 2 ──────────────────────────────────────────── -->
    <div class="chart-grid">

        <div class="chart-card">
            <div class="chart-title">Dépenses carburant (DZD)</div>
            <canvas id="carburantChart" height="220"></canvas>
        </div>

        <div class="chart-card">
            <div class="chart-title">Missions par statut</div>
            <canvas id="gravitesChart" height="220"></canvas>
        </div>

    </div>

    <!-- ── RECENT TABLES ─────────────────────────────────────────── -->
    <div class="chart-grid">

        <div class="chart-card">
            <div class="chart-title">Dernières missions</div>
            <table>
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Chauffeur</th>
                        <th>Départ</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_missions as $m): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($m['marque']) ?></strong><br><small><?= htmlspecialchars($m['matricule']) ?></small></td>
                        <td><?= htmlspecialchars($m['nom']) ?> <?= htmlspecialchars($m['prenom']) ?></td>
                        <td><?= $m['date_sortie'] ?></td>
                        <td>
                            <?php if(empty($m['date_retour']) || $m['date_retour'] == '0000-00-00'): ?>
                                <span class="badge active">En cours</span>
                            <?php else: ?>
                                <span class="badge done">Terminée</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="chart-card">
            <div class="chart-title">Derniers incidents</div>
            <table>
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Date</th>
                        <th>Gravité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_incidents as $i): ?>
                    <?php
                        $bc = match(strtolower($i['gravite'])) {
                            'grave'   => 'danger',
                            'moyenne' => 'warning',
                            default   => 'done'
                        };
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($i['marque'] ?? '—') ?></strong><br><small><?= htmlspecialchars($i['matricule'] ?? '') ?></small></td>
                        <td><?= $i['date_incident'] ?></td>
                        <td><span class="badge <?= $bc ?>"><?= htmlspecialchars($i['gravite']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- ── STYLES ──────────────────────────────────────────────────────── -->
<style>
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.kpi-card {
    background: var(--card-bg);
    border-radius: 12px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    transition: transform 0.2s, box-shadow 0.2s;
}
.kpi-card:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,0,0,0.1); }
.kpi-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.kpi-value { font-size: 28px; font-weight: 700; color: var(--text); line-height: 1; }
.kpi-label { font-size: 13px; color: #888; margin-top: 4px; }

.chart-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}
.chart-card {
    background: var(--card-bg);
    border-radius: 12px;
    padding: 20px 22px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}
.chart-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--green-dark);
    margin-bottom: 16px;
}

/* dark mode adjustments */
body.dark .kpi-card { box-shadow: 0 2px 8px rgba(0,0,0,0.3); }
body.dark .kpi-label { color: #6b7280; }
body.dark .chart-card { box-shadow: 0 2px 8px rgba(0,0,0,0.3); }
body.dark .chart-title { color: #7dd3a8; }
body.dark .kpi-value { color: #e6edf3; }

@media (max-width: 900px) {
    .chart-grid { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<!-- ── CHART.JS ────────────────────────────────────────────────────── -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const isDark     = () => document.body.classList.contains('dark');
const gridColor  = () => isDark() ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.07)';
const textColor  = () => isDark() ? '#8b949e' : '#555';
const borderClr  = () => isDark() ? '#161b22' : '#fff';

// ── Bar: Missions per month — multi-color bars
const barColors = [
    '#6366f1','#f59e0b','#10b981','#3b82f6','#ec4899','#f97316'
];
new Chart(document.getElementById('missionsChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($month_labels) ?>,
        datasets: [{
            label: 'Missions',
            data: <?= json_encode($month_values) ?>,
            backgroundColor: <?= json_encode($month_values) ?>.map((_, i) => barColors[i % barColors.length]),
            borderRadius: 7,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: gridColor() }, ticks: { color: textColor() } },
            y: { grid: { color: gridColor() }, ticks: { color: textColor(), stepSize: 1 }, beginAtZero: true }
        }
    }
});

// ── Pie: Vehicle states
new Chart(document.getElementById('etatsChart'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($etat_labels) ?>,
        datasets: [{
            data: <?= json_encode($etat_values) ?>,
            backgroundColor: ['#10b981','#f59e0b','#ef4444','#3b82f6','#8b5cf6','#f97316'],
            borderWidth: 3,
            borderColor: borderClr(),
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { color: textColor(), padding: 14, font: { size: 13 } } }
        }
    }
});

// ── Line: Carburant spend — orange/amber
new Chart(document.getElementById('carburantChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($carb_labels) ?>,
        datasets: [{
            label: 'DZD',
            data: <?= json_encode($carb_values) ?>,
            borderColor: '#f97316',
            backgroundColor: 'rgba(249,115,22,0.12)',
            borderWidth: 2.5,
            pointRadius: 5,
            pointBackgroundColor: '#f97316',
            pointBorderColor: borderClr(),
            pointBorderWidth: 2,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: gridColor() }, ticks: { color: textColor() } },
            y: { grid: { color: gridColor() }, ticks: { color: textColor() }, beginAtZero: true }
        }
    }
});

// ── Doughnut: Missions by status
new Chart(document.getElementById('gravitesChart'), {
    type: 'doughnut',
    data: {
        labels: ['En cours', 'Terminées'],
        datasets: [{
            data: [<?= $missions_en_cours ?>, <?= $missions_terminees ?>],
            backgroundColor: ['#f59e0b', '#10b981'],
            borderWidth: 3,
            borderColor: borderClr(),
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        cutout: '62%',
        plugins: {
            legend: { position: 'bottom', labels: { color: textColor(), padding: 14, font: { size: 13 } } }
        }
    }
});
</script>

<?php include "footer.php"; ?>
