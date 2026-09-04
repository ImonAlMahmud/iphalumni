<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= e($reportTitle) ?></title>
<style>
  body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #111; margin: 0; padding: 20px; }
  .header { text-align: center; border-bottom: 2px solid #800020; padding-bottom: 12px; margin-bottom: 20px; }
  .header h1 { margin: 0; color: #800020; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
  .header p { margin: 4px 0 0 0; color: #555; font-size: 11px; }
  table { width: 100%; border-collapse: collapse; margin-top: 10px; }
  th, td { border: 1px solid #ddd; padding: 7px 99px; text-align: left; vertical-align: top; }
  th, td { padding: 7px 9px; }
  th { background-color: #f8f9fa; color: #800020; font-size: 10px; text-transform: uppercase; }
  tr:nth-child(even) { background-color: #fafafa; }
  .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
  .status-approved { background: #e6f4ea; color: #137333; }
  .status-pending { background: #fef7e0; color: #b06000; }
  .status-under_review { background: #e8f0fe; color: #1a73e8; }
  .footer { margin-top: 25px; text-align: right; font-size: 9px; color: #777; border-top: 1px solid #eee; padding-top: 8px; }
  @media print {
    body { padding: 0; }
    .no-print { display: none; }
  }
</style>
</head>
<body>

<div class="no-print" style="margin-bottom: 15px; text-align: right;">
  <button onclick="window.print()" style="background: #800020; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: bold;">
    🖨️ Print / Save as PDF
  </button>
</div>

<div class="header">
  <div style="display: flex; items-center; justify-content: center; gap: 15px; margin-bottom: 10px;">
    <img src="<?= asset('images/LOGO.png') ?>" alt="IPH Logo" style="width: 55px; height: 55px; object-fit: contain;">
    <div style="text-align: left;">
      <h1 style="margin: 0; color: #800020; font-size: 22px; font-weight: bold; letter-spacing: 0.5px;">IPH Alumni Association</h1>
      <p style="margin: 2px 0 0 0; color: #800020; font-size: 11px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase;">Institute of Public Health</p>
    </div>
  </div>
  <p style="margin: 6px 0 0 0; color: #444; font-size: 12px; font-weight: bold; border-top: 1px solid #eee; pt-2;"><?= e($reportTitle) ?> — Generated on <?= date('d F Y, h:i A') ?></p>
</div>

<table>
  <thead>
    <tr>
      <th style="width: 30px;">#</th>
      <th>Alumni Name</th>
      <th>Contact Info</th>
      <th>Academic Info</th>
      <th>Professional Details</th>
      <th>Location</th>
      <th style="width: 70px;">Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($alumni)): ?>
    <tr><td colspan="7" style="text-align: center; padding: 20px;">No alumni records found.</td></tr>
    <?php else: ?>
    <?php foreach ($alumni as $idx => $a): ?>
    <tr>
      <td><?= $idx + 1 ?></td>
      <td>
        <strong><?= e($a['name']) ?></strong><br>
        <span style="color: #666; font-size: 9.5px;">Registered: <?= date('d M Y', strtotime($a['registered_at'])) ?></span>
      </td>
      <td>
        <?= e($a['email']) ?><br>
        <span style="color: #555;"><?= e($a['phone'] ?? 'N/A') ?></span>
      </td>
      <td>
        <strong>Batch:</strong> <?= e($a['batch'] ?? 'N/A') ?><br>
        <strong>Degree:</strong> <?= e($a['degree'] ?? 'N/A') ?> (<?= e($a['passing_year'] ?? 'N/A') ?>)
      </td>
      <td>
        <strong><?= e($a['designation'] ?? 'N/A') ?></strong><br>
        <span style="color: #555;"><?= e($a['organization'] ?? 'N/A') ?></span>
      </td>
      <td>
        <?= e($a['city'] ?? 'N/A') ?><?= !empty($a['country']) ? ', ' . e($a['country']) : '' ?>
      </td>
      <td>
        <span class="badge status-<?= e($a['status']) ?>">
          <?= ucfirst(str_replace('_', ' ', $a['status'])) ?>
        </span>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<div class="footer">
  IPH Alumni Database Management System &copy; <?= date('Y') ?> | Confidentially generated for Admin use only.
</div>

<script>
  if (window.location.search.includes('autoprint=1')) {
    window.print();
  }
</script>
</body>
</html>
