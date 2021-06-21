<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config

set('application', 'api');
set('deploy_path', '~/home/www/dom1.click/{{application}}');
set(
    'env',
    [
        'APP_ENV' => 'prod',
    ]
);
set('keep_releases', 2);
set('repository', 'git@github.com:rybindmitriy/dom1.click-api.git');
set('ssh_multiplexing', true);

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('')
    ->setRemoteUser('root');

// Tasks

task('build', function () {
    cd('{{release_path}}');
    run('npm run build');
});

after('deploy:failed', 'deploy:unlock');
