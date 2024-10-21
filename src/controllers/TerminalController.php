<?php

namespace Maso\WebShell\controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class TerminalController extends Controller
{

    public function index()
    {
        return view('web-shell::terminal');
    }

    /**
     * Run the command.
     */
    public function runCommand(Request $request)
    {
        $output = [];

        $command = $request->input('command');
        if (str_starts_with($command, 'cd '))
        {
            $pos = strpos($command, ' ');
            $requestedDirectory = substr($command, $pos + 1);
            $completeRequestedPath = $this->currentDirectory() . '/' . $requestedDirectory;

            if (is_dir($completeRequestedPath))
            {
                chdir($completeRequestedPath);
                $currentDirectory = getcwd();
                Session::put('web-shell.directory', str_replace('\\', '/', $currentDirectory));
            }
            else
            {
                $output[] = 'System cannot find the path';
            }
        }

        else
        {

            $currentDirectory = $this->currentDirectory();

            chdir($currentDirectory);
            exec($command  . ' 2>&1', $output);
        }

        return response()->json(['output' => $output, 'directory' => $this->currentDirectory()]);
    }

    public function getWorkingDirectory()
    {
        $workingDirectory = $this->currentDirectory();
        return response()->json(['directory' => $workingDirectory]);
    }

    private function currentDirectory(): string
    {
        $currentDirectory = str_replace('\\', '/', Session::get('web-shell.directory', base_path()));
        return $currentDirectory;
    }
}
