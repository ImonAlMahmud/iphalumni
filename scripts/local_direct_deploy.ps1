param (
    [string]$Url = "https://iphalumni.dev.cv/deploy.php",
    [string]$Secret = "iph_alumni_secret_key_deploy_2026",
    [switch]$IncludeVendor = $false
)

$ErrorActionPreference = "Stop"

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

Write-Host "=================================================================" -ForegroundColor Cyan
Write-Host "         DIRECT LOCAL-TO-SERVER AUTO DEPLOY (NO GITHUB)          " -ForegroundColor Cyan
Write-Host "=================================================================" -ForegroundColor Cyan
Write-Host "Target Server : $Url" -ForegroundColor Gray
Write-Host ""

$projectRoot = (Resolve-Path "$PSScriptRoot\..").Path
$tempZip = Join-Path $env:TEMP ("iph_deploy_" + (Get-Date -Format "yyyyMMddHHmmss") + ".zip")

try {
    # Step 1: Pre-flight check
    Write-Host "[1/3] Checking connection to live deploy endpoint..." -ForegroundColor Yellow
    $pingUrl = "$Url`?secret=$Secret&action=ping"
    
    try {
        $pingResponse = Invoke-RestMethod -Uri $pingUrl -Method Get -TimeoutSec 15 -ErrorAction Stop
        Write-Host "Server is ready! (PHP " + $pingResponse.php_version + ", Zip: " + $pingResponse.zip_enabled + ")" -ForegroundColor Green
    }
    catch {
        Write-Host "[!] Warning: Pre-flight check returned an error. Proceeding with upload..." -ForegroundColor Yellow
    }
    Write-Host ""

    # Step 2: Build cross-platform POSIX ZIP package
    Write-Host "[2/3] Packaging project files & avatars..." -ForegroundColor Yellow

    if (Test-Path $tempZip) {
        Remove-Item $tempZip -Force
    }

    $zip = [System.IO.Compression.ZipFile]::Open($tempZip, [System.IO.Compression.ZipArchiveMode]::Create)

    $includeFolders = @(
        "app", "bootstrap", "config", "database", "public", "resources", "routes",
        "public\videos", "storage\app\public",
        "storage\avatars", "storage\app\public\avatars",
        "storage\gallery", "storage\app\public\gallery",
        "storage\stories", "storage\app\public\stories"
    )
    if ($IncludeVendor) {
        $includeFolders += "vendor"
    }

    foreach ($folder in $includeFolders) {
        $folderPath = Join-Path $projectRoot $folder
        if (Test-Path $folderPath) {
            Get-ChildItem -Path $folderPath -Recurse -File | ForEach-Object {
                $relPath = $_.FullName.Substring($projectRoot.Length + 1).Replace('\', '/')
                if (-not ($relPath.StartsWith("storage/logs") -or $relPath.StartsWith("storage/framework"))) {
                    [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $_.FullName, $relPath, [System.IO.Compression.CompressionLevel]::Optimal) | Out-Null
                }
            }
        }
    }

    $includeFiles = @("composer.json", "composer.lock", "package.json", "artisan", "iph_alumni_database_fixed.sql")
    foreach ($file in $includeFiles) {
        $filePath = Join-Path $projectRoot $file
        if (Test-Path $filePath) {
            $relPath = (Get-Item $filePath).Name
            [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, (Get-Item $filePath).FullName, $relPath, [System.IO.Compression.CompressionLevel]::Optimal) | Out-Null
        }
    }

    $zip.Dispose()
    
    $zipSize = (Get-Item $tempZip).Length / 1MB
    $zipSizeFormatted = [math]::Round($zipSize, 2)
    Write-Host "Package created successfully ($zipSizeFormatted MB)." -ForegroundColor Green
    Write-Host ""

    # Step 3: Direct Binary Upload
    Write-Host "[3/3] Uploading package directly to live server..." -ForegroundColor Yellow

    $requestUrl = "$Url`?secret=$Secret"
    
    $webClient = New-Object System.Net.WebClient
    $webClient.Headers.Add("X-Deploy-Token", $Secret)
    $webClient.Headers.Add("Content-Type", "application/octet-stream")

    $fileBytes = [System.IO.File]::ReadAllBytes($tempZip)
    $startTime = Get-Date
    $rawResponse = $webClient.UploadData($requestUrl, "POST", $fileBytes)
    $responseString = [System.Text.Encoding]::UTF8.GetString($rawResponse)

    $duration = (Get-Date) - $startTime
    $durationSec = [math]::Round($duration.TotalSeconds, 2)

    $response = $responseString | ConvertFrom-Json

    Write-Host ""
    Write-Host "=================================================================" -ForegroundColor Green
    Write-Host "                   SERVER DEPLOYMENT RESULT                      " -ForegroundColor Green
    Write-Host "=================================================================" -ForegroundColor Green
    Write-Host ("Status   : " + $response.status) -ForegroundColor Cyan
    Write-Host ("Message  : " + $response.message) -ForegroundColor Cyan
    $durationText = if ($response.duration) { $response.duration } else { "$durationSec s" }
    Write-Host ("Duration : " + $durationText) -ForegroundColor Cyan
    Write-Host ""
    Write-Host "---------------- COMMAND EXECUTION LOG ----------------" -ForegroundColor Yellow

    if ($response.output) {
        foreach ($step in $response.output) {
            Write-Host ("$ " + $step.command) -ForegroundColor White
            if ($step.result) {
                Write-Host $step.result -ForegroundColor DarkGray
            }
        }
    }

    Write-Host "=================================================================" -ForegroundColor Green
    Write-Host ">>> DIRECT DEPLOY SUCCESSFUL! Your live website is updated! <<<" -ForegroundColor Green
    Write-Host "=================================================================" -ForegroundColor Green
}
catch {
    Write-Host ""
    Write-Host "=================================================================" -ForegroundColor Red
    Write-Host "                   DIRECT DEPLOYMENT ERROR                       " -ForegroundColor Red
    Write-Host "=================================================================" -ForegroundColor Red
    Write-Host ("Error: " + $_.Exception.Message) -ForegroundColor Red

    if ($_.Exception.Response) {
        try {
            $stream = $_.Exception.Response.GetResponseStream()
            $reader = New-Object System.IO.StreamReader($stream)
            $resBody = $reader.ReadToEnd()
            if ($resBody) {
                Write-Host "Server Details: " -ForegroundColor Yellow
                Write-Host $resBody -ForegroundColor Yellow
            }
        } catch {}
    }
    Write-Host "=================================================================" -ForegroundColor Red
}
finally {
    if (Test-Path $tempZip) {
        Remove-Item $tempZip -Force -ErrorAction SilentlyContinue
    }
}
