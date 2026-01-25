param(
    [string]$Output = "dist\\cpanel-deploy.zip",
    [switch]$SkipBuild,
    [switch]$SkipVendor,
    [switch]$IncludeEnv
)

$ErrorActionPreference = "Stop"

$root = Resolve-Path (Join-Path $PSScriptRoot "..")
Push-Location $root

if (-not $SkipBuild) {
    $npm = Get-Command npm -ErrorAction SilentlyContinue
    if (-not $npm) {
        throw "npm was not found. Run with -SkipBuild or install Node.js."
    }

    npm run build
}

$stage = Join-Path $root "dist\\cpanel"
if (Test-Path $stage) {
    Remove-Item $stage -Recurse -Force
}
New-Item -ItemType Directory -Path $stage | Out-Null

$dirs = @(
    "app",
    "bootstrap",
    "config",
    "database",
    "public",
    "resources",
    "routes",
    "storage"
)

if (-not $SkipVendor -and (Test-Path (Join-Path $root "vendor"))) {
    $dirs += "vendor"
}

foreach ($dir in $dirs) {
    $src = Join-Path $root $dir
    if (Test-Path $src) {
        Copy-Item -Recurse -Force -Path $src -Destination $stage
    }
}

$files = @("artisan", "composer.json", "composer.lock")
if ($IncludeEnv -and (Test-Path (Join-Path $root ".env"))) {
    $files += ".env"
}

foreach ($file in $files) {
    $src = Join-Path $root $file
    if (Test-Path $src) {
        Copy-Item -Force -Path $src -Destination $stage
    }
}

$clearTargets = @(
    "storage\\logs",
    "storage\\framework\\cache",
    "storage\\framework\\views",
    "storage\\framework\\sessions"
)

foreach ($target in $clearTargets) {
    $path = Join-Path $stage $target
    if (Test-Path $path) {
        Get-ChildItem -Force -Path $path | Remove-Item -Force -Recurse
    }
}

foreach ($target in $clearTargets) {
    $path = Join-Path $stage $target
    if (-not (Test-Path $path)) {
        New-Item -ItemType Directory -Path $path | Out-Null
    }
}

$zipPath = Join-Path $root $Output
$zipDir = Split-Path $zipPath
if (-not (Test-Path $zipDir)) {
    New-Item -ItemType Directory -Path $zipDir | Out-Null
}
if (Test-Path $zipPath) {
    Remove-Item $zipPath -Force
}

Compress-Archive -Path (Join-Path $stage "*") -DestinationPath $zipPath
Write-Host "Created $zipPath"

Pop-Location
