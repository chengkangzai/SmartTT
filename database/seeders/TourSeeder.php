<?php

namespace Database\Seeders;


use App\Models\Country;
use App\Models\Tour;
use File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    private Collection $countries;
    /** @var Collection|Tour[] */
    private Collection|array $tours;

    public function __construct()
    {
        $this->countries = Country::all();
    }

    public function run()
    {
        Tour::insert([
            [
                'tour_code' => '5BKK',
                'name' => '5D4N Bangkok + Hua Hin Tour',
                'category' => 'Asia',
                'days' => 5,
                'nights' => 4,
            ], [
                'tour_code' => '5DPS',
                'name' => '5D4N Bali Dolphin',
                'category' => 'Asia',
                'days' => 5,
                'nights' => 4,
            ], [
                'tour_code' => '5HAN',
                'name' => '5D4N Hanoi – Halong Bay – Ninh Binh Bich Dong Tour',
                'category' => 'Asia',
                'days' => 5,
                'nights' => 4,
            ], [
                'tour_code' => '5JBP',
                'name' => '5D4N Jakart– Puncak – Bandung Tour',
                'category' => 'Asia',
                'days' => 5,
                'nights' => 4,
            ], [
                'tour_code' => '6TPE',
                'name' => '6D5N Taiwan Value West Tour',
                'category' => 'Asia',
                'days' => 6,
                'nights' => 5,
            ], [
                'tour_code' => '7TPE',
                'name' => '7D6N West Of Taiwan + Explorer Alishan Tour',
                'category' => 'Asia',
                'days' => 7,
                'nights' => 6,
            ], [
                'tour_code' => '8EGNP',
                'name' => '8D6N Always Popular Europe Trips',
                'category' => 'Europe',
                'days' => 8,
                'nights' => 6,
            ], [
                'tour_code' => '8ITL',
                'name' => '8D5N Best of Italy',
                'category' => 'Europe',
                'days' => 8,
                'nights' => 5,
            ], [
                'tour_code' => '11ELPS',
                'name' => '11D8N Europe Classic Full Board',
                'category' => 'Europe',
                'days' => 11,
                'nights' => 8,
            ], [
                'tour_code' => '14EGSA',
                'name' => '14D11N The Best of Austria and Switzerland',
                'category' => 'Europe',
                'days' => 14,
                'nights' => 11,
            ], [
                'tour_code' => '14ESIMF',
                'name' => '14D11N The Best of Switzerland - Italy - Monaco - French Riviera',
                'category' => 'Europe',
                'days' => 14,
                'nights' => 11,
            ], [
                'tour_code' => '14EWP',
                'name' => '14D11N Classic Eastern Europe',
                'category' => 'Europe',
                'days' => 14,
                'nights' => 11,
            ], [
                'tour_code' => '8XSAB',
                'name' => '8D5N South Africa Explorer Tour',
                'category' => 'Exotic',
                'days' => 8,
                'nights' => 5,
            ], [
                'tour_code' => '9CAI',
                'name' => '9D7N Cairo & 5 Star Nile Cruise',
                'category' => 'Exotic',
                'days' => 9,
                'nights' => 7,
            ], [
                'tour_code' => '10XII',
                'name' => '10D7N Best of Turkey',
                'category' => 'Exotic',
                'days' => 10,
                'nights' => 7,
            ]
        ]);
        $this->tours = Tour::all();

        $this->mapCountryToTour();
        $this->attachMedia();

        $this->attachDes();
    }

    #region Map Country to Tour
    private function attachCountry(string $touCode, $country)
    {
        $tour = $this->tours->where('tour_code', $touCode)->first();
        if (is_array($country)) {
            $this->countries->whereIn('name', $country)->each(function (Country $country, $index) use ($tour) {
                $tour->countries()->attach($country->id, [
                    'order' => $index,
                ]);
            });
        } else {
            $this->countries->where('name', $country)->each(function (Country $country, $index) use ($tour) {
                $tour->countries()->attach($country->id, [
                    'order' => $index,
                ]);
            });
        }
    }

    private function mapCountryToTour()
    {
        $array = [
            ['5BKK', 'Thailand'],
            ['5DPS', 'Indonesia'],
            ['5HAN', 'Vietnam'],
            ['5JBP', 'Indonesia'],
            ['6TPE', 'Taiwan'],
            ['7TPE', 'Taiwan'],
            ['8EGNP', ['Germany', 'Netherlands', 'Belgium', 'France']],
            ['8ITL', 'Italy'],
            ['11ELPS', ['United Kingdom', 'Belgium', 'Netherlands', 'Germany', 'Switzerland', 'France']],
            ['14EGSA', 'Austria'],
            ['14ESIMF', ['Italy', 'Germany', 'Switzerland', 'France']],
            ['14EWP', ['Germany', 'Poland', 'Czech Republic', 'Slovakia', 'Hungary', 'Austria']],
            ['8XSAB', 'South Africa'],
            ['9CAI', 'Egypt'],
            ['10XII', 'Turkey'],
        ];

        foreach ($array as $item) {
            $this->attachCountry($item[0], $item[1]);
        }
    }

    #endregion

    private function attachMedia()
    {
        $this->tours->each(function (Tour $tour) {
            $path = 'seeding_data/Tours/' . $tour->category . '/' . $tour->tour_code;
            $tour->addMediaFromStream(File::get(storage_path($path . '.jpg')))->usingFileName($tour->tour_code . '.jpg')->toMediaCollection('thumbnail');
            $tour->addMediaFromStream(File::get(storage_path($path . '.pdf')))->usingFileName($tour->tour_code . '.pdf')->toMediaCollection('itinerary');
        });
    }

    private function attachDes()
    {
        $des = [
            ['5BKK', [
                'Ploern Wan Market' => 'look like back to the place in times past and imitate that period of lifestyle.',
                'Hua Hin Railway Station' => 'it is one of the oldest railway stations in Thailand and it was once the Thai royal family dedicated waiting station',
                'Hua Hin Safari' => 'it gives a fun filled variety entertainment and activities for you and your family.',
                'Asiatique the Riverfront' => 'original appearance of the marina and the old warehouse are retained, redesigned and new packaging, now become a famous tourist night market.'
            ]],
            ['5DPS', [
                'Kintamani Tour ~ Lovina Beach' => 'After breakfast, visit Celuk Mas Village for see the amazing gold and silver art. Then visit the TirtaEmpul Temple, Kintamani Volcano.',
                'Dolphin Hunting' => 'Early Morning visit to Dolphin Hunting by traditional boat.',
                'Kuta' => 'Lunch at local restaurant and BBQ Seafood Jimbaran Beach Dinner.',
                'Tanah Lot Temple' => 'In the afternoon visiting to Candi Kuning Market and Tanah Lot Temple while watching beautiful sunset. Buffet Lunch at Local restaurant and Local restaurant Dinner.'
            ]],
            ['5HAN', [
                'FREE' => 'Water puppet show Vietnam cultural',
                'Tasting' => 'French Style Afternoon Tea',
                'The One Pillar Pagoda' => 'historic Buddhist temple in Hanoi, the capital of Vietnam. It is regarded alongside the Perfume Temple, as one of Vietnam\'s two most iconic temples.',
                'Water Puppet Show' => 'The farmers in this region devised a form of entertainment using what natural medium they can find in their environment.'
            ]],
            ['5JBP', [
                'Museum Gajah' => 'The museum is regarded as one of the most complete and the best in Indonesia, as well as one of the finest museum in Southeast Asia',
                'Factory Outlet Grand' => 'This large and complete mall is right in the city centre and houses a good selection of shops of high-end brands and entertainment outlets.',
                'Semanggi Plaza' => 'It was a very crowded and popular shopping mall in Jarkarta and its located in Down town in Jakarta',
                'Kawah Putih Valcano' => 'Patuha is a twin stratovolcano about 50 km to the southwest of Bandung in West Java, Indonesia'
            ]],
            ['6TPE', [
                'Shilin Night Market' => 'a night market in the Shilin District of Taipei, Taiwan, and is often considered to be the largest and most famous night market in the city.',
                'Sun Moon Lake' => 'is in the foothills of Taiwan’s Central Mountain Range. It’s surrounded by forested peaks and has foot trails. Aboriginal Culture Village is a theme park with a section devoted to re-created indigenous villages',
                'Sun Moon Lake Wen Wu Temple' => 'is a Wen Wu temple located on the perimeter of Sun Moon Lake in Yuchi Township, Nantou County, Taiwan',
                'Jiufen old street' => 'is known for the narrow alleyways of its old town, packed with tea houses, street food shacks and souvenir shops.',
            ]],
            ['7TPE', [
                'Alishan National Scenic Are' => 'Altitude of 2,216 meters, surrounded by alpine ring, the climate is cool, sunrise, sea of clouds, sunset, forest, mountain forest railway as Alishan five odd.',
                'Jingtong Statio' => 'For the Taiwan Railway Administration Pingxi branch railway terminus.',
                'Monster village' => 'Followed the "pine forest town" architectural style, playing into the Japanese characteristics of the store street.',
                'Four Four South Village' => 'Kept near Taipei 101, is one of the villages that the National Government has built in Taiwan, because the live is the joint logistics unit 44 factory workers and is located in the south of the arsenal, so there named "four four South Village".',
            ]],
            ['8EGNP', [
                'Germany' => 'visit Cologne - the twin spire Gothic cathedral',
                'Roemond' => 'Factory Outlet – for designer name brands',
                'Netherland – Amsterdam' => 'enjoy your Canal cruise,  visit Zaanse Schaan-typical Dutch village, cheese farm   Clog Factory, windmills, diamond factory, red light  District and Canal cruise.',
                'Belgium-Brussels' => 'view the Atomium, Grand Place Mannekin Pis',
            ]],
            ['8ITL', [
                'Rome' => 'Vatican City, St. Peter’s Square, St. Peter’s Basilica, Constantin, Arch de Triumph, Circus Maximus, Victor Emmanuel Monument, Piazza Venezia, Roman Forum, Trevi Fountain, Spanish Steps.',
                'Florence' => 'Michelangelo Square, Ponte Vecchio Bridge, Duomo Cathedral Factory Outlet – designer shopping',
                'La Spezia' => 'Italian Rivieria – views of Mediterranean Sea',
                'Cinque Terre' => 'is a string of centuries-old seaside villages on the rugged Italian Riviera coastline. A UNESCO World Heritage Site'
            ]],
            ['11ELPS', [
                'England,London' => 'Panoramic views of St Paul’s Cathedral, Tower Bridge, London Bridge, London Tower, Parliament House',
                'Eurostar' => 'by high speed train – London to Brussels,Belgium',
                'Belgium, Brussels' => 'View the Atomium, Grand Place, MannekenPis',
                'Holland, Amsterdam' => 'Enjoy Canal Cruise, Visit ZaanseSchaan – typical Dutch Village, Cheese Farm, Clog Factory, Windmills, Diamond Factory, Red Light District.'
            ]],
            ['14EGSA', [
                'Munich' => 'Marienplatz , Peterskirche, Cathedral of our Lady',
                'Salzburg' => 'Birthplace of Wolfgang Amadeus Mozart, Hohensalzburg castle.',
                'Hallstatt' => 'The Lake Hallstatt, a spectacular mountain lake in Austrias Salzkammergut region',
                'Innsbruck' => 'Golden Roof , Swarovski Crystal',
            ]],
            ['14ESIMF', [
                'Frankfurt' => 'Façade of the Romer, Frankfurt Cathedral, Frankfurt Museum',
                'Stuttgart' => 'visit Mercedes Benz Museum',
                'Basel' => 'visit the beautiful old town, Marktplatz',
                'Bern' => 'famous Clock Tower and House of Parliament, Rose Garden, Bear Park ',
            ]],
            ['14EWP', [
                'Warsaw' => 'Visit Ghetto Hero’s Monument, Market Sq and view the Royal Palace',
                'Krakow' => 'Visit Wieliczka Salt Mines',
                'Bratislava' => 'Orientation sights of SLOVAKIA capital city',
                'Budapest' => 'Fabulous sightseeing twin cities of BUDA and PEST',
            ]],
            ['8XSAB', [
                'Table Mountain' => 'The Table Mountain encompasses the Incredibly scenic peninsula Mountain Chain stretching from Signal Hill in the north to Cape Point in the south and the seas and coastline of the peninsula.',
                'Africa\'s Big 5 Game Drive' => 'Experience the thrill of tracking Africas wild animal or the famous Africa’s Big Five, which are the Buffalo, Rhino, Elephant, Lion and Leopard through the African bush on dawn and dusk game drives in open Safari Vehicles.',
                'Johannesburg' => 'Johannesburg, South Africas biggest city and capital of Gauteng province began as a 19th-century gold-mining settlement. Its sprawling Soweto township was once home to Nelson Mandela and Desmond Tutu.',
                'Cape Peninsula' => 'Cape Point is not the fixed "meeting point" of the cold Benguela Current, running northwards along the west coast of Africa, and the warm Agulhas Current, running south from the equator along the east coast of Africa.',
            ]],
            ['9CAI', [
                'Great Pyramid of Giza and the Sphinx' => 'It is the oldest of the Seven Wonders of the Ancient World and still remains largely intact.',
                'Temple of Karnak' => 'Largest temple complex ever constructed anywhere in the world.',
                'Temple of Luxor' => 'It is also famous with its huge columns which end with the shape of papyrus plant.',
                'Temple of Philae' => 'One of the most important monuments sites and was an ancient pilgrimage center'
            ]],
            ['10XII', [
                '4 Star Hotel' => 'Local 4 Star Hotel Accommodation',
                'Istanbul' => 'Excursion Bosphorus Cruise along Istanbul’s famous waterway dividing Europe and Asia 2 continents',
                'HIPPODROME' => 'visit HIPPODROME is the scene of chariot races and the center of Byzantine civic life',
                'Pamukkale' => 'surreal, brilliant white travertine terraces and warm, limpid pools of Pamukkale hang, recognize – by UNESCO World Heritage in 1988.'
            ]]
        ];
        foreach ($des as $de) {
            /** @var Tour $tour */
            $tour = $this->tours->where('tour_code', $de[0])->first();
            collect($de[1])->each(function ($a, $b) use ($tour) {
                $tour->description()->create([
                    'place' => $b,
                    'description' => $a,
                    'order' => $tour->description()->count()
                ]);
            });
        }
    }
}
