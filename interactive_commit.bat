@echo off
echo ========================================================
echo        INTERACTIVE GIT COMMIT AND PUSH SCRIPT
echo ========================================================
echo.
echo Checking for changes in your project...
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

REM Prompt for custom commit message
echo.
echo ===================================================================
echo  PROMPT: You will now be asked to enter a commit message
echo  Make sure to type your message and press ENTER
echo ===================================================================
echo.
set /p commit_msg="Enter your commit message (or press Enter for default): "
if "%commit_msg%"=="" (
    set commit_msg=Update: Improve footer design and remove user info from admin sidebar
)

echo.
echo Using commit message: %commit_msg%
echo.
echo Committing changes...
git commit -m "%commit_msg%"

REM Show commit result
if errorlevel 1 (
    echo.
    echo Commit failed
    pause
    exit /b 1
) else (
    echo.
    echo Successfully committed changes!
    echo.
    git log -1 --pretty=format:"Commit: %%h%%nDate: %%ad%%nMessage: %%s" --date=short
)

echo.
echo ===================================================================
echo  PROMPT: Do you want to push these changes to remote repository?
echo  Type 'y' and press ENTER to push, or just press ENTER to skip
echo ===================================================================
REM Ask if user wants to push changes
set /p push_choice="Do you want to push changes to remote repository? (y/N): "
if /i "%push_choice%"=="y" (
    echo.
    echo Pushing changes to remote...
    git push
    if errorlevel 1 (
        echo Push failed
    ) else (
        echo Successfully pushed changes!
    )
) else (
    echo Changes committed locally. Remember to push later when ready.
)

echo.
echo Script completed successfully!
echo.
echo Press any key to exit...
pause >nul