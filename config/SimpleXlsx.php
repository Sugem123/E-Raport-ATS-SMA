<?php

class SimpleXlsx
{
    /**
     * Membaca file .xlsx menjadi array 2D
     */
    public static function parse(string $filename): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filename) !== true) {
            return [];
        }

        // 1. Baca shared strings jika ada
        $sharedStrings = [];
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXml !== false) {
            $xml = simplexml_load_string($sharedXml);
            if ($xml !== false) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string)$si->t;
                    } elseif (isset($si->r)) {
                        $text = '';
                        foreach ($si->r as $r) {
                            $text .= (string)$r->t;
                        }
                        $sharedStrings[] = $text;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Baca sheet1.xml
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXml === false) {
            $zip->close();
            return [];
        }

        $xml = simplexml_load_string($sheetXml);
        $zip->close();

        if ($xml === false) {
            return [];
        }

        $rows = [];
        foreach ($xml->sheetData->row as $row) {
            $r = [];
            foreach ($row->c as $c) {
                // Tentukan index kolom berdasarkan koordinat (misal A1, B1, C1)
                $cellRef = (string)$c['r'];
                preg_match('/^([A-Z]+)/', $cellRef, $matches);
                $colLetters = $matches[1] ?? 'A';
                $colIndex = self::colIndex($colLetters);

                $val = (string)$c->v;
                $type = (string)$c['t'];

                if ($type === 's') { // shared string
                    $val = $sharedStrings[(int)$val] ?? '';
                } elseif ($type === 'inlineStr' && isset($c->is->t)) {
                    $val = (string)$c->is->t;
                }

                $r[$colIndex] = trim($val);
            }

            // Normalisasi array kolom berurutan 0, 1, 2...
            if (!empty($r)) {
                $maxCol = max(array_keys($r));
                $normalizedRow = [];
                for ($i = 0; $i <= $maxCol; $i++) {
                    $normalizedRow[$i] = $r[$i] ?? '';
                }
                $rows[] = $normalizedRow;
            }
        }

        return $rows;
    }

    private static function colIndex(string $col): int
    {
        $len = strlen($col);
        $index = 0;
        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($col[$i]) - ord('A') + 1);
        }
        return $index - 1;
    }

    /**
     * Membuat dan mendownload file Excel (.xlsx) murni
     */
    public static function download(string $filename, array $headers, array $data): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new ZipArchive();
        $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // xl/workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Sheet1" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // xl/styles.xml
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="2">
    <font><sz val="11"/><name val="Calibri"/></font>
    <font><b/><sz val="11"/><name val="Calibri"/></font>
  </fonts>
  <fills count="2">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
  </fills>
  <borders count="1">
    <border><left/><right/><top/><bottom/><diagonal/></border>
  </borders>
  <cellStyleXfs count="1">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
  </cellStyleXfs>
  <cellXfs count="2">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>
  </cellXfs>
