const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

const angkaRahasia = Math.floor(Math.random() * 100) + 1;
let jumlahTebakan = 0;

console.log("========================================");
console.log("🎮 Selamat Datang di Game Tebak Angka 🎮");
console.log("========================================");
console.log("Saya sudah memilih sebuah angka antara 1 dan 100. Coba tebak!");
console.log("");

function tanya() {
  rl.question("Masukkan tebakanmu: ", (jawaban) => {
    const tebakan = parseInt(jawaban);

    if (isNaN(tebakan)) {
      console.log("Mohon masukkan angka yang valid.");
      tanya();
      return;
    }

    if (tebakan < 1 || tebakan > 100) {
      console.log("Tebakan harus antara 1 dan 100.");
      tanya();
      return;
    }

    jumlahTebakan++;

    if (tebakan > angkaRahasia) {
      console.log("Terlalu tinggi! Coba lagi.");
      tanya();
    } else if (tebakan < angkaRahasia) {
      console.log("Terlalu rendah! Coba lagi.");
      tanya();
    } else {
      console.log(
        `🎉 Selamat! Kamu menebak angka ${angkaRahasia} dengan benar dalam ${jumlahTebakan} tebakan! 🎉`
      );
      rl.close();
    }
  });
}
tanya();