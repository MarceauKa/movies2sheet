<?php

namespace App\Commands;

use App\Utils\Env;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class FrontendBuild extends Command
{
    protected static $defaultName = 'frontend:build';

    protected function configure()
    {
        $this->setDescription('Build frontend');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Generating frontend</info>');

        ob_start();
        $exporting = true;
        require base_path('index.php');
        $content = ob_get_clean();

        $volume = Env::get('VOLUME_PATH');
        $writing = file_put_contents($volume . 'mamarflix.html', $content);

        if (!$writing) {
            $output->writeln(sprintf("<error>Can't write to volume %s</error>", $volume));

            return Command::FAILURE;
        }

        $output->writeln(sprintf('<info>File %s writed in %s</info>', 'mamarflix.html', $volume));
        $output->writeln('<info>Copying images</info>');

        $finder = new Finder();
        $finder->files()
            ->name('*.jpg')
            ->in(base_path('data/images/'));

        $images = iterator_to_array($finder);
        $path = sprintf('%s.mamarflix/', $volume);

        if (false === is_dir($path)) {
            mkdir($path);
        }

        $bar = new ProgressBar($output, count($images));
        $bar->start();

        foreach ($images as $image) {
            $filename = $path . $image->getFilename();

            if (false === file_exists($filename)) {
                file_put_contents($filename, file_get_contents($image->getPathname()));
            }

            $bar->advance();
        }

        $bar->finish();
        $output->writeln('');

        $output->writeln('<info>Images copied!</info>');

        return Command::SUCCESS;
    }
}