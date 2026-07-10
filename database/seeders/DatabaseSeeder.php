<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bien;
use App\Models\BienImage;
use App\Models\Visite;
use App\Models\Transaction;
use App\Models\Favori;
use App\Models\Message;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin, Agent and Client users
        $admin = User::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'tel' => '0102030405',
            'email' => 'admin@immo.com',
            'password' => 'password', // Auto-hashed by model cast
            'role' => 'admin',
        ]);

        $agent1 = User::create([
            'nom' => 'Martin',
            'prenom' => 'Sophie',
            'tel' => '0612345678',
            'email' => 'agent@immo.com',
            'password' => 'password',
            'role' => 'agent',
        ]);

        $agent2 = User::create([
            'nom' => 'Bernard',
            'prenom' => 'Pierre',
            'tel' => '0687654321',
            'email' => 'pierre.bernard@immo.com',
            'password' => 'password',
            'role' => 'agent',
        ]);

        $client1 = User::create([
            'nom' => 'Leroy',
            'prenom' => 'Alice',
            'tel' => '0711223344',
            'email' => 'client@immo.com',
            'password' => 'password',
            'role' => 'client',
        ]);

        $client2 = User::create([
            'nom' => 'Moreau',
            'prenom' => 'Lucas',
            'tel' => '0755667788',
            'email' => 'lucas.moreau@example.com',
            'password' => 'password',
            'role' => 'client',
        ]);

        $client3 = User::create([
            'nom' => 'Petit',
            'prenom' => 'Emma',
            'tel' => '0799001122',
            'email' => 'emma.petit@example.com',
            'password' => 'password',
            'role' => 'client',
        ]);

        // 2. Create Biens (Properties)
        $biensData = [
            [
                'titre' => 'Magnifique villa contemporaine avec piscine',
                'description' => 'Située dans un quartier calme et recherché, cette superbe villa de plain-pied offre des prestations haut de gamme. Grand séjour lumineux, cuisine américaine équipée, terrasse ensoleillée et piscine chauffée.',
                'type' => 'maison',
                'statut' => 'a_vendre',
                'prix' => 650000.00,
                'surface' => 180.00,
                'pieces' => 5,
                'chambres' => 4,
                'salles_de_bain' => 2,
                'adresse' => '12 Rue des Alouettes',
                'ville' => 'Montpellier',
                'code_postal' => '34000',
                'features' => ['piscine', 'jardin', 'garage', 'climatisation'],
                'agent_id' => $agent1->id,
                'proprietaire_id' => $client2->id,
            ],
            [
                'titre' => 'Bel appartement T3 en plein centre-ville',
                'description' => 'Idéalement situé au 3ème étage d\'une résidence sécurisée avec ascenseur. Cet appartement comprend une entrée, un salon avec balcon, une cuisine séparée, deux chambres et une salle de bain. Parking inclus.',
                'type' => 'appartement',
                'statut' => 'a_louer',
                'prix' => 950.00,
                'surface' => 68.00,
                'pieces' => 3,
                'chambres' => 2,
                'salles_de_bain' => 1,
                'adresse' => '45 Avenue Foch',
                'ville' => 'Montpellier',
                'code_postal' => '34000',
                'features' => ['ascenseur', 'balcon', 'parking'],
                'agent_id' => $agent1->id,
                'proprietaire_id' => $client3->id,
            ],
            [
                'titre' => 'Terrain constructible viabilisé',
                'description' => 'Beau terrain plat de 800m², libre de constructeur, situé dans un environnement verdoyant et calme. Viabilisation en bordure de route (eau, électricité, tout-à-l\'égout).',
                'type' => 'terrain',
                'statut' => 'a_vendre',
                'prix' => 120000.00,
                'surface' => 800.00,
                'pieces' => null,
                'chambres' => null,
                'salles_de_bain' => null,
                'adresse' => 'Lieu-dit Le Moulin',
                'ville' => 'Castelnau-le-Lez',
                'code_postal' => '34170',
                'features' => [],
                'agent_id' => $agent2->id,
                'proprietaire_id' => null,
            ],
            [
                'titre' => 'Local commercial bien situé',
                'description' => 'Local commercial de 120m² avec une belle vitrine de 8 mètres sur un axe très passant. Idéal pour commerce de détail, bureaux ou profession libérale. Disponible immédiatement.',
                'type' => 'local_commercial',
                'statut' => 'a_louer',
                'prix' => 2200.00,
                'surface' => 120.00,
                'pieces' => 2,
                'chambres' => null,
                'salles_de_bain' => 1,
                'adresse' => '88 Grand Rue',
                'ville' => 'Montpellier',
                'code_postal' => '34000',
                'features' => ['climatisation', 'vitrine'],
                'agent_id' => $agent2->id,
                'proprietaire_id' => $client2->id,
            ],
            [
                'titre' => 'Studio étudiant meublé',
                'description' => 'Studio de 20m² entièrement rénové et équipé. Idéal investisseur ou étudiant. Proche des universités et des transports en commun (tramway ligne 1). Récemment loué.',
                'type' => 'appartement',
                'statut' => 'loue',
                'prix' => 450.00,
                'surface' => 20.00,
                'pieces' => 1,
                'chambres' => null,
                'salles_de_bain' => 1,
                'adresse' => '15 Rue des Ecoles',
                'ville' => 'Montpellier',
                'code_postal' => '34090',
                'features' => ['meuble', 'internet'],
                'agent_id' => $agent1->id,
                'proprietaire_id' => $client1->id,
            ]
        ];

        $biens = [];
        foreach ($biensData as $data) {
            $biens[] = Bien::create($data);
        }

        // 3. Create BienImages
        $imagesData = [
            // Villa
            [
                'bien_id' => $biens[0]->id,
                'image_path' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80',
                'is_primary' => true,
            ],
            [
                'bien_id' => $biens[0]->id,
                'image_path' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
                'is_primary' => false,
            ],
            // Appartement
            [
                'bien_id' => $biens[1]->id,
                'image_path' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80',
                'is_primary' => true,
            ],
            // Terrain
            [
                'bien_id' => $biens[2]->id,
                'image_path' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80',
                'is_primary' => true,
            ],
            // Local
            [
                'bien_id' => $biens[3]->id,
                'image_path' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80',
                'is_primary' => true,
            ],
            // Studio
            [
                'bien_id' => $biens[4]->id,
                'image_path' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80',
                'is_primary' => true,
            ]
        ];

        foreach ($imagesData as $img) {
            BienImage::create($img);
        }

        // 4. Create Visites
        Visite::create([
            'bien_id' => $biens[0]->id,
            'client_id' => $client1->id,
            'agent_id' => $agent1->id,
            'date_visite' => now()->addDays(2),
            'statut' => 'planifie',
            'commentaire' => 'Client très intéressé par la piscine. Prévoir visite en fin d\'après-midi.',
        ]);

        Visite::create([
            'bien_id' => $biens[1]->id,
            'client_id' => $client3->id,
            'agent_id' => $agent1->id,
            'date_visite' => now()->subDays(3),
            'statut' => 'effectue',
            'commentaire' => 'Visite terminée. Le client réfléchit au budget mensuel.',
        ]);

        // 5. Create Transactions
        Transaction::create([
            'bien_id' => $biens[4]->id,
            'client_id' => $client1->id,
            'agent_id' => $agent1->id,
            'type' => 'location',
            'prix_final' => 450.00,
            'commission' => 200.00,
            'date_transaction' => now()->subDays(5)->toDateString(),
        ]);

        // 6. Create Favoris
        Favori::create([
            'bien_id' => $biens[0]->id,
            'client_id' => $client1->id,
        ]);
        Favori::create([
            'bien_id' => $biens[1]->id,
            'client_id' => $client1->id,
        ]);

        // 7. Create Messages
        Message::create([
            'nom' => 'Martin Dupont',
            'email' => 'martin.dupont@yahoo.fr',
            'tel' => '0600000001',
            'sujet' => 'Demande d\'informations - Villa contemporaine',
            'message' => 'Bonjour, je souhaiterais savoir s\'il est possible d\'effectuer une visite ce samedi après-midi pour la villa avec piscine. Cordialement.',
            'bien_id' => $biens[0]->id,
        ]);

        Message::create([
            'nom' => 'Inconnu Public',
            'email' => 'public@contact.com',
            'tel' => null,
            'sujet' => 'Partenariat commercial',
            'message' => 'Bonjour, nous sommes une agence de décoration d\'intérieur et souhaitons vous proposer nos services de home staging pour vos biens à vendre.',
            'bien_id' => null,
        ]);
    }
}
