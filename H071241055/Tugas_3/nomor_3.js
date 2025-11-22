const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

function cariHariBerikutnya(hariAwal, jumlahHari) {
  const daftarHari = [
    "senin",
    "selasa",
    "rabu",
    "kamis",
    "jumat",
    "sabtu",
    "minggu",
  ];

  const hariInput = hariAwal.toLowerCase().trim();

  const indexAwal = daftarHari.indexOf(hariInput);

  if (indexAwal === -1) {
    return null;
  }

  const indexMendatang = (indexAwal + jumlahHari) % 7;

  return daftarHari[indexMendatang];
}


function hurufKapital(string){
  awalKapital =  string.charAt(0).toUpperCase() + string.slice(1)
  return awalKapital
}


rl.question("Masukkan hari saat ini: ", (namaHari) => {
  rl.question("Masukkan jumlah hari yang akan datang: ", (jumlahHariInput) => {
    const jumlahHari = parseInt(jumlahHariInput);

    if (isNaN(jumlahHari) || jumlahHari < 0) {
      console.log("Error: Jumlah hari harus berupa angka positif.");
      rl.close();
      return;
    }

    const hariMendatang = cariHariBerikutnya(namaHari, jumlahHari);

    if (hariMendatang) {
      console.log(
        `Output: ${jumlahHari} hari setelah hari ${namaHari} adalah hari ${hariMendatang}.`

      )

    } else {
      console.log("Error: Nama hari yang Anda masukkan tidak valid.");
    }

    rl.close();
  });
});
