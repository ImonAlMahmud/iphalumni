<?php $title = '404 — Not Found'; ?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 — Page Not Found</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
<style>body{margin:0;font-family:'Inter',sans-serif;background:#FAFAFA;display:flex;align-items:center;justify-content:center;min-height:100vh;color:#101820;}</style>
</head><body>
<div style="text-align:center;padding:40px;">
  <div style="font-family:'Fraunces',serif;font-size:80px;font-weight:600;color:rgba(16,24,32,0.08);line-height:1;">404</div>
  <h1 style="font-family:'Fraunces',serif;font-size:28px;margin:0 0 12px;">Page Not Found</h1>
  <p style="color:#6B7178;margin-bottom:24px;">The page you're looking for doesn't exist or has been moved.</p>
  <a href="<?= url('/') ?>" style="display:inline-flex;padding:12px 24px;border-radius:12px;background:linear-gradient(135deg,#A22638,#800020);color:#241a06;font-weight:600;text-decoration:none;">Go Home →</a>
</div>
</body></html>
