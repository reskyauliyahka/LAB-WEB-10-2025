const readline = require("readline");
const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

function hitungHargaSetelahDiskon(harga, jenis) {
  const daftarJenisBarang = ["elektronik", "pakaian", "makanan", "lainnya"];
  let diskonPersen = 0;
  const jenisBarang = jenis.trim().toLowerCase();

  if (jenisBarang == daftarJenisBarang[0]) {
    diskonPersen = 10;
  } else if (jenisBarang == daftarJenisBarang[1]) {
    diskonPersen = 20;
  } else if (jenisBarang == daftarJenisBarang[2]) {
    diskonPersen = 5;
  }

  const diskonJumlah = (harga * diskonPersen) / 100;
  const hargaAkhir = harga - diskonJumlah;

  return {
    hargaAwal: harga,
    persen: diskonPersen,
    hargaAkhir: hargaAkhir,
  };
}

rl.question("Masukkan harga barang: ", (hargaInput) => {
  const harga = parseFloat(hargaInput);

  if (isNaN(harga) || harga < 0) {
    console.log("Error: Harga yang dimasukkan harus berupa angka positif.");
    rl.close();
    return;
  }

  rl.question(
    "Masukkan jenis barang (Elektronik, Pakaian, Makanan, Lainnya): ",
    (jenisInput) => {
      const hasil = hitungHargaSetelahDiskon(harga, jenisInput);

      console.log("\n--- Hasil Kalkulasi ---");
      console.log(`Harga awal: Rp ${hasil.hargaAwal}`);
      if (hasil.persen > 0) {
        console.log(`Diskon: ${hasil.persen}%`);
      } else {
        console.log("Diskon: Tidak ada");
      }
      console.log(`Harga setelah diskon: Rp ${hasil.hargaAkhir}`);

      rl.close();
    }
  );
});