</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // Bangun xl/worksheets/sheet1.xml
        $sheetData = '<sheetData>';
        $rowIdx = 1;

        // Header Row
        $sheetData .= '<row r="1">';
        foreach ($headers as $colIdx => $h) {
            $colLetter = self::colLetter($colIdx);
            $safeH = htmlspecialchars((string)$h, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $sheetData .= '<c r="' . $colLetter . '1" t="inlineStr" s="1"><is><t>' . $safeH . '</t></is></c>';
        }
        $sheetData .= '</row>';
        $rowIdx++;

        // Data Rows
        foreach ($data as $row) {
            $sheetData .= '<row r="' . $rowIdx . '">';
            $colIdx = 0;
            foreach ($row as $val) {
                $colLetter = self::colLetter($colIdx);
                if ($val === '' || $val === null) {
                    $sheetData .= '<c r="' . $colLetter . $rowIdx . '"/>';
                } elseif (is_numeric($val) && !preg_match('/^0[0-9]+/', (string)$val)) {
                    $sheetData .= '<c r="' . $colLetter . $rowIdx . '"><v>' . (float)$val . '</v></c>';
                } else {
                    $safeVal = htmlspecialchars((string)$val, ENT_QUOTES | ENT_XML1, 'UTF-8');
                    $sheetData .= '<c r="' . $colLetter . $rowIdx . '" t="inlineStr"><is><t>' . $safeVal . '</t></is></c>';
                }
                $colIdx++;
            }
            $sheetData .= '</row>';
            $rowIdx++;
        }
        $sheetData .= '</sheetData>';

        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  ' . $sheetData . '
</worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        // Output download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tempFile));
        header('Pragma: no-cache');
        header('Expires: 0');
        readfile($tempFile);
        @unlink($tempFile);
        exit;
    }

    /**
     * Membuat dan mendownload file Template Nilai STS yang rapi dengan:
     * - Identitas kop kelas & mapel di atas tabel
     * - Kolom bergaris tipis rapi (borders) & warna header soft blue
     * - Proteksi lembar (sheetProtection): NIS & Nama Siswa terkunci (read-only),
     *   sedangkan kolom nilai (Sumatif 1, 2, 3, Nilai STS) terbuka untuk diisi oleh guru.
     */
    public static function downloadTemplateNilai(
        string $filename,
        string $namaKelas,
        string $namaMapel,
        string $namaGuru,
        array $headers,
        array $data
    ): void {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_val_');
        $zip = new ZipArchive();
        $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // xl/workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Template Nilai" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // xl/styles.xml
        // 0: Normal
        // 1: Title (Calibri 13 Bold #1E3A8A)
        // 2: Meta Label (Calibri 11 Bold)
        // 3: Meta Value (Calibri 11)
        // 4: Table Header (Calibri 11 Bold, Fill soft blue #D9E1F2, Thin Border, Center)
        // 5: NIS (Thin Border, Center)
        // 6: Nama Siswa (Thin Border, Left)
        // 7: Grades (Thin Border, Center)
        // 8: Petunjuk Hint (Calibri 10 Italic #475569)
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="4">
    <font><sz val="11"/><name val="Calibri"/></font>
    <font><b/><sz val="11"/><name val="Calibri"/></font>
    <font><b/><sz val="13"/><name val="Calibri"/><color rgb="FF1E3A8A"/></font>
    <font><i/><sz val="10"/><name val="Calibri"/><color rgb="FF475569"/></font>
  </fonts>
  <fills count="3">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFD9E1F2"/></patternFill></fill>
  </fills>
  <borders count="2">
    <border><left/><right/><top/><bottom/><diagonal/></border>
    <border>
      <left style="thin"><color auto="1"/></left>
      <right style="thin"><color auto="1"/></right>
      <top style="thin"><color auto="1"/></top>
      <bottom style="thin"><color auto="1"/></bottom>
      <diagonal/>
    </border>
  </borders>
  <cellStyleXfs count="1">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
  </cellStyleXfs>
  <cellXfs count="9">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1"/>
    <xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center" wrapText="1"/>
    </xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1">
      <alignment horizontal="left" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="3" fillId="0" borderId="0" xfId="0" applyFont="1"/>
  </cellXfs>
  <cellStyles count="1">
    <cellStyle name="Normal" xfId="0" builtinId="0"/>
  </cellStyles>
</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // xl/worksheets/sheet1.xml
        $totalRows = count($data) + 6;
        $sheetData = '<sheetData>';

        // Row 1: Judul Dokumen
        $sheetData .= '<row r="1" ht="25"><c r="A1" s="1" t="inlineStr"><is><t>FORMAT IMPORT NILAI STS (ASESMEN TENGAH SEMESTER)</t></is></c></row>';

        // Row 2-4: Identitas Kelas & Mapel
        $safeMapel = htmlspecialchars((string)$namaMapel, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $safeKelas = htmlspecialchars((string)$namaKelas, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $safeGuru  = htmlspecialchars((string)$namaGuru, ENT_QUOTES | ENT_XML1, 'UTF-8');

        $sheetData .= '<row r="2"><c r="A2" s="2" t="inlineStr"><is><t>Mata Pelajaran</t></is></c><c r="B2" s="3" t="inlineStr"><is><t>: ' . $safeMapel . '</t></is></c></row>';
        $sheetData .= '<row r="3"><c r="A3" s="2" t="inlineStr"><is><t>Kelas</t></is></c><c r="B3" s="3" t="inlineStr"><is><t>: ' . $safeKelas . '</t></is></c></row>';
        $sheetData .= '<row r="4"><c r="A4" s="2" t="inlineStr"><is><t>Guru Pengampu</t></is></c><c r="B4" s="3" t="inlineStr"><is><t>: ' . $safeGuru . '</t></is></c></row>';

        // Row 5: Petunjuk Pengisian
        $sheetData .= '<row r="5"><c r="A5" s="8" t="inlineStr"><is><t>*Petunjuk: Isi nilai pada kolom sumatif_1, sumatif_2, sumatif_3, dan nilai_sts. Jangan mengubah kolom NIS dan nama siswa.</t></is></c></row>';

        // Row 6: Header Tabel (Border + Soft Blue + Center)
        $sheetData .= '<row r="6" ht="26">';
        foreach ($headers as $colIdx => $h) {
            $colLetter = self::colLetter($colIdx);
            $safeH = htmlspecialchars((string)$h, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $sheetData .= '<c r="' . $colLetter . '6" s="4" t="inlineStr"><is><t>' . $safeH . '</t></is></c>';
        }
        $sheetData .= '</row>';

        // Row 7+: Data Siswa
        $rowIdx = 7;
        foreach ($data as $row) {
            $sheetData .= '<row r="' . $rowIdx . '" ht="20">';
            $colIdx = 0;
            foreach ($row as $val) {
                $colLetter = self::colLetter($colIdx);

                if ($colIdx === 0) {
                    // Col A: NIS (Center, Border)
                    if (is_numeric($val) && !preg_match('/^0[0-9]+/', (string)$val)) {
                        $sheetData .= '<c r="' . $colLetter . $rowIdx . '" s="5"><v>' . (float)$val . '</v></c>';
                    } else {
                        $safeVal = htmlspecialchars((string)$val, ENT_QUOTES | ENT_XML1, 'UTF-8');
                        $sheetData .= '<c r="' . $colLetter . $rowIdx . '" s="5" t="inlineStr"><is><t>' . $safeVal . '</t></is></c>';
                    }
                } elseif ($colIdx === 1) {
                    // Col B: Nama Siswa (Left, Border)
                    $safeVal = htmlspecialchars((string)$val, ENT_QUOTES | ENT_XML1, 'UTF-8');
                    $sheetData .= '<c r="' . $colLetter . $rowIdx . '" s="6" t="inlineStr"><is><t>' . $safeVal . '</t></is></c>';
                } else {
                    // Col C, D, E, F: Nilai Sumatif 1, 2, 3, Nilai STS (Center, Border, Bebas diedit & paste)
                    if ($val === '' || $val === null) {
                        $sheetData .= '<c r="' . $colLetter . $rowIdx . '" s="7"/>';
                    } elseif (is_numeric($val)) {
                        $sheetData .= '<c r="' . $colLetter . $rowIdx . '" s="7"><v>' . (float)$val . '</v></c>';
                    } else {
                        $safeVal = htmlspecialchars((string)$val, ENT_QUOTES | ENT_XML1, 'UTF-8');
                        $sheetData .= '<c r="' . $colLetter . $rowIdx . '" s="7" t="inlineStr"><is><t>' . $safeVal . '</t></is></c>';
                    }
                }
                $colIdx++;
            }
            $sheetData .= '</row>';
            $rowIdx++;
        }
        $sheetData .= '</sheetData>';

        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <dimension ref="A1:F' . $totalRows . '"/>
  <sheetViews>
    <sheetView tabSelected="1" workbookViewId="0"/>
  </sheetViews>
  <sheetFormatPr defaultRowHeight="20"/>
  <cols>
    <col min="1" max="1" width="14" customWidth="1"/>
    <col min="2" max="2" width="38" customWidth="1"/>
    <col min="3" max="3" width="14" customWidth="1"/>
    <col min="4" max="4" width="14" customWidth="1"/>
    <col min="5" max="5" width="14" customWidth="1"/>
    <col min="6" max="6" width="14" customWidth="1"/>
  </cols>
  ' . $sheetData . '
</worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        // Output download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tempFile));
        header('Pragma: no-cache');
        header('Expires: 0');
        readfile($tempFile);
        @unlink($tempFile);
        exit;
    }

    /**
     * Membuat dan mendownload template Excel Ketidakhadiran Siswa (Presensi STS)
     * Format bersih, bergaris, rapi, dan mudah di-paste
     */
    public static function downloadTemplatePresensi(
        string $filename,
        string $namaKelas,
        string $namaWalikelas,
        array $headers,
        array $data
    ): void {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_att_');
        $zip = new ZipArchive();
        $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // xl/workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Presensi Siswa" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // xl/styles.xml
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="4">
    <font><sz val="11"/><name val="Calibri"/></font>
    <font><b/><sz val="11"/><name val="Calibri"/></font>
    <font><b/><sz val="13"/><name val="Calibri"/><color rgb="FF1E3A8A"/></font>
    <font><i/><sz val="10"/><name val="Calibri"/><color rgb="FF475569"/></font>
  </fonts>
  <fills count="3">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFD9E1F2"/></patternFill></fill>
  </fills>
  <borders count="2">
    <border><left/><right/><top/><bottom/><diagonal/></border>
    <border>
      <left style="thin"><color auto="1"/></left>
      <right style="thin"><color auto="1"/></right>
      <top style="thin"><color auto="1"/></top>
      <bottom style="thin"><color auto="1"/></bottom>
      <diagonal/>
    </border>
  </borders>
  <cellStyleXfs count="1">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
  </cellStyleXfs>
  <cellXfs count="9">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1"/>
    <xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center" wrapText="1"/>
    </xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1">
      <alignment horizontal="left" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="3" fillId="0" borderId="0" xfId="0" applyFont="1"/>
  </cellXfs>
  <cellStyles count="1">
    <cellStyle name="Normal" xfId="0" builtinId="0"/>
  </cellStyles>
</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // xl/worksheets/sheet1.xml
        $totalRows = count($data) + 6;
        $sheetData = '<sheetData>';

        // Row 1: Judul
        $sheetData .= '<row r="1" ht="25"><c r="A1" s="1" t="inlineStr"><is><t>FORMAT IMPORT KETIDAKHADIRAN SISWA (PRESENSI STS)</t></is></c></row>';

        // Row 2-3: Kop Kelas
        $safeKelas = htmlspecialchars((string)$namaKelas, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $safeWali  = htmlspecialchars((string)$namaWalikelas, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $sheetData .= '<row r="2"><c r="A2" s="2" t="inlineStr"><is><t>Kelas</t></is></c><c r="B2" s="3" t="inlineStr"><is><t>: ' . $safeKelas . '</t></is></c></row>';
        $sheetData .= '<row r="3"><c r="A3" s="2" t="inlineStr"><is><t>Wali Kelas</t></is></c><c r="B3" s="3" t="inlineStr"><is><t>: ' . $safeWali . '</t></is></c></row>';
        $sheetData .= '<row r="4"/>';

        // Row 5: Petunjuk
        $sheetData .= '<row r="5"><c r="A5" s="8" t="inlineStr"><is><t>*Petunjuk: Isi angka hari pada kolom sakit, izin, dan alpa (0 jika tidak ada). Jangan mengubah kolom NIS dan nama siswa.</t></is></c></row>';

        // Row 6: Header Tabel
        $sheetData .= '<row r="6" ht="26">';
        foreach ($headers as $colIdx => $h) {
            $colLetter = self::colLetter($colIdx);
            $safeH = htmlspecialchars((string)$h, ENT_QUOTES | ENT_XML1, 'UTF-8');
            $sheetData .= '<c r="' . $colLetter . '6" s="4" t="inlineStr"><is><t>' . $safeH . '</t></is></c>';
        }
        $sheetData .= '</row>';

        // Row 7+: Data Siswa
        $rowIdx = 7;
        foreach ($data as $row) {
            $sheetData .= '<row r="' . $rowIdx . '" ht="20">';
            $colIdx = 0;
            foreach ($row as $val) {
                $colLetter = self::colLetter($colIdx);

                if ($colIdx === 0) {
                    if (is_numeric($val) && !preg_match('/^0[0-9]+/', (string)$val)) {
                        $sheetData .= '<c r="' . $colLetter . $rowIdx . '" s="5"><v>' . (float)$val . '</v></c>';
                    } else {
                        $safeVal = htmlspecialchars((string)$val, ENT_QUOTES | ENT_XML1, 'UTF-8');
                        $sheetData .= '<c r="' . $colLetter . $rowIdx . '" s="5" t="inlineStr"><is><t>' . $safeVal . '</t></is></c>';
                    }
                } elseif ($colIdx === 1) {
                    $safeVal = htmlspecialchars((string)$val, ENT_QUOTES | ENT_XML1, 'UTF-8');
                    $sheetData .= '<c r="' . $colLetter . $rowIdx . '" s="6" t="inlineStr"><is><t>' . $safeVal . '</t></is></c>';
                } else {
                    $valNum = (int)$val;
                    $sheetData .= '<c r="' . $colLetter . $rowIdx . '" s="7"><v>' . $valNum . '</v></c>';
                }
                $colIdx++;
            }
            $sheetData .= '</row>';
            $rowIdx++;
        }
        $sheetData .= '</sheetData>';

        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <dimension ref="A1:E' . $totalRows . '"/>
  <sheetViews>
    <sheetView tabSelected="1" workbookViewId="0"/>
  </sheetViews>
  <sheetFormatPr defaultRowHeight="20"/>
  <cols>
    <col min="1" max="1" width="14" customWidth="1"/>
    <col min="2" max="2" width="38" customWidth="1"/>
    <col min="3" max="3" width="14" customWidth="1"/>
    <col min="4" max="4" width="14" customWidth="1"/>
    <col min="5" max="5" width="14" customWidth="1"/>
  </cols>
  ' . $sheetData . '
</worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        // Output download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tempFile));
        header('Pragma: no-cache');
        header('Expires: 0');
        readfile($tempFile);
        @unlink($tempFile);
        exit;
    }

    private static function colLetter(int $col): string
    {
        $letter = '';
        while ($col >= 0) {
            $letter = chr($col % 26 + ord('A')) . $letter;
            $col = intdiv($col, 26) - 1;
        }
        return $letter;
    }
}
