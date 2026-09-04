<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Reference List Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
            }
        }
        .no-print {
            margin-bottom: 20px;
            text-align: right;
        }
        .btn {
            background-color: #2F8863;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #246b4d;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn">Print / Save as PDF</button>
        <button onclick="window.close()" class="btn" style="background-color: #666;">Close Window</button>
    </div>

    <div class="header">
        <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 8px;">
            <img src="<?= asset('images/LOGO.png') ?>" alt="IPH Logo" style="width: 55px; height: 55px; object-fit: contain;">
            <div style="text-align: left;">
                <h1 style="margin: 0; color: #800020; font-size: 22px; font-weight: bold;">IPH Student Reference Directory</h1>
                <p style="margin: 2px 0 0 0; color: #800020; font-size: 11px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase;">Institute of Public Health Alumni Network</p>
            </div>
        </div>
        <p style="color: #555; font-size: 12px; font-weight: bold; border-top: 1px solid #eee; padding-top: 6px; margin-top: 6px;">Official Verification Database Export — Printed on <?= date('d F Y, h:i A') ?></p>
        <?php if ($batch || $session || $dept || $search): ?>
            <p style="font-size:12px; font-style:italic;">
                Filters applied: 
                <?= $batch ? "Batch: $batch | " : "" ?>
                <?= $session ? "Session: $session | " : "" ?>
                <?= $dept ? "Department: $dept | " : "" ?>
                <?= $search ? "Search: '$search' | " : "" ?>
            </p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Roll</th>
                <th>Name (English)</th>
                <th>Name (Bangla)</th>
                <th>Mobile</th>
                <th>Guardian Mobile</th>
                <th>Batch</th>
                <th>Session</th>
                <th>Department</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="8" style="text-align: center;">No students found matching current filters.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)($s['roll'] ?? '—')) ?></td>
                        <td><strong><?= htmlspecialchars($s['name_english']) ?></strong></td>
                        <td><?= htmlspecialchars($s['name_bangla'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($s['mobile'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($s['guardian_mobile'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($s['batch']) ?></td>
                        <td><?= htmlspecialchars($s['session']) ?></td>
                        <td><?= htmlspecialchars($s['department']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        // Auto trigger print dialog on page load
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => { window.print(); }, 500);
        });
    </script>
</body>
</html>
