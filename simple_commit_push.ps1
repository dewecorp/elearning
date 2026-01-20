Write-Host "Committing and pushing changes to Git..." -ForegroundColor Green
Write-Host ""

# Navigate to the project directory
Set-Location "d:\laragon\www\ELearning"

# Check if git repository exists
try {
    git status *> $null
    if ($LASTEXITCODE -ne 0) {
        throw "Git command failed"
    }
} catch {
    Write-Host "Initializing new Git repository..." -ForegroundColor Yellow
    git init
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Failed to initialize Git repository" -ForegroundColor Red
        Read-Host "Press Enter to exit"
        exit 1
    }
}

# Add all changes to staging
Write-Host "Adding changes to staging..." -ForegroundColor Green
git add .

# Check if there are changes to commit
$status = git status --porcelain

if ($LASTEXITCODE -ne 0) {
    Write-Host "No changes to commit or git command failed" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
} elseif ($status.Count -eq 0) {
    Write-Host "No changes to commit" -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 0
}

# Commit the changes with a default message
Write-Host "Committing changes..." -ForegroundColor Green
git commit -m "Update: Improve footer design and remove user info from admin sidebar"

if ($LASTEXITCODE -ne 0) {
    Write-Host "Commit failed" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
} else {
    Write-Host "Successfully committed changes!" -ForegroundColor Green
    Write-Host ""
    
    # Show commit information
    $logInfo = git log -1 --pretty=format:"Commit: %h%nDate: %ad%nMessage: %s" --date=short
    Write-Host $logInfo -ForegroundColor Cyan
}

Write-Host ""
Write-Host "Pushing changes to remote..." -ForegroundColor Green
git push

if ($LASTEXITCODE -ne 0) {
    Write-Host "Push failed - you may need to set up a remote origin first" -ForegroundColor Yellow
    Write-Host "To add a remote: git remote add origin [your-repository-url]" -ForegroundColor Yellow
} else {
    Write-Host "Successfully pushed changes!" -ForegroundColor Green
}

Write-Host ""
Write-Host "Script completed successfully!" -ForegroundColor Green
Read-Host "Press Enter to exit"