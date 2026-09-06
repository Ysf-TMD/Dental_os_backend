<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Agenda\Models\Appointment;
use App\Modules\Consultations\Models\Consultation;
use App\Modules\Devis\Models\Quote;
use App\Modules\Employes\Models\Employee;
use App\Modules\Facturation\Models\Invoice;
use App\Modules\Laboratoire\Models\LabOrder;
use App\Modules\Paiements\Models\Payment;
use App\Modules\Patients\Models\Patient;
use App\Modules\Role\Models\Role;
use App\Modules\Stock\Models\Product;
use App\Modules\Traitements\Models\Treatment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create one user per seeded role for easy testing
        $roleUsers = [
            'super_admin' => ['super_admin@dentalos.ma', 'Dr. Amine Benjelloun'],
            'admin' => ['admin@dentalos.ma', 'Dr. Salma Idrissi'],
            'dentist' => ['dentist@dentalos.ma', 'Dr. Karim Ouazzani'],
            'assistant' => ['assistant@dentalos.ma', 'Hind Moussaoui'],
            'secretary' => ['secretary@dentalos.ma', 'Laila Bouzidi'],
            'accountant' => ['accountant@dentalos.ma', 'Rachid El Khatib'],
            'lab_technician' => ['lab_technician@dentalos.ma', 'Technicien Lab'],
            'viewer' => ['viewer@dentalos.ma', 'Invité Lecture'],
        ];

        foreach ($roleUsers as $roleName => [$email, $name]) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                continue;
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => Hash::make('password')]
            );

            if (!$user->roles()->where('role_id', $role->id)->exists()) {
                $user->roles()->attach($role->id);
            }
        }

        // Patients
        $patientsData = [
            ['Yasmine', 'El Amrani', 'F', '1992-04-15', 'Rabat', 'actif'],
            ['Omar', 'Benkirane', 'M', '1985-11-02', 'Salé', 'actif'],
            ['Salma', 'Cherkaoui', 'F', '1998-07-23', 'Rabat', 'nouveau'],
            ['Karim', 'Tazi', 'M', '1979-01-30', 'Témara', 'actif'],
            ['Nadia', 'Alaoui', 'F', '1990-09-12', 'Rabat', 'inactif'],
            ['Mehdi', 'Berrada', 'M', '2001-03-08', 'Kénitra', 'actif'],
            ['Imane', 'Fassi', 'F', '1987-12-19', 'Rabat', 'actif'],
            ['Youssef', 'Lahlou', 'M', '1995-06-27', 'Salé', 'nouveau'],
            ['Sofia', 'Bennani', 'F', '1983-02-14', 'Rabat', 'actif'],
            ['Adam', 'Chraibi', 'M', '2010-08-05', 'Rabat', 'actif'],
        ];

        $patients = collect($patientsData)->map(function ($p, $i) {
            return Patient::create([
                'first_name' => $p[0],
                'last_name' => $p[1],
                'gender' => $p[2],
                'birth_date' => $p[3],
                'city' => $p[4],
                'status' => $p[5],
                'phone' => '+212 6 '.str_pad((string) (61234500 + $i), 8, '0', STR_PAD_LEFT),
                'email' => strtolower($p[0].'.'.str_replace(' ', '', $p[1])).'@mail.ma',
                'last_visit' => now()->subDays($i * 7)->toDateString(),
                'cached_balance' => [0, 450, 0, 1200, 0, 300, 0, 0, 850, 0][$i],
            ]);
        });

        // Employees
        foreach ([
            ['Dr. Amine Benjelloun', 'Dentiste — Directeur', 'bg-blue-500'],
            ['Dr. Salma Idrissi', 'Dentiste', 'bg-teal-500'],
            ['Dr. Karim Ouazzani', 'Orthodontiste', 'bg-indigo-500'],
            ['Hind Moussaoui', 'Assistante dentaire', 'bg-pink-500'],
            ['Rachid El Khatib', 'Comptable', 'bg-amber-500'],
            ['Laila Bouzidi', 'Réceptionniste', 'bg-purple-500'],
        ] as $i => $e) {
            Employee::create([
                'name' => $e[0],
                'role' => $e[1],
                'color' => $e[2],
                'email' => strtolower(str_replace([' ', '.'], ['', ''], explode(' ', $e[0])[1] ?? 'staff')).$i.'@dentalos.ma',
                'phone' => '+212 6 '.str_pad((string) (70000000 + $i), 8, '0', STR_PAD_LEFT),
            ]);
        }

        // Appointments (this week)
        $practitioners = ['Dr. Amine', 'Dr. Salma', 'Dr. Karim'];
        $reasons = ['Contrôle annuel', 'Détartrage', 'Pose couronne', 'Douleur dent 36', 'Contrôle ortho', 'Extraction', 'Blanchiment', 'Consultation implant'];
        foreach (range(0, 13) as $i) {
            $hour = 8 + ($i % 9);
            Appointment::create([
                'patient_id' => $patients[$i % 10]->id,
                'practitioner' => $practitioners[$i % 3],
                'date' => now()->startOfWeek()->addDays($i % 6)->toDateString(),
                'start_time' => sprintf('%02d:%s', $hour, $i % 2 ? '30' : '00'),
                'end_time' => sprintf('%02d:%s', $hour + 1, $i % 2 ? '00' : '30'),
                'reason' => $reasons[$i % 8],
                'status' => ['confirmé', 'en attente', 'confirmé', 'terminé'][$i % 4],
            ]);
        }

        // Invoices + payments
        foreach (range(0, 7) as $i) {
            $total = 800 + ($i * 950) % 9000;
            $status = ['payée', 'partielle', 'en attente', 'en retard'][$i % 4];
            $paid = match ($status) {
                'payée' => $total,
                'partielle' => round($total / 2, 2),
                default => 0,
            };
            $invoice = Invoice::create([
                'patient_id' => $patients[$i % 10]->id,
                'date' => now()->subDays($i * 4)->toDateString(),
                'due_date' => now()->subDays($i * 4)->addDays(30)->toDateString(),
                'total' => $total,
                'paid' => $paid,
                'status' => $status,
            ]);
            if ($paid > 0) {
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $paid,
                    'method' => ['espèces', 'carte', 'virement'][$i % 3],
                    'paid_at' => $invoice->date,
                ]);
            }
        }

        // Quotes
        foreach (range(0, 5) as $i) {
            Quote::create([
                'patient_id' => $patients[$i]->id,
                'title' => 'Plan de traitement',
                'total' => 2400 + ($i * 871) % 18000,
                'status' => ['envoyé', 'accepté', 'brouillon', 'refusé'][$i % 4],
                'items' => [['acte' => 'Consultation', 'prix' => 300]],
            ]);
        }

        // Consultations
        $motifs = ['Contrôle annuel', 'Douleur dent 36', 'Suite traitement carie', 'Saignement gingival', 'Contrôle ortho'];
        foreach (range(0, 7) as $i) {
            Consultation::create([
                'patient_id' => $patients[$i % 10]->id,
                'practitioner' => $practitioners[$i % 3],
                'motif' => $motifs[$i % 5],
                'date' => now()->subDays($i * 3)->toDateString(),
                'status' => $i % 4 === 0 ? 'à suivre' : 'clôturée',
            ]);
        }

        // Treatments
        foreach ([
            ['Composite occlusal 36', '36', 450, 'en cours'],
            ['Couronne céramique 24', '24', 4800, 'planifié'],
            ['Extraction 48', '48', 600, 'terminé'],
            ['Implant 46', '46', 8500, 'en cours'],
            ['Dévitalisation 16', '16', 1800, 'planifié'],
            ['Détartrage complet', null, 400, 'terminé'],
        ] as $i => $t) {
            Treatment::create([
                'patient_id' => $patients[$i % 10]->id,
                'name' => $t[0],
                'tooth' => $t[1],
                'price' => $t[2],
                'price_at_moment' => $t[2],
                'total' => $t[2],
                'status' => $t[3],
            ]);
        }

        // Lab orders
        foreach ([
            ['Couronne zircone 24', 'Lab ProDent Rabat', 'en cours'],
            ['Bridge 3 éléments', 'Lab Smile Casablanca', 'livré'],
            ['Gouttière ortho', 'Lab OrthoTech', 'retard'],
            ['Prothèse partielle', 'Lab ProDent Rabat', 'en cours'],
        ] as $i => $l) {
            LabOrder::create([
                'patient_id' => $patients[$i]->id,
                'type' => $l[0],
                'lab_name' => $l[1],
                'sent_at' => now()->subDays(10 + $i * 3)->toDateString(),
                'due_at' => now()->addDays(5 - $i * 4)->toDateString(),
                'status' => $l[2],
            ]);
        }

        // Products
        foreach ([
            ['Gants nitrile M (x100)', 'Consommables', 12, 20, 85],
            ['Composite A2 seringue', 'Restauration', 34, 15, 240],
            ['Anesthésique articaïne', 'Pharmacie', 8, 25, 130],
            ['Fraises diamantées kit', 'Instruments', 45, 10, 320],
            ['Digue dentaire (x36)', 'Consommables', 18, 12, 95],
            ['Ciment verre ionomère', 'Restauration', 6, 10, 410],
            ['Aiguilles 27G (x100)', 'Pharmacie', 52, 30, 60],
            ['Empreinte alginate 500g', 'Prothèse', 22, 8, 145],
        ] as $i => $p) {
            Product::create([
                'name' => $p[0],
                'category' => $p[1],
                'stock' => $p[2],
                'min_stock' => $p[3],
                'price' => $p[4],
                'unit' => 'u',
                'expires_at' => now()->addDays(30 + $i * 45)->toDateString(),
            ]);
        }
    }
}
