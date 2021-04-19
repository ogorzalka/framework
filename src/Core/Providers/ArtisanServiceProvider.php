<?php

namespace Themosis\Core\Providers;

use Illuminate\Auth\Console\ClearResetsCommand;
use Illuminate\Cache\Console\CacheTableCommand;
use Illuminate\Cache\Console\ClearCommand as CacheClearCommand;
use Illuminate\Cache\Console\ForgetCommand as CacheForgetCommand;
use Illuminate\Console\Scheduling\ScheduleFinishCommand;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Database\Console\DbCommand;
use Illuminate\Database\Console\DumpCommand;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Database\Console\Migrations\InstallCommand;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Console\Migrations\RefreshCommand;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Database\Console\WipeCommand;
use Illuminate\Notifications\Console\NotificationTableCommand;
use Illuminate\Queue\Console\BatchesTableCommand;
use Illuminate\Queue\Console\ClearCommand as QueueClearCommand;
use Illuminate\Queue\Console\FailedTableCommand;
use Illuminate\Queue\Console\FlushFailedCommand as FlushFailedQueueCommand;
use Illuminate\Queue\Console\ForgetFailedCommand as ForgetFailedQueueCommand;
use Illuminate\Queue\Console\ListenCommand as ListenQueueCommand;
use Illuminate\Queue\Console\ListFailedCommand as ListFailedQueueCommand;
use Illuminate\Queue\Console\PruneBatchesCommand as PruneBatchesQueueCommand;
use Illuminate\Queue\Console\RestartCommand as RestartQueueCommand;
use Illuminate\Queue\Console\RetryBatchCommand as QueueRetryBatchCommand;
use Illuminate\Queue\Console\RetryCommand as RetryQueueCommand;
use Illuminate\Queue\Console\TableCommand;
use Illuminate\Queue\Console\WorkCommand as WorkQueueCommand;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Routing\Console\MiddlewareMakeCommand;
use Illuminate\Session\Console\SessionTableCommand;
use Illuminate\Support\ServiceProvider;
use Themosis\Auth\Console\AuthMakeCommand;
use Themosis\Core\Console\CastMakeCommand;
use Themosis\Core\Console\ChannelMakeCommand;
use Themosis\Core\Console\ClearCompiledCommand;
use Themosis\Core\Console\ComponentMakeCommand;
use Themosis\Core\Console\ConfigCacheCommand;
use Themosis\Core\Console\ConfigClearCommand;
use Themosis\Core\Console\ConsoleMakeCommand;
use Themosis\Core\Console\CustomerTableCommand;
use Themosis\Core\Console\DownCommand;
use Themosis\Core\Console\DropinClearCommand;
use Themosis\Core\Console\EnvironmentCommand;
use Themosis\Core\Console\EventCacheCommand;
use Themosis\Core\Console\EventClearCommand;
use Themosis\Core\Console\EventGenerateCommand;
use Themosis\Core\Console\EventListCommand;
use Themosis\Core\Console\EventMakeCommand;
use Themosis\Core\Console\ExceptionMakeCommand;
use Themosis\Core\Console\FormMakeCommand;
use Themosis\Core\Console\HookMakeCommand;
use Themosis\Core\Console\JobMakeCommand;
use Themosis\Core\Console\KeyGenerateCommand;
use Themosis\Core\Console\ListenerMakeCommand;
use Themosis\Core\Console\MailMakeCommand;
use Themosis\Core\Console\ModelMakeCommand;
use Themosis\Core\Console\NotificationMakeCommand;
use Themosis\Core\Console\ObserverCommand;
use Themosis\Core\Console\OptimizeClearCommand;
use Themosis\Core\Console\OptimizeCommand;
use Themosis\Core\Console\PackageDiscoverCommand;
use Themosis\Core\Console\PasswordResetTableCommand;
use Themosis\Core\Console\PluginInstallCommand;
use Themosis\Core\Console\PolicyMakeCommand;
use Themosis\Core\Console\ProviderMakeCommand;
use Themosis\Core\Console\RequestMakeCommand;
use Themosis\Core\Console\ResourceMakeCommand;
use Themosis\Core\Console\RouteCacheCommand;
use Themosis\Core\Console\RouteClearCommand;
use Themosis\Core\Console\RouteListCommand;
use Themosis\Core\Console\RuleMakeCommand;
use Themosis\Core\Console\ServeCommand;
use Themosis\Core\Console\StubPublishCommand;
use Themosis\Core\Console\TestMakeCommand;
use Themosis\Core\Console\ThemeInstallCommand;
use Themosis\Core\Console\UpCommand;
use Themosis\Core\Console\VendorPublishCommand;
use Themosis\Core\Console\ViewClearCommand;
use Themosis\Core\Console\WidgetMakeCommand;

