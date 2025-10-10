const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

const namaHari = [
  "minggu",
  "senin",
  "selasa",
  "rabu",
  "kamis",
  "jumat",
  "sabtu",
];

console.log("========================================");
console.log("📅 Selamat Datang di Program Hari 📅");
console.log("========================================");
console.log("");

function tanya() {
  rl.question("Masukkan hari:", (hariInput) => {
    const hari = hariInput.toLowerCase().trim();
    if (!namaHari.includes(hari)) {
      console.log("Hari tidak valid. Silakan masukkan hari yang benar.");
      tanya();
    } else {
      hitungHari(hari);
    }
  });
}

function hitungHari(hari) {
  rl.question(
    "Masukkan hari yang akan datang (contoh: 100):",
    (jumlahInput) => {
      const jumlahHari = parseInt(jumlahInput);
      if (isNaN(jumlahHari) || jumlahHari <= 0) {
        console.log(
          "Jumlah hari tidak valid. Silakan masukkan jumlah yang benar."
        );
        hitungHari(hari);
      } else {
        const indexAwal = namaHari.indexOf(hari);
        const indexAkhir = (indexAwal + jumlahHari) % 7;
        const hariAkhir = namaHari[indexAkhir];

        const hariAwalTampil = hari.charAt(0).toUpperCase() + hari.slice(1);
        const hariAkhirTampil =
          hariAkhir.charAt(0).toUpperCase() + hariAkhir.slice(1);

        console.log("\n================ HASIL ================");
        console.log(
          `${jumlahHari} hari setelah hari ${hariAwalTampil} adalah hari ${hariAkhirTampil}.`
        );
        console.log("========================================");
        rl.close();
      }
    }
  );
}

tanya();
