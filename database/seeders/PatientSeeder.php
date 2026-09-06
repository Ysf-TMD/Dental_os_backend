<?php

namespace Database\Seeders;

use App\Modules\Patients\Models\Patient;
use App\Modules\Patients\Models\PatientEmergencyContact;
use App\Modules\Patients\Models\PatientMedicalHistory;
use App\Modules\Patients\Models\PatientAllergy;
use App\Modules\Patients\Models\PatientMedication;
use App\Modules\Patients\Models\PatientHabit;
use App\Modules\Patients\Models\PatientInsurance;
use App\Modules\Patients\Models\PatientConsent;
use App\Modules\Patients\Models\PatientNote;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'title' => 'M.',
                'first_name' => 'Ahmed',
                'last_name' => 'Benali',
                'first_name_ar' => 'أحمد',
                'last_name_ar' => 'بن علي',
                'phone' => '+212 661 234 567',
                'phone_secondary' => '+212 662 345 678',
                'email' => 'ahmed.benali@email.com',
                'gender' => 'M',
                'birth_date' => '1985-05-15',
                'birth_place' => 'Casablanca',
                'nationality' => 'Marocain',
                'cin' => 'AB123456',
                'passport' => null,
                'address' => '123 Rue Mohammed V, Casablanca',
                'city' => 'Casablanca',
                'region' => 'Casablanca-Settat',
                'postal_code' => '20000',
                'blood_group' => 'A+',
                'referring_dentist' => 'Dr. Karimi',
                'first_visit_date' => '2024-01-15',
                'consultation_reason' => 'Douleur dentaire',
                'oral_health_status' => 'Bonne',
                'status' => 'actif',
                'cached_balance' => 500.00,
                'credit_limit' => 1000.00,
                'discount_rate' => 10.00,
                'preferred_payment_method' => 'Card',
            ],
            [
                'title' => 'Mme',
                'first_name' => 'Fatima',
                'last_name' => 'Alami',
                'first_name_ar' => 'فاطمة',
                'last_name_ar' => 'العلمي',
                'phone' => '+212 663 456 789',
                'phone_secondary' => null,
                'email' => 'fatima.alami@email.com',
                'gender' => 'F',
                'birth_date' => '1990-08-22',
                'birth_place' => 'Rabat',
                'nationality' => 'Marocain',
                'cin' => 'CD234567',
                'passport' => null,
                'address' => '456 Avenue Hassan II, Rabat',
                'city' => 'Rabat',
                'region' => 'Rabat-Salé-Kénitra',
                'postal_code' => '10000',
                'blood_group' => 'O+',
                'referring_dentist' => null,
                'first_visit_date' => '2024-02-01',
                'consultation_reason' => 'Consultation de routine',
                'oral_health_status' => 'Excellente',
                'status' => 'actif',
                'cached_balance' => 0.00,
                'credit_limit' => 500.00,
                'discount_rate' => 5.00,
                'preferred_payment_method' => 'Cash',
            ],
            [
                'title' => 'M.',
                'first_name' => 'Youssef',
                'last_name' => 'Chraibi',
                'first_name_ar' => 'يوسف',
                'last_name_ar' => 'الشرايبي',
                'phone' => '+212 664 567 890',
                'phone_secondary' => null,
                'email' => 'youssef.chraibi@email.com',
                'gender' => 'M',
                'birth_date' => '1978-12-10',
                'birth_place' => 'Marrakech',
                'nationality' => 'Marocain',
                'cin' => 'EF345678',
                'passport' => 'P12345678',
                'address' => '789 Rue Bab Agnaou, Marrakech',
                'city' => 'Marrakech',
                'region' => 'Marrakech-Safi',
                'postal_code' => '40000',
                'blood_group' => 'B+',
                'referring_dentist' => 'Dr. Bennani',
                'first_visit_date' => '2024-03-10',
                'consultation_reason' => 'Implant dentaire',
                'oral_health_status' => 'Moyenne',
                'status' => 'nouveau',
                'cached_balance' => 0.00,
                'credit_limit' => 2000.00,
                'discount_rate' => 15.00,
                'preferred_payment_method' => 'Transfer',
            ],
            [
                'title' => 'Mlle',
                'first_name' => 'Sara',
                'last_name' => 'Idrissi',
                'first_name_ar' => 'سارة',
                'last_name_ar' => 'إدريسي',
                'phone' => '+212 665 678 901',
                'phone_secondary' => '+212 666 789 012',
                'email' => 'sara.idrissi@email.com',
                'gender' => 'F',
                'birth_date' => '1995-03-25',
                'birth_place' => 'Fès',
                'nationality' => 'Marocain',
                'cin' => 'GH456789',
                'passport' => null,
                'address' => '321 Rue Talaa Kebira, Fès',
                'city' => 'Fès',
                'region' => 'Fès-Meknès',
                'postal_code' => '30000',
                'blood_group' => 'AB+',
                'referring_dentist' => null,
                'first_visit_date' => '2024-04-05',
                'consultation_reason' => 'Blanchiment',
                'oral_health_status' => 'Bonne',
                'status' => 'actif',
                'cached_balance' => 1200.00,
                'credit_limit' => 1500.00,
                'discount_rate' => 8.00,
                'preferred_payment_method' => 'Card',
            ],
            [
                'title' => 'M.',
                'first_name' => 'Mohammed',
                'last_name' => 'Tazi',
                'first_name_ar' => 'محمد',
                'last_name_ar' => 'الطازي',
                'phone' => '+212 667 890 123',
                'phone_secondary' => null,
                'email' => 'mohammed.tazi@email.com',
                'gender' => 'M',
                'birth_date' => '1982-07-08',
                'birth_place' => 'Tanger',
                'nationality' => 'Marocain',
                'cin' => 'IJ567890',
                'passport' => null,
                'address' => '654 Boulevard Pasteur, Tanger',
                'city' => 'Tanger',
                'region' => 'Tanger-Tétouan-Al Hoceïma',
                'postal_code' => '90000',
                'blood_group' => 'A-',
                'referring_dentist' => 'Dr. Fassi',
                'first_visit_date' => '2024-05-20',
                'consultation_reason' => 'Orthodontie',
                'oral_health_status' => 'Moyenne',
                'status' => 'inactif',
                'cached_balance' => 0.00,
                'credit_limit' => 0.00,
                'discount_rate' => 0.00,
                'preferred_payment_method' => 'Cash',
            ],
        ];

        foreach ($patients as $patientData) {
            $patient = Patient::create($patientData);

            // Emergency contacts
            PatientEmergencyContact::create([
                'patient_id' => $patient->id,
                'name' => $patient->last_name . ' ' . ($patient->gender === 'M' ? 'épouse' : 'époux'),
                'relationship' => 'Conjoint',
                'phone' => '+212 600 000 001',
                'address' => $patient->address,
            ]);

            // Medical history
            PatientMedicalHistory::create([
                'patient_id' => $patient->id,
                'diabetes' => $patient->id % 2 === 0,
                'hypertension' => $patient->id % 3 === 0,
                'asthma' => false,
                'epilepsy' => false,
                'heart_disease' => false,
                'hepatitis' => false,
                'hiv' => false,
                'pregnancy' => $patient->gender === 'F' && $patient->id === 2,
                'cancer' => false,
                'bleeding_disorder' => false,
                'other_conditions' => null,
            ]);

            // Allergies
            PatientAllergy::create([
                'patient_id' => $patient->id,
                'penicillin' => $patient->id === 1,
                'latex' => false,
                'anesthetics' => false,
                'iodine' => $patient->id === 3,
                'other_allergies' => $patient->id === 1 ? 'Noix' : null,
            ]);

            // Medications
            if ($patient->id % 2 === 0) {
                PatientMedication::create([
                    'patient_id' => $patient->id,
                    'name' => 'Paracétamol',
                    'dose' => '500mg',
                    'frequency' => '2x/jour',
                ]);
            }

            // Habits
            PatientHabit::create([
                'patient_id' => $patient->id,
                'smoker' => $patient->id % 2 === 0,
                'hookah' => $patient->id === 3,
                'alcohol' => false,
                'drugs' => false,
                'notes' => $patient->id === 3 ? 'Fume occasionnellement' : null,
            ]);

            // Insurance
            if ($patient->id <= 3) {
                PatientInsurance::create([
                    'patient_id' => $patient->id,
                    'type' => 'Santé',
                    'company' => 'Wafa Assurance',
                    'policy_number' => 'POL' . str_pad($patient->id, 6, '0', STR_PAD_LEFT),
                    'expiration_date' => '2025-12-31',
                ]);
            }

            // Consents
            PatientConsent::create([
                'patient_id' => $patient->id,
                'terms_of_use' => true,
                'data_processing' => true,
                'sms_notifications' => $patient->id % 2 === 0,
                'email_notifications' => true,
                'whatsapp_notifications' => $patient->id % 2 === 0,
                'cndp_consent' => true,
            ]);

            // Notes
            if ($patient->id === 1) {
                PatientNote::create([
                    'patient_id' => $patient->id,
                    'content' => 'Patient préfère les rendez-vous le matin',
                    'is_private' => false,
                ]);
            }
        }
    }
}
