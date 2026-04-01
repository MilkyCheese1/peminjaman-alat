'======================================
' Equipment Rental System Launcher
' Run this to start backend & frontend
'======================================

Dim objShell, objFSO, projectDir, startBat

Set objShell = CreateObject("WScript.Shell")
Set objFSO = CreateObject("Scripting.FileSystemObject")

' Get the directory where this script is located
projectDir = objFSO.GetParentFolderName(WScript.ScriptFullName)

' Path to START.bat
startBat = projectDir & "\START.bat"

' Check if START.bat exists
If Not objFSO.FileExists(startBat) Then
    MsgBox "Error: START.bat not found in " & projectDir, vbCritical, "Launcher Error"
    WScript.Quit 1
End If

' Run START.bat
On Error Resume Next
objShell.Run "cmd.exe /c """ & startBat & """", 1, False
If Err.Number <> 0 Then
    MsgBox "Error: Could not launch START.bat" & vbCrLf & "Error: " & Err.Description, vbCritical, "Launcher Error"
    WScript.Quit 1
End If
