@echo off
echo Committing and pushing changes to Git...
echo.

REM Navigate to the project directory
cd /d "d:\laragon\www\ELearning"

REM Check if git repository exists
git status >nul 2>&1
if errorlevel 1 (
    echo Initializing new Git repository...
    git init
    if errorlevel 1 (
        echo Failed to initialize Git repository
        pause
        exit /b 1
    )
)

REM Add all changes to staging
echo Adding changes to staging...
git add .

REM Check if there are changes to commit
git status --porcelain
if errorlevel 1 (
    echo No changes to commit or git command failed
    pause
    exit /b 1
) else (
    REM Check if there are actually any changes
    for /f %%i in ('git status --porcelain ^| find /c /v ""') do set count=%%i
    if %count%==0 (
        echo No changes to commit
        pause
        exit /b 0
    )
)

REM Commit the changes with a default message
echo Committing changes...
git commit -m "Update: Improve footer design and remove user info from admin sidebar"

REM Show commit result
if errorlevel 1 (
    echo Commit failed
    pause
    exit /b 1
) else (
    echo Successfully committed changes!
    echo.
    git log -1 --pretty=format:"Commit: %%h%%nDate: %%ad%%nMessage: %%s" --date=short
)

echo.
echo Pushing changes to remote...
git push

if errorlevel 1 (
    echo Push failed - you may need to set up a remote origin first
    echo To add a remote: git remote add origin [your-repository-url]
) else (
    echo Successfully pushed changes!
)

echo.
echo Press any key to exit...
pause >nul