class ArtisanServiceProvider extends ServiceProvider
{
    /**
     * Defer the loading of the provider.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Commands to register.
     *
     * @var array
     */
    protected $commands = [
        'CacheClear' => 'command.cache.clear',
        'CacheForget' => 'command.cache.forget',
        'ClearCompiled' => 'command.clear-compiled',
        'ClearResets' => 'command.auth.resets.clear',
        'ConfigCache' => 'command.config.cache',
        'ConfigClear' => 'command.config.clear',
        'Db' => DbCommand::class,
        'DbWipe' => 'command.db.wipe',
        'Down' => 'command.down',
        'DropinClear' => 'command.dropin.clear',
        'Environment' => 'command.environment',
        'EventCache' => 'command.event.cache',
        'EventClear' => 'command.event.clear',
        'EventList' => 'command.event.list',
        'KeyGenerate' => 'command.key.generate',
        'Optimize' => 'command.optimize',
        'OptimizeClear' => 'command.optimize.clear',
        'PackageDiscover' => 'command.package.discover',
        'QueueClear' => 'command.queue.clear',
        'QueueFailed' => 'command.queue.failed',
        'QueueFlush' => 'command.queue.flush',
        'QueueForget' => 'command.queue.forget',
        'QueueListen' => 'command.queue.listen',
        'QueuePruneBatches' => 'command.queue.prune-batches',
        'QueueRestart' => 'command.queue.restart',
        'QueueRetry' => 'command.queue.retry',
        'QueueRetryBatch' => 'command.queue.retry-batch',
        'QueueWork' => 'command.queue.work',
        'RouteCache' => 'command.route.cache',
        'RouteClear' => 'command.route.clear',
        'RouteList' => 'command.route.list',
        'ScheduleFinish' => ScheduleFinishCommand::class,
        'ScheduleRun' => ScheduleRunCommand::class,
        'SchemaDump' => 'command.schema.dump',
        'Seed' => 'command.seed',
        'Up' => 'command.up',
        'ViewClear' => 'command.view.clear'
    ];

