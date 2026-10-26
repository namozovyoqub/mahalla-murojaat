<?php
require_once __DIR__ . '/../includes/init.php';

$news_items = [
    [
        'title' => '"Mahalla budjeti" tizimi to‘liq joriy etildi',
        'body' => "2024-yil 1-yanvardan boshlab barcha tuman va shaharlarda 'Mahalla budjeti' tizimi ishga tushdi. Endilikda soliq tushumlarining bir qismi to‘g‘ridan-to‘g‘ri mahallalarning o‘zida qoladi va infratuzilmani yaxshilashga sarflanadi.",
        'created_at' => '2024-01-10 10:00:00'
    ],
    [
        'title' => 'Konstitutsiyada mahalla maqomi mustahkamlandi',
        'body' => "Yangi tahrirdagi Konstitutsiyaning 127-moddasida mahalla fuqarolar yig‘inlari maqomi alohida belgilab qo‘yildi. Bu mahalla institutining jamiyatdagi rolini oshirishga xizmat qiladi.",
        'created_at' => '2024-02-15 14:30:00'
    ],
    [
        'title' => '"Mahalla yettiligi" faoliyati samaradorligi oshmoqda',
        'body' => "Respublikamizdagi barcha mahallalarda 'Mahalla yettiligi' tizimi to‘liq shakllandi. Ular orasida yoshlar yetakchisi, hokim yordamchisi va xotin-qizlar faoli kabi yangi lavozimlar bor.",
        'created_at' => '2024-03-20 09:15:00'
    ],
    [
        'title' => 'Mahallalarga davlat mulkini ijaraga berish huquqi berildi',
        'body' => "Endilikda mahallalar o‘z hududidagi bo‘sh turgan davlat mulklarini auksion orqali ijaraga berish va tushgan mablag‘ni mahalla rivojiga sarflash huquqiga ega bo‘ldi.",
        'created_at' => '2024-04-05 11:45:00'
    ],
    [
        'title' => 'Bolalarni zo‘ravonlikdan himoya qilish bo‘yicha yangi qonun',
        'body' => "Yangi qonun bilan bolalarni himoya qilishda mahalla faollarining, xususan, ijtimoiy xodimlarning mas’uliyati va vakolatlari kengaytirildi.",
        'created_at' => '2024-05-12 16:20:00'
    ],
    [
        'title' => 'Mahalla raislarining vakolat muddati uzaytirildi',
        'body' => "Mahalla raislarining faoliyat samaradorligini ta'minlash maqsadida ularning vakolat muddati 3 yildan 5 yilga uzaytirildi. Saylovlar yangi tartibda o‘tkaziladi.",
        'created_at' => '2024-06-01 10:00:00'
    ],
    [
        'title' => '2026-yil - "Mahallani rivojlantirish yili"',
        'body' => "Prezident tomonidan kelgusi yillarda mahalla tizimini rivojlantirishga alohida e'tibor qaratilishi va 2026-yil aynan shu nom bilan atalishi taklif etildi.",
        'created_at' => '2024-07-22 13:10:00'
    ],
    [
        'title' => 'Mahalla xodimlari uchun KPI tizimi joriy etildi',
        'body' => "Mahalla raisi va yettilik a'zolari faoliyati endi KPI (samaradorlik ko‘rsatkichlari) asosida baholanadi va reytingi yuqori xodimlar moddiy rag‘batlantiriladi.",
        'created_at' => '2024-08-30 09:00:00'
    ],
    [
        'title' => 'Tadbirkorlarga yangi imtiyozlar',
        'body' => "'Mahalla yettiligi' uchun bino qurib bergan yoki o‘z binosidan joy ajratgan tadbirkorlarga 3 yildan 7 yilgacha soliq imtiyozlari beriladigan bo‘ldi.",
        'created_at' => '2024-09-15 15:40:00'
    ],
    [
        'title' => '"Raqamli mahalla" platformasi yangilandi',
        'body' => "Barcha ma'lumotlarni yagona bazada jamlash va aholiga onlayn xizmat ko‘rsatish sifatini oshirish maqsadida 'Raqamli mahalla' tizimiga yangi funksiyalar qo‘shildi.",
        'created_at' => '2024-10-05 10:30:00'
    ]
];

$stmt = $pdo->prepare("INSERT INTO news (title, body, created_at) VALUES (:title, :body, :created_at)");

foreach ($news_items as $item) {
    echo "Inserting: " . $item['title'] . "\n";
    $stmt->execute([
        ':title' => $item['title'],
        ':body' => $item['body'],
        ':created_at' => $item['created_at']
    ]);
}

echo "Done successfully.";
