const readline = require("readline"); ////Modul utk baca input dari terminal

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

const hari = ["minggu", "senin", "selasa", "rabu", "kamis", "jumat", "sabtu"]; //daftar string dlm array, pakai indeks

rl.question("Masukkan hari: ", (hariInput) => {
  const indexHari = hari.indexOf(hariInput.toLowerCase());

  if (indexHari === -1) {
    console.log("Hari yang dimasukkan tidak valid!");
    rl.close();
    return;
  }

  rl.question("Masukkan jumlah hari yang akan datang: ", (jumlahInput) => {
    const jumlah = parseInt(jumlahInput); //ubah string jdi bilangan bulat

    if (isNaN(jumlah) || jumlah < 0) {
      console.log("Jumlah hari harus berupa angka positif!");
      rl.close();
      return;
    }

    const indexBaru = (indexHari + jumlah) % 7;
    console.log(`${jumlah} hari setelah ${hariInput} adalah ${hari[indexBaru]}`); //buat string dgn menyelipkan variabel
    rl.close();
  });
});
