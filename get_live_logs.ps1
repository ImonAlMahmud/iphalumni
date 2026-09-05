$wc = New-Object System.Net.WebClient
$json = $wc.DownloadString('https://iphalumni.dev.cv/deploy.php?secret=iph_alumni_secret_key_deploy_2026&action=logs')
$obj = ConvertFrom-Json $json
$lines = $obj.last_100_lines -split "`n"
for ($i = 0; $i -lt $lines.Length; $i++) {
    if ($lines[$i] -match 'local.ERROR|production.ERROR') {
        $start = [Math]::Max(0, $i)
        $end = [Math]::Min($lines.Length - 1, $i + 15)
        $lines[$start..$end]
    }
}
