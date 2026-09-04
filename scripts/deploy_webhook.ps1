param (
    [string]$Url = "https://iphalumni.dev.cv/deploy.php",
    [string]$Secret = "iph_alumni_secret_key_deploy_2026",
    [string]$Branch = "main"
)

$ErrorActionPreference = "Stop"

Write-Host "Calling Deployment Webhook..." -ForegroundColor Cyan
Write-Host "Target URL    : $Url" -ForegroundColor Gray
Write-Host "Target Branch : $Branch" -ForegroundColor Gray
Write-Host "Connecting to live server and executing deployment..." -ForegroundColor Yellow
Write-Host ""

$requestUrl = "$Url`?secret=$Secret"
$headers = @{
    "X-Deploy-Token" = $Secret
    "Accept"         = "application/json"
}

$body = @{
    "ref"    = "refs/heads/$Branch"
    "secret" = $Secret
} | ConvertTo-Json

try {
    $startTime = Get-Date
    $response = Invoke-RestMethod -Uri $requestUrl -Method Post -Headers $headers -Body $body -ContentType "application/json" -TimeoutSec 120

    $duration = (Get-Date) - $startTime
    $durationSec = [math]::Round($duration.TotalSeconds, 2)
    $durationText = if ($response.duration) { $response.duration } else { "$durationSec s" }

    Write-Host "=================================================================" -ForegroundColor Green
    Write-Host "                   SERVER DEPLOYMENT RESULT                      " -ForegroundColor Green
    Write-Host "=================================================================" -ForegroundColor Green
    Write-Host ("Status   : " + $response.status) -ForegroundColor Cyan
    Write-Host ("Message  : " + $response.message) -ForegroundColor Cyan
    if ($response.branch) {
        Write-Host ("Branch   : " + $response.branch) -ForegroundColor Cyan
    }
    Write-Host ("Duration : " + $durationText) -ForegroundColor Cyan
    Write-Host ""
    Write-Host "---------------- COMMAND EXECUTION OUTPUT ----------------" -ForegroundColor Yellow

    if ($response.output) {
        foreach ($step in $response.output) {
            Write-Host ("$ " + $step.command) -ForegroundColor White
            if ($step.result) {
                Write-Host $step.result -ForegroundColor DarkGray
            }
        }
    } else {
        Write-Host ($response | ConvertTo-Json -Depth 3) -ForegroundColor Gray
    }

    Write-Host "=================================================================" -ForegroundColor Green
    Write-Host " >>> DEPLOYMENT SUCCESSFUL! Live site is fully updated. <<<" -ForegroundColor Green
    Write-Host "=================================================================" -ForegroundColor Green
}
catch {
    Write-Host "=================================================================" -ForegroundColor Red
    Write-Host "                   DEPLOYMENT WEBHOOK ERROR                      " -ForegroundColor Red
    Write-Host "=================================================================" -ForegroundColor Red
    Write-Host ("Error: " + $_.Exception.Message) -ForegroundColor Red
    
    if ($_.Exception.Response) {
        try {
            $stream = $_.Exception.Response.GetResponseStream()
            $reader = New-Object System.IO.StreamReader($stream)
            $resBody = $reader.ReadToEnd()
            Write-Host "Server Response:" -ForegroundColor Yellow
            Write-Host $resBody -ForegroundColor Yellow
        } catch {}
    }
    Write-Host "=================================================================" -ForegroundColor Red
    Write-Host "Tip: Verify that the live server domain is online and DEPLOY_SECRET matches." -ForegroundColor Yellow
}
