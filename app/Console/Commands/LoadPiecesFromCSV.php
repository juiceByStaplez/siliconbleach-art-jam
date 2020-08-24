<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Piece;

class LoadPiecesFromCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jam:pieces:load {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int;
     */
    public function handle()
    {
        $fileName = $this->argument('file');

        $loadFile = File::get($fileName);

        if (!empty($loadFile)) {
            $loadFile   = Str::of($loadFile);
            $lineByLine = $loadFile->explode("\n");

            $lineCount = count($lineByLine);

            $columnHeaders = [];
            foreach ($lineByLine as $key => $line) :
                $lineNumber = $key + 1;
                $this->info("Processing $line $lineNumber of $lineCount");
            endforeach;

            // TODO: Finish this script

            $pieces = [
                [
                    'id' => 213,
                    'name'     => "Flight or Fight - The Transition to Nightmare",
                    'artist'   => "mr6volt",
                ],
                [
                    'id' => 214,
                    'name'     => "Untitled",
                    'artist'   => "aiyellahensrud"
                ],
                [
                    'id' => 215,
                    'name'     => "Departure",
                    'artist'   => "PMsAnonymous"
                ],
                [
                    'id' => 216,
                    'name'     => "Blues in the gray",
                    'artist'   => "Lucifer_The_Saint"
                ],
                [
                    'id' => 217,
                    'name'     => "Deathbed",
                    'artist'   => "SocialHack"
                ],
                [
                    'id' => 218,
                    'name'     => "A Fleeting Collage",
                    'artist'   => "Linkoln_sosias"
                ],
                [
                    'id' => 219,
                    'name'     => "Fight or flight",
                    'artist'   => "Nstav"
                ],
                [
                    'id' => 220,
                    'name'     => "Torn",
                    'artist'   => "Tylertr0n85"
                ],
                [
                    'id' => 221,
                    'name'     => "The moment of decision",
                    'artist'   => "kamyk00"
                ],
                [
                    'id' => 222,
                    'name'     => "i'm not strong i'm 5",
                    'artist'   => "talldogwithlongbeak"
                ],
                [
                    'id' => 223,
                    'name'     => "British Warbird",
                    'artist'   => "Medjoe"
                ],
                [
                    'id' => 224,
                    'name'     => "Adapt to situation ",
                    'artist'   => "YvonnePistachette"
                ],
                [
                    'id' => 225,
                    'name'     => "A choice to make",
                    'artist'   => "RenLen80"
                ],
                [
                    'id' => 226,
                    'name'     => "Nalulunod Sa Dugo",
                    'artist'   => "zeni75"
                ],
                [
                    'id' => 227,
                    'name'     => "Submission",
                    'artist'   => "daninovakstudio"
                ],
                [
                    'id' => 228,
                    'name'     => "Primal Twins",
                    'artist'   => "Yadadame"
                ],
                [
                    'id' => 229,
                    'name'     => "Fear of Death",
                    'artist'   => "whooves"
                ],
                [
                    'id' => 230,
                    'name'     => "This must be hell",
                    'artist'   => "Tuomashl"
                ],
                [
                    'id' => 231,
                    'name'     => "Pandemic Elysium",
                    'artist'   => "vicarious_10k"
                ],
                [
                    'id' => 232,
                    'name'     => "Stranger in the Desert",
                    'artist'   => "Bloomingzonda"
                ],
                [
                    'id' => 233,
                    'name'     => "Island",
                    'artist'   => "willthethrillz"
                ],
                [
                    'id' => 234,
                    'name'     => "Hamstrung",
                    'artist'   => "alexjensenfa"
                ],
                [
                    'id' => 235,
                    'name'     => "Untitled",
                    'artist'   => "KeepOnFailing"
                ],
                [
                    'id' => 236,
                    'name'     => "Demon’s Deconstructionist",
                    'artist'   => "lycheejellyslug"
                ],
                [
                    'id' => 237,
                    'name'     => "Uncertainty",
                    'artist'   => "KickingJoub"
                ],
                [
                    'id' => 238,
                    'name'     => "Hygeine",
                    'artist'   => "gardenshoe"
                ],
                [
                    'id' => 239,
                    'name'     => "In the Mouth of Madness",
                    'artist'   => "RoyBatty06"
                ],
                [
                    'id' => 240,
                    'name'     => "Joining The Circus",
                    'artist'   => "VooVee"
                ],
                [
                    'id' => 241,
                    'name'     => "Fire.Fight.Flight.",
                    'artist'   => "SSJets_STC"
                ],
                [
                    'id' => 242,
                    'name'     => "Good vibes",
                    'artist'   => "fru_drusse"
                ],
                [
                    'id' => 243,
                    'name'     => "Incoming Fear",
                    'artist'   => "kontemplartimo"
                ],
            ];

            foreach ($pieces as $piece) {
                $createPiece = Piece::create($piece);
            }


            // TODO: Add PiecesController
            return 0;
        }
    }
}
