@echo off
echo Menginisialisasi proses deploy...

REM Cek apakah remote origin sudah ada
git remote get-url origin >nul 2>&1
if %errorlevel% neq 0 (
    echo Menambahkan remote origin...
    git remote add origin https://github.com/dewecorp/elearning.git
) else (
    echo Remote origin sudah ada.
)

echo.
echo Menambahkan file ke staging...
git add .

echo.
echo Membuat commit...
set timestamp=%date% %time%
git commit -m "Auto deploy: %timestamp%"

echo.
echo Mengirim ke GitHub...
git push -u origin master

echo.
echo Membuat file ZIP...
set "zip_name=elearning_project.zip"
echo Mengompresi file ke %zip_name%...
powershell -Command "Get-ChildItem -Path . -Exclude *.git*,*.zip | Compress-Archive -DestinationPath %zip_name% -Force"

echo.
echo Selesai! 
echo Repository: https://github.com/dewecorp/elearning.git
echo File ZIP: %CD%\%zip_name%
pause
