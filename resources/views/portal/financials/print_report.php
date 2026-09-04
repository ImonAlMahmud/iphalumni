<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title><?= e($reportTitle ?? 'Financial Report') ?></title>
  <style>
    @import url('https://fonts.maateen.me/kalpurush/font.css');
    body { font-family: 'Kalpurush', sans-serif; margin: 30px; color: #101820; font-size: 13px; line-height: 1.5; }
    .header { text-align: center; border-b: 2px solid #800020; padding-bottom: 15px; margin-bottom: 25px; }
    .header h1 { font-size: 22px; margin: 0; color: #800020; }
    .header h2 { font-size: 16px; margin: 5px 0 0 0; color: #444; }
    .header p { font-size: 12px; color: #666; margin: 3px 0 0 0; }
    .meta-table { width: 100%; margin-bottom: 20px; font-size: 12px; }
    .meta-table td { padding: 4px 0; }
    table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    table.data-table th, table.data-table td { border: 1px solid #ddd; padding: 8px 12px; text-align: left; }
    table.data-table th { background: #800020; color: #fff; font-weight: bold; }
    table.data-table tr:nth-child(even) { background: #f9f9f9; }
    .text-right { text-align: right; }
    .font-bold { font-weight: bold; }
    .total-row { background: #f0f0f0 !important; font-weight: bold; }
    .footer { margin-top: 50px; width: 100%; font-size: 12px; }
    .footer-col { width: 33%; text-align: center; display: inline-block; vertical-align: top; }
    .signature-line { border-top: 1px solid #000; width: 80%; margin: 40px auto 5px auto; }
    @media print {
      body { margin: 0; }
      .no-print { display: none; }
    }
  </style>
</head>
<body onload="window.print()">

  <div class="no-print" style="margin-bottom: 20px; text-align: right;">
    <button onclick="window.print()" style="padding: 8px 18px; background: #800020; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
      🖨️ প্রিন্ট করুন / PDF ডাউনলোড
    </button>
  </div>

  <div class="header">
    <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 8px;">
      <img src="<?= asset('images/LOGO.png') ?>" alt="IPH Logo" style="width: 55px; height: 55px; object-fit: contain;">
      <div style="text-align: left;">
        <h1 style="font-size: 22px; margin: 0; color: #800020; font-weight: bold;">আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন</h1>
        <div style="font-size: 13px; color: #800020; font-weight: bold; letter-spacing: 1.5px; margin-top: 2px;">IPH ALUMNI ASSOCIATION</div>
      </div>
    </div>
    <p style="font-size: 12px; color: #444; margin: 6px 0 0 0; font-weight: bold; border-top: 1px dashed #ccc; padding-top: 6px;"><?= e($reportTitle ?? 'Official Financial Report') ?></p>
  </div>

  <table class="meta-table">
    <tr>
      <td><strong>প্রতিবেদন প্রকাশের তারিখ:</strong> <?= date('d F Y, h:i A') ?></td>
      <td class="text-right"><strong>প্রস্তুতকারক:</strong> <?= e($user['name'] ?? 'Authorized Finance Officer') ?></td>
    </tr>
  </table>

  <table class="data-table">
    <thead>
      <?php if (!empty($funds)): ?>
      <tr>
        <th>তারিখ</th>
        <th>তহবিলের বিবরণ</th>
        <th>উৎস</th>
        <th>রেফারেন্স নং</th>
        <th class="text-right">পরিমাণ (৳)</th>
      </tr>
      <?php elseif (!empty($expenses)): ?>
      <tr>
        <th>তারিখ</th>
        <th>ব্যয়ের বিবরণ</th>
        <th>খাত / ক্যাটাগরি</th>
        <th>ভাউচার নং</th>
        <th class="text-right">পরিমাণ (৳)</th>
      </tr>
      <?php elseif (!empty($budgets)): ?>
      <tr>
        <th>অর্থবছর</th>
        <th>বাজেট খাত</th>
        <th>নোট</th>
        <th class="text-right">বরাদ্দকৃত পরিমাণ (৳)</th>
      </tr>
      <?php endif; ?>
    </thead>
    <tbody>
      <?php if (!empty($funds)): ?>
        <?php foreach ($funds as $row): ?>
        <tr>
          <td><?= date('d/m/Y', strtotime($row['fund_date'])) ?></td>
          <td><?= e($row['title']) ?></td>
          <td><?= e($row['source']) ?></td>
          <td><?= e($row['reference_no'] ?: '-') ?></td>
          <td class="text-right">৳ <?= number_format((float)$row['amount'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      <?php elseif (!empty($expenses)): ?>
        <?php foreach ($expenses as $row): ?>
        <tr>
          <td><?= date('d/m/Y', strtotime($row['expense_date'])) ?></td>
          <td><?= e($row['title']) ?></td>
          <td><?= e($row['category']) ?></td>
          <td><?= e($row['voucher_no'] ?: '-') ?></td>
          <td class="text-right">৳ <?= number_format((float)$row['amount'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      <?php elseif (!empty($budgets)): ?>
        <?php foreach ($budgets as $row): ?>
        <tr>
          <td><?= e($row['fiscal_year']) ?></td>
          <td><?= e($row['category']) ?></td>
          <td><?= e($row['notes'] ?: '-') ?></td>
          <td class="text-right">৳ <?= number_format((float)$row['allocated_amount'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>

      <tr class="total-row">
        <td colspan="<?= !empty($budgets) ? '3' : '4' ?>" class="text-right font-bold">সর্বমোট সর্বসাকুল্যে (Total Sum):</td>
        <td class="text-right font-bold">৳ <?= number_format((float)($totalAmount ?? 0), 2) ?></td>
      </tr>
    </tbody>
  </table>

  <div class="footer">
    <div class="footer-col">
      <div class="signature-line"></div>
      প্রস্তুতকারকের স্বাক্ষর
    </div>
    <div class="footer-col">
      <div class="signature-line"></div>
      অর্থ সম্পাদক / ক্যাশিয়ার
    </div>
    <div class="footer-col">
      <div class="signature-line"></div>
      সভাপতি / সাধারণ সম্পাদক
    </div>
  </div>

</body>
</html>
