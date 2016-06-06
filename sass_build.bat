@echo off
for %%* in (.) do set CurrDirName=%%~nx*
echo [BUILDING CSS FROM SASS]
sass --update application/sass/app.sass:public/res/app.css --stop-on-error --sourcemap=none --no-cache --style expanded