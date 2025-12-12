<?php

namespace App\DataFixtures;

use App\Entity\Medicine;
use App\Entity\Order;
use App\Entity\PharmacyInventory;
use App\Entity\Supplier;
use App\Entity\SupplierInventory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Tunisian cities with coordinates
        $tunisianCities = [
            ['name' => 'Tunis', 'lat' => 36.8065, 'lng' => 10.1815, 'postal' => '1000'],
            ['name' => 'Sfax', 'lat' => 34.7406, 'lng' => 10.7603, 'postal' => '3000'],
            ['name' => 'Sousse', 'lat' => 35.8256, 'lng' => 10.6411, 'postal' => '4000'],
            ['name' => 'Kairouan', 'lat' => 35.6781, 'lng' => 10.0963, 'postal' => '3100'],
            ['name' => 'Bizerte', 'lat' => 37.2744, 'lng' => 9.8739, 'postal' => '7000'],
            ['name' => 'Gabès', 'lat' => 33.8815, 'lng' => 10.0982, 'postal' => '6000'],
            ['name' => 'Ariana', 'lat' => 36.8625, 'lng' => 10.1956, 'postal' => '2080'],
            ['name' => 'Gafsa', 'lat' => 34.425, 'lng' => 8.7842, 'postal' => '2100'],
            ['name' => 'Monastir', 'lat' => 35.7643, 'lng' => 10.8113, 'postal' => '5000'],
            ['name' => 'Ben Arous', 'lat' => 36.7539, 'lng' => 10.2189, 'postal' => '2013'],
            ['name' => 'Kasserine', 'lat' => 35.1675, 'lng' => 8.8361, 'postal' => '1200'],
            ['name' => 'Médenine', 'lat' => 33.3550, 'lng' => 10.5053, 'postal' => '4100'],
            ['name' => 'Nabeul', 'lat' => 36.4516, 'lng' => 10.7361, 'postal' => '8000'],
            ['name' => 'Tataouine', 'lat' => 32.9296, 'lng' => 10.4517, 'postal' => '3200'],
            ['name' => 'Béja', 'lat' => 36.7256, 'lng' => 9.1817, 'postal' => '9000'],
        ];

        // Create Admin Users
        for ($i = 1; $i <= 5; $i++) {
            $admin = new User();
            $admin->setEmail("admin{$i}@pharmacy.tn");
            $admin->setName("Admin User {$i}");
            $admin->setPhone("+216 " . rand(20, 99) . " " . rand(100, 999) . " " . rand(100, 999));
            $admin->setRoles([User::ROLE_ADMIN]);
            $admin->setIsActive(true);
            $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
            
            $cityData = $tunisianCities[array_rand($tunisianCities)];
            $admin->setCity($cityData['name']);
            $admin->setLatitude($cityData['lat']);
            $admin->setLongitude($cityData['lng']);
            $admin->setPostalCode($cityData['postal']);
            $admin->setAddress("Avenue Habib Bourguiba, " . $cityData['name']);
            
            $manager->persist($admin);
        }

        $pharmacyUsers = [];
        // Create Pharmacy Users
        for ($i = 1; $i <= 50; $i++) {
            $pharmacy = new User();
            $pharmacy->setEmail("pharmacy{$i}@pharmacy.tn");
            $pharmacy->setName("Pharmacie {$i}");
            $pharmacy->setPhone("+216 " . rand(70, 79) . " " . rand(100, 999) . " " . rand(100, 999));
            $pharmacy->setRoles([User::ROLE_PHARMACY]);
            $pharmacy->setIsActive(true);
            $pharmacy->setPassword($this->passwordHasher->hashPassword($pharmacy, 'pharmacy123'));
            
            $cityData = $tunisianCities[array_rand($tunisianCities)];
            $pharmacy->setCity($cityData['name']);
            $pharmacy->setLatitude($cityData['lat'] + (rand(-100, 100) / 1000));
            $pharmacy->setLongitude($cityData['lng'] + (rand(-100, 100) / 1000));
            $pharmacy->setPostalCode($cityData['postal']);
            $pharmacy->setAddress("Rue de la République " . rand(1, 100) . ", " . $cityData['name']);
            
            $manager->persist($pharmacy);
            $pharmacyUsers[] = $pharmacy;
        }

        // Create Suppliers
        $suppliers = [];
        $supplierNames = [
            'Pharma Tunisia', 'MediSupply', 'TunisPharma', 'HealthCare Supply',
            'PharmaDirect', 'MedExpress', 'WellnessSupply', 'CarePharmaceuticals',
            'TunisHealth', 'MediCare Supply', 'PharmaCentral', 'GlobalMed Tunisia',
            'SupplyCare', 'MediPlus', 'PharmaSolutions', 'HealthFirst Supply',
            'TunisMed', 'PharmaTrade', 'MediSource', 'CarePlus Supply'
        ];

        foreach ($supplierNames as $index => $supplierName) {
            $supplier = new Supplier();
            $supplier->setName($supplierName);
            $supplier->setEmail(strtolower(str_replace(' ', '', $supplierName)) . "@supplier.tn");
            $supplier->setPhone("+216 " . rand(20, 29) . " " . rand(100, 999) . " " . rand(100, 999));
            $cityData = $tunisianCities[array_rand($tunisianCities)];
            $supplier->setAddress("Zone Industrielle, " . $cityData['name']);
            
            $manager->persist($supplier);
            $suppliers[] = $supplier;
        }

        // Create Supplier Users
        foreach ($suppliers as $index => $supplier) {
            $supplierUser = new User();
            $supplierUser->setEmail("supplier" . ($index + 1) . "@pharmacy.tn");
            $supplierUser->setName("Supplier User " . ($index + 1));
            $supplierUser->setPhone("+216 " . rand(90, 99) . " " . rand(100, 999) . " " . rand(100, 999));
            $supplierUser->setRoles([User::ROLE_SUPPLIER]);
            $supplierUser->setIsActive(true);
            $supplierUser->setPassword($this->passwordHasher->hashPassword($supplierUser, 'supplier123'));
            $supplierUser->setSupplier($supplier);
            
            $cityData = $tunisianCities[array_rand($tunisianCities)];
            $supplierUser->setCity($cityData['name']);
            $supplierUser->setLatitude($cityData['lat']);
            $supplierUser->setLongitude($cityData['lng']);
            $supplierUser->setPostalCode($cityData['postal']);
            
            $manager->persist($supplierUser);
        }

        // Create Delivery Users
        for ($i = 1; $i <= 20; $i++) {
            $delivery = new User();
            $delivery->setEmail("delivery{$i}@pharmacy.tn");
            $delivery->setName("Delivery Person {$i}");
            $delivery->setPhone("+216 " . rand(50, 59) . " " . rand(100, 999) . " " . rand(100, 999));
            $delivery->setRoles([User::ROLE_DELIVERY]);
            $delivery->setIsActive(true);
            $delivery->setPassword($this->passwordHasher->hashPassword($delivery, 'delivery123'));
            
            $cityData = $tunisianCities[array_rand($tunisianCities)];
            $delivery->setCity($cityData['name']);
            $delivery->setLatitude($cityData['lat']);
            $delivery->setLongitude($cityData['lng']);
            $delivery->setPostalCode($cityData['postal']);
            
            $manager->persist($delivery);
        }

        // Create Medicines
        $medicines = [];
        $medicineData = [
            ['Paracetamol 500mg', 'Pain relief and fever reducer', 'Analgesics', 15.5],
            ['Ibuprofen 400mg', 'Anti-inflammatory pain relief', 'Analgesics', 18.0],
            ['Amoxicillin 500mg', 'Antibiotic for bacterial infections', 'Antibiotics', 25.0],
            ['Azithromycin 250mg', 'Antibiotic for respiratory infections', 'Antibiotics', 32.0],
            ['Omeprazole 20mg', 'Proton pump inhibitor for acid reflux', 'Gastrointestinal', 22.5],
            ['Metformin 500mg', 'Diabetes medication', 'Diabetes', 28.0],
            ['Atorvastatin 20mg', 'Cholesterol-lowering medication', 'Cardiovascular', 35.0],
            ['Amlodipine 5mg', 'Blood pressure medication', 'Cardiovascular', 24.0],
            ['Losartan 50mg', 'Blood pressure medication', 'Cardiovascular', 26.5],
            ['Simvastatin 20mg', 'Cholesterol medication', 'Cardiovascular', 30.0],
            ['Levothyroxine 50mcg', 'Thyroid hormone replacement', 'Endocrine', 20.0],
            ['Salbutamol Inhaler', 'Bronchodilator for asthma', 'Respiratory', 42.0],
            ['Cetirizine 10mg', 'Antihistamine for allergies', 'Allergy', 12.5],
            ['Loratadine 10mg', 'Non-drowsy antihistamine', 'Allergy', 14.0],
            ['Prednisone 5mg', 'Corticosteroid anti-inflammatory', 'Anti-inflammatory', 19.0],
            ['Diclofenac 50mg', 'NSAID for pain and inflammation', 'Analgesics', 16.5],
            ['Citalopram 20mg', 'Antidepressant', 'Mental Health', 38.0],
            ['Sertraline 50mg', 'Antidepressant for anxiety', 'Mental Health', 40.0],
            ['Alprazolam 0.5mg', 'Anti-anxiety medication', 'Mental Health', 28.0],
            ['Zolpidem 10mg', 'Sleep aid medication', 'Mental Health', 32.0],
        ];

        foreach ($medicineData as $data) {
            $medicine = new Medicine();
            $medicine->setName($data[0]);
            $medicine->setDescription($data[1]);
            $medicine->setCategory($data[2]);
            $medicine->setQuantity(rand(50, 500));
            $medicine->setPrice($data[3]);
            
            $futureDate = new \DateTime();
            $futureDate->modify('+' . rand(180, 730) . ' days');
            $medicine->setExpirationDate($futureDate);
            
            $manager->persist($medicine);
            $medicines[] = $medicine;
        }

        $manager->flush();

        // Create Pharmacy Inventory
        foreach ($pharmacyUsers as $pharmacy) {
            $medicineCount = rand(min(15, count($medicines)), min(count($medicines), 60));
            $selectedMedicines = array_rand($medicines, $medicineCount);
            if (!is_array($selectedMedicines)) {
                $selectedMedicines = [$selectedMedicines];
            }
            
            foreach ($selectedMedicines as $medicineIndex) {
                $inventory = new PharmacyInventory();
                $inventory->setPharmacy($pharmacy);
                $inventory->setMedicine($medicines[$medicineIndex]);
                $inventory->setQuantity(rand(10, 200));
                $basePurchasePrice = $medicines[$medicineIndex]->getPrice() * (rand(90, 120) / 100);
                $inventory->setPurchasePrice(number_format($basePurchasePrice, 2, '.', ''));
                
                $restockDate = new \DateTime();
                $restockDate->modify('-' . rand(1, 90) . ' days');
                $inventory->setLastRestocked($restockDate);
                
                $manager->persist($inventory);
            }
        }

        // Create Supplier Inventory
        foreach ($suppliers as $supplier) {
            $medicineCount = rand(min(10, count($medicines)), count($medicines));
            $selectedMedicines = array_rand($medicines, $medicineCount);
            if (!is_array($selectedMedicines)) {
                $selectedMedicines = [$selectedMedicines];
            }
            
            foreach ($selectedMedicines as $medicineIndex) {
                $inventory = new SupplierInventory();
                $inventory->setSupplier($supplier);
                $inventory->setMedicine($medicines[$medicineIndex]);
                $inventory->setQuantity(rand(100, 1000));
                $baseWholesalePrice = $medicines[$medicineIndex]->getPrice() * (rand(50, 75) / 100);
                $inventory->setWholesalePrice(number_format($baseWholesalePrice, 2, '.', ''));
                
                $restockDate = new \DateTime();
                $restockDate->modify('-' . rand(1, 60) . ' days');
                $inventory->setLastRestocked($restockDate);
                
                $manager->persist($inventory);
            }
        }

        // Create Orders
        $statuses = ['pending', 'completed', 'cancelled'];
        for ($i = 0; $i < 100; $i++) {
            $order = new Order();
            $order->setMedicine($medicines[array_rand($medicines)]);
            $order->setSupplier($suppliers[array_rand($suppliers)]);
            $order->setQuantity(rand(10, 200));
            $order->setStatus($statuses[array_rand($statuses)]);
            
            $orderDate = new \DateTime();
            $orderDate->modify('-' . rand(1, 180) . ' days');
            $order->setOrderDate($orderDate);
            
            $manager->persist($order);
        }

        $manager->flush();
    }
}
