<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= e($reportTitle) ?></title>
<style>
  body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #111; margin: 0; padding: 20px; }
  .header { text-align: center; border-bottom: 2px solid #800020; padding-bottom: 12px; margin-bottom: 15px; }
  .header h1 { margin: 0; color: #800020; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
  .header p { margin: 3px 0 0 0; color: #555; font-size: 11px; }
  
  .stats-bar { display: flex; justify-content: space-around; background: #fdf5f6; border: 1px solid #f3d4d8; border-radius: 6px; padding: 8px 12px; margin-bottom: 15px; font-size: 11px; }
  .stats-item { text-align: center; }
  .stats-item strong { display: block; font-size: 14px; color: #800020; font-family: monospace; }
  
  table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 10.5px; }
  th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; vertical-align: top; }
  th { background-color: #f8f9fa; color: #800020; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.5px; }
  tr:nth-child(even) { background-color: #fafafa; }
  
  .badge { display: inline-block; padding: 2px 5px; border-radius: 3px; font-size: 9px; font-weight: bold; text-transform: uppercase; font-family: monospace; }
  .badge-active { background: #e6f4ea; color: #137333; border: 1px solid #ceead6; }
  .badge-pending { background: #fef7e0; color: #b06000; border: 1px solid #feefc3; }
  .badge-expired { background: #f1f3f4; color: #5f6368; border: 1px solid #dadce0; }
  .badge-rejected { background: #fce8e6; color: #c5221f; border: 1px solid #fad2cf; }
  
  .footer { margin-top: 25px; text-align: right; font-size: 9px; color: #777; border-top: 1px solid #eee; padding-top: 8px; }
  
  @media print {
    body { padding: 0; }
    .no-print { display: none; }
    @page { size: landscape; margin: 12mm; }
  }
</style>
</head>
<body>

<div class="no-print" style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
  <a href="javascript:window.close()" onclick="window.history.back()" style="color: #666; text-decoration: none; font-size: 12px;">← Back to Admin Panel</a>
  <button onclick="window.print()" style="background: #800020; color: white; border: none; padding: 8px 18px; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
    🖨️ Print / Save as PDF
  </button>
</div>

<div class="header">
  <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 8px;">
    <img src="<?= asset('images/LOGO.png') ?>" alt="IPH Logo" style="width: 50px; height: 50px; object-fit: contain;">
    <div style="text-align: left;">
      <h1 style="margin: 0; color: #800020; font-size: 21px; font-weight: bold; letter-spacing: 0.5px;">IPH Alumni Association</h1>
      <p style="margin: 2px 0 0 0; color: #800020; font-size: 10.5px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase;">Institute of Public Health</p>
    </div>
  </div>
  <p style="margin: 6px 0 0 0; color: #444; font-size: 12px; font-weight: bold; border-top: 1px solid #eee; padding-top: 6px;">
    <?= e($reportTitle) ?> — Generated on <?= date('d F Y, h:i A') ?>
  </p>
</div>

<div class="stats-bar">
  <div class="stats-item">
    <span>Total Filtered Records</span>
    <strong><?= number_format($stats['total'] ?? 0) ?></strong>
  </div>
  <div class="stats-item">
    <span>Active Subscriptions</span>
    <strong style="color: #137333;"><?= number_format($stats['active'] ?? 0) ?></strong>
  </div>
  <div class="stats-item">
    <span>Total Collected Payments</span>
    <strong style="color: #800020;">৳ <?= number_format($stats['total_payments'] ?? 0) ?> BDT</strong>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th style="width: 25px;">#</th>
      <th>Member Details</th>
      <th>Member ID & Tier</th>
      <th>Status & Validity</th>
      <th>Payment Amount</th>
      <th>Method & TrxID</th>
      <th>Payment Status</th>
      <th>Payment Date</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($memberships)): ?>
    <tr><td colspan="8" style="text-align: center; padding: 25px; color: #888;">No membership records found matching criteria.</td></tr>
    <?php else: ?>
    <?php foreach ($memberships as $idx => $m): 
      $st = strtolower($m['status'] ?? 'pending');
      $stClass = match($st) {
        'active' => 'badge-active',
        'pending' => 'badge-pending',
        'expired' => 'badge-expired',
        'rejected', 'cancelled' => 'badge-rejected',
        default => 'badge-pending',
      };
      $amount = (float)($m['payment_amount'] ?? ($m['type_fee'] ?? 0));
      $pStatus = strtolower($m['payment_status'] ?? ($st === 'active' ? 'paid' : 'pending'));
    ?>
    <tr>
      <td><?= $idx + 1 ?></td>
      <td>
        <strong><?= e($m['name']) ?></strong><br>
        <span style="color: #555; font-size: 10px;"><?= e($m['email']) ?></span>
        <?php if (!empty($m['phone'])): ?><br><span style="color: #777; font-size: 9.5px;"><?= e($m['phone']) ?></span><?php endif; ?>
        <?php if (!empty($m['batch_year'])): ?><br><span style="color: #444; font-size: 9px; font-weight: bold;">Batch: <?= e($m['batch_year']) ?></span><?php endif; ?>
      </td>
      <td>
        <strong style="font-family: monospace; color: #800020; font-size: 11px;"><?= e($m['membership_number']) ?></strong><br>
        <span style="font-size: 10px; color: #333; font-weight: bold;"><?= e($m['type_name']) ?></span><br>
        <span style="color: #777; font-size: 9.5px;">Fee: ৳ <?= number_format((float)($m['type_fee'] ?? 0)) ?></span>
      </td>
      <td>
        <span class="badge <?= $stClass ?>"><?= strtoupper($st) ?></span><br>
        <div style="font-size: 9.5px; color: #555; margin-top: 3px; font-family: monospace;">
          From: <?= $m['start_date'] ? date('d M Y', strtotime($m['start_date'])) : '—' ?><br>
          Thru: <?= $m['end_date'] ? date('d M Y', strtotime($m['end_date'])) : 'Lifetime' ?>
        </div>
      </td>
      <td>
        <strong style="font-family: monospace; font-size: 12px; color: #111;">৳ <?= number_format($amount) ?></strong>
        <span style="font-size: 9.5px; color: #666;"><?= e($m['payment_currency'] ?? 'BDT') ?></span>
      </td>
      <td>
        <strong style="text-transform: uppercase; font-size: 10px;"><?= e($m['payment_method'] ?: 'Admin/Free') ?></strong>
        <?php if (!empty($m['transaction_id'])): ?>
        <div style="font-family: monospace; color: #0d652d; font-size: 10px; margin-top: 2px;">
          TrxID: <?= e($m['transaction_id']) ?>
        </div>
        <?php else: ?>
        <div style="color: #999; font-size: 9px; font-style: italic;">No TrxID</div>
        <?php endif; ?>
      </td>
      <td>
        <span class="badge <?= $pStatus === 'paid' ? 'badge-active' : 'badge-pending' ?>">
          <?= strtoupper($pStatus) ?>
        </span>
      </td>
      <td style="font-family: monospace; font-size: 10px; color: #555;">
        <?= !empty($m['payment_date']) ? date('d M Y H:i', strtotime($m['payment_date'])) : (!empty($m['created_at']) ? date('d M Y', strtotime($m['created_at'])) : '—') ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<div class="footer">
  IPH Alumni Association Official System Records · Confidential Financial & Membership Audit Report · Page 1
</div>

</body>
</html>
