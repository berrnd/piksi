set projectPath=%~dp0
if %projectPath:~-1%==\ set projectPath=%projectPath:~0,-1%
set projectPath=%projectPath%\..

set releasePath=%projectPath%\.release
mkdir "%releasePath%"

copy "%projectPath%\version.json" versiontemp.json
for /f "tokens=*" %%a in ('jq .Version versiontemp.json --raw-output') do set version=%%a
del versiontemp.json

del "%releasePath%\piksi_%version%.zip"
7za a -r "%releasePath%\piksi_%version%.zip" "%projectPath%\*" -xr!.* -xr!composer.json -xr!composer.lock -xr!package.json -xr!yarn.lock
7za a "%releasePath%\piksi_%version%.zip" "%projectPath%\public\.htaccess"
7za rn "%releasePath%\piksi_%version%.zip" .htaccess public\.htaccess
7za d "%releasePath%\piksi_%version%.zip" data\*.* data\viewcache\*
7za a "%releasePath%\piksi_%version%.zip" "%projectPath%\data\.htaccess"
7za rn "%releasePath%\piksi_%version%.zip" .htaccess data\.htaccess
