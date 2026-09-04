<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>Official Notice - <?= e($n['title']) ?></title>
<style>
  @import url('https://fonts.maateen.me/kalpurush/font.css');
  @page { size: A4; margin: 15mm 15mm 20mm 15mm; }
  body {
    font-family: 'Kalpurush', 'Helvetica Neue', Arial, sans-serif;
    color: #101820;
    margin: 0;
    padding: 0;
    background: #fff;
    font-size: 13.5px;
    line-height: 1.6;
  }

  .letterhead {
    position: relative;
    min-height: 95vh;
    box-sizing: border-box;
    padding-bottom: 90px;
  }

  .pad-header {
    border-bottom: 3px double #800020;
    padding-bottom: 12px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .header-brand {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  .header-logo {
    width: 68px;
    height: 68px;
    object-fit: contain;
  }
  .header-titles h1 {
    font-size: 24px;
    margin: 0;
    color: #800020;
    font-weight: bold;
    letter-spacing: 0.5px;
  }
  .header-titles h2 {
    font-size: 13px;
    margin: 2px 0 0 0;
    color: #800020;
    font-weight: bold;
    letter-spacing: 1.5px;
    text-transform: uppercase;
  }
  .header-titles p {
    font-size: 11px;
    margin: 3px 0 0 0;
    color: #555;
    font-weight: 500;
  }
  .header-qr {
    text-align: right;
  }
  .header-qr img {
    width: 72px;
    height: 72px;
    border: 1px solid #ddd;
    padding: 2px;
    border-radius: 6px;
  }
  .header-qr span {
    display: block;
    font-size: 9px;
    color: #666;
    margin-top: 2px;
    font-family: monospace;
  }

  .meta-bar {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #444;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 6px;
  }

  .notice-title {
    font-size: 19px;
    color: #800020;
    text-align: center;
    font-weight: bold;
    margin: 15px 0 20px 0;
    text-decoration: underline;
    text-underline-offset: 6px;
  }

  .notice-body {
    font-size: 14px;
    color: #111;
    text-align: justify;
    margin-bottom: 40px;
    white-space: pre-line;
  }

  /* Official Seal Background / Stamp */
  .seal-box {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin: 20px 40px 10px 0;
  }
  .official-seal-badge {
    position: relative;
    width: 160px;
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    transform: rotate(-12deg);
    opacity: 0.88;
    mix-blend-mode: multiply;
  }
  .official-seal-badge img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .signatories-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    gap: 20px;
    margin-top: 35px;
    page-break-inside: avoid;
  }
  .signatory-col {
    flex: 1;
    min-width: 160px;
    max-width: 220px;
    text-align: center;
  }
  .sig-img-wrap {
    height: 55px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    margin-bottom: 4px;
  }
  .sig-img-wrap img {
    max-height: 50px;
    max-width: 150px;
    object-fit: contain;
  }
  .sig-line {
    border-top: 1px solid #333;
    margin-top: 4px;
    padding-top: 4px;
  }
  .sig-name {
    font-weight: bold;
    font-size: 13px;
    color: #101820;
  }
  .sig-title {
    font-size: 11px;
    color: #555;
  }

  .pad-footer {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    border-top: 1.5px solid #800020;
    padding-top: 8px;
    text-align: center;
    font-size: 10.5px;
    color: #555;
    background: #fff;
  }
  .pad-footer span {
    margin: 0 8px;
  }

  @media print {
    .no-print { display: none !important; }
    .letterhead { min-height: 98vh; }
  }
</style>
</head>
<body onload="if(window.location.search.includes('autoprint=1')) window.print();">

<div class="no-print" style="margin: 15px auto; max-width: 800px; text-align: right;">
  <button onclick="window.print()" style="background: #800020; color: white; border: none; padding: 10px 22px; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px;">
    🖨️ Print Official Notice / Save PDF
  </button>
</div>

<div class="letterhead" style="max-width: 800px; margin: 0 auto;">
  
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

  <!-- Official Rubber Stamp Seal -->
  <div class="seal-box">
    <div class="official-seal-badge">
      <img src="<?= asset('images/Stamp.png') ?>" alt="Official Seal">
    </div>
  </div>

  <?php if (!empty($signatories)): ?>
  <div class="signatories-grid">
    <?php foreach ($signatories as $sig): ?>
    <div class="signatory-col">
      <div class="sig-img-wrap">
        <?php if (!empty($sig['signature_image'])): ?>
        <img src="<?= asset('storage/signatures/' . e($sig['signature_image'])) ?>" alt="Signature">
        <?php endif; ?>
      </div>
      <div class="sig-line">
        <div class="sig-name"><?= e($sig['name']) ?></div>
        <div class="sig-title"><?= e($sig['designation_title'] ?: $sig['default_designation'] ?: 'Committee Member') ?></div>
        <div class="sig-title" style="font-size: 9.5px; color: #777;">IPH Alumni Association</div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

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
    <div style="font-size: 9px; color: #888; margin-top: 2px;">This is an official computer-generated document verified via embedded QR Security Code.</div>
  </div>

</div>

</body>
</html>
