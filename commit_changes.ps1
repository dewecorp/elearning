Write-Host "Committing changes to Git..." -ForegroundColor Green
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

# Prompt for custom commit message
Write-Host ""
$commitMsg = Read-Host "Enter your commit message (or press Enter for default)"
if ([string]::IsNullOrWhiteSpace($commitMsg)) {
    $commitMsg = "Update: Improve footer design and remove user info from admin sidebar"
}

Write-Host ""
Write-Host "Using commit message: $commitMsg" -ForegroundColor Cyan
Write-Host ""

# Commit the changes with the user-provided message
Write-Host "Committing changes..." -ForegroundColor Green
$commitResult = git commit -m $commitMsg

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

# Ask if user wants to push changes
$pushChoice = Read-Host "Do you want to push changes to remote repository? (y/N)"
if ($pushChoice -eq "y" -or $pushChoice -eq "Y") {
    Write-Host "Pushing changes to remote..." -ForegroundColor Green
    git push
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Successfully pushed changes!" -ForegroundColor Green
    } else {
        Write-Host "Push failed" -ForegroundColor Red
    }
} else {
    Write-Host "Changes committed locally. Remember to push later when ready." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Script completed successfully!" -ForegroundColor Green
Read-Host "Press Enter to exit"