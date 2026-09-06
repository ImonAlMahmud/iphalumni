<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>Official Notice - <?= e($n['title']) ?></title>
<style>
  @import url('https://fonts.maateen.me/kalpurush/font.css');
  @page {
    size: A4 portrait;
    margin: 6mm 12mm 8mm 12mm;
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
    font-size: 13px;
    line-height: 1.55;
  }

  .letterhead {
    max-width: 800px;
    margin: 15px auto;
    background: #fff;
    padding: 24px 30px;
    min-height: 297mm;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  }

  .notice-main-content {
    flex: 1 0 auto;
  }

  /* Letterhead Pad Header */
  .pad-header {
    border-bottom: 2px double #800020;
    padding-bottom: 6px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .header-brand {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .header-logo {
    width: 54px;
    height: 54px;
    object-fit: contain;
  }
  .header-titles h1 {
    font-size: 19px;
    margin: 0;
    color: #800020;
    font-weight: bold;
    letter-spacing: 0.3px;
    line-height: 1.2;
  }
  .header-titles h2 {
    font-size: 11px;
    margin: 1px 0 0 0;
    color: #800020;
    font-weight: bold;
    letter-spacing: 1.2px;
    text-transform: uppercase;
  }
  .header-titles p {
    font-size: 9.5px;
    margin: 1px 0 0 0;
    color: #555;
    font-weight: 500;
  }
  .header-qr {
    text-align: right;
  }
  .header-qr img {
    width: 56px;
    height: 56px;
    border: 1px solid #ddd;
    padding: 2px;
    border-radius: 4px;
  }
  .header-qr span {
    display: block;
    font-size: 8.5px;
    color: #666;
    margin-top: 1px;
    font-family: monospace;
  }

  /* Reference No & Date */
  .meta-bar {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    color: #444;
    margin-bottom: 10px;
    border-bottom: 1px solid #eee;
    padding-bottom: 4px;
  }

  /* Notice Title */
  .notice-title {
    font-size: 16px;
    color: #800020;
    text-align: center;
    font-weight: bold;
    margin: 8px 0 12px 0;
    text-decoration: underline;
    text-underline-offset: 4px;
  }

  /* Notice Content Body */
  .notice-body {
    font-size: 13px;
    color: #111;
    text-align: justify;
    margin-bottom: 14px;
    white-space: pre-line;
    line-height: 1.55;
  }

  /* Combined Seal & Signatories Area */
  .authorization-area {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    margin-top: 14px;
    margin-bottom: 10px;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .seal-col {
    flex-shrink: 0;
    display: flex;
    align-items: flex-end;
  }

  .official-seal-badge {
    width: 96px;
    height: 96px;
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

  .signatories-col {
    display: flex;
    align-items: flex-end;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 18px;
    flex-grow: 1;
  }

  .signatory-item {
    min-width: 140px;
    max-width: 180px;
    text-align: center;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .sig-img-wrap {
    height: 42px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    margin-bottom: 2px;
  }
  .sig-img-wrap img {
    max-height: 40px;
    max-width: 130px;
    object-fit: contain;
  }

  .sig-line {
    border-top: 1.2px solid #222;
    padding-top: 3px;
  }
  .sig-name {
    font-weight: bold;
    font-size: 12px;
    color: #101820;
    line-height: 1.2;
  }
  .sig-title {
    font-size: 10.5px;
    color: #444;
    line-height: 1.2;
    margin-top: 1px;
  }
  .sig-org {
    font-size: 9px;
    color: #777;
    margin-top: 1px;
  }

  /* Pad Footer */
  .pad-footer {
    margin-top: auto;
    border-top: 1.5px solid #800020;
    padding-top: 6px;
    text-align: center;
    font-size: 9.5px;
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
      min-height: calc(100vh - 14mm) !important;
      box-shadow: none !important;
      border: none !important;
    }
    .authorization-area {
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
  
  <div class="notice-main-content">
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

    <!-- Combined Authorization Area: Official Seal (Stamp) on left + Signatories on right -->
    <div class="authorization-area">
      <div class="seal-col">
        <div class="official-seal-badge">
          <img src="<?= asset('images/Stamp.png') ?>" alt="Official Seal">
        </div>
      </div>

      <?php if (!empty($signatories)): ?>
      <div class="signatories-col">
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
    </div>
  </div>

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
