<?php

namespace Maso\WebShell\http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Maso\WebShell\http\Requests\CommandRequest;

class TerminalController extends Controller
{
    /**
     * Display the terminal view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('web-shell::terminal');
    }

    /**
     * Handle and run the given command.
     *
     * @param CommandRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleCommand(CommandRequest $request)
    {
        $command = $request->input('command');

        $output = $this->executeCommand($command);

        $currentDirectory = $this->currentDirectory();

        return response()->json(['output' => $output, 'directory' => $currentDirectory]);
    }

    /**
     * Get the current working directory.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWorkingDirectory()
    {
        $workingDirectory = $this->currentDirectory();
        return response()->json(['directory' => $workingDirectory]);
    }

    /**
     * Get the current working directory.
     *
     * @return string
     */
    private function currentDirectory(): string
    {
        $currentDirectory = str_replace('\\', '/', Session::get('web-shell.directory', base_path()));
        return $currentDirectory;
    }

    /**
     * Handle the 'cd' command to change directories.
     *
     * @param string $command
     * @return string|bool Returns the new current directory or false if the directory is not found.
     */
    private function handleChangeDirectoryCommand(string $command)
    {
        if (str_starts_with($command, 'cd '))
        {
            $pos = strpos($command, ' ');
            $requestedDirectory = substr($command, $pos + 1);
            $completeRequestedPath = $this->currentDirectory() . '/' . $requestedDirectory;

            if (!is_dir($completeRequestedPath))
            {
                return false;
            }
            else
            {
                chdir($completeRequestedPath);
                $currentDirectory = getcwd();
                Session::put('web-shell.directory', str_replace('\\', '/', $currentDirectory));
                return $currentDirectory;
            }
        }
        return false;
    }

    /**
     * Execute a command or a chain of commands with && and || operators.
     *
     * @param string $command
     * @return array The output of the executed commands.
     */
    private function executeCommand(string $command): array
    {
        $output = [];

        // Split the command by `&&` and `||` operators
        $commands = preg_split('/(\s*&&\s*|\s*\|\|\s*)/', $command, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $isLastResultSuccessful = true;

        foreach ($commands as $cmdPart)
        {
            if (trim($cmdPart) === '&&' && !$isLastResultSuccessful)
            {
                break;
            }
            elseif (trim($cmdPart) === '||' && $isLastResultSuccessful)
            {
                break;
            }
            // If command is a change directory command handle it separately (special case)
            if (str_starts_with($cmdPart, 'cd'))
            {
                if (!$this->handleChangeDirectoryCommand($cmdPart))
                {
                    $output[] = 'System cannot find the path';
                    $isLastResultSuccessful = false;
                }
            }
            else
            {
                // Move to the current working directory for each command
                chdir($this->currentDirectory());

                // Execute the command
                $commandOutput = [];
                $resultCode = 0;
                exec($cmdPart . ' 2>&1', $commandOutput, $resultCode);

                $output = array_merge($output, $commandOutput);

                // Determine if the command was successful or failed
                $isLastResultSuccessful = ($resultCode === 0);
            }
        }

        return $output;
    }
}
