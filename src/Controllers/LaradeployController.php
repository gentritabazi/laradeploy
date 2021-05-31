<?php

namespace GentritAbazi\Laradeploy\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class LaradeployController
{
    public function deploy(Request $request)
    {
        $this->validateRequest($request);

        $allowedBranch = Config::get('laradeploy.branch');
        $githubRef = explode('/', $request->get('ref', []));
        $githubRef = array_pop($githubRef);

        if ($allowedBranch != $githubRef) {
            return;
        }

        $this->executeShellCommands();

        $this->log();
    }

    private function executeShellCommands()
    {
        $process = Process::fromShellCommandline('sh ./scripts/deploy.sh');
        $process->setWorkingDirectory(App::basePath());
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    private function log()
    {
        $logChannel = Config::get('laradeploy.log_channel');

        if (is_null($logChannel)) {
            return;
        }

        Log::channel($logChannel)->info('🎉 App Deployed!');
    }

    private function validateRequest(Request $request)
    {
        $githubPayload = $request->getContent();
        $githubHash = $request->header('X-Hub-Signature');
        $localToken = Config::get('laradeploy.deploy_secret');
        $localHash = 'sha1=' . hash_hmac('sha1', $githubPayload, $localToken, false);

        if ($request->headers->get('X-Hub-Signature') == null) {
            throw new \Error('Header X-Hub-Signature not set.');
        }

        if (!hash_equals($githubHash, $localHash)) {
            throw new \Error('Could not verify request signature.');
        }
    }
}
