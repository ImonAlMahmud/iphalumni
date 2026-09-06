<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>Official Notice - <?= e($n['title']) ?></title>
<style>
  @import url('https://fonts.maateen.me/kalpurush/font.css');
  @page {
    size: A4 portrait;
    margin: 8mm 12mm 8mm 12mm;
  }
  * {
    box-sizing: border-box;
  }
  body {
    font-family: 'Kalpurush', 'Helvetica Neue', Arial, sans-serif;
    color: #101820;
    margin: 0;
    padding: 0;
    background: #f8fafc;
    font-size: 15px;
    line-height: 1.7;
  }

  .letterhead {
    max-width: 800px;
    margin: 15px auto;
    background: #fff;
    padding: 24px 30px;
    min-height: 275mm;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  }

  .notice-top-content {
    flex: 1 0 auto;
  }

  /* Letterhead Pad Header */
  .pad-header {
    border-bottom: 2.5px double #800020;
    padding-bottom: 8px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .header-brand {
    display: flex;
    align-items: center;
    gap: 14px;
  }
  .header-logo {
    width: 60px;
    height: 60px;
    object-fit: contain;
  }
  .header-titles h1 {
    font-size: 22px;
    margin: 0;
    color: #800020;
    font-weight: bold;
    letter-spacing: 0.4px;
    line-height: 1.25;
  }
  .header-titles h2 {
    font-size: 12px;
    margin: 2px 0 0 0;
    color: #800020;
    font-weight: bold;
    letter-spacing: 1.2px;
    text-transform: uppercase;
  }
  .header-titles p {
    font-size: 10.5px;
    margin: 2px 0 0 0;
    color: #555;
    font-weight: 500;
  }
  .header-qr {
    text-align: right;
  }
  .header-qr img {
    width: 60px;
    height: 60px;
    border: 1px solid #ddd;
    padding: 2px;
    border-radius: 4px;
  }
  .header-qr span {
    display: block;
    font-size: 9px;
    color: #666;
    margin-top: 1px;
    font-family: monospace;
  }

  /* Reference No & Date */
  .meta-bar {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #333;
    margin-bottom: 12px;
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
  }

  /* Notice Title */
  .notice-title {
    font-size: 19px;
    color: #800020;
    text-align: center;
    font-weight: bold;
    margin: 10px 0 16px 0;
    text-decoration: underline;
    text-underline-offset: 5px;
  }

  /* Notice Content Body */
  .notice-body {
    font-size: 15px;
    color: #111;
    text-align: justify;
    margin-bottom: 16px;
    white-space: pre-line;
    line-height: 1.7;
    word-spacing: 0.5px;
  }

  /* Fixed Bottom Zone: Always fixed at the footer of the page */
  .letterhead-bottom-zone {
    margin-top: auto;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  /* 1. Official Seal (আগে সিল আসবে) */
  .seal-section {
    display: flex;
    justify-content: flex-end;
    padding-right: 25px;
    margin-bottom: 6px;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .official-seal-badge {
    width: 90px;
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: center;
    transform: rotate(-8deg);
    opacity: 0.92;
    mix-blend-mode: multiply;
  }
  .official-seal-badge img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  /* 2. Signatures (তারপর সিগনেচার - পাশাপাশি একই লাইনে) */
  .signatories-row {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: flex-end;
    align-items: flex-end;
    gap: 35px;
    width: 100%;
    margin-bottom: 12px;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .signatory-item {
    min-width: 140px;
    max-width: 190px;
    text-align: center;
    flex-shrink: 0;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .sig-img-wrap {
    height: 48px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    margin-bottom: 3px;
  }
  .sig-img-wrap img {
    max-height: 46px;
    max-width: 140px;
    object-fit: contain;
  }

  .sig-line {
    border-top: 1.2px solid #222;
    padding-top: 4px;
  }
  .sig-name {
    font-weight: bold;
    font-size: 13px;
    color: #101820;
    line-height: 1.25;
  }
  .sig-title {
    font-size: 11.5px;
    color: #444;
    line-height: 1.25;
    margin-top: 1px;
  }
  .sig-org {
    font-size: 10px;
    color: #777;
    margin-top: 1px;
  }

  /* Pad Footer */
  .pad-footer {
    border-top: 1.5px solid #800020;
    padding-top: 7px;
    text-align: center;
    font-size: 10.5px;
    color: #555;
    background: #fff;
    page-break-inside: avoid;
    break-inside: avoid;
  }
  .pad-footer span {
    margin: 0 6px;
  }

  @media print {
    body {
      background: #fff !important;
      margin: 0 !important;
      padding: 0 !important;
    }
    .no-print {
      display: none !important;
    }
    .letterhead {
      max-width: 100% !important;
      margin: 0 !important;
      padding: 0 !important;
      min-height: 275mm !important;
      box-shadow: none !important;
      border: none !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: space-between !important;
    }
    .notice-top-content {
      flex: 1 0 auto !important;
    }
    .letterhead-bottom-zone {
      margin-top: auto !important;
      page-break-inside: avoid !important;
      break-inside: avoid !important;
    }
    .seal-section {
      page-break-inside: avoid !important;
      break-inside: avoid !important;
    }
    .signatories-row {
      page-break-inside: avoid !important;
      break-inside: avoid !important;
    }
    .pad-footer {
      page-break-inside: avoid !important;
      break-inside: avoid !important;
    }
  }
</style>
</head>
<body onload="if(window.location.search.includes('autoprint=1')) window.print();">

<div class="no-print" style="margin: 15px auto; max-width: 800px; text-align: right;">
  <button onclick="window.print()" style="background: #800020; color: white; border: none; padding: 10px 22px; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px;">
    🖨️ Print Official Notice / Save PDF
  </button>
</div>

<div class="letterhead">
  
  <div class="notice-top-content">
    <div class="pad-header">
      <div class="header-brand">
        <img src="<?= asset('images/LOGO.png') ?>" alt="IPH Logo" class="header-logo">
        <div class="header-titles">
          <h1>আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন</h1>
          <h2>INSTITUTE OF PUBLIC HEALTH ALUMNI ASSOCIATION</h2>
          <p>Estd: 2015 | Mohakhali, Dhaka-1212, Bangladesh</p>
        </div>
      </div>
      
      <div class="header-qr">
        <img src="<?= $qrUrl ?>" alt="Scan to Verify Notice">
        <span>Scan to Verify</span>
      </div>
    </div>

    <div class="meta-bar">
      <div><strong>স্মারক নং / Ref:</strong> IPH-AA/NOT/<?= date('Y') ?>/<?= sprintf('%04d', $n['id']) ?></div>
      <div><strong>তারিখ / Date:</strong> <?= date('d F Y', strtotime($n['published_at'] ?? $n['created_at'])) ?></div>
    </div>

    <div class="notice-title">
      <?= e($n['title']) ?>
    </div>

    <div class="notice-body">
      <?= e($n['content']) ?>
    </div>
  </div>

  <!-- Bottom Fixed Zone: Always fixed at the footer of the page -->
  <div class="letterhead-bottom-zone">
    <!-- 1. Official Seal (আগে সিল আসবে) -->
    <div class="seal-section">
      <div class="official-seal-badge">
        <img src="<?= asset('images/Stamp.png') ?>" alt="Official Seal">
      </div>
    </div>

    <!-- 2. Signatures (তারপর সিগনেচার - পাশাপাশি একই লাইনে) -->
    <?php if (!empty($signatories)): ?>
    <div class="signatories-row">
      <?php foreach ($signatories as $sig): ?>
      <div class="signatory-item">
        <div class="sig-img-wrap">
          <?php if (!empty($sig['signature_image'])): ?>
          <img src="<?= asset('storage/signatures/' . e($sig['signature_image'])) ?>" alt="Signature">
          <?php endif; ?>
        </div>
        <div class="sig-line">
          <div class="sig-name"><?= e($sig['name']) ?></div>
          <div class="sig-title"><?= e($sig['designation_title'] ?: $sig['default_designation'] ?: 'Committee Member') ?></div>
          <div class="sig-org">IPH Alumni Association</div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- 3. Pad Footer Bar -->
    <div class="pad-footer">
      <span>📞 Phone: <?= e(!empty($siteSettings['site_phone']) ? $siteSettings['site_phone'] : ($siteSettings['contact_phone'] ?? '+880 1811-332204')) ?></span> | 
      <span>✉️ Email: <?= e(!empty($siteSettings['site_email']) ? $siteSettings['site_email'] : ($siteSettings['contact_email'] ?? 'info@iphalumni.org')) ?></span> | 
      <?php
        $appUrl = env('APP_URL', 'http://localhost/alumni/public');
        $parsedHost = parse_url($appUrl, PHP_URL_HOST);
        if ($parsedHost === 'localhost' || empty($parsedHost)) {
            $displayWeb = url('/');
        } else {
            $displayWeb = (parse_url($appUrl, PHP_URL_SCHEME) ?? 'https') . '://' . $parsedHost;
        }
      ?>
      <span>🌐 Website: <?= e($displayWeb) ?></span>
      <div style="font-size: 10px; color: #888; margin-top: 3px;">This is an official computer-generated document verified via embedded QR Security Code.</div>
    </div>
  </div>

</div>

</body>
</html>
