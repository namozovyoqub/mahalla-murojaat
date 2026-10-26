<?php
require_once __DIR__ . '/../includes/init.php';

$updates = [
    '"Mahalla budjeti" tizimi to‘liq joriy etildi' => 'assets/news-open-budget.jpg',
    'Konstitutsiyada mahalla maqomi mustahkamlandi' => 'assets/news-constitution.jpg',
    '"Mahalla yettiligi" faoliyati samaradorligi oshmoqda' => 'assets/news-prezident.jpg',
    'Mahallalarga davlat mulkini ijaraga berish huquqi berildi' => 'assets/news-mahalla-building.jpg',
    'Bolalarni zo‘ravonlikdan himoya qilish bo‘yicha yangi qonun' => 'assets/news-kids.jpg',
    'Mahalla raislarining vakolat muddati uzaytirildi' => 'assets/news-elders.jpg',
    '2026-yil - "Mahallani rivojlantirish yili"' => 'assets/news-2026.jpg',
    'Mahalla xodimlari uchun KPI tizimi joriy etildi' => 'assets/news-center.jpg',
    'Tadbirkorlarga yangi imtiyozlar' => 'assets/news-workers.jpg',
    '"Raqamli mahalla" platformasi yangilandi' => 'assets/news-raqamli.png'
];

$stmt = $pdo->prepare("UPDATE news SET cover_url = :url WHERE title = :title");

foreach ($updates as $title => $url) {
    echo "Updating image for: $title... ";
    $stmt->execute([':url' => $url, ':title' => $title]);
    echo "Done.\n";
}
echo "All images updated successfully.";
