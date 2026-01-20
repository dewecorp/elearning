@echo off
echo ================================================
echo INTERACTIVE GIT COMMIT & PUSH SCRIPT
echo ================================================
echo.

cd /d "d:\laragon\www\ELearning"

REM Check git repo
git status >nul 2>&1
if errorlevel 1 (
    echo Setting up Git repository...
    git init
)

REM Add changes
git add .

REM SHOW EXPLICIT PROMPT FOR COMMIT MESSAGE
echo ------------------------------------------------
echo NOW ENTER YOUR COMMIT MESSAGE WHEN PROMPTED BELOW
echo ------------------------------------------------
echo.
set /p user_commit_msg="YOUR COMMIT MESSAGE: "
if "%user_commit_msg%"=="" set user_commit_msg="Updated project files"

echo.
echo Using message: %user_commit_msg%
git commit -m "%user_commit_msg%"

echo.
REM SHOW EXPLICIT PROMPT FOR PUSH DECISION
echo ---------------------------------------------------
echo NOW DECIDE WHETHER TO PUSH - TYPE 'YES' OR 'NO' BELOW
echo ---------------------------------------------------
echo.
set /p user_push_choice="PUSH TO REMOTE? (YES/no): "
if /i "%user_push_choice%"=="YES" (
    echo.
    echo Pushing to remote...
    git push
) else (
    echo.
    echo Skipped pushing to remote.
)

echo.
echo Done!
pause