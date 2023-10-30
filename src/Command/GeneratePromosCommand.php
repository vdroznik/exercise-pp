<?php

declare(strict_types=1);

namespace ExercisePromo\Command;

use Random\Engine\Secure;
use Random\Randomizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'generate-promos')]
class GeneratePromosCommand extends Command
{
    protected string $promocodesAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filename = dirname(__DIR__, 2) . '/exchange' . '/promocodes.csv';
        if (file_exists($filename)) {
            $output->writeln('<error>File promocodes.csv already exists</error>');

            return Command::INVALID;
        }

        $alphabetLength = strlen($this->promocodesAlphabet);
        $randomizer = new Randomizer(new Secure());

        $promos = [];
        while (count($promos) < 500000) {
            $promo = '';
            for($i = 0; $i < 10; $i++) {
                $promo .= $this->promocodesAlphabet[$randomizer->getInt(0, $alphabetLength - 1)];
            }
            $promos[$promo] = 1;
        }

        // write to temp file first then copy with file_put_contents to overcome docker i/o performance issues
        $f = tmpfile();
        foreach ($promos as $promo => $v) {
            fputcsv($f, [$promo]);
        }
        rewind($f);
        file_put_contents($filename, $f);
        fclose($f);

        return Command::SUCCESS;
    }
}
