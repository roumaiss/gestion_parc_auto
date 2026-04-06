<?php
/**
 * Excel XML (SpreadsheetML) export — no library needed
 * Opens natively in Microsoft Excel with full styling
 */
function exportExcel(string $title, array $headers, array $rows, string $filename): void
{
    $exportDate = date('d/m/Y H:i');
    $colCount   = count($headers);

    // ── helpers ──────────────────────────────────────────────────────
    $esc = fn($v) => htmlspecialchars((string)$v, ENT_XML1, 'UTF-8');

    // Column letters for auto-width reference
    $colLetter = fn(int $i) => chr(65 + $i); // A, B, C ...

    // ── build cells ──────────────────────────────────────────────────
    // Row 1: big title (merged look via first cell, rest empty)
    $titleRow  = '<Row ss:Height="30">';
    $titleRow .= '<Cell ss:StyleID="sTitle"><Data ss:Type="String">' . $esc($title) . '</Data></Cell>';
    for ($i = 1; $i < $colCount; $i++) $titleRow .= '<Cell ss:StyleID="sTitleEmpty"><Data ss:Type="String"></Data></Cell>';
    $titleRow .= '</Row>';

    // Row 2: export date
    $metaRow  = '<Row ss:Height="18">';
    $metaRow .= '<Cell ss:StyleID="sMeta"><Data ss:Type="String">Exporté le ' . $esc($exportDate) . ' — Mobilis Fleet</Data></Cell>';
    for ($i = 1; $i < $colCount; $i++) $metaRow .= '<Cell ss:StyleID="sMetaEmpty"><Data ss:Type="String"></Data></Cell>';
    $metaRow .= '</Row>';

    // Row 3: blank spacer
    $spacer = '<Row ss:Height="8"></Row>';

    // Row 4: headers
    $headerRow = '<Row ss:Height="22">';
    foreach ($headers as $h) {
        $headerRow .= '<Cell ss:StyleID="sHeader"><Data ss:Type="String">' . $esc($h) . '</Data></Cell>';
    }
    $headerRow .= '</Row>';

    // Data rows
    $dataRows = '';
    foreach ($rows as $i => $row) {
        $style = ($i % 2 === 0) ? 'sDataEven' : 'sDataOdd';
        $dataRows .= '<Row ss:Height="18">';
        foreach (array_values($row) as $cell) {
            $type = is_numeric($cell) && !preg_match('/^0\d/', (string)$cell) ? 'Number' : 'String';
            $dataRows .= '<Cell ss:StyleID="' . $style . '"><Data ss:Type="' . $type . '">' . $esc($cell) . '</Data></Cell>';
        }
        $dataRows .= '</Row>';
    }

    // ── column widths (auto estimate) ────────────────────────────────
    $colDefs = '';
    foreach ($headers as $h) {
        $w = max(80, min(200, strlen($h) * 12 + 20));
        $colDefs .= '<Column ss:Width="' . $w . '"/>';
    }

    // ── XML ──────────────────────────────────────────────────────────
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
          xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
          xmlns:x="urn:schemas-microsoft-com:office:excel">

  <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
    <Title>' . $esc($title) . '</Title>
    <Author>Mobilis Fleet</Author>
    <Created>' . date('c') . '</Created>
  </DocumentProperties>

  <Styles>

    <!-- Title -->
    <Style ss:ID="sTitle">
      <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
      <Font ss:Bold="1" ss:Size="16" ss:Color="#1b5e42" ss:FontName="Segoe UI"/>
      <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
      <Borders/>
    </Style>
    <Style ss:ID="sTitleEmpty">
      <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
    </Style>

    <!-- Meta -->
    <Style ss:ID="sMeta">
      <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
      <Font ss:Italic="1" ss:Size="10" ss:Color="#888888" ss:FontName="Segoe UI"/>
      <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
    </Style>
    <Style ss:ID="sMetaEmpty">
      <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
    </Style>

    <!-- Header row -->
    <Style ss:ID="sHeader">
      <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="0"/>
      <Font ss:Bold="1" ss:Size="11" ss:Color="#FFFFFF" ss:FontName="Segoe UI"/>
      <Interior ss:Color="#1b5e42" ss:Pattern="Solid"/>
      <Borders>
        <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#FFFFFF"/>
        <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#2e8b57"/>
      </Borders>
    </Style>

    <!-- Even data row -->
    <Style ss:ID="sDataEven">
      <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
      <Font ss:Size="10" ss:FontName="Segoe UI" ss:Color="#222222"/>
      <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
      <Borders>
        <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e5e7eb"/>
        <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e5e7eb"/>
      </Borders>
    </Style>

    <!-- Odd data row -->
    <Style ss:ID="sDataOdd">
      <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
      <Font ss:Size="10" ss:FontName="Segoe UI" ss:Color="#222222"/>
      <Interior ss:Color="#f0faf5" ss:Pattern="Solid"/>
      <Borders>
        <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e5e7eb"/>
        <Border ss:Position="Right"  ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e5e7eb"/>
      </Borders>
    </Style>

  </Styles>

  <Worksheet ss:Name="' . $esc($title) . '">
    <Table>
      ' . $colDefs . '
      ' . $titleRow . '
      ' . $metaRow . '
      ' . $spacer . '
      ' . $headerRow . '
      ' . $dataRows . '
    </Table>
    <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
      <FreezePanes/>
      <FrozenNoSplit/>
      <SplitHorizontal>4</SplitHorizontal>
      <TopRowBottomPane>4</TopRowBottomPane>
      <ActivePane>2</ActivePane>
      <Print>
        <ValidPrinterInfo/>
        <PaperSizeIndex>9</PaperSizeIndex>
        <HorizontalResolution>600</HorizontalResolution>
        <VerticalResolution>600</VerticalResolution>
      </Print>
    </WorksheetOptions>
  </Worksheet>

</Workbook>';

    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
    header('Cache-Control: max-age=0');
    echo $xml;
    exit;
}
