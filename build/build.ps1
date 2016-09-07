$sourceCodeDir = (Get-ChildItem Env:BUILD_SOURCESDIRECTORY).Value
$wwwRootDir = Join-Path -Path $sourceCodeDir -ChildPath "www"
$outputFile = Join-Path -Path $sourceCodeDir -ChildPath "build\www.zip"

Add-Type -A System.IO.Compression.FileSystem
[IO.Compression.ZipFile]::CreateFromDirectory($wwwRootDir, $outputFile)
