<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Faktur Penjualan Vendor - Minimarket AlfaSunday</title>
  <style>
    body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background: #f7f8fa;}
    .invoice {
      width: 210mm; min-height: 297mm; margin: 0 auto; background: #fff;
      box-shadow: 0 3px 15px #ddd; padding: 28px 36px 36px 36px;
      box-sizing: border-box;
      position: relative;
    }
    .header-flex {
      display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 18px;
    }
    .vendor-title { font-size: 19px; font-weight: 600; color: #255686; letter-spacing: 1px;}
    .vendor-info-box, .to-info-box {
      border-radius: 8px; padding: 12px 18px; margin-bottom: 10px; background: #f5faff;
      border: 1.5px solid #d2e3f3; width: 47%; font-size: 14px;
    }
    .flexbox-2 { display: flex; justify-content: space-between; gap: 5%; margin-bottom: 18px; }
    .invoice-meta {
      margin-bottom: 18px; margin-top: -6px; font-size: 14px; display: flex; gap: 28px;
    }
    .invoice-meta div { margin-bottom: 3px; }
    h1 { font-size: 24px; letter-spacing: 1px; color: #2a4e75; margin: 0 0 4px 0;}
    .no-faktur { font-weight: bold; color: #305580; letter-spacing: 0.5px; }
    table {
      width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 14px;
      box-shadow: 0 1px 5px #f0f3fa;
    }
    th, td {
      border: 1px solid #b7d2ef; padding: 8px 6px; text-align: left;
    }
    th { background: #eaf4fd; font-size: 15px; }
    tr.total-row td { font-weight: bold; background: #f2f8fd; font-size: 15px;}
    tr.subtotal-row td { background: #f8fbfe; }
    .note-box {
      font-size: 13px; background: #fefae6; color: #745d1c;
      border-left: 4px solid #ffe08c; padding: 8px 16px; margin-bottom: 10px; border-radius: 6px;
    }
    .footer { font-size: 12.8px; color: #3c3c3c; margin-top: 18px; text-align: center; }
    .signature-box {
      margin-top: 28px; display: flex; justify-content: flex-end; align-items: flex-end; gap: 54px;
      font-size: 14px;
      height: 105px;
    }
    .ttd-svg { width: 170px; height: 64px; display: block; }
    .sig-name { margin-top: 5px; text-align: right; font-style: italic; font-size: 15px; color: #222; }
    .page-break { page-break-after: always; }
    @media print {
      body { background: none; }
      .invoice { box-shadow: none; }
      .page-break { page-break-after: always; }
    }
  </style>
  <script>
    window.onload = function() { window.print(); }
    function getDueDate() {
      const today = new Date();
      today.setDate(today.getDate() + 30);
      return today.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
    }
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.due-date').forEach(function(el){
        el.textContent = getDueDate();
      });
      const todayStr = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
      document.querySelectorAll('.tgl-faktur').forEach(function(el){ el.textContent = todayStr; });
    });
  </script>
</head>
<body>

<!-- VD28 -->
<div class="invoice">
  <div class="header-flex">
    <div class="vendor-title">Pak Yoseph Berkah</div>
    <div>
      <h1>Faktur Penjualan</h1>
      <div class="no-faktur">No: INV-VD28-2025-001</div>
    </div>
  </div>
  <div class="flexbox-2">
    <div class="vendor-info-box">
      <strong>Vendor:</strong> Pak Yoseph Berkah<br>
      <strong>Kode:</strong> VD28<br>
      Jalan Cemara No. 10, RT 01/RW 07,<br>
      Kel. Cilandak Barat, Kec. Cilandak,<br>
      Kota Jakarta Selatan, DKI Jakarta, 12430
    </div>
    <div class="to-info-box">
      <strong>Kepada Yth:</strong><br>
      Minimarket AlfaSunday<br>
      Jalan Bunga Raya No. 78, RT 07/RW 02,<br>
      Kelurahan Rawamangun, Kecamatan Pulo Gadung,<br>
      Kota Jakarta Timur, DKI Jakarta, 13220
    </div>
  </div>
  <div class="invoice-meta">
    <div><b>Tanggal Faktur:</b> <span class="tgl-faktur"></span></div>
    <div><b>Jatuh Tempo (n/30):</b> <span class="due-date"></span></div>
  </div>
  <table>
    <thead>
      <tr>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Qty</th>
        <th>Harga Satuan</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>BRG0078</td>
        <td>Beras Pulen 10kg</td>
        <td>20</td>
        <td>Rp 120.000</td>
        <td>Rp 2.400.000</td>
      </tr>
      <tr>
        <td>BRG0161</td>
        <td>Baterai Alkaline ABC 4pcs</td>
        <td>4</td>
        <td>Rp 15.000</td>
        <td>Rp 60.000</td>
      </tr>
      <tr>
        <td>BRG0634</td>
        <td>Blush On Wardah Mini</td>
        <td>1</td>
        <td>Rp 10.000</td>
        <td>Rp 10.000</td>
      </tr>
      <tr class="total-row">
        <td colspan="4" align="right">TOTAL</td>
        <td>Rp 2.470.000</td>
      </tr>
    </tbody>
  </table>
  <div class="note-box">
    <b>Catatan:</b> Pembayaran mohon dilakukan ke rekening vendor sebelum jatuh tempo.
    <br>Terima kasih atas kepercayaan Anda.
  </div>
  <div class="signature-box">
    <div>
      <svg class="ttd-svg" viewBox="0 0 170 64">
        <path d="M10,45 Q32,20 44,45 Q58,60 70,22 Q78,7 90,44 Q100,64 110,30 Q120,10 125,37 Q130,55 138,25 Q145,4 160,44"
          stroke="#247ba0" stroke-width="2.1" fill="none" />
        <text x="70" y="62" font-size="14" fill="#247ba0" font-family="Brush Script MT, Segoe Script, cursive">Yoseph</text>
      </svg>
      <div class="sig-name">Pak Yoseph Berkah</div>
    </div>
  </div>
  <div class="footer">
    <em>Faktur ini dicetak otomatis dan sah tanpa tanda tangan basah.</em>
  </div>
</div>
<div class="page-break"></div>

<!-- VD02 -->
<div class="invoice">
  <div class="header-flex">
    <div class="vendor-title">Toko Elektronik Megah Jaya</div>
    <div>
      <h1>Faktur Penjualan</h1>
      <div class="no-faktur">No: INV-VD02-2025-001</div>
    </div>
  </div>
  <div class="flexbox-2">
    <div class="vendor-info-box">
      <strong>Vendor:</strong> Toko Elektronik Megah Jaya<br>
      <strong>Kode:</strong> VD02<br>
      Jalan Anggrek Neli Murni No. 23, RT 05/RW 03,<br>
      Kel. Kemanggisan, Kec. Palmerah,<br>
      Kota Jakarta Barat, DKI Jakarta, 11480
    </div>
    <div class="to-info-box">
      <strong>Kepada Yth:</strong><br>
      Minimarket AlfaSunday<br>
      Jalan Bunga Raya No. 78, RT 07/RW 02,<br>
      Kelurahan Rawamangun, Kecamatan Pulo Gadung,<br>
      Kota Jakarta Timur, DKI Jakarta, 13220
    </div>
  </div>
  <div class="invoice-meta">
    <div><b>Tanggal Faktur:</b> <span class="tgl-faktur"></span></div>
    <div><b>Jatuh Tempo (n/30):</b> <span class="due-date"></span></div>
  </div>
  <table>
    <thead>
      <tr>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Qty</th>
        <th>Harga Satuan</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>BRG0058</td>
        <td>Maspion Kipas Angin Box</td>
        <td>28</td>
        <td>Rp 210.000</td>
        <td>Rp 5.880.000</td>
      </tr>
      <tr>
        <td>BRG0273</td>
        <td>Kursi Plastik Napolly Jumbo</td>
        <td>15</td>
        <td>Rp 72.000</td>
        <td>Rp 1.080.000</td>
      </tr>
      <tr>
        <td>BRG0335</td>
        <td>Thermos Air Panas 1.8L</td>
        <td>1</td>
        <td>Rp 55.000</td>
        <td>Rp 55.000</td>
      </tr>
      <tr class="total-row">
        <td colspan="4" align="right">TOTAL</td>
        <td>Rp 7.015.000</td>
      </tr>
    </tbody>
  </table>
  <div class="note-box">
    <b>Catatan:</b> Pembayaran mohon dilakukan ke rekening vendor sebelum jatuh tempo.
    <br>Terima kasih atas kepercayaan Anda.
  </div>
  <div class="signature-box">
    <div>
      <svg class="ttd-svg" viewBox="0 0 170 64">
        <path d="M10,54 Q35,28 44,55 Q60,60 78,10 Q90,45 110,58 Q120,40 132,12 Q150,20 165,50"
          stroke="#b47b11" stroke-width="2.1" fill="none"/>
        <text x="95" y="61" font-size="15" fill="#b47b11" font-family="Brush Script MT, Segoe Script, cursive">Megah Jaya</text>
      </svg>
      <div class="sig-name">Toko Elektronik Megah Jaya</div>
    </div>
  </div>
  <div class="footer">
    <em>Faktur ini dicetak otomatis dan sah tanpa tanda tangan basah.</em>
  </div>
</div>
<div class="page-break"></div>

<!-- VD53 -->
<div class="invoice">
  <div class="header-flex">
    <div class="vendor-title">Toko Elektronik Puri Waru</div>
    <div>
      <h1>Faktur Penjualan</h1>
      <div class="no-faktur">No: INV-VD53-2025-001</div>
    </div>
  </div>
  <div class="flexbox-2">
    <div class="vendor-info-box">
      <strong>Vendor:</strong> Toko Elektronik Puri Waru<br>
      <strong>Kode:</strong> VD53<br>
      Jalan Gajah Mada No. 122, RT 02/RW 04,<br>
      Kel. Glodok, Kec. Taman Sari,<br>
      Kota Jakarta Barat, DKI Jakarta, 11120
    </div>
    <div class="to-info-box">
      <strong>Kepada Yth:</strong><br>
      Minimarket AlfaSunday<br>
      Jalan Bunga Raya No. 78, RT 07/RW 02,<br>
      Kelurahan Rawamangun, Kecamatan Pulo Gadung,<br>
      Kota Jakarta Timur, DKI Jakarta, 13220
    </div>
  </div>
  <div class="invoice-meta">
    <div><b>Tanggal Faktur:</b> <span class="tgl-faktur"></span></div>
    <div><b>Jatuh Tempo (n/30):</b> <span class="due-date"></span></div>
  </div>
  <table>
    <thead>
      <tr>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Qty</th>
        <th>Harga Satuan</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>BRG0137</td>
        <td>Philips Rice Cooker HD3128</td>
        <td>18</td>
        <td>Rp 420.000</td>
        <td>Rp 7.560.000</td>
      </tr>
      <tr>
        <td>BRG0288</td>
        <td>Philips Rice Cooker HD3129</td>
        <td>10</td>
        <td>Rp 395.000</td>
        <td>Rp 3.950.000</td>
      </tr>
      <tr class="total-row">
        <td colspan="4" align="right">TOTAL</td>
        <td>Rp 11.510.000</td>
      </tr>
    </tbody>
  </table>
  <div class="note-box">
    <b>Catatan:</b> Pembayaran mohon dilakukan ke rekening vendor sebelum jatuh tempo.
    <br>Terima kasih atas kepercayaan Anda.
  </div>
  <div class="signature-box">
    <div>
      <svg class="ttd-svg" viewBox="0 0 170 64">
        <path d="M13,40 Q24,19 36,48 Q52,64 59,22 Q65,4 87,40 Q107,64 117,25 Q125,9 143,37 Q157,58 164,11"
          stroke="#293979" stroke-width="2.1" fill="none"/>
        <text x="95" y="57" font-size="15" fill="#293979" font-family="Brush Script MT, Segoe Script, cursive">Puri Waru</text>
      </svg>
      <div class="sig-name">Toko Elektronik Puri Waru</div>
    </div>
  </div>
  <div class="footer">
    <em>Faktur ini dicetak otomatis dan sah tanpa tanda tangan basah.</em>
  </div>
</div>
<div class="page-break"></div>

<!-- VD18 -->
<div class="invoice">
  <div class="header-flex">
    <div class="vendor-title">Toko Elektronik Hardianto</div>
    <div>
      <h1>Faktur Penjualan</h1>
      <div class="no-faktur">No: INV-VD18-2025-001</div>
    </div>
  </div>
  <div class="flexbox-2">
    <div class="vendor-info-box">
      <strong>Vendor:</strong> Toko Elektronik Hardianto<br>
      <strong>Kode:</strong> VD18<br>
      Jalan Pulo Mas Barat No. 56, RT 09/RW 06,<br>
      Kel. Kayu Putih, Kec. Pulogadung,<br>
      Kota Jakarta Timur, DKI Jakarta, 13210
    </div>
    <div class="to-info-box">
      <strong>Kepada Yth:</strong><br>
      Minimarket AlfaSunday<br>
      Jalan Bunga Raya No. 78, RT 07/RW 02,<br>
      Kelurahan Rawamangun, Kecamatan Pulo Gadung,<br>
      Kota Jakarta Timur, DKI Jakarta, 13220
    </div>
  </div>
  <div class="invoice-meta">
    <div><b>Tanggal Faktur:</b> <span class="tgl-faktur"></span></div>
    <div><b>Jatuh Tempo (n/30):</b> <span class="due-date"></span></div>
  </div>
  <table>
    <thead>
      <tr>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Qty</th>
        <th>Harga Satuan</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>BRG0510</td>
        <td>Philips Setrika HD1174</td>
        <td>17</td>
        <td>Rp 175.000</td>
        <td>Rp 2.975.000</td>
      </tr>
      <tr>
        <td>BRG0301</td>
        <td>Rice Cooker Cosmos CRJ-323</td>
        <td>10</td>
        <td>Rp 270.000</td>
        <td>Rp 2.700.000</td>
      </tr>
      <tr>
        <td>BRG0440</td>
        <td>Dispenser Miyako WD-190H</td>
        <td>25</td>
        <td>Rp 255.000</td>
        <td>Rp 6.375.000</td>
      </tr>
      <tr>
        <td>BRG0525</td>
        <td>Blender Cosmos CB-801</td>
        <td>15</td>
        <td>Rp 215.000</td>
        <td>Rp 3.225.000</td>
      </tr>
      <tr class="total-row">
        <td colspan="4" align="right">TOTAL</td>
        <td>Rp 15.275.000</td>
      </tr>
    </tbody>
  </table>
  <div class="note-box">
    <b>Catatan:</b> Pembayaran mohon dilakukan ke rekening vendor sebelum jatuh tempo.
    <br>Terima kasih atas kepercayaan Anda.
  </div>
  <div class="signature-box">
    <div>
      <svg class="ttd-svg" viewBox="0 0 170 64">
        <path d="M18,56 Q31,36 44,44 Q60,54 71,21 Q80,8 95,44 Q105,64 115,32 Q124,13 132,38 Q138,59 155,27"
          stroke="#8C376A" stroke-width="2.1" fill="none"/>
        <text x="102" y="59" font-size="14" fill="#8C376A" font-family="Brush Script MT, Segoe Script, cursive">Hardianto</text>
      </svg>
      <div class="sig-name">Toko Elektronik Hardianto</div>
    </div>
  </div>
  <div class="footer">
    <em>Faktur ini dicetak otomatis dan sah tanpa tanda tangan basah.</em>
  </div>
</div>
<!-- END ALL -->

<script>
  // Script sudah otomatis mengisi tanggal hari ini & due date +30 hari
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Transaksi - AlfaSunday</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 24px; }
        .bukti { 
            border: 1px solid #333; 
            border-radius: 8px; 
            padding: 18px 28px; 
            margin-bottom: 30px; 
            box-sizing: border-box;
            width: 90%;
            max-width: 800px;
        }
        .kop {
            text-align: center;
            margin-bottom: 22px;
        }
        .kop-title {
            font-size: 1.6em;
            font-weight: bold;
        }
        .kop-alamat {
            font-size: 1.04em;
        }
        h2 { margin-top: 0; }
        .ttd { margin-top: 48px; }
        .ttd-pelapor, .ttd-penerima {
            display: inline-block;
            vertical-align: top;
            width: 48%;
            margin-top: 36px;
            text-align: center;
        }
        .table-barang { border-collapse: collapse; width: 100%; margin: 10px 0 15px 0; }
        .table-barang th, .table-barang td { border: 1px solid #ccc; padding: 6px 12px; }
        .table-barang th { background: #f5f5f5; }
        @media print {
            .bukti {
                page-break-after: always;
                border: none;
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 18px 28px;
            }
            body { margin: 0; }
        }
    </style>
</head>
<body>

<!-- 1. Memo Gaji Harian -->
<div class="bukti">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2>MEMO PEMBAYARAN GAJI HARIAN KARYAWAN</h2>
    <p><b>Tanggal:</b> 7 Juli 2025</p>
    <p>Telah dilakukan pembayaran gaji harian kepada 5 karyawan harian AlfaSunday sebagai berikut:</p>
    <table class="table-barang">
        <tr><th>No</th><th>Nama Karyawan</th><th>Nomor Rekening</th><th>Bank</th><th>Nominal</th></tr>
        <tr><td>1</td><td>Imam Santoso</td><td>1290012234587</td><td>Bank Mandiri</td><td>Rp130.000</td></tr>
        <tr><td>2</td><td>Lisa Maulida</td><td>0421178902</td><td>BCA</td><td>Rp130.000</td></tr>
        <tr><td>3</td><td>Slamet Raharjo</td><td>1560009996517</td><td>BRI</td><td>Rp130.000</td></tr>
        <tr><td>4</td><td>Ayu Puspita</td><td>812004225678</td><td>BNI</td><td>Rp130.000</td></tr>
        <tr><td>5</td><td>Hendrik Wijaya</td><td>10133227456</td><td>Bank Mandiri</td><td>Rp130.000</td></tr>
        <tr><td colspan="4" style="text-align:right"><b>Total</b></td><td><b>Rp650.000</b></td></tr>
    </table>
    <div class="ttd">
        <div class="ttd-pelapor">
            Jakarta, 7 Juli 2025<br>
            Dibuat oleh,<br><br><br><br>
            <u>Sri Wahyuni</u><br>
            Staf HRD
        </div>
        <div class="ttd-penerima">
            Disetujui oleh,<br><br><br><br>
            <u>M. Agung Prasetyo</u><br>
            HRD Manager
        </div>
    </div>
</div>

<!-- 2. Nota Plastik Kecil -->
<div class="bukti">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2>NOTA PEMBELIAN</h2>
    <p><b>Tanggal:</b> 7 Juli 2025</p>
    <table class="table-barang">
        <tr><th>Barang</th><th>Jumlah</th><th>Harga Satuan</th><th>Total</th></tr>
        <tr><td>Plastik ukuran kecil</td><td>15 Pak</td><td>Rp7.500</td><td>Rp112.500</td></tr>
    </table>
    <p>Pembayaran dilakukan tunai kepada:</p>
    <ul>
        <li><b>Nama Toko:</b> Toko Maju Bersama</li>
        <li><b>No. Rekening:</b> 123-456-7890 (BCA)</li>
        <li><b>Alamat:</b> Jl. Melati No. 17, Kebon Jeruk, Jakarta Barat</li>
    </ul>
    <div class="ttd">
        <div class="ttd-pelapor">
            Jakarta, 7 Juli 2025<br>
            Pelapor,<br><br><br>
            <u>Hendra Saputra</u><br>
            Admin Gudang
        </div>
        <div class="ttd-penerima">
            Penerima,<br><br><br>
            <u>Lilis Suryani</u><br>
            Kasir Toko
        </div>
    </div>
</div>

<!-- 3. Nota Plastik Sedang -->
<div class="bukti">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2>NOTA PEMBELIAN</h2>
    <p><b>Tanggal:</b> 7 Juli 2025</p>
    <table class="table-barang">
        <tr><th>Barang</th><th>Jumlah</th><th>Harga Satuan</th><th>Total</th></tr>
        <tr><td>Plastik ukuran sedang</td><td>15 Pak</td><td>Rp12.000</td><td>Rp180.000</td></tr>
    </table>
    <p>Pembayaran dilakukan transfer ke:</p>
    <ul>
        <li><b>Nama Toko:</b> Toko Maju Bersama</li>
        <li><b>No. Rekening:</b> 123-456-7890 (BCA)</li>
        <li><b>Alamat:</b> Jl. Melati No. 17, Kebon Jeruk, Jakarta Barat</li>
    </ul>
    <div class="ttd">
        <div class="ttd-pelapor">
            Jakarta, 7 Juli 2025<br>
            Pelapor,<br><br><br>
            <u>Novi Andriani</u><br>
            Staf Pembelian
        </div>
        <div class="ttd-penerima">
            Penerima,<br><br><br>
            <u>Dian Permana</u><br>
            Kasir Toko
        </div>
    </div>
</div>

<!-- 4. Nota Plastik Besar -->
<div class="bukti">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2>NOTA PEMBELIAN</h2>
    <p><b>Tanggal:</b> 7 Juli 2025</p>
    <table class="table-barang">
        <tr><th>Barang</th><th>Jumlah</th><th>Harga Satuan</th><th>Total</th></tr>
        <tr><td>Plastik ukuran besar</td><td>15 Pak</td><td>Rp18.000</td><td>Rp270.000</td></tr>
    </table>
    <p>Pembayaran dilakukan transfer ke:</p>
    <ul>
        <li><b>Nama Toko:</b> Toko Maju Bersama</li>
        <li><b>No. Rekening:</b> 123-456-7890 (BCA)</li>
        <li><b>Alamat:</b> Jl. Melati No. 17, Kebon Jeruk, Jakarta Barat</li>
    </ul>
    <div class="ttd">
        <div class="ttd-pelapor">
            Jakarta, 7 Juli 2025<br>
            Pelapor,<br><br><br>
            <u>Yuni Nursanti</u><br>
            Supervisor Logistik
        </div>
        <div class="ttd-penerima">
            Penerima,<br><br><br>
            <u>Firdaus Zikri</u><br>
            Kasir Toko
        </div>
    </div>
</div>

<!-- 5. Nota Kertas Nota -->
<div class="bukti">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2>NOTA PEMBELIAN</h2>
    <p><b>Tanggal:</b> 7 Juli 2025</p>
    <table class="table-barang">
        <tr><th>Barang</th><th>Jumlah</th><th>Harga Satuan</th><th>Total</th></tr>
        <tr><td>Kertas Nota</td><td>50 roll</td><td>Rp25.076</td><td>Rp1.253.800</td></tr>
    </table>
    <p>Pembayaran transfer ke:</p>
    <ul>
        <li><b>Nama Toko:</b> Toko Kertas Sejahtera</li>
        <li><b>No. Rekening:</b> 900-1212-1199 (Bank Mandiri)</li>
        <li><b>Alamat:</b> Jl. Anggrek No. 10, Cilandak Barat, Jakarta Selatan</li>
    </ul>
    <div class="ttd">
        <div class="ttd-pelapor">
            Jakarta, 7 Juli 2025<br>
            Pelapor,<br><br><br>
            <u>Surya Budi</u><br>
            Admin Pembelian
        </div>
        <div class="ttd-penerima">
            Penerima,<br><br><br>
            <u>Ratna Dewanti</u><br>
            Kasir Toko
        </div>
    </div>
</div>

<!-- 6. Memo Biaya Kebersihan -->
<div class="bukti">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2>MEMO PEMBAYARAN BIAYA KEBERSIHAN</h2>
    <p><b>Tanggal:</b> 7 Juli 2025</p>
    <p>Pembayaran biaya kebersihan untuk 4 petugas kebersihan:</p>
    <table class="table-barang">
        <tr><th>No</th><th>Nama Petugas</th><th>No Rekening</th><th>Bank</th><th>Nominal</th></tr>
        <tr><td>1</td><td>Solehudin</td><td>2233400011</td><td>BRI</td><td>Rp150.000</td></tr>
        <tr><td>2</td><td>Andi Sunarto</td><td>1123004540</td><td>BNI</td><td>Rp150.000</td></tr>
        <tr><td>3</td><td>Siti Nurjanah</td><td>3021131142</td><td>BCA</td><td>Rp150.000</td></tr>
        <tr><td>4</td><td>Fajar Pratama</td><td>4500002002</td><td>Bank Mandiri</td><td>Rp150.000</td></tr>
        <tr><td colspan="4" style="text-align:right"><b>Total</b></td><td><b>Rp600.000</b></td></tr>
    </table>
    <div class="ttd">
        <div class="ttd-pelapor">
            Jakarta, 7 Juli 2025<br>
            Pelapor,<br><br><br>
            <u>Rizki Amalia</u><br>
            Staf Umum
        </div>
        <div class="ttd-penerima">
            Disetujui,<br><br><br>
            <u>Erwin Setiadi</u><br>
            Manager Umum
        </div>
    </div>
</div>

<!-- 7. Nota Kwitansi Buku -->
<div class="bukti">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2>NOTA PEMBELIAN</h2>
    <p><b>Tanggal:</b> 7 Juli 2025</p>
    <table class="table-barang">
        <tr><th>Barang</th><th>Jumlah</th><th>Harga Satuan</th><th>Total</th></tr>
        <tr><td>Kwitansi Buku</td><td>24 pcs</td><td>Rp5.000</td><td>Rp120.000</td></tr>
    </table>
    <p>Pembayaran tunai ke:</p>
    <ul>
        <li><b>Nama Toko:</b> Toko Alat Tulis Bintang</li>
        <li><b>No. Rekening:</b> 799003004 (BRI)</li>
        <li><b>Alamat:</b> Jl. Flamboyan No. 45, Tanjung Sari, Jakarta Barat</li>
    </ul>
    <div class="ttd">
        <div class="ttd-pelapor">
            Jakarta, 7 Juli 2025<br>
            Pelapor,<br><br><br>
            <u>Siti Nurjanah</u><br>
            Staf Pembelian
        </div>
        <div class="ttd-penerima">
            Penerima,<br><br><br>
            <u>Tommy Herlambang</u><br>
            Kasir Toko
        </div>
    </div>
</div>

<!-- 8. Memo Biaya Kirim Barang -->
<div class="bukti">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2>MEMO PEMBAYARAN BIAYA KIRIM BARANG</h2>
    <p><b>Tanggal:</b> 7 Juli 2025</p>
    <p>Telah dibayarkan biaya kirim barang kepada jasa ekspedisi “JNE Jakarta”:</p>
    <ul>
        <li><b>No. Rekening JNE:</b> 505005050 (BCA)</li>
        <li><b>Nominal:</b> Rp30.000</li>
        <li><b>Penerima:</b> Agus Priyanto</li>
    </ul>
    <div class="ttd">
        <div class="ttd-pelapor">
            Jakarta, 7 Juli 2025<br>
            Pelapor,<br><br><br>
            <u>Galih Saputra</u><br>
            Staf Penjualan
        </div>
        <div class="ttd-penerima">
            Penerima,<br><br><br>
            <u>Agus Priyanto</u><br>
            Staf Ekspedisi JNE
        </div>
    </div>
</div>

<!-- 9. Nota Biaya Konsumsi -->
<div class="bukti">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2>NOTA PEMBELIAN</h2>
    <p><b>Tanggal:</b> 7 Juli 2025</p>
    <table class="table-barang">
        <tr><th>Barang</th><th>Jumlah</th><th>Harga Satuan</th><th>Total</th></tr>
        <tr><td>Konsumsi Karyawan</td><td>27 kotak</td><td>Rp30.000</td><td>Rp810.000</td></tr>
    </table>
    <p>Pembayaran tunai ke:</p>
    <ul>
        <li><b>Nama Toko:</b> Toko Katering Selera</li>
        <li><b>No. Rekening:</b> 3311990022 (Bank Mandiri)</li>
        <li><b>Alamat:</b> Jl. Pulo Mas Barat No. 56, Kayu Putih, Jakarta Timur</li>
    </ul>
    <div class="ttd">
        <div class="ttd-pelapor">
            Jakarta, 7 Juli 2025<br>
            Pelapor,<br><br><br>
            <u>Sandra Prameswari</u><br>
            Admin Karyawan
        </div>
        <div class="ttd-penerima">
            Penerima,<br><br><br>
            <u>Fajar Gunawan</u><br>
            Pemilik Katering
        </div>
    </div>
</div>
<!-- Memo Pencatatan Gaji Karyawan Tetap Minimarket -->
<div class="bukti" style="font-size:15px;">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat" style="font-size:1em;">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2 style="margin-bottom:12px;">MEMO PENCATATAN GAJI KARYAWAN TETAP</h2>
    <div style="margin-bottom:7px;"><b>Tanggal:</b> 7 Juli 2025</div>
    <div style="margin-bottom:10px;">
        Pada tanggal ini dilakukan <b>pencatatan</b> atas gaji karyawan tetap AlfaSunday untuk periode Juli 2025 sebagai berikut:
    </div>
    <table class="table-barang" style="font-size:13px;">
        <tr>
            <th style="width:25px;">No</th>
            <th style="width:140px;">Nama Karyawan</th>
            <th style="width:120px;">Jabatan</th>
            <th style="width:70px;">Gaji</th>
        </tr>
        <tr><td>1</td><td>Deni Setiawan</td><td>Kasir</td><td>Rp130.000</td></tr>
        <tr><td>2</td><td>Rina Sari</td><td>Pramuniaga</td><td>Rp130.000</td></tr>
        <tr><td>3</td><td>Budi Prakoso</td><td>Staff Gudang</td><td>Rp130.000</td></tr>
        <tr><td>4</td><td>Ayu Puspita</td><td>Staff Kebersihan</td><td>Rp130.000</td></tr>
        <tr><td>5</td><td>Hendra Wijaya</td><td>Staff Administrasi</td><td>Rp130.000</td></tr>
        <tr><td>6</td><td>Novi Andriani</td><td>Staff Pengiriman</td><td>Rp130.000</td></tr>
        <tr><td>7</td><td>Irwan Kurniawan</td><td>Staff Pembelian</td><td>Rp130.000</td></tr>
        <tr><td>8</td><td>Lilis Suryani</td><td>Staff Keuangan</td><td>Rp130.000</td></tr>
        <tr><td>9</td><td>Ahmad Fauzi</td><td>Staff Konsumsi</td><td>Rp130.000</td></tr>
        <tr><td>10</td><td>Maya Lestari</td><td>Staff Promosi</td><td>Rp130.000</td></tr>
        <tr><td>11</td><td>Riko Pratama</td><td>Staff Display</td><td>Rp130.000</td></tr>
        <tr><td>12</td><td>Lisa Maulida</td><td>Staff Pengisian Rak</td><td>Rp130.000</td></tr>
        <tr><td>13</td><td>Andi Sunarto</td><td>Staff Packing</td><td>Rp130.000</td></tr>
        <tr><td>14</td><td>Sri Wahyuni</td><td>Staff Retur</td><td>Rp130.000</td></tr>
        <tr><td>15</td><td>Fajar Pratama</td><td>Staff Maintenance</td><td>Rp130.000</td></tr>
        <tr><td>16</td><td>Galih Saputra</td><td>Staff IT</td><td>Rp130.000</td></tr>
        <tr><td>17</td><td>Imam Santoso</td><td>Penjaga Parkir</td><td>Rp130.000</td></tr>
        <tr><td>18</td><td>Sandra Prameswari</td><td>Penerima Barang</td><td>Rp130.000</td></tr>
        <tr><td>19</td><td>Bima Putra</td><td>Staff Katalog</td><td>Rp130.000</td></tr>
        <tr><td>20</td><td>Dian Puspitasari</td><td>Staff Member</td><td>Rp130.000</td></tr>
        <tr>
            <td colspan="3" style="text-align:right"><b>Total</b></td>
            <td style="font-weight:bold;">Rp2.600.000</td>
        </tr>
    </table>
    <div style="font-size:12px; margin-top:7px;"><i>Bukti ini bukan untuk pembayaran, hanya sebagai pencatatan atas beban gaji periode ini.</i></div>
    <div class="ttd" style="margin-top:20px;">
        <div class="ttd-pelapor" style="font-size:13px;">
            Jakarta, 7 Juli 2025<br>
            Dicatat oleh,<br><br><br>
            <u>Lia Yuliana</u><br>
            Staff Administrasi
        </div>
        <div class="ttd-penerima" style="font-size:13px;">
            Diketahui,<br><br><br>
            <u>Dewanto Sulistyo</u><br>
            Staff Keuangan
        </div>
    </div>
</div>



<!-- 11. Memo Pembayaran Biaya Promosi -->
<div class="bukti">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2>MEMO PEMBAYARAN BIAYA PROMOSI</h2>
    <p><b>Tanggal:</b> 7 Juli 2025</p>
    <p>Pembayaran biaya promosi kepada 10 petugas sales event dengan rincian:</p>
    <table class="table-barang">
        <tr>
            <th>No</th><th>Nama Petugas</th><th>Nominal</th><th>No Rekening</th><th>Bank</th>
        </tr>
        <tr><td>1</td><td>Mira Fadilah</td><td>Rp50.000</td><td>1112233445</td><td>BRI</td></tr>
        <tr><td>2</td><td>Bima Putra</td><td>Rp50.000</td><td>3302211988</td><td>BCA</td></tr>
        <tr><td>3</td><td>Widya Kartika</td><td>Rp50.000</td><td>9032111122</td><td>Bank Mandiri</td></tr>
        <tr><td>4</td><td>Reza Anggara</td><td>Rp50.000</td><td>8120114482</td><td>BNI</td></tr>
        <tr><td>5</td><td>Syifa Zahra</td><td>Rp50.000</td><td>7519002334</td><td>BCA</td></tr>
        <tr><td>6</td><td>Anjas Arifin</td><td>Rp50.000</td><td>3901000225</td><td>Bank Mandiri</td></tr>
        <tr><td>7</td><td>Desi Amalia</td><td>Rp50.000</td><td>8829001123</td><td>BRI</td></tr>
        <tr><td>8</td><td>Gilang Saputra</td><td>Rp50.000</td><td>2229900441</td><td>BNI</td></tr>
        <tr><td>9</td><td>Dian Puspitasari</td><td>Rp50.000</td><td>3001882900</td><td>Bank Mandiri</td></tr>
        <tr><td>10</td><td>Andra Wijaya</td><td>Rp50.000</td><td>7789002002</td><td>BCA</td></tr>
        <tr><td colspan="2" style="text-align:right"><b>Total</b></td><td colspan="3"><b>Rp500.000</b></td></tr>
    </table>
    <p>Seluruh pembayaran ditransfer langsung ke rekening masing-masing petugas melalui rekening perusahaan berikut:</p>
    <ul>
        <li><b>Bank:</b> Bank Mandiri</li>
        <li><b>No. Rekening:</b> 9988776655</li>
        <li><b>Nama Pemilik Rekening:</b> PT AlfaSunday</li>
    </ul>
    <div class="ttd">
        <div class="ttd-pelapor">
            Jakarta, 7 Juli 2025<br>
            Pelapor,<br><br><br>
            <u>Herlina Citra</u><br>
            Staf Marketing
        </div>
        <div class="ttd-penerima">
            Otorisasi,<br><br><br>
            <u>Bagus Ramdani</u><br>
            Manager Marketing
        </div>
    </div>
</div>


<!-- 12. Memo Biaya Iuran Lingkungan -->
<div class="bukti">
    <div class="kop">
        <div class="kop-title">AlfaSunday</div>
        <div class="kop-alamat">Jl. Bunga Raya No. 78, Rawamangun, Pulo Gadung, Jakarta Timur 13220<br>Telp: (021) 88888888</div>
    </div>
    <h2>MEMO PEMBAYARAN BIAYA IURAN LINGKUNGAN</h2>
    <p><b>Tanggal:</b> 7 Juli 2025</p>
    <p>Pembayaran iuran lingkungan ke RT 07/RW 02 Rawamangun:</p>
    <ul>
        <li><b>No. Rekening RT:</b> 8811002299 (BRI)</li>
        <li><b>Nama Penanggung Jawab:</b> Sugiyono</li>
        <li><b>Nominal:</b> Rp50.000</li>
    </ul>
    <div class="ttd">
        <div class="ttd-pelapor">
            Jakarta, 7 Juli 2025<br>
            Pelapor,<br><br><br>
            <u>Indah Lestari</u><br>
            Admin Keuangan
        </div>
        <div class="ttd-penerima">
            Penerima,<br><br><br>
            <u>Sugiyono</u><br>
            Ketua RT 07
        </div>
    </div>
</div>

</body>
</html>
