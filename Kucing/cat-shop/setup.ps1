# PowerShell setup script: creates Laravel project and copies scaffold files
# Run as: .\setup.ps1

param(
    [string]$ProjectName = 'cat-shop-app'
)

$cwd = Split-Path -Parent $MyInvocation.MyCommand.Definition
Write-Host "Working in: $cwd"

# Check composer
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host 'Composer not found in PATH. Install Composer first: https://getcomposer.org' -ForegroundColor Yellow
    exit 1
}

# Create laravel project
$target = Join-Path $cwd $ProjectName
if (Test-Path $target) {
    Write-Host "Target $target exists. Skipping composer create-project." -ForegroundColor Yellow
} else {
    composer create-project laravel/laravel $target
}

Write-Host 'Copying scaffold files into Laravel project...'
# Copy scaffold files (except setup itself) into the new project
$copyFrom = $cwd
$exclude = @('setup.ps1','README.md')
Get-ChildItem -Path $copyFrom -Recurse | Where-Object { -not ($exclude -contains $_.Name) } | ForEach-Object {
    $rel = $_.FullName.Substring($copyFrom.Length).TrimStart('\')
    $dest = Join-Path $target $rel
    if ($_.PSIsContainer) {
        New-Item -ItemType Directory -Force -Path $dest | Out-Null
    } else {
        Copy-Item -Force -Path $_.FullName -Destination $dest
    }
}

Write-Host 'Installing NPM deps and building assets (optional)...'
Push-Location $target
if (Test-Path package.json) {
    npm install
    npm run dev
}

Write-Host 'Generating app key and migrating database...'
php artisan key:generate
php artisan migrate

Write-Host 'Setup complete. Start server with: php artisan serve'
Pop-Location