    /**
     * Development commands to register.
     *
     * @var array
     */
    protected $devCommands = [
        //'AuthMake' => 'command.auth.make',
        'CacheTable' => 'command.cache.table',
        'CastMake' => 'command.cast.make',
        'ChannelMake' => 'command.channel.make',
        'ComponentMake' => 'command.component.make',
        'ConsoleMake' => 'command.console.make',
        'ControllerMake' => 'command.controller.make',
        'CustomerTable' => 'command.customer.table',
        'EventGenerate' => 'command.event.generate',
        'EventMake' => 'command.event.make',
        'ExceptionMake' => 'command.exception.make',
        'FactoryMake' => 'command.factory.make',
        'FormMake' => 'command.form.make',
        'HookMake' => 'command.hook.make',
        'JobMake' => 'command.job.make',
        'ListenerMake' => 'command.listener.make',
        'MailMake' => 'command.mail.make',
        'MiddlewareMake' => 'command.middleware.make',
        'ModelMake' => 'command.model.make',
        'NotificationMake' => 'command.notification.make',
        'NotificationTable' => 'command.notification.table',
        'ObserverMake' => 'command.observer.make',
        'PasswordResetTable' => 'command.password.reset.table',
        'PluginInstall' => 'command.plugin.install',
        'PolicyMake' => 'command.policy.make',
        'ProviderMake' => 'command.provider.make',
        'QueueFailedTable' => 'command.queue.failed-table',
        'QueueTable' => 'command.queue.table',
        'QueueBatchesTable' => 'command.queue.batches-table',
        'RequestMake' => 'command.request.make',
        'ResourceMake' => 'command.resource.make',
        'RuleMake' => 'command.rule.make',
        'SeederMake' => 'command.seeder.make',
        'SessionTable' => 'command.session.table',
        'Serve' => 'command.serve',
        'StubPublish' => 'command.stub.publish',
        'TestMake' => 'command.test.make',
        'ThemeInstall' => 'command.theme.install',
        'VendorPublish' => 'command.vendor.publish',
        'WidgetMake' => 'command.widget.make',
    ];

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerCommands(array_merge(
            $this->commands,
            $this->devCommands
        ));
    }

    /**
     * Register the given commands.
     *
     * @param array $commands
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            call_user_func_array([$this, "register{$command}Command"], [$commands[$command]]);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the make:auth command.
     *
     * @param string $abstract
     */
    protected function registerAuthMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new AuthMakeCommand($app['files']);
        });
    }

    /**
     * Register the cache:clear command.
     *
     * @param string $abstract
     */
    protected function registerCacheClearCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new CacheClearCommand($app['cache'], $app['files']);
        });
    }

    /**
     * Register the cache:forget command.
     *
     * @param string $abstract
     */
    protected function registerCacheForgetCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new CacheForgetCommand($app['cache']);
        });
    }

    /**
     * Register the cache:table command.
     *
     * @param string $abstract
     */
    protected function registerCacheTableCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new CacheTableCommand($app['files'], $app['composer']);
        });
    }

    /**
     * Register the make:cast command.
     *
     * @param string $abstract
     */
    protected function registerCastMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new CastMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:channel command.
     *
     * @param string $abstract
     */
    protected function registerChannelMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ChannelMakeCommand($app['files']);
        });
    }

    /**
     * Register the clear-compiled command.
     *
     * @param string $abstract
     */
    protected function registerClearCompiledCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new ClearCompiledCommand();
        });
    }

    protected function registerClearResetsCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new ClearResetsCommand();
        });
    }

    /**
     * Register the make:component command.
     *
     * @param string $abstract
     */
    protected function registerComponentMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ComponentMakeCommand($app['files']);
        });
    }

    /**
     * Register the config:cache command.
     *
     * @param string $abstract
     */
    protected function registerConfigCacheCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ConfigCacheCommand($app['files']);
        });
    }

    /**
     * Register the config:clear command.
     *
     * @param string $abstract
     */
    protected function registerConfigClearCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ConfigClearCommand($app['files']);
        });
    }

    /**
     * Register the make:command command.
     *
     * @param string $abstract
     */
    protected function registerConsoleMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ConsoleMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:controller command.
     *
     * @param string $abstract
     */
    protected function registerControllerMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ControllerMakeCommand($app['files']);
        });
    }

    /**
     * Register the customer:table command.
     *
     * @param string $abstract
     */
    protected function registerCustomerTableCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new CustomerTableCommand($app['files'], $app['composer']);
        });
    }

    /**
     * Register the db command.
     */
    protected function registerDbCommand()
    {
        $this->app->singleton(DbCommand::class);
    }

    /**
     * Register the db:wipe command.
     *
     * @param string $abstract
     */
    protected function registerDbWipeCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new WipeCommand();
        });
    }

    /**
     * Register the down command.
     *
     * @param string $abstract
     */
    protected function registerDownCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new DownCommand();
        });
    }

    /**
     * Register the dropin:clear command.
     *
     * @param string $abstract
     */
    protected function registerDropinClearCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new DropinClearCommand($app['files']);
        });
    }

    /**
     * Register the env command.
     *
     * @param string $abstract
     */
    protected function registerEnvironmentCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new EnvironmentCommand();
        });
    }

    /**
     * Register the event:cache command.
     *
     * @param string $abstract
     */
    protected function registerEventCacheCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new EventCacheCommand();
        });
    }

    /**
     * Register the event:clear command.
     *
     * @param string $abstract
     */
    protected function registerEventClearCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new EventClearCommand($app['files']);
        });
    }

    /**
     * Register the event:generate command.
     *
     * @param string $abstract
     */
    protected function registerEventGenerateCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new EventGenerateCommand();
        });
    }

    /**
     * Register the event:list command.
     *
     * @param string $abstract
     */
    protected function registerEventListCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new EventListCommand();
        });
    }

    /**
     * Register the make:event command.
     *
     * @param string $abstract
     */
    protected function registerEventMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new EventMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:exception command.
     *
     * @param string $abstract
     */
    protected function registerExceptionMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ExceptionMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:factory command.
     *
     * @param string $abstract
     */
    protected function registerFactoryMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new FactoryMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:form command.
     *
     * @param string $abstract
     */
    protected function registerFormMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new FormMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:hook command.
     *
     * @param string $abstract
     */
    protected function registerHookMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new HookMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:job command.
     *
     * @param string $abstract
     */
    protected function registerJobMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new JobMakeCommand($app['files']);
        });
    }

    /**
     * Register the key:generate command.
     *
     * @param string $abstract
     */
    protected function registerKeyGenerateCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new KeyGenerateCommand();
        });
    }

    /**
     * Register the make:listener command.
     *
     * @param string $abstract
     */
    protected function registerListenerMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ListenerMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:mail command.
     *
     * @param string $abstract
     */
    protected function registerMailMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new MailMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:middleware command.
     *
     * @param string $abstract
     */
    protected function registerMiddlewareMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new MiddlewareMakeCommand($app['files']);
        });
    }

    /**
     * Register the migrate command.
     *
     * @param string $abstract
     */
    protected function registerMigrateCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new MigrateCommand($app['migrator']);
        });
    }

    /**
     * Register the migrate:fresh command.
     *
     * @param string $abstract
     */
    protected function registerMigrateFreshCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new FreshCommand();
        });
    }

    /**
     * Register the migrate:install command.
     *
     * @param string $abstract
     */
    protected function registerMigrateInstallCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new InstallCommand($app['migration.repository']);
        });
    }

    /**
     * Register the make:migration command.
     *
     * @param string $abstract
     */
    protected function registerMigrateMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            // Once we have the migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the migrations, and may be extended by these developers.
            $creator = $app['migration.creator'];
            $composer = $app['composer'];

            return new MigrateMakeCommand($creator, $composer);
        });
    }

    /**
     * Register the migrate:refresh command.
     *
     * @param string $abstract
     */
    protected function registerMigrateRefreshCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new RefreshCommand();
        });
    }

    /**
     * Register the migrate:reset command.
     *
     * @param string $abstract
     */
    protected function registerMigrateResetCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ResetCommand($app['migrator']);
        });
    }

    /**
     * Register the migrate:rollback command.
     *
     * @param string $abstract
     */
    protected function registerMigrateRollbackCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new RollbackCommand($app['migrator']);
        });
    }

    /**
     * Register the migrate:status command.
     *
     * @param string $abstract
     */
    protected function registerMigrateStatusCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new StatusCommand($app['migrator']);
        });
    }

    /**
     * Register the make:model command.
     *
     * @param string $abstract
     */
    protected function registerModelMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ModelMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:notification command.
     *
     * @param string $abstract
     */
    protected function registerNotificationMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new NotificationMakeCommand($app['files']);
        });
    }

    /**
     * Register the notification:table command.
     *
     * @param string $abstract
     */
    protected function registerNotificationTableCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new NotificationTableCommand($app['files'], $app['composer']);
        });
    }

    /**
     * Register the make:observer command.
     *
     * @param string $abstract
     */
    protected function registerObserverMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ObserverCommand($app['files']);
        });
    }

    /**
     * Register the optimize command.
     *
     * @param string $abstract
     */
    protected function registerOptimizeCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new OptimizeCommand();
        });
    }

    /**
     * Register the optimize:clear command.
     *
     * @param string $abstract
     */
    protected function registerOptimizeClearCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new OptimizeClearCommand();
        });
    }

    /**
     * Register the package:discover command.
     *
     * @param string $abstract
     */
    protected function registerPackageDiscoverCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new PackageDiscoverCommand();
        });
    }

    /**
     * Register the password:table command.
     *
     * @param string $abstract
     */
    protected function registerPasswordResetTableCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new PasswordResetTableCommand($app['files'], $app['composer']);
        });
    }

    /**
     * Register the plugin:install command.
     *
     * @param string $abstract
     */
    protected function registerPluginInstallCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new PluginInstallCommand($app['files'], new \ZipArchive());
        });
    }

    /**
     * Register the make:policy command.
     *
     * @param string $abstract
     */
    protected function registerPolicyMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new PolicyMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:provider command.
     *
     * @param string $abstract
     */
    protected function registerProviderMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ProviderMakeCommand($app['files']);
        });
    }

    /**
     * Register the queue:clear command.
     *
     * @param string $abstract
     */
    protected function registerQueueClearCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new QueueClearCommand();
        });
    }

    /**
     * Register the queue:failed command.
     *
     * @param string $abstract
     */
    protected function registerQueueFailedCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new ListFailedQueueCommand();
        });
    }

    /**
     * Register the queue:flush command.
     *
     * @param string $abstract
     */
    protected function registerQueueFlushCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new FlushFailedQueueCommand();
        });
    }

    /**
     * Register the queue:forget command.
     *
     * @param string $abstract
     */
    protected function registerQueueForgetCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new ForgetFailedQueueCommand();
        });
    }

    /**
     * Register the queue:listen command.
     *
     * @param string $abstract
     */
    protected function registerQueueListenCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ListenQueueCommand($app['queue.listener']);
        });
    }

    /**
     * Register the queue:prune-batches command.
     *
     * @param string $abstract
     */
    protected function registerQueuePruneBatchesCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new PruneBatchesQueueCommand();
        });
    }

    /**
     * Register the queue:restart command.
     *
     * @param string $abstract
     */
    protected function registerQueueRestartCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new RestartQueueCommand($app['cache.store']);
        });
    }

    /**
     * Register the queue:retry-batch command.
     *
     * @param string $abstract
     */
    protected function registerQueueRetryBatchCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new QueueRetryBatchCommand();
        });
    }

    /**
     * Register the queue:retry command.
     *
     * @param string $abstract
     */
    protected function registerQueueRetryCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new RetryQueueCommand();
        });
    }

    /**
     * Register the queue:work command.
     *
     * @param string $abstract
     */
    protected function registerQueueWorkCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new WorkQueueCommand($app['queue.worker'], $app['cache.store']);
        });
    }

    /**
     * Register the queue:failed-table command.
     *
     * @param string $abstract
     */
    protected function registerQueueFailedTableCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new FailedTableCommand($app['files'], $app['composer']);
        });
    }

    /**
     * Register the queue:table command.
     *
     * @param string $abstract
     */
    protected function registerQueueTableCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new TableCommand($app['files'], $app['composer']);
        });
    }

    /**
     * Register the queue:batches-table command.
     *
     * @param string $abstract
     */
    protected function registerQueueBatchesTableCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new BatchesTableCommand($app['files'], $app['composer']);
        });
    }

    /**
     * Register the make:request command.
     *
     * @param string $abstract
     */
    protected function registerRequestMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new RequestMakeCommand($app['files']);
        });
    }

    /**
     * Register the make:resource command.
     *
     * @param string $abstract
     */
    protected function registerResourceMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ResourceMakeCommand($app['files']);
        });
    }

    /**
     * Register the route:cache command.
     *
     * @param string $abstract
     */
    protected function registerRouteCacheCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new RouteCacheCommand($app['files']);
        });
    }

    /**
     * Register the route:clear command.
     *
     * @param string $abstract
     */
    protected function registerRouteClearCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new RouteClearCommand($app['files']);
        });
    }

    /**
     * Register the route:list command.
     *
     * @param string $abstract
     */
    protected function registerRouteListCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new RouteListCommand($app['router']);
        });
    }

    /**
     * Register the make:rule command.
     *
     * @param string $abstract
     */
    protected function registerRuleMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new RuleMakeCommand($app['files']);
        });
    }

    /**
     * Register the schedule:finish {id} command.
     */
    protected function registerScheduleFinishCommand()
    {
        $this->app->singleton(ScheduleFinishCommand::class);
    }

    /**
     * Register the schedule:run command.
     */
    protected function registerScheduleRunCommand()
    {
        $this->app->singleton(ScheduleRunCommand::class);
    }

    /**
     * Register the schema:dump command.
     *
     * @param string $abstract
     */
    protected function registerSchemaDumpCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new DumpCommand();
        });
    }

    /**
     * Register the db:seed command.
     *
     * @param string $abstract
     */
    protected function registerSeedCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new SeedCommand($app['db']);
        });
    }

    /**
     * Register the make:seeder command.
     *
     * @param string $abstract
     */
    protected function registerSeederMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new SeederMakeCommand($app['files']);
        });
    }

    /**
     * Register the serve command.
     *
     * @param string $abstract
     */
    protected function registerServeCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new ServeCommand();
        });
    }

    /**
     * Register the session:table command.
     *
     * @param string $abstract
     */
    protected function registerSessionTableCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new SessionTableCommand($app['files'], $app['composer']);
        });
    }

    /**
     * Register the stub:publish command.
     *
     * @param string $abstract
     */
    protected function registerStubPublishCommand($abstract)
    {
        $this->app->singleton($abstract, function () {
            return new StubPublishCommand();
        });
    }

    /**
     * Register the make:test command.
     *
     * @param string $abstract
     */
    protected function registerTestMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new TestMakeCommand($app['files']);
        });
    }

    /**
     * Register the theme:install command.
     *
     * @param string $abstract
     */
    protected function registerThemeInstallCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ThemeInstallCommand($app['files'], new \ZipArchive());
        });
    }

    /**
     * Register the up command.
     *
     * @param string $abstract
     */
    public function registerUpCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new UpCommand();
        });
    }

    /**
     * Register the vendor:publish command.
     *
     * @param string $abstract
     */
    protected function registerVendorPublishCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new VendorPublishCommand($app['files']);
        });
    }

    /**
     * Register the view:clear command.
     *
     * @param string $abstract
     */
    protected function registerViewClearCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ViewClearCommand($app['files']);
        });
    }

    /**
     * Register the make:widget command.
     *
     * @param string $abstract
     */
    protected function registerWidgetMakeCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new WidgetMakeCommand($app['files']);
        });
    }

    /**
     * Return list of services provided.
     *
     * @return array
     */
    public function provides()
    {
        return array_merge(array_values($this->commands), array_values($this->devCommands));
    }
}
