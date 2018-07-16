<?php
session_start();
setlocale(LC_TIME, 'Indonesian');

require_once "../src/database.php";
require_once '../bower_components/autoload.php';

if (isset($_SESSION['userSessionID']) == "" && isset($_SESSION['userSessionName']) == "" && isset($_SESSION['userSessionEmail']) == "") {
    header("location: /403.php");
}

$id = $_GET['id'];
$sql = "SELECT t.*, u.id AS id_notulis, u.nama AS user_name FROM transkrip t JOIN notulis u ON u.id = t.id_notulis WHERE t.id = '$id'";
$res = mysqli_query($db, $sql) or die(mysqli_error($db));
$row = mysqli_fetch_assoc($res);

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->getCompatibility()->setOoxmlVersion(15);
$phpWord->setDefaultFontName('Times New Roman');
$phpWord->setDefaultFontSize(12);
$phpWord->setDefaultParagraphStyle(
    array(
        'spaceAfter' => 0
    )
);

$rightTabStyleName = 'rightTab';
$phpWord->addParagraphStyle($rightTabStyleName, array('tabs' => array(new \PhpOffice\PhpWord\Style\Tab('right', 8989))));

$section = $phpWord->addSection();

$header = $section->addHeader();
$header->addText($row['proyek'] . "\t" . strftime("%d %B %Y", strtotime($row['tanggal'])), null, $rightTabStyleName);

$footer = $section->addFooter();
$footer->addPreserveText('IDI ' . $row['idi'] . ' : ' . $row['kriteria'] . "\t" . 'Page {PAGE} of {NUMPAGES}', null, $rightTabStyleName);

$tableName = 'Transcript';
$tableStyle = array('borderSize' => 1, 'borderColor' => '000000', 'cellMarginLeft' => 100);
$tableFirstRowStyle = array('borderBottomSize' => 1, 'borderBottomColor' => '000000', 'bgColor' => 'FFFFFF');
$tableFontStyle = array('bold' => true);

$phpWord->addTableStyle($tableName, $tableStyle, $tableFirstRowStyle);

$table = $section->addTable($tableName);
$table->addRow();
$table->addCell(4500)->addText('Proyek', $tableFontStyle);
$table->addCell(4500)->addText(': ' . $row['proyek']);
$table->addRow();
$table->addCell(4500)->addText('Notulis', $tableFontStyle);
$table->addCell(4500)->addText(': ' . $row['user_name']);
$table->addRow();
$table->addCell(4500)->addText('IDI', $tableFontStyle);
$table->addCell(4500)->addText(': ' . $row['idi']);
$table->addRow();
$table->addCell(4500)->addText('Tanggal', $tableFontStyle);
$table->addCell(4500)->addText(': ' . strftime("%d %B %Y", strtotime($row['tanggal'])));
$table->addRow();
$table->addCell(4500)->addText('Hari / Waktu', $tableFontStyle);
$table->addCell(4500)->addText(': ' . $row['hari'] . ' / ' . $row['waktu']);
$table->addRow();
$table->addCell(4500)->addText('Moderator', $tableFontStyle);
$table->addCell(4500)->addText(': ' . $row['moderator']);
$table->addRow();
$table->addCell(4500)->addText('Kriteria', $tableFontStyle);
$table->addCell(4500)->addText(': ' . $row['kriteria']);

$section->addTextBreak(1);
$section->addTextBreak(1);

$html = $row['isi'];
\PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

$file = $row['proyek'] . ' - ' . $row['idi'] . '.docx';
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');
