<?php

namespace PHPStatic\Command;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class GenerateCommand extends Command
{
  /**
   * Configure the command options.
   *
   * @return void
   */
  protected function configure()
  {
    $this
      ->setName('generate')
      ->setDescription('Generate static site');
  }

  /**
   * Execute the command.
   *
   * @param  \Symfony\Component\Console\Input\InputInterface  $input
   * @param  \Symfony\Component\Console\Output\OutputInterface  $output
   * @return int
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    if (version_compare(PHP_VERSION, '7.3.0', '<')) {
      throw new RuntimeException('The PHPStatic requires PHP 7.3.0 or greater.');
    }

    $finder = new Finder();
    $template_engine = \League\Plates\Engine::create(getcwd() . '/templates');
    mkdir(getcwd() . '/dist/');

    foreach ($finder->files()->in(getcwd() . '/pages') as $file) {
      @require_once(getcwd() . '/pages/' . $file->getFilename());
      $page_class = ucfirst($file->getBasename('.php'));
      $page = new $page_class();
      $page_data = $page->data();
      $page_template = $page->template();
      file_put_contents(getcwd() . '/dist/' . $file->getBasename('.php') . '.html', $template_engine->render($page_template, $page_data));
    }

    return 0;
  }
}
