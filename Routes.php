<?php

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('apiato:generate:command:cli', function() {
    $section = $this->ask('Enter the name of the Section [AppSection]');
    if (empty($section)) {
        $section = 'AppSection';
    }

    $sectionPath = app_path('Containers/'.$section);
    if (!file_exists($sectionPath)) {

        $this->error('Section Not Exist');
        exit();
    }
    
    $container = $this->ask('Enter the name of the Container');
    if (empty($container)) {
        $this->error('Please Input of Name Container');
        exit();
    }

    $containerPath = $sectionPath.DIRECTORY_SEPARATOR.$container;
    if (!file_exists($containerPath)) {
        $this->error('Container Not Exist');
        exit();
    }

    $commandname = $this->ask('Enter the name of the Command');
    if (empty($commandname)) {
        $this->error('Please Input of Name Command');
        exit();
    }
    $commandname = $commandname.'Command';

    $templateCommand = 'PD9waHAKCm5hbWVzcGFjZSBBcHBcQ29udGFpbmVyc1w9PT09c2VjdGlvbj09PT1cPT09PWNvbnRhaW5lcj09PT1cVUlcQ0xJXENvbW1hbmRzOwoKdXNlIEFwcFxTaGlwXFBhcmVudHNcQ29tbWFuZHNcQ29uc29sZUNvbW1hbmQ7CgpjbGFzcyA9PT09Y29tbWFuZG5hbWU9PT09IGV4dGVuZHMgQ29uc29sZUNvbW1hbmQKewogICAgcHJvdGVjdGVkICRzaWduYXR1cmUgPSAnY29tbWFuZDpuYW1lJzsKCiAgICBwcm90ZWN0ZWQgJGRlc2NyaXB0aW9uID0gJ2NvbW1hbmQgZGlzY3JpcHRpb24nOwoKICAgIHB1YmxpYyBmdW5jdGlvbiBoYW5kbGUoKQogICAgewogICAgfQp9';
    $templateCommand = @base64_decode($templateCommand);

    $command['section'] = $section;
    $command['container'] = $container;
    $command['commandname'] = $commandname;

    foreach ($command as $key => $value) {
        $templateCommand = str_replace("====$key====", $value, $templateCommand);
    }

    $CLICommandPath = $containerPath.DIRECTORY_SEPARATOR.'UI'.DIRECTORY_SEPARATOR.'CLI';
    if (!file_exists($CLICommandPath)) {
        @mkdir($CLICommandPath, '0755');
    }
    $UICommandPath = $CLICommandPath.DIRECTORY_SEPARATOR.'Commands';
    if (!file_exists($UICommandPath)) {
        @mkdir($UICommandPath, '0755');
    }

    $targetNewCommandPath = $UICommandPath.DIRECTORY_SEPARATOR.$commandname.'.php';
    $file = fopen($targetNewCommandPath, 'w');
    flock($file, LOCK_EX);
    fwrite($file, $templateCommand);
    flock($file, LOCK_UN);
    fclose($file);

    $this->info('Success Create Command');

})->purpose('Create a Command for apiato from scracth (CLI Part)');