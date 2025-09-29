const readline = require("readline");
const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

const daftarBarang = ["elektronik", "pakaian", "makanan", "lainnya"];

function tanyaHarga() {
  rl.question("Masukkan harga barang: ", (hargaInput) => {
    const hargaBarang = parseFloat(hargaInput);

    if (isNaN(hargaBarang) || hargaBarang <= 0) {
      console.log("Harga tidak valid. Silakan masukkan Harga yang benar.");
      tanyaHarga();
    } else {
      tanyaJenis(hargaBarang);
    }
  });
}

function tanyaJenis(hargaBarang) {
  rl.question(
    "Masukkan jenis barang (Elektronik, Pakaian, Makanan, Lainnya): ",
    (jenisInput) => {
      const jenisBarang = jenisInput.toLowerCase().trim();
      if (!daftarBarang.includes(jenisBarang)) {
        console.log(
          "Jenis barang tidak valid. Silakan masukkan jenis yang benar."
        );
        tanyaJenis(hargaBarang);
      } else {
        hitungDiskon(hargaBarang, jenisBarang);
      }
    }
  );
}

function hitungDiskon(hargaBarang, jenisBarang) {
  try {
    let diskon = 0;
    switch (jenisBarang) {
      case "elektronik":
        diskon = 10;
        break;
      case "pakaian":
        diskon = 20;
        break;
      case "makanan":
        diskon = 5;
        break;
      default:
    }
    const jumlahDiskon = (diskon / 100) * hargaBarang;
    const hargaSetelahDiskon = hargaBarang - jumlahDiskon;

    console.log(
      `\nHarga Awal          : Rp${hargaBarang.toLocaleString("id-ID")}`
    );
    console.log(
      `Jenis Barang        : ${
        jenisBarang.charAt(0).toUpperCase() + jenisBarang.slice(1)
      }`
    );
    console.log(`Diskon              : ${diskon}%`);
    console.log(
      `Jumlah Diskon       : Rp${jumlahDiskon.toLocaleString("id-ID")}`
    );
    console.log(
      `Harga Setelah Diskon: Rp${hargaSetelahDiskon.toLocaleString("id-ID")}`
    );
  } catch (error) {
    console.log("Terjadi kesalahan:", error.message);
  } finally {
    rl.close();
  }
}

tanyaHarga();
