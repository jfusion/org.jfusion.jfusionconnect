@echo off
SET FULLPATH=%CD%
setlocal enableextensions

:START
IF NOT EXIST administrator echo JFusion files not found. goto end

echo Looking for required commands...
IF NOT EXIST c:\WINDOWS\system32\7za.exe (
	echo "7za.exe does not exist!  Please see create_release_readme.txt".
	goto end
)
IF NOT EXIST c:\WINDOWS\system32\sed.exe (
	echo "sed.exe does not exist! Please see create_release_readme.txt". 
	goto end
)
IF NOT EXIST "C:\Program Files\CollabNet\Subversion Client\svnversion.exe"  (
	IF NOT EXIST "C:\Program Files (x86)\CollabNet\Subversion Client\svnversion.exe"  (
		echo "CollabNet SVN client not installed!  Please see create_release_readme.txt". 
		goto end
	)
)

cls
echo Choices:
echo 1 - Create Main Packages
echo 2 - Create Plugin and Module Packages
echo 3 - Create All Packages
echo 4 - Delete Main Packages
echo 5 - Delete Plugin and Module Packages
echo 6 - Delete All Packages
set /p useraction=Choose a number(1-6):
set action=%useraction:~0,1%


IF "%action%"=="6" goto CLEAR_ALL
IF "%action%"=="5" goto CLEAR_PACKAGES
IF "%action%"=="4" goto CLEAR_MAIN
IF "%action%"=="3" goto CREATE_ALL
IF "%action%"=="2" goto CREATE_PACKAGES
IF "%action%"=="1" goto CREATE_MAIN
echo Invalid Choice
goto start

:CLEAR_ALL
echo Clearing All Packages
goto CLEAR_MAIN
goto CLEAR_PACKAGES
goto end

:CLEAR_PACKAGES
echo Remove module and plugin packages
del "%FULLPATH%\administrator\components\com_jfusionconnect\packages\*.zip"
IF "%action%"=="5" goto end

:CLEAR_MAIN
echo Remove main packages
del *.zip
IF "%action%"=="4" goto end

:CREATE_ALL
goto create_packages
goto create_main
goto end

:CREATE_PACKAGES
del "%FULLPATH%\administrator\components\com_jfusionconnect\packages\*.zip"

chdir %FULLPATH%\plugins\
7za a "%FULLPATH%\administrator\components\com_jfusionconnect\packages\jfusionconnect_plugin_system.zip" .\system\jfusionconnect* -xr!*.svn* > NUL

chdir %FULLPATH%

chdir %FULLPATH%\libraries\
7za a "%FULLPATH%\administrator\components\com_jfusionconnect\packages\lib_openid.zip" .\openid\* -xr!*.svn* > NUL

chdir %FULLPATH%

IF "%action%"=="2" goto end


:CREATE_MAIN
chdir %FULLPATH%

echo Prepare the files for packaging
md tmp
md tmp\admin
c:\windows\system32\xcopy /E /C /V /Y "%FULLPATH%\administrator\components\com_jfusionconnect\*.*" "%FULLPATH%\tmp\admin" > NUL
del "%FULLPATH%\tmp\admin\jfusionconnect.xml"

md tmp\admin\languages
c:\windows\system32\xcopy /E /C /V /Y "%FULLPATH%\administrator\language\en-GB\en-GB.com_jfusionconnect.ini" "%FULLPATH%\tmp\admin\languages\en-GB\" > NUL
c:\windows\system32\xcopy /E /C /V /Y "%FULLPATH%\administrator\language\en-GB\en-GB.plg_system_jfusionconnect.ini" "%FULLPATH%\tmp\admin\languages\en-GB\" > NUL

md tmp\front
c:\windows\system32\xcopy /E /C /V /Y "%FULLPATH%\components\com_jfusionconnect\*.*" "%FULLPATH%\tmp\front" > NUL

md tmp\front\languages
c:\windows\system32\xcopy /E /C /V /Y "%FULLPATH%\language\en-GB\en-GB.com_jfusionconnect.ini" "%FULLPATH%\tmp\front\languages\en-GB\" > NUL


copy "%FULLPATH%\administrator\components\com_jfusionconnect\jfusionconnect.xml" "%FULLPATH%\tmp" /V /Y > NUL
copy "%FULLPATH%\administrator\components\com_jfusionconnect\install.jfusionconnect.php" "%FULLPATH%\tmp" /V /Y > NUL
copy "%FULLPATH%\administrator\components\com_jfusionconnect\uninstall.jfusionconnect.php" "%FULLPATH%\tmp" /V /Y > NUL

echo Update the revision number

move "%FULLPATH%\tmp\jfusionconnect.xml" "%FULLPATH%\tmp\jfusionconnect.tmp"

for /f "tokens=*" %%a in ( 'svnversion' ) do ( set REVISION=%%a )

if "%REVISION:~-1%" == " " SET REVISION=%REVISION:~0,-1%
if "%REVISION:~-1%" == "P" SET REVISION=%REVISION:~0,-1%
if "%REVISION:~-1%" == "S" SET REVISION=%REVISION:~0,-1%
if "%REVISION:~-1%" == "M" SET REVISION=%REVISION:~0,-1%

echo Revision set to %REVISION%
c:\WINDOWS\system32\sed.exe "s/<revision>\$revision\$<\/revision>/<revision>%REVISION%<\/revision>/g" "%FULLPATH%\tmp\jfusionconnect.tmp" > "%FULLPATH%\tmp\jfusionconnect.xml"
del "%FULLPATH%\tmp\jfusionconnect.tmp"

echo Create the new master package
chdir %FULLPATH%
del *.zip

7za a "%FULLPATH%\jfusionconnect_package.zip" .\tmp\* -xr!*.svn* > NUL

RMDIR "%FULLPATH%\tmp" /S /Q

:: echo Create a ZIP containing all files to allow for easy updates
:: chdir %FULLPATH%
:: 7za a "%FULLPATH%\jfusionconnect_files.zip" administrator components language libraries plugins -r -xr!*.svn* > NUL
IF "%action%"=="1" goto end

:end
echo Complete
pause>nul