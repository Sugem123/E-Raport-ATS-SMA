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
        $sheetData .= '<row r="5"><c r="A5" s="8" t="inlineStr"><is><t>*Petunjuk: Isi angka nilai pada kolom sumatif_1, sumatif_2, sumatif_3, sumatif_4, dan nilai_ats. Jangan mengubah kolom NIS dan nama siswa.</t></is></c></row>';

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
                    // Col C, D, E, F, G: Sumatif 1, 2, 3, 4, Nilai ATS (Center, Border, Bebas diedit & paste)
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
  <dimension ref="A1:G' . $totalRows . '"/>
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
    <col min="7" max="7" width="14" customWidth="1"/>
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

    /**
     * Download Excel Ledger Nilai STS - Opsi A (Ringkas: Nilai ATS saja per mapel)
     */
    public static function downloadLedgerRingkas(
        string $filename,
        array $info,
        array $mapelList,
        array $siswaList,
        array $grades,
        array $stats
    ): void {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_led_r_');
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
    <sheet name="Ledger Ringkas" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // xl/styles.xml
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="4">
    <font><sz val="10"/><name val="Calibri"/></font>
    <font><b/><sz val="10"/><name val="Calibri"/></font>
    <font><b/><sz val="13"/><name val="Calibri"/><color rgb="FF1E3A8A"/></font>
    <font><i/><sz val="9"/><name val="Calibri"/><color rgb="FF64748B"/></font>
  </fonts>
  <fills count="6">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFD9E1F2"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFE2EFDA"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFFFF2CC"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFF2F2F2"/></patternFill></fill>
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
  <cellXfs count="11">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1"/>
    <xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>
    <xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center" wrapText="1"/>
    </xf>
    <xf numFmtId="0" fontId="1" fillId="3" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center" wrapText="1"/>
    </xf>
    <xf numFmtId="0" fontId="1" fillId="4" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center" wrapText="1"/>
    </xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1">
      <alignment horizontal="left" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="1" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="1" fillId="5" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="1" fillId="5" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="left" vertical="center"/>
    </xf>
  </cellXfs>
  <cellStyles count="1">
    <cellStyle name="Normal" xfId="0" builtinId="0"/>
  </cellStyles>
</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // xl/worksheets/sheet1.xml
        $sheetData = '<sheetData>';

        // Row 1: Judul
        $sheetData .= '<row r="1" ht="25"><c r="A1" s="1" t="inlineStr"><is><t>LEGER NILAI ASESMEN TENGAH SEMESTER (RINGKAS)</t></is></c></row>';

        // Row 2-4: Kop
        $safeSekolah = htmlspecialchars((string)($info['nama_sekolah'] ?? 'SMA NEGERI 1 PRAMBON NGANJUK'), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $safeKelas   = htmlspecialchars((string)($info['nama_kelas'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $safeWali    = htmlspecialchars((string)($info['nama_walikelas'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $safeSem     = htmlspecialchars((string)($info['semester'] ?? '1'), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $safeTa      = htmlspecialchars((string)($info['tahun_ajaran'] ?? '2026/2027'), ENT_QUOTES | ENT_XML1, 'UTF-8');

        $sheetData .= '<row r="2"><c r="A2" s="2" t="inlineStr"><is><t>Sekolah</t></is></c><c r="B2" t="inlineStr"><is><t>: ' . $safeSekolah . '</t></is></c></row>';
        $sheetData .= '<row r="3"><c r="A3" s="2" t="inlineStr"><is><t>Kelas / Fase</t></is></c><c r="B3" t="inlineStr"><is><t>: Kelas ' . $safeKelas . ' (Tingkat ' . ($info['tingkat'] ?? '-') . ')</t></is></c><c r="E3" s="2" t="inlineStr"><is><t>Semester / TA</t></is></c><c r="F3" t="inlineStr"><is><t>: Semester ' . $safeSem . ' / ' . $safeTa . '</t></is></c></row>';
        $sheetData .= '<row r="4"><c r="A4" s="2" t="inlineStr"><is><t>Wali Kelas</t></is></c><c r="B4" t="inlineStr"><is><t>: ' . $safeWali . '</t></is></c></row>';
        $sheetData .= '<row r="5"/>';

        // Row 6: Header Tabel (No, NIS, NISN, Nama, Mapel..., Total, Rata, Sakit, Izin, Alpa)
        $sheetData .= '<row r="6" ht="28">';
        $cIdx = 0;
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="3" t="inlineStr"><is><t>No</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="3" t="inlineStr"><is><t>NIS</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="3" t="inlineStr"><is><t>NISN</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="3" t="inlineStr"><is><t>Nama Siswa</t></is></c>';

        foreach ($mapelList as $m) {
            $isUmum = (strtolower(trim((string)$m['kategori'])) === 'umum');
            $styleHeader = $isUmum ? '3' : '4'; // 3=blue, 4=green
            $safeMName = htmlspecialchars((string)$m['nama_mapel'], ENT_QUOTES | ENT_XML1, 'UTF-8');
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="' . $styleHeader . '" t="inlineStr"><is><t>' . $safeMName . '</t></is></c>';
        }

        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="5" t="inlineStr"><is><t>Total Nilai</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="5" t="inlineStr"><is><t>Rata-rata</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="3" t="inlineStr"><is><t>S</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="3" t="inlineStr"><is><t>I</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="3" t="inlineStr"><is><t>A</t></is></c>';
        $sheetData .= '</row>';

        // Rows 7+: Data Siswa
        $rowIdx = 7;
        $studentStats = $stats['student_stats'] ?? [];
        foreach ($siswaList as $idx => $s) {
            $nis = $s['nis'];
            $st = $studentStats[$nis] ?? ['total_ats' => 0, 'avg_ats' => 0];

            $sheetData .= '<row r="' . $rowIdx . '" ht="20">';
            $cIdx = 0;
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"><v>' . ($idx + 1) . '</v></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6" t="inlineStr"><is><t>' . htmlspecialchars((string)$s['nis'], ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6" t="inlineStr"><is><t>' . htmlspecialchars((string)($s['nisn'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="7" t="inlineStr"><is><t>' . htmlspecialchars((string)$s['nama'], ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';

            foreach ($mapelList as $m) {
                $idP = (int)$m['id_pengampu'];
                $val = $grades[$nis][$idP]['ats'] ?? null;
                if ($val !== null && $val !== '') {
                    $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"><v>' . (float)$val . '</v></c>';
                } else {
                    $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"/>';
                }
            }

            // Total ATS & Rata-rata
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="8"><v>' . (float)$st['total_ats'] . '</v></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="8"><v>' . (float)$st['avg_ats'] . '</v></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"><v>' . (int)$s['sakit'] . '</v></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"><v>' . (int)$s['izin'] . '</v></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"><v>' . (int)$s['alpa'] . '</v></c>';

            $sheetData .= '</row>';
            $rowIdx++;
        }

        // Summary Row: Rata-rata Kelas
        $subjectSummary = $stats['subject_summary'] ?? [];
        $rowRataRingkas = $rowIdx;
        $sheetData .= '<row r="' . $rowIdx . '" ht="21">';
        $sheetData .= '<c r="A' . $rowIdx . '" s="10" t="inlineStr"><is><t>Rata-rata Kelas</t></is></c>';
        $sheetData .= '<c r="B' . $rowIdx . '" s="10"/>';
        $sheetData .= '<c r="C' . $rowIdx . '" s="10"/>';
        $sheetData .= '<c r="D' . $rowIdx . '" s="10"/>';
        $cIdx = 4;
        foreach ($mapelList as $m) {
            $idP = (int)$m['id_pengampu'];
            $avgM = $subjectSummary[$idP]['avg_ats'] ?? '-';
            if (is_numeric($avgM)) {
                $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"><v>' . (float)$avgM . '</v></c>';
            } else {
                $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
            }
        }
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
        $sheetData .= '</row>';
        $rowIdx += 2;

        // Signatures
        $rowIdx++;
        $cWaliCol = self::colLetter(max(1, count($mapelList)));
        $sheetData .= '<row r="' . $rowIdx . '">';
        $sheetData .= '<c r="B' . $rowIdx . '" t="inlineStr"><is><t>Mengetahui,</t></is></c>';
        $sheetData .= '<c r="' . $cWaliCol . $rowIdx . '" t="inlineStr"><is><t>' . htmlspecialchars((string)($info['tempat_rapor'] ?? 'Nganjuk'), ENT_QUOTES | ENT_XML1, 'UTF-8') . ', ' . htmlspecialchars((string)($info['tanggal_rapor'] ?? date('d F Y')), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
        $sheetData .= '</row>';
        $rowIdx++;
        $sheetData .= '<row r="' . $rowIdx . '">';
        $sheetData .= '<c r="B' . $rowIdx . '" t="inlineStr"><is><t>Kepala Sekolah</t></is></c>';
        $sheetData .= '<c r="' . $cWaliCol . $rowIdx . '" t="inlineStr"><is><t>Wali Kelas</t></is></c>';
        $sheetData .= '</row>';
        $rowIdx += 4;
        $sheetData .= '<row r="' . $rowIdx . '">';
        $sheetData .= '<c r="B' . $rowIdx . '" s="2" t="inlineStr"><is><t>' . htmlspecialchars((string)($info['nama_kepala_sekolah'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
        $sheetData .= '<c r="' . $cWaliCol . $rowIdx . '" s="2" t="inlineStr"><is><t>' . $safeWali . '</t></is></c>';
        $sheetData .= '</row>';
        $rowIdx++;
        $sheetData .= '<row r="' . $rowIdx . '">';
        $sheetData .= '<c r="B' . $rowIdx . '" t="inlineStr"><is><t>NIP. ' . htmlspecialchars((string)($info['nip_kepala_sekolah'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
        $sheetData .= '<c r="' . $cWaliCol . $rowIdx . '" t="inlineStr"><is><t>NIP. ' . htmlspecialchars((string)($info['id_guru_walikelas'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
        $sheetData .= '</row>';

        $sheetData .= '</sheetData>';

        $totalCols = 4 + count($mapelList) + 5;
        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <dimension ref="A1:' . self::colLetter($totalCols - 1) . $rowIdx . '"/>
  <sheetViews>
    <sheetView tabSelected="1" workbookViewId="0"/>
  </sheetViews>
  <sheetFormatPr defaultRowHeight="20"/>
  <cols>
    <col min="1" max="1" width="6" customWidth="1"/>
    <col min="2" max="2" width="13" customWidth="1"/>
    <col min="3" max="3" width="15" customWidth="1"/>
    <col min="4" max="4" width="34" customWidth="1"/>';
        for ($i = 5; $i <= $totalCols; $i++) {
            $sheet .= '<col min="' . $i . '" max="' . $i . '" width="12" customWidth="1"/>';
        }
        $sheet .= '</cols>
  ' . $sheetData . '
  <mergeCells count="1">
    <mergeCell ref="A' . $rowRataRingkas . ':D' . $rowRataRingkas . '"/>
  </mergeCells>
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
     * Download Excel Ledger Nilai STS - Opsi B (Lengkap: Sumatif 1-4 & Nilai ATS per mapel)
     */
    public static function downloadLedgerLengkap(
        string $filename,
        array $info,
        array $mapelList,
        array $siswaList,
        array $grades,
        array $stats
    ): void {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_led_l_');
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
    <sheet name="Ledger Lengkap" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // xl/styles.xml
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="4">
    <font><sz val="10"/><name val="Calibri"/></font>
    <font><b/><sz val="10"/><name val="Calibri"/></font>
    <font><b/><sz val="13"/><name val="Calibri"/><color rgb="FF1E3A8A"/></font>
    <font><i/><sz val="9"/><name val="Calibri"/><color rgb="FF64748B"/></font>
  </fonts>
  <fills count="6">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFD9E1F2"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFE2EFDA"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFFFF2CC"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFF2F2F2"/></patternFill></fill>
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
  <cellXfs count="11">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1"/>
    <xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>
    <xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center" wrapText="1"/>
    </xf>
    <xf numFmtId="0" fontId="1" fillId="3" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center" wrapText="1"/>
    </xf>
    <xf numFmtId="0" fontId="1" fillId="4" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center" wrapText="1"/>
    </xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1">
      <alignment horizontal="left" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="1" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="1" fillId="5" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="1" fillId="5" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="left" vertical="center"/>
    </xf>
  </cellXfs>
  <cellStyles count="1">
    <cellStyle name="Normal" xfId="0" builtinId="0"/>
  </cellStyles>
</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // xl/worksheets/sheet1.xml
        $sheetData = '<sheetData>';

        // Row 1: Judul
        $sheetData .= '<row r="1" ht="25"><c r="A1" s="1" t="inlineStr"><is><t>LEGER NILAI ASESMEN TENGAH SEMESTER (RINCIAN LENGKAP)</t></is></c></row>';

        // Row 2-4: Kop
        $safeSekolah = htmlspecialchars((string)($info['nama_sekolah'] ?? 'SMA NEGERI 1 PRAMBON NGANJUK'), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $safeKelas   = htmlspecialchars((string)($info['nama_kelas'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $safeWali    = htmlspecialchars((string)($info['nama_walikelas'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $safeSem     = htmlspecialchars((string)($info['semester'] ?? '1'), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $safeTa      = htmlspecialchars((string)($info['tahun_ajaran'] ?? '2026/2027'), ENT_QUOTES | ENT_XML1, 'UTF-8');

        $sheetData .= '<row r="2"><c r="A2" s="2" t="inlineStr"><is><t>Sekolah</t></is></c><c r="B2" t="inlineStr"><is><t>: ' . $safeSekolah . '</t></is></c></row>';
        $sheetData .= '<row r="3"><c r="A3" s="2" t="inlineStr"><is><t>Kelas / Fase</t></is></c><c r="B3" t="inlineStr"><is><t>: Kelas ' . $safeKelas . ' (Tingkat ' . ($info['tingkat'] ?? '-') . ')</t></is></c><c r="E3" s="2" t="inlineStr"><is><t>Semester / TA</t></is></c><c r="F3" t="inlineStr"><is><t>: Semester ' . $safeSem . ' / ' . $safeTa . '</t></is></c></row>';
        $sheetData .= '<row r="4"><c r="A4" s="2" t="inlineStr"><is><t>Wali Kelas</t></is></c><c r="B4" t="inlineStr"><is><t>: ' . $safeWali . '</t></is></c></row>';
        $sheetData .= '<row r="5"/>';

        // Row 6: Header Row 1 (Mapel names spanning 5 cols)
        $sheetData .= '<row r="6" ht="26">';
        $cIdx = 0;
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="3" t="inlineStr"><is><t>No</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="3" t="inlineStr"><is><t>NIS</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="3" t="inlineStr"><is><t>NISN</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="3" t="inlineStr"><is><t>Nama Siswa</t></is></c>';

        $mergeList = [
            'A6:A7',
            'B6:B7',
            'C6:C7',
            'D6:D7'
        ];

        foreach ($mapelList as $m) {
            $isUmum = (strtolower(trim((string)$m['kategori'])) === 'umum');
            $styleHeader = $isUmum ? '3' : '4';
            $safeMName = htmlspecialchars((string)$m['nama_mapel'], ENT_QUOTES | ENT_XML1, 'UTF-8');
            $startCol = $cIdx;
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="' . $styleHeader . '" t="inlineStr"><is><t>' . $safeMName . '</t></is></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="' . $styleHeader . '"/>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="' . $styleHeader . '"/>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="' . $styleHeader . '"/>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="' . $styleHeader . '"/>';
            $endCol = $cIdx - 1;
            $mergeList[] = self::colLetter($startCol) . '6:' . self::colLetter($endCol) . '6';
        }

        $startKet = $cIdx;
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="5" t="inlineStr"><is><t>Ketidakhadiran</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="5"/>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '6" s="5"/>';
        $endKet = $cIdx - 1;
        $mergeList[] = self::colLetter($startKet) . '6:' . self::colLetter($endKet) . '6';

        $colTot = self::colLetter($cIdx++);
        $sheetData .= '<c r="' . $colTot . '6" s="5" t="inlineStr"><is><t>Total ATS</t></is></c>';
        $mergeList[] = $colTot . '6:' . $colTot . '7';

        $colRata = self::colLetter($cIdx++);
        $sheetData .= '<c r="' . $colRata . '6" s="5" t="inlineStr"><is><t>Rata ATS</t></is></c>';
        $mergeList[] = $colRata . '6:' . $colRata . '7';
        $sheetData .= '</row>';

        // Row 7: Sub-headers (01, 02, 03, 04, ATS for each mapel)
        $sheetData .= '<row r="7" ht="22">';
        $sheetData .= '<c r="A7" s="3"/>';
        $sheetData .= '<c r="B7" s="3"/>';
        $sheetData .= '<c r="C7" s="3"/>';
        $sheetData .= '<c r="D7" s="3"/>';
        $cIdx = 4;

        foreach ($mapelList as $m) {
            $isUmum = (strtolower(trim((string)$m['kategori'])) === 'umum');
            $styleHeader = $isUmum ? '3' : '4';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . '7" s="' . $styleHeader . '" t="inlineStr"><is><t>01</t></is></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . '7" s="' . $styleHeader . '" t="inlineStr"><is><t>02</t></is></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . '7" s="' . $styleHeader . '" t="inlineStr"><is><t>03</t></is></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . '7" s="' . $styleHeader . '" t="inlineStr"><is><t>04</t></is></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . '7" s="' . $styleHeader . '" t="inlineStr"><is><t>ATS</t></is></c>';
        }

        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '7" s="5" t="inlineStr"><is><t>S</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '7" s="5" t="inlineStr"><is><t>I</t></is></c>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . '7" s="5" t="inlineStr"><is><t>A</t></is></c>';
        $sheetData .= '<c r="' . $colTot . '7" s="5"/>';
        $sheetData .= '<c r="' . $colRata . '7" s="5"/>';
        $sheetData .= '</row>';

        // Rows 8+: Data Siswa
        $rowIdx = 8;
        $studentStats = $stats['student_stats'] ?? [];
        foreach ($siswaList as $idx => $s) {
            $nis = $s['nis'];
            $st = $studentStats[$nis] ?? ['total_ats' => 0, 'avg_ats' => 0];

            $sheetData .= '<row r="' . $rowIdx . '" ht="20">';
            $cIdx = 0;
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"><v>' . ($idx + 1) . '</v></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6" t="inlineStr"><is><t>' . htmlspecialchars((string)$s['nis'], ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6" t="inlineStr"><is><t>' . htmlspecialchars((string)($s['nisn'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="7" t="inlineStr"><is><t>' . htmlspecialchars((string)$s['nama'], ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';

            foreach ($mapelList as $m) {
                $idP = (int)$m['id_pengampu'];
                $g = $grades[$nis][$idP] ?? null;

                foreach (['s1', 's2', 's3', 's4', 'ats'] as $key) {
                    $val = $g[$key] ?? null;
                    if ($val !== null && $val !== '') {
                        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"><v>' . (float)$val . '</v></c>';
                    } else {
                        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"/>';
                    }
                }
            }

            // Ketidakhadiran & Total ATS
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"><v>' . (int)$s['sakit'] . '</v></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"><v>' . (int)$s['izin'] . '</v></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="6"><v>' . (int)$s['alpa'] . '</v></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="8"><v>' . (float)$st['total_ats'] . '</v></c>';
            $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="8"><v>' . (float)$st['avg_ats'] . '</v></c>';

            $sheetData .= '</row>';
            $rowIdx++;
        }

        // Summary Row: Rata-rata Kelas
        $subjectSummary = $stats['subject_summary'] ?? [];
        $rowRataLengkap = $rowIdx;
        $sheetData .= '<row r="' . $rowIdx . '" ht="21">';
        $sheetData .= '<c r="A' . $rowIdx . '" s="10" t="inlineStr"><is><t>Rata-rata Kelas</t></is></c>';
        $sheetData .= '<c r="B' . $rowIdx . '" s="10"/>';
        $sheetData .= '<c r="C' . $rowIdx . '" s="10"/>';
        $sheetData .= '<c r="D' . $rowIdx . '" s="10"/>';
        $mergeList[] = 'A' . $rowRataLengkap . ':D' . $rowRataLengkap;
        $cIdx = 4;
        foreach ($mapelList as $m) {
            $idP = (int)$m['id_pengampu'];
            $sm = $subjectSummary[$idP] ?? [];

            foreach (['avg_s1', 'avg_s2', 'avg_s3', 'avg_s4', 'avg_ats'] as $k) {
                $val = $sm[$k] ?? '-';
                if (is_numeric($val)) {
                    $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"><v>' . (float)$val . '</v></c>';
                } else {
                    $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
                }
            }
        }
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
        $sheetData .= '<c r="' . self::colLetter($cIdx++) . $rowIdx . '" s="9"/>';
        $sheetData .= '</row>';
        $rowIdx += 2;

        // Signatures
        $rowIdx++;
        $cWaliCol = self::colLetter(max(1, count($mapelList) * 5));
        $sheetData .= '<row r="' . $rowIdx . '">';
        $sheetData .= '<c r="B' . $rowIdx . '" t="inlineStr"><is><t>Mengetahui,</t></is></c>';
        $sheetData .= '<c r="' . $cWaliCol . $rowIdx . '" t="inlineStr"><is><t>' . htmlspecialchars((string)($info['tempat_rapor'] ?? 'Nganjuk'), ENT_QUOTES | ENT_XML1, 'UTF-8') . ', ' . htmlspecialchars((string)($info['tanggal_rapor'] ?? date('d F Y')), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
        $sheetData .= '</row>';
        $rowIdx++;
        $sheetData .= '<row r="' . $rowIdx . '">';
        $sheetData .= '<c r="B' . $rowIdx . '" t="inlineStr"><is><t>Kepala Sekolah</t></is></c>';
        $sheetData .= '<c r="' . $cWaliCol . $rowIdx . '" t="inlineStr"><is><t>Wali Kelas</t></is></c>';
        $sheetData .= '</row>';
        $rowIdx += 4;
        $sheetData .= '<row r="' . $rowIdx . '">';
        $sheetData .= '<c r="B' . $rowIdx . '" s="2" t="inlineStr"><is><t>' . htmlspecialchars((string)($info['nama_kepala_sekolah'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
        $sheetData .= '<c r="' . $cWaliCol . $rowIdx . '" s="2" t="inlineStr"><is><t>' . $safeWali . '</t></is></c>';
        $sheetData .= '</row>';
        $rowIdx++;
        $sheetData .= '<row r="' . $rowIdx . '">';
        $sheetData .= '<c r="B' . $rowIdx . '" t="inlineStr"><is><t>NIP. ' . htmlspecialchars((string)($info['nip_kepala_sekolah'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
        $sheetData .= '<c r="' . $cWaliCol . $rowIdx . '" t="inlineStr"><is><t>NIP. ' . htmlspecialchars((string)($info['id_guru_walikelas'] ?? '-'), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</t></is></c>';
        $sheetData .= '</row>';

        $sheetData .= '</sheetData>';

        $mergeXml = '';
        if (!empty($mergeList)) {
            $mergeXml = '<mergeCells count="' . count($mergeList) . '">';
            foreach ($mergeList as $mRef) {
                $mergeXml .= '<mergeCell ref="' . $mRef . '"/>';
            }
            $mergeXml .= '</mergeCells>';
        }

        $totalCols = 4 + (count($mapelList) * 5) + 5;
        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <dimension ref="A1:' . self::colLetter($totalCols - 1) . $rowIdx . '"/>
  <sheetViews>
    <sheetView tabSelected="1" workbookViewId="0"/>
  </sheetViews>
  <sheetFormatPr defaultRowHeight="20"/>
  <cols>
    <col min="1" max="1" width="6" customWidth="1"/>
    <col min="2" max="2" width="13" customWidth="1"/>
    <col min="3" max="3" width="15" customWidth="1"/>
    <col min="4" max="4" width="34" customWidth="1"/>';
        for ($i = 5; $i <= $totalCols; $i++) {
            $sheet .= '<col min="' . $i . '" max="' . $i . '" width="8" customWidth="1"/>';
        }
        $sheet .= '</cols>
  ' . $sheetData . '
  ' . $mergeXml . '
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
